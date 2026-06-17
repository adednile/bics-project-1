<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chama Gold & Trust')</title>

    <!-- Tailwind + Fonts -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a',
                        secondary: '#1e293b',
                        gold: {
                            50: '#fefdf0',
                            100: '#fefac2',
                            200: '#fde68a',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                        },
                        brand: {
                            navy: '#0b0f19',
                            dark: '#151d30',
                            gold: '#c59b27',
                            goldlight: '#e5c060',
                            emerald: '#10b981',
                            rose: '#f43f5e',
                            slate: '#334155'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        title: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        body {
            background-color: #0b0f19;
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
        }
        .premium-card {
            background: rgba(21, 29, 48, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .gold-glow {
            box-shadow: 0 0 15px rgba(197, 155, 39, 0.15);
        }
        .sidebar-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .sidebar-link::after {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            height: 70%;
            width: 3px;
            background: linear-gradient(180deg, #e5c060 0%, #c59b27 100%);
            border-radius: 0 4px 4px 0;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .sidebar-link.active::after {
            opacity: 1;
        }
        .sidebar-link.active {
            background: rgba(197, 155, 39, 0.1);
            color: #e5c060;
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, #e5c060 0%, #c59b27 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gold-gradient-btn {
            background: linear-gradient(135deg, #e5c060 0%, #c59b27 100%);
            color: #0b0f19;
            transition: all 0.2s ease;
        }
        .gold-gradient-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(197, 155, 39, 0.25);
        }
        .gold-gradient-btn:active {
            transform: translateY(0);
        }
        /* Smooth transitions */
        .fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-brand-navy min-h-screen text-slate-200">

<!-- Sidebar -->
<aside class="h-screen w-64 fixed left-0 top-0 bg-brand-dark/95 border-r border-white/5 z-40 hidden md:flex flex-col py-6 px-4 shadow-xl">
    <div class="mb-8 px-3">
        <a href="/" class="flex items-center gap-2">
            <div class="w-10 h-10 bg-gradient-to-br from-gold-500 to-gold-700 rounded-xl flex items-center justify-center text-brand-navy font-extrabold text-xl shadow-lg">G</div>
            <div>
                <h1 class="text-lg font-title font-extrabold text-white tracking-tight leading-none">Chama Gold</h1>
                <p class="text-[10px] text-gold-500 font-semibold tracking-widest uppercase mt-1">Wealth &amp; Trust</p>
            </div>
        </a>
    </div>
    
    <nav class="flex-1 space-y-1">
        @php
            $currentRoute = request()->route()->getName();
        @endphp
        <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 {{ $currentRoute == 'dashboard' ? 'active' : '' }}">
            <span class="material-symbols-outlined text-lg">dashboard</span>
            <span class="text-sm font-medium">Dashboard</span>
        </a>
        <a href="{{ route('member.contributions') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'member.contributions') ? 'active' : '' }}">
            <span class="material-symbols-outlined text-lg">payments</span>
            <span class="text-sm font-medium">Contributions</span>
        </a>
        <a href="{{ route('member.loans') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'member.loans') ? 'active' : '' }}">
            <span class="material-symbols-outlined text-lg">account_balance</span>
            <span class="text-sm font-medium">Loans</span>
        </a>
        @if(auth()->user()->role === 'treasurer')
            <div class="pt-4 pb-2 px-4">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Administration</span>
            </div>
            <a href="{{ route('treasurer.sms-parser') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'treasurer.sms-parser') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-lg">sms</span>
                <span class="text-sm font-medium">SMS Parser</span>
            </a>
            <a href="{{ route('treasurer.penalties') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'treasurer.penalties') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-lg">gavel</span>
                <span class="text-sm font-medium">Penalties</span>
            </a>
            <a href="{{ route('reports.treasurer') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'reports.treasurer') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-lg">assessment</span>
                <span class="text-sm font-medium">Reports</span>
            </a>
        @endif
    </nav>

    <div class="mt-auto pt-6 border-t border-white/5 space-y-1">
        <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 {{ $currentRoute == 'profile.edit' ? 'active' : '' }}">
            <span class="material-symbols-outlined text-lg">account_circle</span>
            <span class="text-sm font-medium">My Profile</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 w-full text-left">
                <span class="material-symbols-outlined text-lg">logout</span>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Main wrapper -->
<div class="md:ml-64 min-h-screen flex flex-col">

    <!-- Top Bar -->
    <header class="bg-brand-dark/70 backdrop-blur-md sticky top-0 z-30 border-b border-white/5 shadow-lg">
        <div class="flex justify-between items-center px-6 h-16 max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <button class="md:hidden text-slate-300 hover:text-white" onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="text-lg font-title font-bold text-white tracking-wide">@yield('title', 'Chama Gold')</span>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="hidden sm:inline-block bg-white/5 border border-white/10 text-gold-500 text-xs font-semibold px-3 py-1.5 rounded-xl">
                    {{ auth()->user()->chama->name ?? 'Kenya Chama' }}
                </span>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 text-slate-300 hover:text-white focus:outline-none py-1.5 px-2 rounded-xl hover:bg-white/5 transition">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-gold-500 to-gold-700 text-brand-navy flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="text-xs font-semibold hidden md:inline">{{ auth()->user()->name }}</span>
                        <span class="material-symbols-outlined text-sm">keyboard_arrow_down</span>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-48 rounded-xl premium-card shadow-2xl py-1 border border-white/15 z-50 text-sm">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-slate-300 hover:text-white hover:bg-white/5">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="p-6 max-w-7xl mx-auto w-full flex-1 fade-in">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-300 flex items-center justify-between shadow-lg backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-400">check_circle</span>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-xl font-bold">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 flex items-center justify-between shadow-lg backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-rose-400">error</span>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200 text-xl font-bold">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 shadow-lg backdrop-blur-md">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-rose-400">warning</span>
                    <p class="text-sm font-bold">Please correct the following errors:</p>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-brand-dark/40 border-t border-white/5 py-6 mt-12 text-sm text-slate-500 text-center">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <span class="font-bold text-white tracking-wide font-title">Chama Gold &amp; Trust</span>
                <span class="mx-2">·</span>
                <span>© {{ date('Y') }} Safe &amp; Secure Bookkeeping</span>
            </div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-slate-300 transition">Privacy</a>
                <a href="#" class="hover:text-slate-300 transition">Terms</a>
                <a href="#" class="hover:text-slate-300 transition">Support</a>
            </div>
        </div>
    </footer>
</div>

<!-- Mobile Sidebar -->
<div id="mobile-sidebar" class="fixed inset-0 bg-brand-navy/60 backdrop-blur-sm z-50 hidden md:hidden" onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-brand-dark w-72 h-full p-6 overflow-y-auto shadow-2xl flex flex-col border-r border-white/5">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center text-brand-navy font-bold text-lg">G</div>
                <span class="text-lg font-title font-extrabold text-white">Chama Gold</span>
            </div>
            <button onclick="document.getElementById('mobile-sidebar').classList.add('hidden')" class="text-2xl text-slate-400 hover:text-white">&times;</button>
        </div>
        <nav class="space-y-2 flex-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 {{ $currentRoute == 'dashboard' ? 'bg-white/5 text-gold-500 font-bold' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>Dashboard
            </a>
            <a href="{{ route('member.contributions') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'member.contributions') ? 'bg-white/5 text-gold-500 font-bold' : '' }}">
                <span class="material-symbols-outlined">payments</span>Contributions
            </a>
            <a href="{{ route('member.loans') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'member.loans') ? 'bg-white/5 text-gold-500 font-bold' : '' }}">
                <span class="material-symbols-outlined">account_balance</span>Loans
            </a>
            @if(auth()->user()->role === 'treasurer')
                <div class="pt-4 pb-2 px-4">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Admin</span>
                </div>
                <a href="{{ route('treasurer.sms-parser') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'treasurer.sms-parser') ? 'bg-white/5 text-gold-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined">sms</span>SMS Parser
                </a>
                <a href="{{ route('treasurer.penalties') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'treasurer.penalties') ? 'bg-white/5 text-gold-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined">gavel</span>Penalties
                </a>
                <a href="{{ route('reports.treasurer') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 {{ str_starts_with($currentRoute, 'reports.treasurer') ? 'bg-white/5 text-gold-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined">assessment</span>Reports
                </a>
            @endif
        </nav>
    </div>
</div>

@stack('scripts')
</body>
</html>