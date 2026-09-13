<?php require __DIR__ . '/../layouts/main.php'; ?>
<?php
$totalProducts = count($products);
$totalStock = (int) array_sum(array_column($products, 'current_stock'));
$lowStock = count(array_filter($products, fn($p) => $p['current_stock'] > 0 && $p['current_stock'] <= $p['min_stock']));
$outStock = count(array_filter($products, fn($p) => $p['current_stock'] == 0));
$categories = count(array_unique(array_column($products, 'category')));
$totalValue = array_sum(array_map(fn($p) => $p['base_price'] * $p['current_stock'], $products));
?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Products</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage your nursery product catalog, pricing, and stock levels.</p>
    </div>
    <button type="button" data-modal-open="productModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> New Product
    </button>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-md hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Products</span>
            <div class="h-8 w-8 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600"><i data-lucide="package" class="w-4 h-4"></i></div>
        </div>
        <div class="text-2xl font-bold text-slate-800"><?= $totalProducts ?></div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-md hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Stock</span>
            <div class="h-8 w-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600"><i data-lucide="layers" class="w-4 h-4"></i></div>
        </div>
        <div class="text-2xl font-bold text-slate-800"><?= number_format($totalStock) ?></div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-md hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Categories</span>
            <div class="h-8 w-8 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600"><i data-lucide="tags" class="w-4 h-4"></i></div>
        </div>
        <div class="text-2xl font-bold text-slate-800"><?= $categories ?></div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-md hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Low Stock</span>
            <div class="h-8 w-8 rounded-xl <?= $lowStock > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-600' ?> flex items-center justify-center"><i data-lucide="alert-circle" class="w-4 h-4"></i></div>
        </div>
        <div class="text-2xl font-bold <?= $lowStock > 0 ? 'text-amber-600' : 'text-slate-800' ?>"><?= $lowStock ?></div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-md hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Out of Stock</span>
            <div class="h-8 w-8 rounded-xl <?= $outStock > 0 ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-600' ?> flex items-center justify-center"><i data-lucide="ban" class="w-4 h-4"></i></div>
        </div>
        <div class="text-2xl font-bold <?= $outStock > 0 ? 'text-red-600' : 'text-slate-800' ?>"><?= $outStock ?></div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-md hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Est. Value</span>
            <div class="h-8 w-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600"><i data-lucide="banknote" class="w-4 h-4"></i></div>
        </div>
        <div class="text-2xl font-bold text-slate-800">₱<?= number_format($totalValue, 0) ?></div>
    </div>
</div>

<!-- Search / Filter -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="productsTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search products by name or category...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="list" class="w-4 h-4"></i>
            <span id="productCount"><?= $totalProducts ?> product(s)</span>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="package" class="w-5 h-5 text-indigo-600"></i>
            <h2 class="font-semibold text-slate-800">Product Inventory</h2>
        </div>
        <span class="text-xs text-slate-500">Click a row to view or edit details</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="productsTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Photo</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Category</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Base Price</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Stock</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Min. Stock</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-lucide="package-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No products found</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Add your first nursery product to get started.</p>
                        <button type="button" data-modal-open="productModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add Product
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $p): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4">
                        <?php if (!empty($p['image'])): ?>
                        <img src="<?= url($p['image']) ?>" alt="<?= e($p['name']) ?>" class="h-11 w-11 object-cover rounded-xl border border-slate-200 shadow-sm">
                        <?php else: ?>
                        <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400"><i data-lucide="image" class="w-4 h-4"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800"><?= e($p['name']) ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium badge-neutral">
                            <?= e($p['category']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-slate-800 whitespace-nowrap">₱<?= number_format($p['base_price'], 2) ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium <?= $p['current_stock'] <= $p['min_stock'] ? 'text-red-600' : 'text-slate-700' ?>"><?= $p['current_stock'] ?> <?= e($p['unit']) ?></span>
                            <?php if ($p['current_stock'] == 0): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                    <i data-lucide="ban" class="w-3 h-3"></i> Out
                                </span>
                            <?php elseif ($p['current_stock'] <= $p['min_stock']): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i> Low
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> OK
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right text-slate-600 whitespace-nowrap"><?= $p['min_stock'] ?></td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= url('products/' . $p['id'] . '/edit') ?>" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php ob_start(); ?>
    <?php require __DIR__ . '/_form.php'; ?>
<?php
$modalContent = ob_get_clean();
$modalId = 'productModal';
$modalTitle = 'New Product';
$modalIcon = 'package';
$modalSubtitle = 'Add a new nursery product';
require __DIR__ . '/../components/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
