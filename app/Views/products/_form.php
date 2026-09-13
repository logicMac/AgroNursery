<form action="<?= url('products/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
    <?= \App\Core\Csrf::field() ?>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" required placeholder="e.g. Dwarf Coconut" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
            <input type="text" name="category" required placeholder="e.g. Fruit Tree" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <textarea name="description" rows="2" placeholder="Brief description..." class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm"></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Photo</label>
        <input type="file" name="image" accept="image/*" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-mint-50 file:text-mint-700 hover:file:bg-mint-100">
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Base Price <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" name="base_price" required placeholder="0.00" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Initial Stock</label>
            <input type="number" name="current_stock" value="0" placeholder="0" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Min Stock <span class="text-red-500">*</span></label>
            <input type="number" name="min_stock" required placeholder="0" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
        <input type="text" name="unit" value="pc" placeholder="pc, kg, bundle" class="w-full rounded-2xl bg-white border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
    </div>
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-mint-100">
        <button type="button" data-modal-close class="px-5 py-2.5 rounded-2xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition">Cancel</button>
        <button type="submit" class="px-5 py-2.5 rounded-2xl text-sm font-medium bg-mint-600 hover:bg-mint-700 text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Save Product
        </button>
    </div>
</form>
