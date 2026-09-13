<form action="<?= url('pricing/store') ?>" method="POST" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Product <span class="text-red-500">*</span></label>
        <select name="product_id" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>"><?= e($p['name']) ?></option><?php endforeach; ?>
        </select>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Grade</label>
            <select name="grade" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"><option value="*">Any</option><option>Healthy</option><option>Good</option><option>Fair</option><option>Poor</option></select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Season</label>
            <select name="season" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"><option value="">Any</option><option>Dry</option><option>Wet</option></select>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Size Min (cm)</label>
            <input type="number" step="0.1" name="size_cm_min" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0.0">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Size Max (cm)</label>
            <input type="number" step="0.1" name="size_cm_max" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="∞">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Age Min (days)</label>
            <input type="number" name="age_days_min" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Age Max (days)</label>
            <input type="number" name="age_days_max" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="∞">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Multiplier <span class="text-red-500">*</span></label>
            <input type="number" step="0.001" name="multiplier" value="1.000" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="1.000">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Fixed Amount</label>
            <input type="number" step="0.01" name="fixed_amount" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="optional">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Priority</label>
        <input type="number" name="priority" value="0" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0">
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Save Rule
        </button>
    </div>
</form>
