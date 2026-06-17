<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Chama Gold') }} - Automated Financial Ledger & Loan Management</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#0b0f19',
                        dark: '#151d30',
                        gold: {
                            50: '#fefdf0',
                            100: '#fefac2',
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
    
    <style>
        body {
            background-color: #0b0f19;
            color: #f1f5f9;
        }
        .hero-bg {
            background: radial-gradient(circle at 70% 30%, rgba(197, 155, 39, 0.08) 0%, transparent 60%),
                        radial-gradient(circle at 10% 80%, rgba(16, 185, 129, 0.04) 0%, transparent 50%),
                        #0b0f19;
        }
        .premium-card {
            background: rgba(21, 29, 48, 0.65);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, #f59e0b 0%, #c59b27 50%, #e5c060 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gold-btn {
            background: linear-gradient(135deg, #e5c060 0%, #c59b27 100%);
            color: #0b0f19;
            box-shadow: 0 4px 20px rgba(197, 155, 39, 0.25);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .gold-btn:hover {
            transform: translateY(-2px);
            opacity: 0.95;
            box-shadow: 0 6px 24px rgba(197, 155, 39, 0.35);
        }
        .pulse-emerald {
            animation: pulse-emerald-anim 2s infinite;
        }
        @keyframes pulse-emerald-anim {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: 0.8; }
        }
        .float-delayed {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
    </style>
</head>
<body class="font-sans antialiased selection:bg-gold-500/20 selection:text-gold-500">

    <!-- Header Navigation -->
    <nav class="fixed w-full z-50 bg-brand-navy/80 backdrop-blur-md border-b border-white/5 transition-all">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-gold-500 to-gold-700 rounded-xl flex items-center justify-center text-brand-navy font-black text-xl shadow-lg">G</div>
                <div>
                    <span class="text-lg font-title font-extrabold text-white tracking-tight leading-none">Chama Gold</span>
                    <p class="text-[9px] text-gold-500 font-bold tracking-widest uppercase mt-0.5">Wealth &amp; Trust</p>
                </div>
            </a>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-300">
                <a href="#features" class="hover:text-white transition">Platform Features</a>
                <a href="#benefits" class="hover:text-white transition">Chama Benefits</a>
                <a href="#stats" class="hover:text-white transition">Impact &amp; Security</a>
            </div>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="gold-btn px-6 py-2.5 rounded-xl font-bold text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-bold text-sm transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="gold-btn px-6 py-2.5 rounded-xl font-bold text-sm">Register Chama</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center pt-24 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6 py-20 w-full relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-gold-500 px-4 py-2 rounded-full text-xs font-semibold mb-6">
                    <span class="w-2.5 h-2.5 bg-brand-emerald rounded-full pulse-emerald"></span>
                    Smart Ledger System for Kenyan Chamas
                </div>
                <h1 class="text-4xl md:text-6xl font-title font-black text-white leading-[1.1] mb-6">
                    Wealth Building, <br>
                    <span class="gold-gradient-text">Automated &amp; Trusted.</span>
                </h1>
                <p class="text-lg text-slate-400 mb-8 max-w-xl leading-relaxed">
                    Say goodbye to manual books, spreadsheets, and calculations. Automate your Chama’s ledger bookkeeping, credit scoring, late penalties, and M-Pesa SMS mapping.
                </p>
                
                <div class="flex flex-wrap gap-4 items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="gold-btn px-8 py-4 rounded-xl font-bold inline-flex items-center gap-2">
                            Go to Member Portal <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="gold-btn px-8 py-4 rounded-xl font-bold inline-flex items-center gap-2">
                            Register Your Group <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                        <a href="#features" class="border border-white/10 hover:border-white/20 bg-white/5 text-white px-8 py-4 rounded-xl font-semibold transition">
                            Explore Features
                        </a>
                    @endauth
                </div>

                <div class="mt-12 flex items-center gap-8">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border-2 border-brand-navy flex items-center justify-center font-bold text-slate-300 text-xs">AM</div>
                        <div class="w-10 h-10 rounded-full bg-slate-800 border-2 border-brand-navy flex items-center justify-center font-bold text-slate-300 text-xs">KW</div>
                        <div class="w-10 h-10 rounded-full bg-slate-800 border-2 border-brand-navy flex items-center justify-center font-bold text-slate-300 text-xs">JO</div>
                        <div class="w-10 h-10 rounded-full bg-slate-800 border-2 border-brand-navy flex items-center justify-center font-bold text-slate-300 text-xs">SM</div>
                    </div>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">
                        Helping <span class="text-white font-bold">50+ local savings circles</span> manage group liquidity, minimize delays, and secure member savings.
                    </p>
                </div>
            </div>

            <!-- Visual Dashboard Preview -->
            <div class="lg:col-span-5 relative">
                <div class="float-delayed">
                    <div class="premium-card rounded-2xl p-6 shadow-2xl relative overflow-hidden border border-white/5">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-brand-rose"></span>
                                <span class="w-3 h-3 rounded-full bg-gold-500"></span>
                                <span class="w-3 h-3 rounded-full bg-brand-emerald"></span>
                            </div>
                            <span class="text-[10px] bg-white/5 border border-white/10 text-slate-400 px-3 py-1 rounded-full font-bold">Live Preview</span>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                                <span class="text-xs text-slate-400 block mb-1">Chama Account Balance</span>
                                <div class="flex justify-between items-end">
                                    <span class="text-2xl font-title font-extrabold text-white">Ksh 1,248,500</span>
                                    <span class="text-[10px] text-brand-emerald bg-brand-emerald/10 border border-brand-emerald/20 px-2 py-0.5 rounded-full font-bold">+14.2%</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block mb-1">Active Loans</span>
                                    <span class="text-sm font-bold text-white">Ksh 480,000</span>
                                </div>
                                <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                                    <span class="text-[10px] text-slate-400 block mb-1">Credit Score</span>
                                    <span class="text-sm font-bold text-gold-500">8.2 / 10</span>
                                </div>
                            </div>

                            <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs text-slate-300 font-semibold">Ledger Entries</span>
                                    <span class="text-[10px] text-slate-500">M-Pesa Verified</span>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between items-center border-b border-white/5 pb-2">
                                        <span class="text-slate-400">Jane Kamau</span>
                                        <span class="text-brand-emerald font-bold">+Ksh 10,000</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400">Albert Mwangi</span>
                                        <span class="text-brand-emerald font-bold">+Ksh 5,000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Glow Background Behind Card -->
                <div class="absolute -z-10 w-48 h-48 bg-gold-600/10 rounded-full blur-[80px] -right-10 -bottom-10"></div>
                <div class="absolute -z-10 w-36 h-36 bg-brand-emerald/5 rounded-full blur-[85px] -left-10 -top-10"></div>
            </div>

        </div>
    </section>

    <!-- Platform Modules Features -->
    <section id="features" class="py-24 bg-brand-navy border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="text-gold-500 text-xs font-bold uppercase tracking-widest">Platform Core Modules</span>
                <h2 class="text-3xl md:text-5xl font-title font-black text-white mt-3 leading-tight">
                    Powering Kenyan Chamas with Modern Financial Tools
                </h2>
                <p class="text-slate-400 mt-4 text-base">
                    Align your operations with Kenyan constitution bylaws while removing errors, bookkeeping delays, and loan defaults.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: M-Pesa Integration -->
                <div class="premium-card p-8 rounded-2xl border border-white/5 shadow-lg relative group hover:border-gold-500/20 transition-all duration-300">
                    <div class="w-12 h-12 bg-gold-500/10 border border-gold-500/20 rounded-xl flex items-center justify-center text-gold-500 mb-6 group-hover:scale-110 transition duration-300">
                        <span class="material-symbols-outlined">sms</span>
                    </div>
                    <h3 class="text-lg font-title font-bold text-white mb-2">Automated M-Pesa Parsing</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Say goodbye to manual ledger entries. Copy and paste confirmation SMS strings to dynamically parse names, phone numbers, transactional amounts, and references. Unmapped cash is routed to a review queue.
                    </p>
                </div>

                <!-- Feature 2: Credit Scoring -->
                <div class="premium-card p-8 rounded-2xl border border-white/5 shadow-lg relative group hover:border-gold-500/20 transition-all duration-300">
                    <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-brand-emerald mb-6 group-hover:scale-110 transition duration-300">
                        <span class="material-symbols-outlined">calculate</span>
                    </div>
                    <h3 class="text-lg font-title font-bold text-white mb-2">Rule-Based Credit Scoring</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Calculate dynamic member credit scores on a scale from 1 to 10. The score is computed using past savings consistency, meeting attendance records, and debt punctuality to keep borrow limits safe.
                    </p>
                </div>

                <!-- Feature 3: Automated Penalties -->
                <div class="premium-card p-8 rounded-2xl border border-white/5 shadow-lg relative group hover:border-gold-500/20 transition-all duration-300">
                    <div class="w-12 h-12 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center justify-center text-brand-rose mb-6 group-hover:scale-110 transition duration-300">
                        <span class="material-symbols-outlined">gavel</span>
                    </div>
                    <h3 class="text-lg font-title font-bold text-white mb-2">Automatic Compliance & Fines</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Late contributions or delayed debt repayments trigger system compliance checks. Fines are calculated automatically, restricting further loan applications until penalties are fully cleared.
                    </p>
                </div>

                <!-- Feature 4: PDF Financial Statements -->
                <div class="premium-card p-8 rounded-2xl border border-white/5 shadow-lg relative group hover:border-gold-500/20 transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 mb-6 group-hover:scale-110 transition duration-300">
                        <span class="material-symbols-outlined">picture_as_pdf</span>
                    </div>
                    <h3 class="text-lg font-title font-bold text-white mb-2">Formatted PDF Statements</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        One-click PDF downloads compiling member statements or group-wide financial metrics. Aggregates liquidity, loan exposures, default rates, and interest earnings, powered by DOMPdf.
                    </p>
                </div>

                <!-- Feature 5: Member Portals -->
                <div class="premium-card p-8 rounded-2xl border border-white/5 shadow-lg relative group hover:border-gold-500/20 transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-500/10 border border-purple-500/20 rounded-xl flex items-center justify-center text-purple-400 mb-6 group-hover:scale-110 transition duration-300">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <h3 class="text-lg font-title font-bold text-white mb-2">Self-Service Portals</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Let member users view their individual savings, apply for loans, check repayments, track outstanding penalties, and manage profiles without booking manual treasurer time.
                    </p>
                </div>

                <!-- Feature 6: Security & Double-Entry -->
                <div class="premium-card p-8 rounded-2xl border border-white/5 shadow-lg relative group hover:border-gold-500/20 transition-all duration-300">
                    <div class="w-12 h-12 bg-teal-500/10 border border-teal-500/20 rounded-xl flex items-center justify-center text-teal-400 mb-6 group-hover:scale-110 transition duration-300">
                        <span class="material-symbols-outlined">security</span>
                    </div>
                    <h3 class="text-lg font-title font-bold text-white mb-2">Double-Entry Precision</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Zero-variance bookkeeping accuracy. Integrates explicitly cast database types (DECIMAL 10,2) to safeguard cash flow integrity and prevent float rounding vulnerabilities.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact/Stats -->
    <section id="stats" class="py-20 bg-brand-dark/50 border-t border-b border-white/5 relative">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <span class="text-4xl font-title font-black text-white block">Ksh 10B+</span>
                <span class="text-xs text-gold-500 font-bold uppercase tracking-wider mt-1 block">Chama Capital in Kenya</span>
            </div>
            <div>
                <span class="text-4xl font-title font-black text-white block">98.5%</span>
                <span class="text-xs text-gold-500 font-bold uppercase tracking-wider mt-1 block">SMS Parse Accuracy</span>
            </div>
            <div>
                <span class="text-4xl font-title font-black text-white block">&lt; 3s</span>
                <span class="text-xs text-gold-500 font-bold uppercase tracking-wider mt-1 block">Transaction Booking</span>
            </div>
            <div>
                <span class="text-4xl font-title font-black text-white block">100%</span>
                <span class="text-xs text-gold-500 font-bold uppercase tracking-wider mt-1 block">Transparency Guarantee</span>
            </div>
        </div>
    </section>

    <!-- Platform CTA -->
    <section class="py-24 bg-brand-navy relative">
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-title font-black text-white mb-4 leading-tight">
                Empower Your Chama's <br>Financial Future.
            </h2>
            <p class="text-slate-400 max-w-xl mx-auto mb-10 text-base leading-relaxed">
                Reconcile ledger accounts instantly, evaluate loan eligibilities fairly, and generate professional sheets for your next group meeting.
            </p>
            
            <div class="flex justify-center gap-4 flex-wrap">
                @auth
                    <a href="{{ route('dashboard') }}" class="gold-btn px-8 py-4 rounded-xl font-bold inline-flex items-center gap-2">
                        Go to Dashboard <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="gold-btn px-8 py-4 rounded-xl font-bold inline-flex items-center gap-2">
                        Register Your Chama <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                    <a href="#features" class="border border-white/10 hover:border-white/20 text-white px-8 py-4 rounded-xl font-semibold transition">
                        Explore Features
                    </a>
                @endauth
            </div>
        </div>
        
        <!-- Glow Backdrops -->
        <div class="absolute -z-10 w-96 h-96 bg-gold-600/5 rounded-full blur-[100px] left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2"></div>
    </section>

    <!-- Footer -->
    <footer class="bg-brand-navy border-t border-white/5 py-12 text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center text-brand-navy font-bold text-base">G</div>
                    <span class="text-base font-title font-bold text-white tracking-wide">Chama Gold</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    A premium Web-Based Financial Ledger and Loan Management Platform designed specifically to meet the operational needs of Kenyan Chamas.
                </p>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4 text-xs uppercase tracking-wider">Useful Directory</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#features" class="hover:text-white transition">Platform Features</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">My Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Log in</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Create Chama</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-xs uppercase tracking-wider">Compliance &amp; Safety</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Designed with high precision data types, secure ORM database bindings, hash encryptions, and strict role segregation.
                </p>
                <div class="mt-4 text-slate-400 font-bold text-[10px] uppercase tracking-widest">Made in Kenya 🇰🇪</div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 border-t border-white/5 mt-8 pt-8 text-center text-xs text-slate-600">
            © {{ date('Y') }} Chama Gold. All rights reserved.
        </div>
    </footer>

</body>
</html>