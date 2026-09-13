<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Edit Batch</h1>
    <form action="<?= url('batches/' . $batch['id'] . '/update') ?>" method="POST" class="bg-white rounded-2xl border border-mint-100 p-6 shadow-md hover:shadow-lg transition duration-200 space-y-4">
        <?= \App\Core\Csrf::field() ?>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Product</label>
            <select name="product_id" disabled class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-2 text-sm">
                <?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>" <?= $p['id'] == $batch['product_id'] ? 'selected' : '' ?>><?= e($p['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Growth Stage</label>
                <select name="growth_stage" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm">
                    <?php foreach (['Seedling','Vegetative','Juvenile','Ready'] as $s): ?><option <?= $s === $batch['growth_stage'] ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Health Status</label>
                <select name="health_status" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm">
                    <?php foreach (['Healthy','Good','Fair','Poor'] as $h): ?><option <?= $h === $batch['health_status'] ? 'selected' : '' ?>><?= $h ?></option><?php endforeach; ?>
                </select>
            </div>
        </div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Expected Ready Date</label><input type="date" name="expected_ready_date" value="<?= e($batch['expected_ready_date'] ?? '') ?>" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Location</label><input type="text" name="location" value="<?= e($batch['location'] ?? '') ?>" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Notes</label><textarea name="notes" rows="2" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"><?= e($batch['notes'] ?? '') ?></textarea></div>
        <button type="submit" class="w-full bg-mint-600 hover:bg-mint-700 text-white font-medium py-2.5 rounded-lg">Update Batch</button>
    </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
