<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-lg font-semibold text-slate-800">Sesi Aktif</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Kelola dan batalkan sesi aktif Anda di browser dan perangkat lain.</p>
                </div>

                <div class="divide-y divide-slate-50">
                    @forelse($sessions as $session)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl {{ $session->is_current ? 'bg-emerald-50' : 'bg-slate-50' }} flex items-center justify-center">
                                @if(str_contains(strtolower($session->platform ?? ''), 'android'))
                                    <svg class="w-5 h-5 {{ $session->is_current ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                @elseif(str_contains(strtolower($session->platform ?? ''), 'ios') || str_contains(strtolower($session->platform ?? ''), 'iphone'))
                                    <svg class="w-5 h-5 {{ $session->is_current ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                @else
                                    <svg class="w-5 h-5 {{ $session->is_current ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-slate-800">{{ $session->browser }} on {{ $session->platform }}</p>
                                    @if($session->is_current)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Saat Ini</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $session->ip_address }} &middot; {{ $session->last_activity->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if(!$session->is_current)
                        <form method="POST" action="{{ route('sessions.destroy', $session->id) }}" onsubmit="return confirm('Apakah Anda y ingin mengakhiri sesi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700 transition">
                                Batalkan
                            </button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-slate-400">Tidak ada sesi aktif yang ditemukan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
