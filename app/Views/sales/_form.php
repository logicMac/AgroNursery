<form action="<?= url('sales/store') ?>" method="POST" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Customer <span class="text-red-500">*</span></label>
            <input type="text" name="customer_name" required placeholder="e.g. Juan Dela Cruz" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Contact</label>
            <input type="text" name="customer_contact" placeholder="Phone or email (optional)" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Product <span class="text-red-500">*</span></label>
            <select name="product_id" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
                <?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>"><?= e($p['name']) ?> (<?= e($p['current_stock']) ?>)</option><?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Batch / Lot</label>
            <select name="batch_id" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
                <option value="">None</option>
                <?php foreach ($batches as $b): ?><option value="<?= $b['id'] ?>"><?= e($b['lot_id']) ?> — <?= e($b['product_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Quantity <span class="text-red-500">*</span></label>
            <input type="number" name="quantity" min="1" required placeholder="1" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Size (cm)</label>
            <input type="number" step="0.1" name="size_cm" placeholder="Optional" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Season</label>
            <select name="season" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"><option value="">Default</option><option value="Dry">Dry</option><option value="Wet">Wet</option></select>
        </div>
    </div>
    <input type="hidden" name="sale_date" value="<?= date('Y-m-d H:i:s') ?>">
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Record Sale
        </button>
    </div>
</form>
