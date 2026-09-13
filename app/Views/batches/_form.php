<form action="<?= url('batches/store') ?>" method="POST" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Product <span class="text-red-500">*</span></label>
            <select name="product_id" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
                <option value="">Select a product</option>
                <?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>"><?= e($p['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Lot ID <span class="text-red-500">*</span></label>
            <input type="text" name="lot_id" required placeholder="BAN-2026-001" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Seed Source</label>
            <input type="text" name="seed_source" placeholder="Supplier or farm" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Seedling Count <span class="text-red-500">*</span></label>
            <input type="number" name="seedling_count" required placeholder="e.g. 500" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Plant Date <span class="text-red-500">*</span></label>
            <input type="date" name="plant_date" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Expected Ready</label>
            <input type="date" name="expected_ready_date" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Growth Stage</label>
            <select name="growth_stage" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"><option>Seedling</option><option>Vegetative</option><option>Juvenile</option><option>Ready</option></select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Health Status</label>
            <select name="health_status" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"><option>Healthy</option><option>Good</option><option>Fair</option><option>Poor</option></select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
        <input type="text" name="location" placeholder="Greenhouse / row" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" placeholder="Additional notes..." class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"></textarea>
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Create Batch
        </button>
    </div>
</form>
