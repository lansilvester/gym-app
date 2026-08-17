@extends('layouts.app')
@section('title', 'Edit Subscription')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Subscription</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.subscriptions.update', $subscription) }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                <input type="text" value="{{ $subscription->member->user->name }} ({{ $subscription->member->member_code }})" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100" disabled>
                <input type="hidden" name="member_id" value="{{ $subscription->member_id }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Package</label>
                <input type="text" value="{{ $subscription->package->name }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100" disabled>
                <input type="hidden" name="package_id" value="{{ $subscription->package_id }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="active" {{ old('status', $subscription->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ old('status', $subscription->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="cancelled" {{ old('status', $subscription->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Auto Renew</label>
                <div class="flex items-center gap-2 pt-1">
                    <input type="hidden" name="auto_renew" value="0">
                    <input type="checkbox" name="auto_renew" value="1" {{ old('auto_renew', $subscription->auto_renew) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="text-sm text-gray-700">Enable auto renew</label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                <input type="date" name="start_date" value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                <input type="date" name="end_date" value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Update Subscription</button>
            <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection
