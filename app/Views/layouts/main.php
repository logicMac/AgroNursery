<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= \App\Core\Csrf::token() ?>">
    <title><?= e($title ?? APP_NAME) ?> — <?= APP_NAME ?></title>
    <script>
        (function() {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>?v=3">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
                        }
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }

        .nav-item.active {
            background-color: #dcfce7;
            color: #15803d;
            border-right: 4px solid #22c55e;
        }

        #sidebar.collapsed {
            width: 5rem;
            transition: width 0.3s ease;
        }
        #sidebar.collapsed .brand-text,
        #sidebar.collapsed .nav-text,
        #sidebar.collapsed .group-label,
        #sidebar.collapsed .chevron {
            display: none;
        }
        #sidebar.collapsed .nav-item {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        details[open] .chevron { transform: rotate(180deg); }

        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-page-load {
            animation: pageFadeIn 0.35s ease-out both;
        }

        thead { background-color: #f8fafc !important; }
        html body table th { color: #475569 !important; background-color: transparent !important; }

        /* Dark mode overrides */
        .dark body { background-color: #0f172a !important; color: #e2e8f0 !important; }
        .dark .bg-slate-50 { background-color: #1e293b !important; }
        html.dark thead { background-color: #1e293b !important; }
        html.dark table th { color: #e2e8f0 !important; background-color: transparent !important; }
        .dark .bg-mint-50 { background-color: #0f172a !important; }
        .dark .bg-white { background-color: #1e293b !important; }
        .dark aside { background-color: #1e293b !important; }
        .dark header { background-color: #1e293b !important; }
        .dark main { background-color: transparent !important; }
        .dark .border-mint-100 { border-color: #334155 !important; }
        .dark .border-slate-200 { border-color: #334155 !important; }
        .dark .border-gray-300 { border-color: #475569 !important; }
        .dark .text-slate-800 { color: #f8fafc !important; }
        .dark .text-slate-700 { color: #e2e8f0 !important; }
        .dark .text-slate-600 { color: #cbd5e1 !important; }
        .dark .text-slate-500 { color: #94a3b8 !important; }
        .dark .text-slate-400 { color: #94a3b8 !important; }
        .dark .text-mint-700 { color: #86efac !important; }
        .dark .text-mint-600 { color: #4ade80 !important; }
        .dark .bg-mint-100 { background-color: #064e3b !important; }
        .dark .bg-slate-100 { background-color: #334155 !important; }
        .dark .bg-green-100 { background-color: #064e3b !important; }
        .dark .text-green-700 { color: #86efac !important; }
        .dark .bg-amber-100 { background-color: #713f12 !important; }
        .dark .text-amber-700 { color: #fcd34d !important; }
        .dark .bg-red-100 { background-color: #7f1d1d !important; }
        .dark .text-red-700 { color: #fca5a5 !important; }
        .dark .nav-item { color: #cbd5e1 !important; }
        .dark .nav-item.active { background-color: #064e3b !important; color: #86efac !important; border-right-color: #22c55e !important; }
        .dark .hover\:bg-mint-50:hover { background-color: #334155 !important; }
        .dark .hover\:bg-mint-50\/60:hover { background-color: rgba(51, 65, 85, 0.6) !important; }
        .dark .nav-item:hover { background-color: #334155 !important; }
        .dark input,
        .dark select,
        .dark textarea { background-color: #0f172a !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        .dark #sidebarOverlay { background-color: rgba(0, 0, 0, 0.6) !important; }

        .badge-neutral { background-color: #e2e8f0 !important; color: #334155 !important; }
        html.dark .badge-neutral { background-color: #475569 !important; color: #e2e8f0 !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 antialiased">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/30 z-20 hidden lg:hidden"></div>

        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-mint-100 hidden lg:static lg:flex flex-col transition-[width] duration-300">
            <div class="h-16 flex items-center justify-between px-6 border-b border-mint-100">
                <span class="text-mint-700 font-bold text-lg flex items-center gap-2">
                    <i data-lucide="sprout" class="w-6 h-6"></i>
                    <span class="brand-text"><?= APP_NAME ?></span>
                </span>
                <button id="sidebarCollapse" class="hidden lg:block text-slate-500 hover:text-mint-700 p-1.5 rounded-md hover:bg-mint-50 transition" title="Collapse sidebar">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 space-y-2">
                <?php
                $navGroups = [
                    'General' => [
                        ['dashboard', 'Dashboard', 'layout-dashboard', 'text-sky-600'],
                        ['sales', 'Sales', 'receipt', 'text-emerald-600'],
                    ],
                    'Production' => [
                        ['products', 'Products', 'package', 'text-indigo-600'],
                        ['batches', 'Batches', 'grid-3x3', 'text-violet-600'],
                        ['quality', 'Quality', 'clipboard-check', 'text-amber-600'],
                        ['inventory', 'Inventory', 'archive', 'text-rose-600'],
                    ],
                    'Planning' => [
                        ['pricing', 'Pricing', 'tags', 'text-fuchsia-600'],
                        ['forecast', 'Forecast', 'calendar', 'text-cyan-600'],
                    ],
                    'Care & Trace' => [
                        ['environment', 'Environment', 'cloud-sun', 'text-sky-600'],
                        ['traceability', 'Traceability', 'search', 'text-teal-600'],
                        ['shrinkage', 'Shrinkage', 'trending-down', 'text-red-600'],
                    ],
                    'Admin' => [
                        ['users', 'Users', 'users', 'text-slate-600'],
                        ['settings', 'AI Settings', 'sparkles', 'text-fuchsia-600'],
                    ],
                ];
                $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '';
                ?>
                <?php foreach ($navGroups as $label => $items):
                    $visible = [];
                    foreach ($items as $n) {
                        if ($n[0] === 'dashboard' || !empty($_SESSION['user']['permissions'][$n[0]])) $visible[] = $n;
                    }
                    if (empty($visible)) continue;
                    $hasActive = false;
                    foreach ($visible as $n) {
                        $link = url($n[0]);
                        if ($link !== '/' && strpos($currentPath, $link) === 0) { $hasActive = true; break; }
                    }
                ?>
                <details class="group-nav" open>
                    <summary class="px-6 py-2.5 text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center justify-between cursor-pointer select-none hover:text-mint-700 transition">
                        <span class="group-label"><?= $label ?></span>
                        <i data-lucide="chevron-down" class="chevron w-3.5 h-3.5 transition-transform duration-200"></i>
                    </summary>
                    <div class="py-1 space-y-1">
                        <?php foreach ($visible as $n):
                            $link = url($n[0]);
                            $active = ($link !== '/' && strpos($currentPath, $link) === 0);
                        ?>
                        <a href="<?= $link ?>" class="nav-item flex items-center gap-3 px-6 py-2.5 text-sm font-medium hover:bg-mint-50 transition <?= $active ? 'active' : 'text-slate-600' ?>">
                            <i data-lucide="<?= $n[2] ?>" class="w-4 h-4 <?= $n[3] ?>"></i>
                            <span class="nav-text"><?= $n[1] ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </details>
                <?php endforeach; ?>
            </nav>
            <div class="p-4 border-t border-mint-100 lg:hidden">
                <button id="sidebarClose" class="w-full flex items-center justify-center gap-2 text-slate-600 hover:text-mint-700 py-2 text-sm">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Close Menu
                </button>
            </div>
        </aside>
        <div class="flex-1 flex flex-col min-w-0 w-full">
            <header class="h-16 bg-white border-b border-mint-100 flex items-center justify-between px-6">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="lg:hidden text-slate-500 hover:text-mint-700 p-1.5 rounded-md hover:bg-mint-50 transition">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h2 class="font-semibold text-slate-800"><?= e($title ?? 'Dashboard') ?></h2>
                </div>
                <div class="flex items-center gap-4">
                    <button id="themeToggle" class="text-slate-500 hover:text-mint-700 p-1.5 rounded-md hover:bg-mint-50 transition" title="Toggle dark mode">
                        <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
                        <i data-lucide="moon" class="w-4 h-4 dark:hidden"></i>
                    </button>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full <?= ($_SESSION['user']['role'] ?? '') === 'owner' ? 'bg-mint-100 text-mint-800' : 'bg-slate-100 text-slate-700' ?>">
                        <?= ucfirst(e($_SESSION['user']['role'] ?? 'user')) ?>
                    </span>
                    <span class="text-sm hidden sm:inline"><?= e($_SESSION['user']['name'] ?? '') ?></span>
                    <form action="<?= url('logout') ?>" method="POST" class="inline">
                        <?= \App\Core\Csrf::field() ?>
                        <button class="text-slate-500 hover:text-red-600"><i data-lucide="log-out" class="w-4 h-4"></i></button>
                    </form>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-6 animate-page-load">
                <?= renderFlash() ?>
