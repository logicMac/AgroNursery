<?php require __DIR__ . '/../layouts/main.php'; ?>

<?php if ($isOwner): ?>

<!-- Owner / Analytics Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-11 w-11 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
            <i data-lucide="dollar-sign" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">30-Day Revenue</div>
            <div class="text-xl font-bold text-slate-800">₱<?= number_format(array_sum(array_column($revenue, 'total')), 2) ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-11 w-11 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Low Stock</div>
            <div class="text-xl font-bold text-slate-800"><?= count($lowStock) ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-11 w-11 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600">
            <i data-lucide="calendar" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Batches Ready Soon</div>
            <div class="text-xl font-bold text-slate-800"><?= count($readySoon) ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-11 w-11 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600">
            <i data-lucide="trending-down" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Shrinkage Batches</div>
            <div class="text-xl font-bold text-slate-800"><?= count(array_filter($shrinkage, fn($s) => $s['rate'] > 0)) ?></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="line-chart" class="w-5 h-5 text-emerald-600"></i>
            <div>
                <h3 class="font-semibold text-slate-800">Revenue Trend (30 days)</h3>
                <p class="text-xs text-slate-500">Daily sales totals</p>
            </div>
        </div>
        <div class="p-5">
            <div style="position: relative; width: 100%; height: 220px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="package" class="w-5 h-5 text-indigo-600"></i>
            <div>
                <h3 class="font-semibold text-slate-800">Top Selling Products</h3>
                <p class="text-xs text-slate-500">By quantity sold</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                        <th class="text-right px-6 py-3.5 font-semibold">Qty</th>
                        <th class="text-right px-6 py-3.5 font-semibold">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($topProducts)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center">
                            <i data-lucide="package-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No sales data yet</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($topProducts as $p): ?>
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-3.5 font-medium text-slate-800"><?= e($p['name']) ?></td>
                        <td class="px-6 py-3.5 text-right text-slate-600"><?= $p['qty'] ?></td>
                        <td class="px-6 py-3.5 text-right font-semibold text-slate-700">₱<?= number_format($p['revenue'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
        <i data-lucide="award" class="w-5 h-5 text-amber-600"></i>
        <div>
            <h3 class="font-semibold text-slate-800">Quality vs Sales</h3>
            <p class="text-xs text-slate-500">Average sale price per grade</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Grade</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Sold</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Avg Price</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($gradeSales)): ?>
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center">
                        <i data-lucide="clipboard-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No grade data yet</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($gradeSales as $g): ?>
                <tr class="hover:bg-slate-50 transition duration-150">
                    <td class="px-6 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($g['quality_grade']) ?></span>
                    </td>
                    <td class="px-6 py-3.5 text-right text-slate-600"><?= $g['sold'] ?></td>
                    <td class="px-6 py-3.5 text-right font-semibold text-slate-700">₱<?= number_format($g['avg_price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rev = <?= json_encode(array_column($revenue, 'total')) ?>;
    const labels = <?= json_encode(array_map(fn($r) => $r['day'], $revenue)) ?>;
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: { labels, datasets: [{ data: rev, borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.1)', tension: 0.4, fill: true }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>

<?php else: ?>

<!-- Staff / Task Dashboard -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Good day, <?= e($user['name']) ?>!</h1>
    <p class="text-sm text-slate-500 mt-0.5">Here are your tasks and quick actions for today.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="<?= url('sales/create') ?>" class="bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg transition duration-200 flex flex-col justify-between">
        <i data-lucide="receipt" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">New Sale</span>
        <span class="text-xs text-emerald-100">Record a transaction</span>
    </a>
    <a href="<?= url('environment/create') ?>" class="bg-gradient-to-br from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg transition duration-200 flex flex-col justify-between">
        <i data-lucide="cloud-sun" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Log Conditions</span>
        <span class="text-xs text-sky-100">Care & environment</span>
    </a>
    <a href="<?= url('quality/create') ?>" class="bg-gradient-to-br from-violet-500 to-violet-600 hover:from-violet-600 hover:to-violet-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg transition duration-200 flex flex-col justify-between">
        <i data-lucide="clipboard-check" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Quality Check</span>
        <span class="text-xs text-violet-100">Assess a batch</span>
    </a>
    <a href="<?= url('shrinkage/create') ?>" class="bg-gradient-to-br from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white rounded-2xl p-5 shadow-md hover:shadow-lg transition duration-200 flex flex-col justify-between">
        <i data-lucide="trending-down" class="w-6 h-6 mb-3"></i>
        <span class="font-semibold">Record Loss</span>
        <span class="text-xs text-rose-100">Dead / damaged stock</span>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-5 h-5 text-sky-600"></i>
                <h3 class="font-semibold text-slate-800">Care Logs Due Today</h3>
            </div>
            <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full"><?= count($dueToday) ?> task(s)</span>
        </div>
        <div class="p-5">
            <?php if (empty($dueToday)): ?>
                <div class="text-center py-4">
                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                    <p class="text-sm text-slate-500 font-medium">No care logs due today</p>
                    <p class="text-xs text-slate-400 mt-1">All caught up!</p>
                </div>
            <?php else: ?>
                <ul class="space-y-3 text-sm">
                    <?php foreach ($dueToday as $t): ?>
                    <li class="flex items-start gap-2.5">
                        <span class="w-2 h-2 bg-sky-500 rounded-full mt-1.5 shrink-0"></span>
                        <span class="text-slate-700"><?= e($t['activity']) ?> — <span class="text-slate-500"><?= e($t['lot_id'] ?? '-') ?></span></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
                <h3 class="font-semibold text-slate-800">Low Stock Alerts</h3>
            </div>
            <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full"><?= count($lowStock) ?> item(s)</span>
        </div>
        <div class="p-5">
            <?php if (empty($lowStock)): ?>
                <div class="text-center py-4">
                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                    <p class="text-sm text-slate-500 font-medium">No low stock items</p>
                    <p class="text-xs text-slate-400 mt-1">All products are well stocked.</p>
                </div>
            <?php else: ?>
                <ul class="space-y-3 text-sm">
                    <?php foreach ($lowStock as $p): ?>
                    <li class="flex items-start gap-2.5">
                        <span class="w-2 h-2 bg-amber-500 rounded-full mt-1.5 shrink-0"></span>
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
                <i data-lucide="calendar" class="w-5 h-5 text-emerald-600"></i>
                <h3 class="font-semibold text-slate-800">Batches Ready Soon</h3>
            </div>
            <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full"><?= count($readySoon) ?> batch(es)</span>
        </div>
        <div class="p-5">
            <?php if (empty($readySoon)): ?>
                <div class="text-center py-4">
                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                    <p class="text-sm text-slate-500 font-medium">No batches nearing ready date</p>
                    <p class="text-xs text-slate-400 mt-1">Nothing due in the next 7 days.</p>
                </div>
            <?php else: ?>
                <ul class="space-y-3 text-sm">
                    <?php foreach ($readySoon as $b): ?>
                    <li class="flex items-start gap-2.5">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mt-1.5 shrink-0"></span>
                        <span class="text-slate-700"><?= e($b['lot_id']) ?> — <?= e($b['product_name']) ?> <span class="text-slate-500">(<?= e($b['expected_ready_date']) ?>)</span></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Quality Assessments -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-violet-600"></i>
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
                        <th class="text-left px-6 py-3.5 font-semibold">Lot</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Grade</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Assessed</th>
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
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="pie-chart" class="w-5 h-5 text-fuchsia-600"></i>
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
        data: { labels: qLabels, datasets: [{ data: qData, backgroundColor: ['#22c55e','#86efac','#fbbf24','#f87171'], borderWidth: 0 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } }
        }
    });
</script>

<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
