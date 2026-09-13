<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Lot Trace: <?= e($lot) ?></h1>
            <p class="text-sm text-slate-500 mt-0.5">Full seed-to-sale history for this lot.</p>
        </div>
        <a href="<?= url('traceability') ?>" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-sm font-medium transition duration-150">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="sprout" class="w-5 h-5 text-violet-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Batch Origin</h2>
                <p class="text-xs text-slate-500">Planting and source details</p>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div><span class="text-slate-500">Product:</span> <span class="font-medium text-slate-800"><?= e($chain['batch']['product_name']) ?></span></div>
                <div><span class="text-slate-500">Seed Source:</span> <span class="text-slate-700"><?= e($chain['batch']['seed_source'] ?? '-') ?></span></div>
                <div><span class="text-slate-500">Planted:</span> <span class="text-slate-700"><?= e($chain['batch']['plant_date']) ?></span></div>
                <div><span class="text-slate-500">Count:</span> <span class="text-slate-700"><?= $chain['batch']['seedling_count'] ?></span></div>
                <div><span class="text-slate-500">Stage:</span> <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($chain['batch']['growth_stage']) ?></span></div>
                <div><span class="text-slate-500">Health:</span> <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= strtolower($chain['batch']['health_status'] ?? '') === 'healthy' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' ?>"><?= e($chain['batch']['health_status']) ?></span></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-amber-600"></i>
                <h2 class="font-semibold text-slate-800">Quality Assessments</h2>
            </div>
            <div class="text-sm text-slate-500"><?= count($chain['quality']) ?> record(s)</div>
        </div>
        <ul class="divide-y divide-slate-200 text-sm">
            <?php if (empty($chain['quality'])): ?>
            <li class="px-6 py-8 text-center">
                <i data-lucide="clipboard-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                <p class="text-slate-500 font-medium">No quality records</p>
            </li>
            <?php else: ?>
            <?php foreach ($chain['quality'] as $q): ?>
            <li class="px-6 py-3.5 flex items-center gap-2 hover:bg-slate-50 transition duration-150">
                <i data-lucide="award" class="w-4 h-4 text-slate-400"></i>
                <span class="text-slate-600"><?= e($q['assessed_at']) ?></span>
                <span class="text-slate-400">—</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($q['grade']) ?></span>
                <span class="text-slate-600">by <?= e($q['staff_name'] ?? '-') ?></span>
                <?php if ($q['notes']): ?><span class="text-slate-400 text-xs">(<?= e($q['notes']) ?>)</span><?php endif; ?>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="cloud-sun" class="w-5 h-5 text-sky-600"></i>
                <h2 class="font-semibold text-slate-800">Environmental / Care Logs</h2>
            </div>
            <div class="text-sm text-slate-500"><?= count($chain['environmental']) ?> record(s)</div>
        </div>
        <ul class="divide-y divide-slate-200 text-sm">
            <?php if (empty($chain['environmental'])): ?>
            <li class="px-6 py-8 text-center">
                <i data-lucide="cloud-off" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                <p class="text-slate-500 font-medium">No environmental logs</p>
            </li>
            <?php else: ?>
            <?php foreach ($chain['environmental'] as $e): ?>
            <li class="px-6 py-3.5 flex items-center gap-2 hover:bg-slate-50 transition duration-150">
                <i data-lucide="activity" class="w-4 h-4 text-slate-400"></i>
                <span class="text-slate-600"><?= e($e['log_date']) ?></span>
                <span class="text-slate-400">—</span>
                <span class="font-medium text-slate-700"><?= e($e['activity']) ?></span>
                <span class="text-slate-600">by <?= e($e['staff_name'] ?? '-') ?></span>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="banknote" class="w-5 h-5 text-emerald-600"></i>
                <h2 class="font-semibold text-slate-800">Sales & Final Buyer</h2>
            </div>
            <div class="text-sm text-slate-500"><?= count($chain['sales']) ?> record(s)</div>
        </div>
        <ul class="divide-y divide-slate-200 text-sm">
            <?php if (empty($chain['sales'])): ?>
            <li class="px-6 py-8 text-center">
                <i data-lucide="receipt" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                <p class="text-slate-500 font-medium">No sales recorded for this lot</p>
            </li>
            <?php else: ?>
            <?php foreach ($chain['sales'] as $s): ?>
            <li class="px-6 py-3.5 flex items-center gap-2 hover:bg-slate-50 transition duration-150">
                <i data-lucide="receipt" class="w-4 h-4 text-slate-400"></i>
                <span class="text-slate-600"><?= e($s['sale_date']) ?></span>
                <span class="text-slate-400">—</span>
                <span class="text-slate-700">Receipt <span class="font-medium"><?= e($s['receipt_number']) ?></span>, <?= $s['quantity'] ?> @ ₱<?= number_format($s['unit_price'], 2) ?></span>
                <span class="text-slate-500">(<?= e($s['buyer_staff'] ?? '-') ?>)</span>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="trending-down" class="w-5 h-5 text-rose-600"></i>
                <h2 class="font-semibold text-slate-800">Shrinkage / Loss</h2>
            </div>
            <div class="text-sm text-slate-500"><?= count($chain['shrinkage']) ?> record(s)</div>
        </div>
        <ul class="divide-y divide-slate-200 text-sm">
            <?php if (empty($chain['shrinkage'])): ?>
            <li class="px-6 py-8 text-center">
                <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                <p class="text-slate-500 font-medium">No shrinkage records</p>
            </li>
            <?php else: ?>
            <?php foreach ($chain['shrinkage'] as $sh): ?>
            <li class="px-6 py-3.5 flex items-center gap-2 hover:bg-slate-50 transition duration-150">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-400"></i>
                <span class="text-slate-600"><?= e($sh['recorded_at']) ?></span>
                <span class="text-slate-400">—</span>
                <span class="font-semibold text-red-600"><?= $sh['quantity'] ?> lost</span>
                <span class="text-slate-600"><?= e($sh['reason']) ?></span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><?= e($sh['cause_category'] ?? 'Uncategorized') ?></span>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
