<form action="<?= url('quality/store') ?>" method="POST" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Batch / Lot <span class="text-red-500">*</span></label>
        <select name="batch_id" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <option value="">Select a lot</option>
            <?php foreach ($batches as $b): ?><option value="<?= $b['id'] ?>"><?= e($b['lot_id']) ?> — <?= e($b['product_name']) ?></option><?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Grade <span class="text-red-500">*</span></label>
        <select name="grade" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <option>Healthy</option><option>Good</option><option>Fair</option><option>Poor</option>
        </select>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Height (cm)</label>
            <input type="number" step="0.1" name="height_cm" placeholder="Optional" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Leaf Count</label>
            <input type="number" name="leaf_count" placeholder="Optional" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Color</label>
            <input type="text" name="color" placeholder="e.g. Deep green" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Pest/Disease Notes</label>
        <input type="text" name="pests" placeholder="Any signs of pests or disease" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" placeholder="Additional observations" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Assessed At</label>
        <input type="datetime-local" name="assessed_at" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Log Assessment
        </button>
    </div>
</form>
