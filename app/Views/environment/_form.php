<form action="<?= url('environment/store') ?>" method="POST" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Batch (optional)</label>
        <select name="batch_id" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
            <option value="">General / Nursery</option>
            <?php foreach ($batches as $b): ?><option value="<?= $b['id'] ?>"><?= e($b['lot_id']) ?></option><?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Log Date <span class="text-red-500">*</span></label>
        <input type="date" name="log_date" required value="<?= date('Y-m-d') ?>" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Activity / Care <span class="text-red-500">*</span></label>
        <input type="text" name="activity" required class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="e.g. Watering, Fertilizer">
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Light Hours</label>
            <input type="number" step="0.1" name="light_hours" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0.0">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Water (ml)</label>
            <input type="number" name="water_ml" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Soil pH</label>
            <input type="number" step="0.01" name="soil_ph" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0.00">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Temp (°C)</label>
            <input type="number" step="0.1" name="temperature_c" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0.0">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Humidity (%)</label>
            <input type="number" step="0.01" name="humidity_pct" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="0.00">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Fertilizer</label>
            <input type="text" name="fertilizer" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Optional">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Pest Control</label>
            <input type="text" name="pest_control" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Optional">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Additional notes..."></textarea>
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Save Log
        </button>
    </div>
</form>
