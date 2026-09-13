<?php
$totalBatches = count($batches);
$withDate = count(array_filter($batches, fn($b) => !empty($b['expected_ready_date'])));
?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Ready-Date Forecasts</h1>
        <p class="text-sm text-slate-500 mt-0.5">Projected harvest-ready dates per batch.</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-cyan-100 flex items-center justify-center text-cyan-600">
            <i data-lucide="layers" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Batches</div>
            <div class="text-xl font-bold text-slate-800"><?= $totalBatches ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
            <i data-lucide="calendar-check" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">With Forecast</div>
            <div class="text-xl font-bold text-slate-800"><?= $withDate ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4 lg:col-span-2">
        <div class="h-10 w-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600">
            <i data-lucide="sparkles" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Prediction Method</div>
            <div class="text-sm font-medium text-slate-700">AI-adjusted growth days based on environment and batch age</div>
        </div>
    </div>
</div>

<!-- Search -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="forecastTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search by lot, product or stage...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="layers" class="w-4 h-4"></i>
            <span id="forecastCount"><?= $totalBatches ?> lot(s)</span>
        </div>
    </div>
</div>

<!-- Batches Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="calendar" class="w-5 h-5 text-cyan-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Forecast Queue</h2>
                <p class="text-xs text-slate-500">Batches eligible for ready-date prediction</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="forecastTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Lot ID</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Stage</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Expected Ready</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($batches)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No batches available for forecasting</p>
                        <p class="text-sm text-slate-400 mt-1">Active batches will appear here automatically.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($batches as $b): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 font-semibold text-slate-700"><?= e($b['lot_id']) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-800"><?= e($b['product_name']) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($b['growth_stage']) ?></span>
                    </td>
                    <td class="px-6 py-4 text-slate-700">
                        <?php if (!empty($b['expected_ready_date'])): ?>
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                <?= e($b['expected_ready_date']) ?>
                            </div>
                        <?php else: ?>
                            <span class="text-slate-400">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= url('forecast/batch/' . $b['id']) ?>" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                            <i data-lucide="line-chart" class="w-3 h-3"></i> Forecast
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
