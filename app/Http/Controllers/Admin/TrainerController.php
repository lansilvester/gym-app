<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\TrainerSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainerController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Trainer::class);

        $query = Trainer::with('user');

        if ($search = $request->input('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
                ->orWhere('trainer_code', 'like', "%{$search}%")
                ->orWhere('specialization', 'like', "%{$search}%");
        }

        $trainers = $query->latest()->paginate(15);
        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
    {
        $this->authorize('create', Trainer::class);

        return view('admin.trainers.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Trainer::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'string', 'min:8', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'specialization' => 'nullable|string|max:255',
            'certifications' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
        ]);

        $user->assignRole('trainer');

        Trainer::create([
            'user_id' => $user->id,
            'trainer_code' => 'TRN-' . strtoupper(Str::random(8)),
            'specialization' => $validated['specialization'] ?? null,
            'certifications' => $validated['certifications'] ?? null,
            'hourly_rate' => $validated['hourly_rate'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_available' => $validated['is_available'] ?? true,
        ]);

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer created successfully.');
    }

    public function show(Trainer $trainer)
    {
        $this->authorize('view', $trainer);

        $trainer->load([
            'user',
            'schedules',
            'ptBookings' => fn($q) => $q->with('member.user')->latest('booking_date')->limit(10),
        ]);
        return view('admin.trainers.show', compact('trainer'));
    }

    public function edit(Trainer $trainer)
    {
        $trainer->load('user');
        return view('admin.trainers.edit', compact('trainer'));
    }

    public function update(Request $request, Trainer $trainer)
    {
        $this->authorize('update', $trainer);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$trainer->user_id}",
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'certifications' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $trainer->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $trainer->update([
            'specialization' => $validated['specialization'] ?? null,
            'certifications' => $validated['certifications'] ?? null,
            'hourly_rate' => $validated['hourly_rate'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_available' => $validated['is_available'] ?? true,
        ]);

        return redirect()->route('admin.trainers.show', $trainer)->with('success', 'Trainer updated successfully.');
    }

    public function destroy(Trainer $trainer)
    {
        $this->authorize('delete', $trainer);

        $trainer->user->delete();
        return redirect()->route('admin.trainers.index')->with('success', 'Trainer deleted successfully.');
    }

    public function updateSchedule(Request $request, Trainer $trainer)
    {
        $this->authorize('update', $trainer);

        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|min:0|max:6',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.is_active' => 'boolean',
        ]);

        $trainer->schedules()->delete();

        foreach ($validated['schedules'] as $schedule) {
            $trainer->schedules()->create([
                'day_of_week' => $schedule['day_of_week'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
                'is_active' => $schedule['is_active'] ?? true,
            ]);
        }

        return redirect()->route('admin.trainers.show', $trainer)->with('success', 'Schedule updated successfully.');
    }
}
