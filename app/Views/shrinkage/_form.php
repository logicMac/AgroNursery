<form action="<?= url('shrinkage/store') ?>" method="POST" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Product <span class="text-red-500">*</span></label>
        <select name="product_id" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>"><?= e($p['name']) ?> (<?= $p['current_stock'] ?>)</option><?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Batch (optional)</label>
        <select name="batch_id" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <option value="">None</option>
            <?php foreach ($batches as $b): ?><option value="<?= $b['id'] ?>"><?= e($b['lot_id']) ?></option><?php endforeach; ?>
        </select>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Quantity <span class="text-red-500">*</span></label>
            <input type="number" name="quantity" min="1" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Cause Category</label>
            <select name="cause_category" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
                <option value="">Select cause</option>
                <option>Pest</option><option>Disease</option><option>Drought</option><option>Overwater</option><option>Handling</option><option>Other</option>
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Reason / Notes <span class="text-red-500">*</span></label>
        <input type="text" name="reason" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="What happened?">
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-red-500 hover:bg-red-600 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Record Loss
        </button>
    </div>
</form>
