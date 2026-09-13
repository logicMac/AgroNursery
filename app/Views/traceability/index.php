<?php
$totalBatches = count($batches);
$uniqueSources = count(array_unique(array_filter(array_column($batches, 'seed_source') ?? [])));
?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Seed-to-Sale Traceability</h1>
        <p class="text-sm text-slate-500 mt-0.5">Follow any lot from seed source to final sale.</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600">
            <i data-lucide="layers" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Traceable Lots</div>
            <div class="text-xl font-bold text-slate-800"><?= $totalBatches ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
            <i data-lucide="truck" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Seed Sources</div>
            <div class="text-xl font-bold text-slate-800"><?= $uniqueSources ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4 lg:col-span-2">
        <div class="h-10 w-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600">
            <i data-lucide="search" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Lookup</div>
            <div class="text-sm font-medium text-slate-700">Search a lot ID or browse the full batch registry</div>
        </div>
    </div>
</div>

<!-- Search by Lot -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <form action="" method="GET" class="flex flex-col sm:flex-row gap-3" onsubmit="location.href='<?= url('traceability/') ?>' + this.lot.value; return false;">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" name="lot" class="w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Enter Lot ID (e.g. BAN-2026-001)">
        </div>
        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition duration-200">
            <i data-lucide="search" class="w-4 h-4"></i> Trace
        </button>
    </form>
</div>

<!-- Traceable Batches Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="search" class="w-5 h-5 text-teal-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Lot Registry</h2>
                <p class="text-xs text-slate-500">All traceable batches</p>
            </div>
        </div>
        <div class="text-sm text-slate-500"><?= $totalBatches ?> lot(s)</div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="traceTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Photo</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Lot ID</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Seed Source</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Planted</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($batches)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i data-lucide="search-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No traceable lots found</p>
                        <p class="text-sm text-slate-400 mt-1">Batches will appear here once registered.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($batches as $b): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4">
                        <?php if (!empty($b['product_image'])): ?>
                        <img src="<?= url($b['product_image']) ?>" alt="<?= e($b['product_name']) ?>" class="h-11 w-11 object-cover rounded-xl border border-slate-200 shadow-sm">
                        <?php else: ?>
                        <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400"><i data-lucide="image" class="w-4 h-4"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-700"><?= e($b['lot_id']) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-800"><?= e($b['product_name']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($b['seed_source'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-slate-700">
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            <?= e($b['plant_date']) ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= url('traceability/' . $b['lot_id']) ?>" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                            <i data-lucide="link" class="w-3 h-3"></i> View Chain
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
