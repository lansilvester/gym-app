<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gym App') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="antialiased bg-slate-50">
    <div class="min-h-screen flex">

        {{-- Mobile overlay --}}
        <div x-data="{ sidebarOpen: false }" x-init="$watch('$store.sidebar', v => sidebarOpen = v)" class="lg:hidden">
            <div x-show="sidebarOpen" x-transition.opacity @click="$store.sidebar = false" class="fixed inset-0 bg-black/50 z-40 backdrop-blur-sm"></div>
        </div>

        {{-- ═══ Sidebar ═══ --}}
        <aside id="sidebar" class="hidden lg:flex lg:flex-col w-[260px] fixed inset-y-0 left-0 z-50 transition-all duration-300"
               style="background: linear-gradient(195deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);">

            {{-- Brand --}}
            <div class="flex items-center gap-3 h-[72px] px-5 border-b border-white/10">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-sm"
                     style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                    G
                </div>
                <div>
                    <div class="text-white font-bold text-[15px] leading-tight">GYM APP</div>
                    <div class="text-slate-400 text-[10px] font-medium tracking-wider uppercase">Management System</div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5 scrollbar-thin">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                @if(Auth::user()->hasRole('super_admin'))
                <div class="sidebar-section-label">User Management</div>
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    Roles & Permissions
                </a>
                @endif

                @if(Auth::user()->hasAnyPermission(['member.view', 'member.create', 'member.edit', 'member.delete']))
                <div class="sidebar-section-label">Member</div>
                <a href="{{ route('admin.members.index') }}" class="sidebar-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Members
                </a>
                @if(Auth::user()->hasAnyRole(['super_admin', 'admin']))
                <a href="{{ route('admin.packages.index') }}" class="sidebar-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Packages
                </a>
                @endif
                <a href="{{ route('admin.subscriptions.index') }}" class="sidebar-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Subscriptions
                </a>
                @endif

                @if(Auth::user()->hasAnyPermission(['checkin.view', 'checkin.manual_override', 'trainer.view', 'trainer.schedule', 'trainer.booking']))
                <div class="sidebar-section-label">Operations</div>
                @if(Auth::user()->hasAnyPermission(['checkin.view', 'checkin.manual_override']))
                <a href="{{ route('admin.checkins.index') }}" class="sidebar-link {{ request()->routeIs('admin.checkins.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Check-in
                </a>
                @endif
                @if(Auth::user()->hasAnyPermission(['trainer.view', 'trainer.schedule', 'trainer.booking']))
                <a href="{{ route('admin.trainers.index') }}" class="sidebar-link {{ request()->routeIs('admin.trainers.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Trainers
                </a>
                <a href="{{ route('admin.pt-bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.pt-bookings.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    PT Bookings
                </a>
                @endif
                @endif

                @if(Auth::user()->hasAnyPermission(['payment.view', 'payment.create', 'payment.refund']))
                <div class="sidebar-section-label">Finance</div>
                <a href="{{ route('admin.invoices.index') }}" class="sidebar-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    Invoices
                </a>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Payments
                </a>
                @endif

                @if(Auth::user()->hasAnyPermission(['inventory.view', 'inventory.manage', 'inventory.maintenance']))
                <div class="sidebar-section-label">Inventory</div>
                <a href="{{ route('admin.inventory.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Inventory Items
                </a>
                <a href="{{ route('admin.inventory-transactions.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventory-transactions.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                    Transactions
                </a>
                @if(Auth::user()->hasPermissionTo('inventory.maintenance'))
                <a href="{{ route('admin.maintenance.index') }}" class="sidebar-link {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Maintenance
                </a>
                @endif
                @endif
            </nav>

            {{-- Sidebar footer --}}
            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="avatar avatar-sm" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-slate-400 text-[11px] truncate">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ═══ Main Content ═══ --}}
        <div class="flex-1 lg:ml-[260px]">

            {{-- Top Navbar --}}
            <header class="sticky top-0 z-30 glass border-b border-slate-200/60">
                <div class="flex items-center justify-between h-[68px] px-4 sm:px-6 lg:px-8">
                    {{-- Left: hamburger + page title --}}
                    <div class="flex items-center gap-4">
                        <button onclick="document.getElementById('sidebar').classList.toggle('hidden'); document.getElementById('sidebar').classList.toggle('flex')"
                                class="lg:hidden p-2 -ml-2 rounded-xl text-slate-500 hover:bg-slate-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-xs text-slate-400 -mt-0.5 hidden sm:block">@yield('page-subtitle', '')</p>
                        </div>
                    </div>

                    {{-- Right: actions --}}
                    <div class="flex items-center gap-2">
                        {{-- Notification bell --}}
                        <button class="relative p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        {{-- User dropdown --}}
                        @auth
                        <div class="relative" x-data="{ open: false, logoutConfirm: false }">
                            <button @click="open = !open" class="flex items-center gap-3 pl-1 pr-3 py-1 rounded-xl hover:bg-slate-100 transition">
                                <div class="avatar avatar-sm" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:inline text-sm font-medium text-slate-700">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                 @click.away="open = false; logoutConfirm = false" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl shadow-slate-200/50 py-2 z-50 border border-slate-100">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-400">{{ Auth::user()->email }}</div>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>

                                {{-- Logout with confirmation --}}
                                <div x-show="!logoutConfirm">
                                    <button @click="logoutConfirm = true" type="button" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Log Out
                                    </button>
                                </div>
                                <div x-show="logoutConfirm" class="px-4 py-3">
                                    <p class="text-xs text-slate-500 mb-2">Yakin mau logout?</p>
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                                Logout
                                            </button>
                                        </form>
                                        <button @click="logoutConfirm = false" type="button" class="flex-1 px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-4 sm:p-6 lg:p-8">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="mb-6 animate-slide-down flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 animate-slide-down flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif
                @if(isset($errors) && $errors->any())
                    <div class="mb-6 animate-slide-down flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold mb-1">Validation Error</p>
                            <ul class="text-sm list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', false);
        });
    </script>
</body>
</html>
