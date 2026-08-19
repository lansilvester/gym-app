@extends('layouts.app')
@section('title', 'Buat Faktur')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Buat Faktur</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.invoices.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anggota *</label>
                <select name="member_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Pilih Anggota...</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>{{ $member->user->name }} ({{ $member->member_code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo *</label>
                <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
            <input type="text" name="description" value="{{ old('description') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="contoh: Perpanjangan Keanggotaan, Sesi Personal Training" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
            <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('notes') }}</textarea>
        </div>

        <h3 class="font-semibold text-gray-700 border-b pb-2">Item Faktur</h3>
        <div id="items-container" class="space-y-3">
            <div class="item-row grid grid-cols-12 gap-2 items-end">
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" name="items[0][description]" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm item-qty" required>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                    <input type="number" name="items[0][unit_price]" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm item-price" required>
                </div>
                <div class="col-span-1">
                    <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-500 hover:text-red-700 text-sm p-2">✕</button>
                </div>
            </div>
        </div>

        <button type="button" onclick="addItem()" class="text-blue-600 hover:underline text-sm">+ Tambah Item</button>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Buat Faktur</button>
            <a href="{{ route('admin.invoices.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;
function addItem() {
    const container = document.getElementById('items-container');
    const html = `
        <div class="item-row grid grid-cols-12 gap-2 items-end">
            <div class="col-span-5">
                <input type="text" name="items[${itemIndex}][description]" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="col-span-3">
                <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="col-span-3">
                <input type="number" name="items[${itemIndex}][unit_price]" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="col-span-1">
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-500 hover:text-red-700 text-sm p-2">✕</button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
}
</script>
@endsection
