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

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200">
        <div class="flex items-center justify-between mb-3">
            <div class="h-11 w-11 rounded-xl bg-mint-100 flex items-center justify-center text-mint-700">
                <i data-lucide="dollar-sign" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-mint-700 bg-mint-50 px-2 py-1 rounded-full">30 days</span>
        </div>
        <div class="text-xs text-slate-500 font-medium">Revenue</div>
        <div class="text-2xl font-bold text-slate-800 mt-0.5">₱<?= number_format(array_sum(array_column($revenue, 'total')), 2) ?></div>
        <div class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
            <i data-lucide="trending-up" class="w-3 h-3 text-mint-600"></i>
            <?= count($revenue) ?> sales days
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200">
        <div class="flex items-center justify-between mb-3">
            <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium <?= count($lowStock) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2 py-1 rounded-full"><?= count($lowStock) > 0 ? 'Attention' : 'OK' ?></span>
        </div>
        <div class="text-xs text-slate-500 font-medium">Low Stock Items</div>
        <div class="text-2xl font-bold text-slate-800 mt-0.5"><?= count($lowStock) ?></div>
        <div class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
            <i data-lucide="package" class="w-3 h-3 text-slate-400"></i>
            Needs reorder
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200">
        <div class="flex items-center justify-between mb-3">
            <div class="h-11 w-11 rounded-xl bg-mint-100 flex items-center justify-center text-mint-700">
                <i data-lucide="calendar-check" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-mint-700 bg-mint-50 px-2 py-1 rounded-full">7 days</span>
        </div>
        <div class="text-xs text-slate-500 font-medium">Batches Ready Soon</div>
        <div class="text-2xl font-bold text-slate-800 mt-0.5"><?= count($readySoon) ?></div>
        <div class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
            <i data-lucide="sprout" class="w-3 h-3 text-mint-600"></i>
            Approaching harvest
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200">
        <div class="flex items-center justify-between mb-3">
            <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <i data-lucide="trending-down" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium <?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2 py-1 rounded-full"><?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) > 0 ? 'Monitor' : 'OK' ?></span>
        </div>
        <div class="text-xs text-slate-500 font-medium">Shrinkage Batches</div>
        <div class="text-2xl font-bold text-slate-800 mt-0.5"><?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) ?></div>
        <div class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
            <i data-lucide="activity" class="w-3 h-3 text-slate-400"></i>
            With losses recorded
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <a href="<?= url('sales') ?>" class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md hover:border-mint-300 transition duration-200 flex flex-col items-center gap-2 group">
        <div class="h-10 w-10 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700 group-hover:bg-mint-600 group-hover:text-white transition duration-200">
            <i data-lucide="receipt" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-medium text-slate-700">Sales</span>
    </a>
    <a href="<?= url('products') ?>" class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md hover:border-mint-300 transition duration-200 flex flex-col items-center gap-2 group">
        <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-mint-600 group-hover:text-white transition duration-200">
            <i data-lucide="package" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-medium text-slate-700">Products</span>
    </a>
    <a href="<?= url('batches') ?>" class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md hover:border-mint-300 transition duration-200 flex flex-col items-center gap-2 group">
        <div class="h-10 w-10 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700 group-hover:bg-mint-600 group-hover:text-white transition duration-200">
            <i data-lucide="grid-3x3" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-medium text-slate-700">Batches</span>
    </a>
    <a href="<?= url('quality') ?>" class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md hover:border-mint-300 transition duration-200 flex flex-col items-center gap-2 group">
        <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-mint-600 group-hover:text-white transition duration-200">
            <i data-lucide="clipboard-check" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-medium text-slate-700">Quality</span>
    </a>
    <a href="<?= url('forecast') ?>" class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md hover:border-mint-300 transition duration-200 flex flex-col items-center gap-2 group">
        <div class="h-10 w-10 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700 group-hover:bg-mint-600 group-hover:text-white transition duration-200">
            <i data-lucide="calendar" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-medium text-slate-700">Forecast</span>
    </a>
    <a href="<?= url('shrinkage') ?>" class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md hover:border-mint-300 transition duration-200 flex flex-col items-center gap-2 group">
        <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-mint-600 group-hover:text-white transition duration-200">
            <i data-lucide="trending-down" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-medium text-slate-700">Shrinkage</span>
    </a>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="line-chart" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Revenue Trend</h3>
                    <p class="text-xs text-slate-500">Daily sales — last 30 days</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-mint-700">₱<?= number_format(array_sum(array_column($revenue, 'total')), 0) ?></div>
                <div class="text-xs text-slate-400">Total</div>
            </div>
        </div>
        <div class="p-5">
            <div style="position: relative; width: 100%; height: 260px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quality Distribution -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                <i data-lucide="pie-chart" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800">Grade Distribution</h3>
                <p class="text-xs text-slate-500">Quality assessments</p>
            </div>
        </div>
        <div class="p-5 flex items-center justify-center" style="min-height: 260px;">
            <div style="position: relative; width: 100%; max-width: 240px; height: 220px;">
                <canvas id="ownerQualityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Top Products -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
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
            <a href="<?= url('sales') ?>" class="text-xs text-mint-700 hover:text-mint-600 font-medium flex items-center gap-1">
                View all <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">#</th>
                        <th class="text-left px-6 py-3 font-semibold">Product</th>
                        <th class="text-right px-6 py-3 font-semibold">Qty</th>
                        <th class="text-right px-6 py-3 font-semibold">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($topProducts)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center">
                            <i data-lucide="package-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No sales data yet</p>
                            <p class="text-xs text-slate-400 mt-1">Sales will appear here once recorded.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $rank = 1; foreach ($topProducts as $p): ?>
                    <?php
                    $rankColors = ['bg-mint-100 text-mint-700', 'bg-slate-200 text-slate-700', 'bg-slate-100 text-slate-500'];
                    $rankColor = $rankColors[$rank - 1] ?? 'bg-slate-100 text-slate-500';
                    ?>
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold <?= $rankColor ?>"><?= $rank ?></span>
                        </td>
                        <td class="px-6 py-3.5 font-medium text-slate-800"><?= e($p['name']) ?></td>
                        <td class="px-6 py-3.5 text-right text-slate-600"><?= $p['qty'] ?></td>
                        <td class="px-6 py-3.5 text-right font-semibold text-mint-700">₱<?= number_format($p['revenue'], 2) ?></td>
                    </tr>
                    <?php $rank++; endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quality vs Sales -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Quality vs Sales</h3>
                    <p class="text-xs text-slate-500">Avg price per grade</p>
                </div>
            </div>
            <a href="<?= url('quality') ?>" class="text-xs text-mint-700 hover:text-mint-600 font-medium flex items-center gap-1">
                View all <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Grade</th>
                        <th class="text-right px-6 py-3 font-semibold">Sold</th>
                        <th class="text-right px-6 py-3 font-semibold">Avg Price</th>
                        <th class="text-left px-6 py-3 font-semibold">Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($gradeSales)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center">
                            <i data-lucide="clipboard-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No grade data yet</p>
                            <p class="text-xs text-slate-400 mt-1">Grade sales will appear here.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php
                    $maxSold = max(array_column($gradeSales, 'sold')) ?: 1;
                    foreach ($gradeSales as $g):
                    $pct = round(($g['sold'] / $maxSold) * 100);
                    ?>
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($g['quality_grade']) ?></span>
                        </td>
                        <td class="px-6 py-3.5 text-right text-slate-600"><?= $g['sold'] ?></td>
                        <td class="px-6 py-3.5 text-right font-semibold text-slate-700">₱<?= number_format($g['avg_price'], 2) ?></td>
                        <td class="px-6 py-3.5">
                            <div class="w-full bg-slate-100 rounded-full h-2 min-w-[80px]">
                                <div class="bg-mint-500 rounded-full h-2 transition-all duration-500" style="width: <?= $pct ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Alerts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Low Stock -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Low Stock</h3>
                    <p class="text-xs text-slate-500">Items needing reorder</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count($lowStock) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count($lowStock) ?> item(s)</span>
        </div>
        <div class="p-5 max-h-64 overflow-y-auto">
            <?php if (empty($lowStock)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-mint-300"></i>
                <p class="text-sm text-slate-500 font-medium">All products well stocked</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($lowStock as $p): ?>
                <li class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition duration-150">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 bg-slate-400 rounded-full shrink-0"></span>
                        <span class="text-slate-700 font-medium"><?= e($p['name']) ?></span>
                    </div>
                    <span class="text-xs text-slate-500 font-semibold"><?= $p['current_stock'] ?> / <?= $p['min_stock'] ?></span>
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
                    <h3 class="font-semibold text-slate-800">Ready Soon</h3>
                    <p class="text-xs text-slate-500">Batches approaching harvest</p>
                </div>
            </div>
            <span class="text-xs font-medium text-mint-700 bg-mint-50 px-2.5 py-1 rounded-full"><?= count($readySoon) ?> batch(es)</span>
        </div>
        <div class="p-5 max-h-64 overflow-y-auto">
            <?php if (empty($readySoon)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                <p class="text-sm text-slate-500 font-medium">No batches due soon</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($readySoon as $b): ?>
                <li class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition duration-150">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 bg-mint-500 rounded-full shrink-0"></span>
                        <div>
                            <span class="text-slate-700 font-medium"><?= e($b['lot_id']) ?></span>
                            <span class="text-slate-500 text-xs block"><?= e($b['product_name']) ?></span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500 font-semibold whitespace-nowrap"><?= e($b['expected_ready_date']) ?></span>
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
                    <p class="text-xs text-slate-500">Batches with highest loss rate</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) > 0 ? 'text-mint-700 bg-mint-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) ?> batch(es)</span>
        </div>
        <div class="p-5 max-h-64 overflow-y-auto">
            <?php
            $lossBatches = array_filter($shrinkage, fn($s) => $s['rate'] > 0);
            if (empty($lossBatches)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-mint-300"></i>
                <p class="text-sm text-slate-500 font-medium">No shrinkage recorded</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach (array_slice($lossBatches, 0, 8) as $s): ?>
                <li class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition duration-150">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 bg-slate-400 rounded-full shrink-0"></span>
                        <div>
                            <span class="text-slate-700 font-medium"><?= e($s['lot_id']) ?></span>
                            <span class="text-slate-500 text-xs block"><?= e($s['product_name']) ?></span>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-slate-500"><?= $s['rate'] ?>% lost</span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rev = <?= json_encode(array_column($revenue, 'total')) ?>;
    const labels = <?= json_encode(array_map(fn($r) => $r['day'], $revenue)) ?>;
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: { labels, datasets: [{
            data: rev,
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointBackgroundColor: '#16a34a',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    const qLabels = <?= json_encode(array_keys($qualityGrades)) ?>;
    const qData = <?= json_encode(array_values($qualityGrades)) ?>;
    new Chart(document.getElementById('ownerQualityChart'), {
        type: 'doughnut',
        data: { labels: qLabels, datasets: [{ data: qData, backgroundColor: ['#16a34a','#22c55e','#86efac','#bbf7d0','#4ade80','#15803d'], borderWidth: 0 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } } },
            cutout: '65%'
        }
    });
</script>

<?php else: ?>

<!-- Staff / Task Dashboard -->

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 rounded-2xl p-6 mb-6 shadow-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 overflow-hidden relative">
    <div class="relative z-10">
        <h1 class="text-2xl font-bold text-white">Good day, <?= e($user['name']) ?>!</h1>
        <p class="text-sm text-violet-50 mt-1">Here are your tasks and quick actions for today.</p>
        <div class="flex items-center gap-4 mt-3 text-xs text-violet-50">
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
    <a href="<?= url('sales/create') ?>" class="bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="receipt" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">New Sale</span>
        <span class="text-xs text-emerald-100">Record a transaction</span>
    </a>
    <a href="<?= url('environment/create') ?>" class="bg-gradient-to-br from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="cloud-sun" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Log Conditions</span>
        <span class="text-xs text-sky-100">Care & environment</span>
    </a>
    <a href="<?= url('quality/create') ?>" class="bg-gradient-to-br from-violet-500 to-violet-600 hover:from-violet-600 hover:to-violet-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="clipboard-check" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Quality Check</span>
        <span class="text-xs text-violet-100">Assess a batch</span>
    </a>
    <a href="<?= url('shrinkage/create') ?>" class="bg-gradient-to-br from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between">
        <i data-lucide="trending-down" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Record Loss</span>
        <span class="text-xs text-rose-100">Dead / damaged stock</span>
    </a>
</div>

<!-- Task Cards Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-sky-50 to-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-sky-100 flex items-center justify-center text-sky-600">
                    <i data-lucide="calendar-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Care Logs Due Today</h3>
                    <p class="text-xs text-slate-500">Scheduled environment tasks</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count($dueToday) > 0 ? 'text-sky-600 bg-sky-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count($dueToday) ?> task(s)</span>
        </div>
        <div class="p-5">
            <?php if (empty($dueToday)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-emerald-300"></i>
                <p class="text-sm text-slate-500 font-medium">No care logs due today</p>
                <p class="text-xs text-slate-400 mt-1">All caught up!</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($dueToday as $t): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-sky-50 transition duration-150">
                    <span class="w-2 h-2 bg-sky-500 rounded-full mt-1.5 shrink-0"></span>
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
        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Low Stock Alerts</h3>
                    <p class="text-xs text-slate-500">Items below minimum</p>
                </div>
            </div>
            <span class="text-xs font-medium <?= count($lowStock) > 0 ? 'text-amber-600 bg-amber-50' : 'text-slate-500 bg-slate-100' ?> px-2.5 py-1 rounded-full"><?= count($lowStock) ?> item(s)</span>
        </div>
        <div class="p-5">
            <?php if (empty($lowStock)): ?>
            <div class="text-center py-6">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-emerald-300"></i>
                <p class="text-sm text-slate-500 font-medium">No low stock items</p>
                <p class="text-xs text-slate-400 mt-1">All products are well stocked.</p>
            </div>
            <?php else: ?>
            <ul class="space-y-3 text-sm">
                <?php foreach ($lowStock as $p): ?>
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-amber-50 transition duration-150">
                    <span class="w-2 h-2 bg-amber-500 rounded-full mt-1.5 shrink-0"></span>
                    <span class="text-slate-700"><?= e($p['name']) ?> <span class="text-slate-500">(<?= $p['current_stock'] ?> / <?= $p['min_stock'] ?>)</span></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Batches Ready Soon</h3>
                    <p class="text-xs text-slate-500">Next 7 days</p>
                </div>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full"><?= count($readySoon) ?> batch(es)</span>
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
                <li class="flex items-start gap-2.5 p-2.5 rounded-lg hover:bg-emerald-50 transition duration-150">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mt-1.5 shrink-0"></span>
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
        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-violet-50 to-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600">
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
                        'excellent', 'a' => 'bg-green-100 text-green-700',
                        'good', 'b' => 'bg-emerald-100 text-emerald-700',
                        'fair', 'c' => 'bg-amber-100 text-amber-700',
                        'poor', 'd' => 'bg-red-100 text-red-700',
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
        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-fuchsia-50 to-white flex items-center gap-2">
            <div class="h-9 w-9 rounded-lg bg-fuchsia-100 flex items-center justify-center text-fuchsia-600">
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
        data: { labels: qLabels, datasets: [{ data: qData, backgroundColor: ['#22c55e','#86efac','#fbbf24','#f87171','#a78bfa','#60a5fa'], borderWidth: 0 }] },
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
