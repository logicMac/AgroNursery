<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-mint-100 p-6 shadow-md hover:shadow-lg transition duration-200 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500">Lot</p>
                <h1 class="text-2xl font-bold text-slate-800"><?= e($batch['lot_id']) ?></h1>
            </div>
            <a href="<?= url('traceability/' . $batch['lot_id']) ?>" class="text-mint-600 text-sm hover:underline flex items-center gap-1"><i data-lucide="search" class="w-4 h-4"></i> Trace Lot</a>
        </div>
        <div class="grid grid-cols-3 gap-4 text-sm mt-4">
            <div><span class="text-slate-500">Product:</span> <?= e($batch['product_name']) ?></div>
            <div><span class="text-slate-500">Seed Source:</span> <?= e($batch['seed_source'] ?? '-') ?></div>
            <div><span class="text-slate-500">Count:</span> <?= $batch['seedling_count'] ?></div>
            <div><span class="text-slate-500">Planted:</span> <?= e($batch['plant_date']) ?></div>
            <div><span class="text-slate-500">Stage:</span> <?= e($batch['growth_stage']) ?></div>
            <div><span class="text-slate-500">Health:</span> <?= e($batch['health_status']) ?></div>
        </div>
        <p class="text-sm text-slate-600 mt-4"><?= e($batch['notes'] ?? '') ?></p>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
