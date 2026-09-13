<form action="<?= url('inventory/stockin') ?>" method="POST" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Product <span class="text-red-500">*</span></label>
        <select name="product_id" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <option value="">Select a product</option>
            <?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>"><?= e($p['name']) ?> (<?= $p['current_stock'] ?>)</option><?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Quantity <span class="text-red-500">*</span></label>
        <input type="number" name="quantity" min="1" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Reason</label>
        <input type="text" name="reason" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="e.g. Restock, delivery">
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Stock
        </button>
    </div>
</form>
