<?php require __DIR__ . '/../layouts/main.php'; ?>

<?php if ($isOwner): ?>

<!-- Owner / Analytics Dashboard -->

<!-- Welcome Banner -->
<div class="bg-mint-600 rounded-2xl p-6 mb-6 shadow-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 overflow-hidden relative">
    <div class="relative z-10">
        <h1 class="text-2xl font-bold text-white">Welcome back, <?= e($user['name']) ?>!</h1>
        <p class="text-sm text-mint-50 mt-1">Here's your nursery business overview for today.</p>
        <div class="flex items-center gap-4 mt-3 text-xs text-mint-50">
            <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> <?= date('F j, Y') ?></span>
            <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> <?= date('g:i A') ?></span>
        </div>
    </div>
    <div class="relative z-10 flex gap-3">
        <a href="<?= url('sales/create') ?>" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition duration-200 border border-white/20">
            <i data-lucide="plus" class="w-4 h-4"></i> New Sale
        </a>
        <a href="<?= url('settings') ?>" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition duration-200 border border-white/20">
            <i data-lucide="sparkles" class="w-4 h-4"></i> AI Settings
        </a>
    </div>
    <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20"></div>
    <div class="absolute right-12 bottom-0 w-40 h-40 bg-white/5 rounded-full -mb-16"></div>
</div>

<!-- Quick Action Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="<?= url('sales') ?>" class="bg-mint-600 hover:bg-mint-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="receipt" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">View Sales</span>
        <span class="text-xs text-mint-50">Track transactions & revenue</span>
    </a>
    <a href="<?= url('products') ?>" class="bg-slate-700 hover:bg-slate-800 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="package" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Manage Products</span>
        <span class="text-xs text-slate-200">Inventory & stock levels</span>
    </a>
    <a href="<?= url('batches') ?>" class="bg-mint-600 hover:bg-mint-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="grid-3x3" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">View Batches</span>
        <span class="text-xs text-mint-50">Production & tracking</span>
    </a>
    <a href="<?= url('settings') ?>" class="bg-slate-700 hover:bg-slate-800 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="sparkles" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">AI Settings</span>
        <span class="text-xs text-slate-200">Configure Groq & features</span>
    </a>
</div>

<!-- Task Cards Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Low Stock Alerts</h3>
                    <p class="text-xs text-slate-500">Items below minimum</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count($lowStock) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count($lowStock) ?> item(s)</span>
        </div>
        <div class="p-5">
            <?php if (empty($lowStock)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-mint-300"></i>
                <p class="text-sm text-slate-500 font-medium">No low stock items</p>
                <p class="text-xs text-slate-400 mt-1">All products are well stocked.</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($lowStock as $p): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-slate-50 transition duration-150">
                    <span class="w-2 h-2 bg-slate-400 rounded-full mt-1.5 shrink-0"></span>
                    <span class="text-slate-700"><?= e($p['name']) ?> <span class="text-slate-500">(<?= $p['current_stock'] ?> / <?= $p['min_stock'] ?>)</span></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Batches Ready Soon -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Batches Ready Soon</h3>
                    <p class="text-xs text-slate-500">Next 7 days</p>
                </div>
            </div>
            <span class="text-xs font-medium text-mint-700 bg-mint-50 px-2.5 py-1 rounded-full"><?= count($readySoon) ?> batch(es)</span>
        </div>
        <div class="p-5">
            <?php if (empty($readySoon)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                <p class="text-sm text-slate-500 font-medium">No batches nearing ready date</p>
                <p class="text-xs text-slate-400 mt-1">Nothing due in the next 7 days.</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($readySoon as $b): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-mint-50 transition duration-150">
                    <span class="w-2 h-2 bg-mint-500 rounded-full mt-1.5 shrink-0"></span>
                    <div>
                        <span class="text-slate-700 font-medium"><?= e($b['lot_id']) ?></span>
                        <span class="text-slate-500 text-xs block"><?= e($b['product_name']) ?> — <?= e($b['expected_ready_date']) ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Shrinkage Watch -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i data-lucide="trending-down" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Shrinkage Watch</h3>
                    <p class="text-xs text-slate-500">Batches with losses</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) ?> batch(es)</span>
        </div>
        <div class="p-5">
            <?php
            $lossBatches = array_filter($shrinkage, fn($s) => $s['rate'] > 0);
            if (empty($lossBatches)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-mint-300"></i>
                <p class="text-sm text-slate-500 font-medium">No shrinkage recorded</p>
                <p class="text-xs text-slate-400 mt-1">All batches are healthy.</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach (array_slice($lossBatches, 0, 5) as $s): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-slate-50 transition duration-150">
                    <span class="w-2 h-2 bg-slate-400 rounded-full mt-1.5 shrink-0"></span>
                    <div>
                        <span class="text-slate-700 font-medium"><?= e($s['lot_id']) ?></span>
                        <span class="text-slate-500 text-xs block"><?= e($s['product_name']) ?> — <?= $s['rate'] ?>% lost</span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Analytics + Chart Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Top Selling Products -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Top Selling Products</h3>
                    <p class="text-xs text-slate-500">By quantity sold</p>
                </div>
            </div>
            <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">₱<?= number_format(array_sum(array_column($revenue, 'total')), 0) ?> total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Product</th>
                        <th class="text-right px-6 py-3 font-semibold">Qty</th>
                        <th class="text-right px-6 py-3 font-semibold">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($topProducts)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <i data-lucide="package-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No sales data yet</p>
                            <p class="text-sm text-slate-400 mt-1">Sales will appear here once recorded.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($topProducts as $p): ?>
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-3.5 font-medium text-slate-800"><?= e($p['name']) ?></td>
                        <td class="px-6 py-3.5 text-right text-slate-600"><?= $p['qty'] ?></td>
                        <td class="px-6 py-3.5 text-right font-semibold text-mint-700">₱<?= number_format($p['revenue'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grade Distribution Chart -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                <i data-lucide="pie-chart" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800">Grade Distribution</h3>
                <p class="text-xs text-slate-500">By assessment count</p>
            </div>
        </div>
        <div class="p-5 flex items-center justify-center" style="min-height: 240px;">
            <div style="position: relative; width: 100%; max-width: 260px; height: 220px;">
                <canvas id="ownerQualityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const qLabels = <?= json_encode(array_keys($qualityGrades)) ?>;
    const qData = <?= json_encode(array_values($qualityGrades)) ?>;
    new Chart(document.getElementById('ownerQualityChart'), {
        type: 'doughnut',
        data: { labels: qLabels, datasets: [{ data: qData, backgroundColor: ['#16a34a','#22c55e','#86efac','#bbf7d0','#4ade80','#15803d'], borderWidth: 0 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } },
            cutout: '65%'
        }
    });
</script>

<?php else: ?>

<!-- Staff / Task Dashboard -->

<!-- Welcome Banner -->
<div class="bg-mint-600 rounded-2xl p-6 mb-6 shadow-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 overflow-hidden relative">
    <div class="relative z-10">
        <h1 class="text-2xl font-bold text-white">Good day, <?= e($user['name']) ?>!</h1>
        <p class="text-sm text-mint-50 mt-1">Here are your tasks and quick actions for today.</p>
        <div class="flex items-center gap-4 mt-3 text-xs text-mint-50">
            <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> <?= date('F j, Y') ?></span>
            <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> <?= date('g:i A') ?></span>
        </div>
    </div>
    <div class="relative z-10 flex gap-3">
        <a href="<?= url('environment/create') ?>" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition duration-200 border border-white/20">
            <i data-lucide="cloud-sun" class="w-4 h-4"></i> Log Conditions
        </a>
        <a href="<?= url('quality/create') ?>" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition duration-200 border border-white/20">
            <i data-lucide="clipboard-check" class="w-4 h-4"></i> Quality Check
        </a>
    </div>
    <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20"></div>
    <div class="absolute right-12 bottom-0 w-40 h-40 bg-white/5 rounded-full -mb-16"></div>
</div>

<!-- Quick Action Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="<?= url('sales/create') ?>" class="bg-mint-600 hover:bg-mint-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="receipt" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">New Sale</span>
        <span class="text-xs text-mint-50">Record a transaction</span>
    </a>
    <a href="<?= url('environment/create') ?>" class="bg-slate-700 hover:bg-slate-800 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="cloud-sun" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Log Conditions</span>
        <span class="text-xs text-slate-200">Care & environment</span>
    </a>
    <a href="<?= url('quality/create') ?>" class="bg-mint-600 hover:bg-mint-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="clipboard-check" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Quality Check</span>
        <span class="text-xs text-mint-50">Assess a batch</span>
    </a>
    <a href="<?= url('shrinkage/create') ?>" class="bg-slate-700 hover:bg-slate-800 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="trending-down" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Record Loss</span>
        <span class="text-xs text-slate-200">Dead / damaged stock</span>
    </a>
</div>

<!-- Task Cards Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="calendar-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Care Logs Due Today</h3>
                    <p class="text-xs text-slate-500">Scheduled environment tasks</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count($dueToday) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count($dueToday) ?> task(s)</span>
        </div>
        <div class="p-5">
            <?php if (empty($dueToday)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-mint-300"></i>
                <p class="text-sm text-slate-500 font-medium">No care logs due today</p>
                <p class="text-xs text-slate-400 mt-1">All caught up!</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($dueToday as $t): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-mint-50 transition duration-150">
                    <span class="w-2 h-2 bg-mint-500 rounded-full mt-1.5 shrink-0"></span>
                    <div>
                        <span class="text-slate-700 font-medium"><?= e($t['activity']) ?></span>
                        <span class="text-slate-500 text-xs block"><?= e($t['lot_id'] ?? '-') ?> — <?= e($t['product_name'] ?? '') ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Low Stock Alerts</h3>
                    <p class="text-xs text-slate-500">Items below minimum</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count($lowStock) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count($lowStock) ?> item(s)</span>
        </div>
        <div class="p-5">
            <?php if (empty($lowStock)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-mint-300"></i>
                <p class="text-sm text-slate-500 font-medium">No low stock items</p>
                <p class="text-xs text-slate-400 mt-1">All products are well stocked.</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($lowStock as $p): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-slate-50 transition duration-150">
                    <span class="w-2 h-2 bg-slate-400 rounded-full mt-1.5 shrink-0"></span>
                    <span class="text-slate-700"><?= e($p['name']) ?> <span class="text-slate-500">(<?= $p['current_stock'] ?> / <?= $p['min_stock'] ?>)</span></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Batches Ready Soon</h3>
                    <p class="text-xs text-slate-500">Next 7 days</p>
                </div>
            </div>
            <span class="text-xs font-medium text-mint-700 bg-mint-50 px-2.5 py-1 rounded-full"><?= count($readySoon) ?> batch(es)</span>
        </div>
        <div class="p-5">
            <?php if (empty($readySoon)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                <p class="text-sm text-slate-500 font-medium">No batches nearing ready date</p>
                <p class="text-xs text-slate-400 mt-1">Nothing due in the next 7 days.</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($readySoon as $b): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-mint-50 transition duration-150">
                    <span class="w-2 h-2 bg-mint-500 rounded-full mt-1.5 shrink-0"></span>
                    <div>
                        <span class="text-slate-700 font-medium"><?= e($b['lot_id']) ?></span>
                        <span class="text-slate-500 text-xs block"><?= e($b['product_name']) ?> — <?= e($b['expected_ready_date']) ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quality + Chart Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Quality Assessments -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Recent Quality Assessments</h3>
                    <p class="text-xs text-slate-500">Latest logged quality checks</p>
                </div>
            </div>
            <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full"><?= count($pendingQuality) ?> total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Lot</th>
                        <th class="text-left px-6 py-3 font-semibold">Grade</th>
                        <th class="text-left px-6 py-3 font-semibold">Assessed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($pendingQuality)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <i data-lucide="clipboard-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No assessments yet</p>
                            <p class="text-sm text-slate-400 mt-1">Quality checks will appear here once logged.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach (array_slice($pendingQuality, 0, 5) as $q): ?>
                    <?php
                    $grade = strtolower($q['grade'] ?? '');
                    $gradeClass = match($grade) {
                        'excellent', 'a' => 'bg-mint-100 text-mint-700',
                        'good', 'b' => 'bg-mint-50 text-mint-600',
                        'fair', 'c' => 'bg-slate-200 text-slate-700',
                        'poor', 'd' => 'bg-slate-300 text-slate-800',
                        default => 'bg-slate-100 text-slate-700'
                    };
                    ?>
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-3.5 font-semibold text-slate-700"><?= e($q['lot_id']) ?></td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $gradeClass ?>"><?= e($q['grade']) ?></span>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600"><?= e($q['assessed_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quality Grade Distribution Chart -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                <i data-lucide="pie-chart" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800">Grade Distribution</h3>
                <p class="text-xs text-slate-500">By assessment count</p>
            </div>
        </div>
        <div class="p-5 flex items-center justify-center" style="min-height: 240px;">
            <div style="position: relative; width: 100%; max-width: 260px; height: 220px;">
                <canvas id="staffQualityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const qLabels = <?= json_encode(array_keys($qualityGrades)) ?>;
    const qData = <?= json_encode(array_values($qualityGrades)) ?>;
    new Chart(document.getElementById('staffQualityChart'), {
        type: 'doughnut',
        data: { labels: qLabels, datasets: [{ data: qData, backgroundColor: ['#16a34a','#22c55e','#86efac','#bbf7d0','#4ade80','#15803d'], borderWidth: 0 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } },
            cutout: '65%'
        }
    });
</script>

<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
