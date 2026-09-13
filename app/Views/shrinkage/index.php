<?php
$totalLost = array_sum(array_column($records, 'quantity'));
$recordCount = count($records);
$causeCount = count($causes);
?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Shrinkage Analytics</h1>
        <p class="text-sm text-slate-500 mt-0.5">Lost or damaged stock by cause and batch.</p>
    </div>
    <button type="button" data-modal-open="shrinkageModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> Record Loss
    </button>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-red-100 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
            <i data-lucide="trending-down" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Total Lost</div>
            <div class="text-xl font-bold text-slate-800"><?= number_format($totalLost) ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
            <i data-lucide="file-text" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Records</div>
            <div class="text-xl font-bold text-slate-800"><?= $recordCount ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600">
            <i data-lucide="pie-chart" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Cause Categories</div>
            <div class="text-xl font-bold text-slate-800"><?= $causeCount ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Top Concern</div>
            <div class="text-sm font-medium text-slate-700"><?= !empty($causes) ? e($causes[0]['cause_category'] ?? 'N/A') : 'No data' ?></div>
        </div>
    </div>
</div>

<!-- Analytics Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="pie-chart" class="w-5 h-5 text-rose-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">By Cause Category</h2>
                <p class="text-xs text-slate-500">Losses grouped by cause</p>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Cause</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Total</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Records</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($causes)): ?>
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center">
                        <i data-lucide="pie-chart" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No cause data</p>
                        <p class="text-sm text-slate-400 mt-1">Loss categories will appear once recorded.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($causes as $c): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 font-medium text-slate-800"><?= e($c['cause_category'] ?: 'Uncategorized') ?></td>
                    <td class="px-6 py-4 font-semibold text-red-600"><?= $c['total'] ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= $c['records'] ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="layers" class="w-5 h-5 text-violet-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">By Batch</h2>
                <p class="text-xs text-slate-500">Shrinkage rate per lot</p>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Lot</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Lost</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Rate %</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($byBatch)): ?>
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center">
                        <i data-lucide="layers" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No batch data</p>
                        <p class="text-sm text-slate-400 mt-1">Per-batch loss rates will appear here.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($byBatch as $r): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 font-medium text-slate-700"><?= e($r['lot_id']) ?></td>
                    <td class="px-6 py-4 font-semibold text-slate-700"><?= $r['lost'] ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $r['rate'] > 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                            <?= $r['rate'] ?>%
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Search & Records -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="shrinkageTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search records by product, lot or cause...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span id="shrinkageCount"><?= $recordCount ?> record(s)</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="file-text" class="w-5 h-5 text-red-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Loss Records</h2>
                <p class="text-xs text-slate-500">Individual shrinkage entries</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="shrinkageTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Lot</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Qty</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Reason</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Category</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Recorded</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($records)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i data-lucide="file-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No shrinkage records yet</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Record dead or damaged stock to track losses.</p>
                        <button type="button" data-modal-open="shrinkageModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> Record Loss
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($records as $r): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 font-medium text-slate-800"><?= e($r['product_name']) ?></td>
                    <td class="px-6 py-4 text-slate-700"><?= e($r['lot_id'] ?? '-') ?></td>
                    <td class="px-6 py-4 font-semibold text-red-600"><?= $r['quantity'] ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($r['reason']) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><?= e($r['cause_category'] ?: 'Uncategorized') ?></span>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= e($r['recorded_at']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php ob_start(); ?>
    <?php require __DIR__ . '/_form.php'; ?>
<?php
$modalContent = ob_get_clean();
$modalId = 'shrinkageModal';
$modalTitle = 'Record Shrinkage / Loss';
$modalIcon = 'trending-down';
$modalSubtitle = 'Record a stock loss or damage';
require __DIR__ . '/../components/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
