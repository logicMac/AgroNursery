<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Edit Product</h1>
    <form action="<?= url('products/' . $product['id'] . '/update') ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-mint-100 p-6 shadow-md hover:shadow-lg transition duration-200 space-y-4">
        <?= \App\Core\Csrf::field() ?>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Name</label><input type="text" name="name" value="<?= e($product['name']) ?>" required class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Category</label><input type="text" name="category" value="<?= e($product['category']) ?>" required class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
        </div>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Description</label><textarea name="description" rows="2" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"><?= e($product['description'] ?? '') ?></textarea></div>
        <?php if (!empty($product['image'])): ?>
        <div class="flex items-center gap-3">
            <img src="<?= url($product['image']) ?>" alt="Product" class="h-16 w-16 object-cover rounded-lg border border-slate-200">
            <span class="text-xs text-slate-500">Current photo. Upload a new one to replace it.</span>
        </div>
        <?php endif; ?>
        <div><label class="block text-sm font-medium text-slate-700 mb-1">Photo</label><input type="file" name="image" accept="image/*" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
        <div class="grid grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Base Price</label><input type="number" step="0.01" name="base_price" value="<?= $product['base_price'] ?>" required class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Min Stock</label><input type="number" name="min_stock" value="<?= $product['min_stock'] ?>" required class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1">Unit</label><input type="text" name="unit" value="<?= e($product['unit']) ?>" class="w-full rounded-lg border-slate-200 focus:border-mint-500 px-4 py-2 text-sm"></div>
        </div>
        <button type="submit" class="w-full bg-mint-600 hover:bg-mint-700 text-white font-medium py-2.5 rounded-lg">Update Product</button>
    </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
