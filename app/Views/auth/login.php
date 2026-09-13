<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mint: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .text-shadow { text-shadow: 0 2px 14px rgba(0,0,0,0.35); }
        .text-shadow-lg { text-shadow: 0 4px 24px rgba(0,0,0,0.45); }
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 10px 40px -12px rgba(0,0,0,0.25);
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.16);
            transform: translateY(-3px);
        }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-page-load {
            animation: pageFadeIn 0.35s ease-out both;
        }
    </style>
</head>
<body class="bg-mint-50">
    <div class="flex min-h-screen animate-page-load">
        <!-- Left / branding panel (prioritized 70%) -->
        <div class="hidden lg:flex lg:w-[70%] relative bg-cover bg-center bg-no-repeat text-white overflow-hidden" style="background-image: url('<?= asset('images/login-bg.jpg') ?>')">
            <div class="absolute inset-0 bg-gradient-to-r from-mint-900/95 via-mint-900/75 to-mint-800/35"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-mint-900/70 via-transparent to-transparent"></div>

            <div class="relative z-10 flex flex-col justify-between w-full p-10 xl:p-16">
                <!-- Top / brand -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 glass rounded-2xl flex items-center justify-center">
                            <i data-lucide="sprout" class="w-8 h-8 text-mint-300"></i>
                        </div>
                        <div>
                            <div class="font-bold text-2xl tracking-tight text-shadow"><?= APP_NAME ?></div>
                            <div class="text-xs text-mint-200/80">Municipality of Polomolok</div>
                        </div>
                    </div>
                    <div class="hidden xl:inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-mint-300"></i>
                        Secure MVC System
                    </div>
                </div>

                <!-- Center / value prop -->
                <div class="space-y-8 max-w-2xl mt-8">
                    <div>
                        <div class="flex flex-wrap items-center gap-3 mb-5">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 glass rounded-full text-xs font-semibold">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-mint-300"></i>
                                Barangay Cannery Site
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 glass rounded-full text-xs font-semibold">
                                <i data-lucide="globe" class="w-3.5 h-3.5 text-mint-300"></i>
                                Polomolok, South Cotabato
                            </div>
                        </div>
                        <div class="text-sm font-medium text-mint-300 uppercase tracking-wider mb-2 text-shadow">Welcome to</div>
                        <h1 class="text-5xl xl:text-7xl font-bold leading-[1.1] text-shadow-lg">
                            Agro Nursery<br>
                            <span class="text-mint-300">Farm System</span>
                        </h1>
                        <p class="text-mint-100 text-base leading-relaxed mt-5 max-w-xl text-shadow">
                            A complete, centralized platform for tracking every seedling from source to sale — managing sales, batch quality, environmental conditions, inventory, and forecasts in one place.
                        </p>
                    </div>

                    <!-- Feature cards -->
                    <div class="grid grid-cols-2 xl:grid-cols-3 gap-4">
                        <div class="glass-card rounded-2xl p-5 transition duration-300">
                            <div class="w-10 h-10 glass rounded-2xl flex items-center justify-center mb-3 text-mint-300">
                                <i data-lucide="receipt" class="w-5 h-5"></i>
                            </div>
                            <div class="font-semibold text-sm">Sales Recording</div>
                            <div class="text-xs text-mint-100/80 mt-1">Auto receipts & tracking</div>
                        </div>
                        <div class="glass-card rounded-2xl p-5 transition duration-300">
                            <div class="w-10 h-10 glass rounded-2xl flex items-center justify-center mb-3 text-mint-300">
                                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                            </div>
                            <div class="font-semibold text-sm">Quality Grading</div>
                            <div class="text-xs text-mint-100/80 mt-1">Batch-by-batch ratings</div>
                        </div>
                        <div class="glass-card rounded-2xl p-5 transition duration-300">
                            <div class="w-10 h-10 glass rounded-2xl flex items-center justify-center mb-3 text-mint-300">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </div>
                            <div class="font-semibold text-sm">Traceability</div>
                            <div class="text-xs text-mint-100/80 mt-1">Seed-to-sale chain</div>
                        </div>
                        <div class="glass-card rounded-2xl p-5 transition duration-300">
                            <div class="w-10 h-10 glass rounded-2xl flex items-center justify-center mb-3 text-mint-300">
                                <i data-lucide="archive" class="w-5 h-5"></i>
                            </div>
                            <div class="font-semibold text-sm">Inventory</div>
                            <div class="text-xs text-mint-100/80 mt-1">Real-time stock alerts</div>
                        </div>
                        <div class="glass-card rounded-2xl p-5 transition duration-300">
                            <div class="w-10 h-10 glass rounded-2xl flex items-center justify-center mb-3 text-mint-300">
                                <i data-lucide="tags" class="w-5 h-5"></i>
                            </div>
                            <div class="font-semibold text-sm">Dynamic Pricing</div>
                            <div class="text-xs text-mint-100/80 mt-1">Rule-based pricing</div>
                        </div>
                        <div class="glass-card rounded-2xl p-5 transition duration-300">
                            <div class="w-10 h-10 glass rounded-2xl flex items-center justify-center mb-3 text-mint-300">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <div class="font-semibold text-sm">Ready-Date Forecast</div>
                            <div class="text-xs text-mint-100/80 mt-1">Growth-based estimates</div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="glass rounded-2xl p-6 flex flex-wrap items-center gap-8 sm:gap-10">
                        <div>
                            <div class="text-4xl font-bold text-shadow-lg">12</div>
                            <div class="text-sm text-mint-100/90 font-medium">Feature Sprints</div>
                        </div>
                        <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                        <div>
                            <div class="text-4xl font-bold text-shadow-lg">∞</div>
                            <div class="text-sm text-mint-100/90 font-medium">Batch Traceability</div>
                        </div>
                        <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                        <div>
                            <div class="text-4xl font-bold text-shadow-lg">24/7</div>
                            <div class="text-sm text-mint-100/90 font-medium">Live Inventory</div>
                        </div>
                    </div>
                </div>

                <!-- Bottom / footer -->
                <div class="flex items-center justify-between text-xs text-mint-100/80 mt-8">
                    <span class="text-shadow">&copy; <?= date('Y') ?> Agro Nursery Farm — Barangay Cannery Site, Polomolok</span>
                    <span class="flex items-center gap-1.5 text-shadow"><i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Secure MVC System</span>
                </div>
            </div>
        </div>

        <!-- Right / login form -->
        <div class="w-full lg:w-[30%] flex items-center justify-center p-6 bg-white">
            <div class="w-full max-w-sm">
                <div class="text-center mb-8">
                    <div class="w-14 h-14 bg-mint-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-mint-700">
                        <i data-lucide="sprout" class="w-8 h-8"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">Sign in</h1>
                    <p class="text-sm text-slate-500 mt-1">Enter your credentials to continue</p>
                </div>

                <?= renderFlash() ?>

                <form action="<?= url('login') ?>" method="POST" class="space-y-4">
                    <?= \App\Core\Csrf::field() ?>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="email" name="email" required placeholder="e.g. owner@agronursery.ph" class="w-full rounded-lg border-slate-200 focus:border-mint-500 focus:ring-mint-500 pl-10 pr-4 py-2.5 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="password" required placeholder="Enter your password" class="w-full rounded-lg border-slate-200 focus:border-mint-500 focus:ring-mint-500 pl-10 pr-4 py-2.5 text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600">
                            <input type="checkbox" class="rounded border-slate-300 text-mint-600 focus:ring-mint-500">
                            Remember me
                        </label>
                        <a href="#" class="text-mint-600 hover:underline font-medium">Forgot password?</a>
                    </div>

                    <button type="submit" class="w-full bg-mint-600 hover:bg-mint-700 text-white font-semibold py-2.5 rounded-lg transition shadow-md hover:shadow-lg transition duration-200 shadow-mint-200">Sign In</button>
                </form>

                <p class="text-xs text-slate-400 mt-6 text-center">
                    Default: owner@agronursery.ph / staff@agronursery.ph (password: password)
                </p>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
