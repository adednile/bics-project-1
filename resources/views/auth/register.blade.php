<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | Chama Gold & Trust</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#0b0f19',
                        dark: '#151d30',
                        gold: {
                            50: '#fefdf0',
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
            background: radial-gradient(circle at 50% 10%, rgba(197, 155, 39, 0.08) 0%, transparent 60%), #0b0f19;
        }
        .premium-card {
            background: rgba(21, 29, 48, 0.65);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }
        .gold-btn {
            background: linear-gradient(135deg, #e5c060 0%, #c59b27 100%);
            color: #0b0f19;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .gold-btn:hover {
            transform: translateY(-1px);
            opacity: 0.95;
            box-shadow: 0 4px 15px rgba(197, 155, 39, 0.25);
        }
    </style>
</head>
<body class="hero-bg min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-gold-500 to-gold-700 rounded-2xl mb-4 shadow-lg">
            <span class="material-symbols-outlined text-brand-navy text-4xl" style="font-variation-settings: 'FILL' 1;">account_balance</span>
        </div>
        <h2 class="text-3xl font-title font-extrabold text-white tracking-tight">Chama Gold &amp; Trust</h2>
        <p class="mt-2 text-sm text-slate-400">Register your savings circle and secure member capital.</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="premium-card py-8 px-6 sm:px-10 rounded-2xl border border-white/5 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-500 to-gold-700"></div>
            
            <form class="space-y-5" action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Group/Treasurer Full Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}" autofocus
                        class="appearance-none block w-full px-4 h-12 bg-white/5 border border-white/10 rounded-xl placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 transition-all text-sm" placeholder="John Doe">
                    @error('name')<p class="text-brand-rose text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="appearance-none block w-full px-4 h-12 bg-white/5 border border-white/10 rounded-xl placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 transition-all text-sm" placeholder="you@example.com">
                    @error('email')<p class="text-brand-rose text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Password</label>
                    <input id="password" name="password" type="password" required
                        class="appearance-none block w-full px-4 h-12 bg-white/5 border border-white/10 rounded-xl placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 transition-all text-sm" placeholder="••••••••">
                    @error('password')<p class="text-brand-rose text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="appearance-none block w-full px-4 h-12 bg-white/5 border border-white/10 rounded-xl placeholder-slate-500 text-white focus:outline-none focus:ring-2 focus:ring-gold-500/50 focus:border-gold-500 transition-all text-sm" placeholder="••••••••">
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-xl text-sm font-bold gold-btn shadow-md">
                        Create Chama Account
                    </button>
                </div>
            </form>
            
            <div class="mt-6 pt-6 border-t border-white/5 text-center">
                <p class="text-xs text-slate-400">Already have an account? <a href="{{ route('login') }}" class="text-gold-500 font-bold hover:text-gold-600 transition">Log In Instead</a></p>
            </div>
        </div>
    </div>
</body>
</html>