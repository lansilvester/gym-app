<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Member::class);

        $query = Member::with('user');
        if ($search = $request->input('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "{$search}%")
                    ->orWhere('email', 'like', "{$search}%"))
                  ->orWhere('member_code', 'like', "{$search}%");
            });
        }
        $members = $query->latest()->paginate(15);
        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        $this->authorize('create', Member::class);

        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Member::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'string', 'min:8', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'nik' => 'nullable|string|max:20|unique:members,nik',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
        ]);

        $user->assignRole('member');

        Member::create([
            'user_id' => $user->id,
            'member_code' => 'MBR-' . strtoupper(Str::random(8)),
            'nik' => $validated['nik'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Member created successfully.');
    }

    public function show(Member $member)
    {
        $this->authorize('view', $member);

        $member->load([
            'user',
            'medicalInfo',
            'bodyMeasurements' => fn($q) => $q->latest('measured_at'),
            'activeSubscription.package',
            'checkIns' => fn($q) => $q->latest('check_in_at')->limit(10),
            'invoices' => fn($q) => $q->latest()->limit(5),
        ]);
        return view('admin.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $this->authorize('update', $member);

        $member->load('user');
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $this->authorize('update', $member);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$member->user_id}",
            'phone' => 'nullable|string|max:20',
            'nik' => "nullable|string|max:20|unique:members,nik,{$member->id}",
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $member->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $member->update([
            'nik' => $validated['nik'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
        ]);

        return redirect()->route('admin.members.show', $member)->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $this->authorize('delete', $member);

        $member->user->delete();
        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }
}
