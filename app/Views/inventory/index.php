<?php
$totalProducts = count($products);
$totalStock = array_sum(array_column($products, 'current_stock'));
$lowCount = count($lowStock);
$movementCount = count($movements);
?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Inventory</h1>
        <p class="text-sm text-slate-500 mt-0.5">Stock levels, adjustments and movement history.</p>
    </div>
    <button type="button" data-modal-open="stockInModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Stock
    </button>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
            <i data-lucide="package" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Products</div>
            <div class="text-xl font-bold text-slate-800"><?= $totalProducts ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
            <i data-lucide="layers" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Total Stock</div>
            <div class="text-xl font-bold text-slate-800"><?= number_format($totalStock) ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-red-100 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Low Stock</div>
            <div class="text-xl font-bold text-slate-800"><?= $lowCount ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600">
            <i data-lucide="arrow-left-right" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Movements</div>
            <div class="text-xl font-bold text-slate-800"><?= $movementCount ?></div>
        </div>
    </div>
</div>

<!-- Stock Levels Search -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="stockTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search stock by product...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="package" class="w-4 h-4"></i>
            <span id="stockCount"><?= $totalProducts ?> product(s)</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Stock Levels -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="package" class="w-5 h-5 text-rose-600"></i>
                <div>
                    <h2 class="font-semibold text-slate-800">Stock Levels</h2>
                    <p class="text-xs text-slate-500">Current stock per product</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="stockTable">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-semibold">Photo</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Stock</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Min</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Status</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Adjust</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i data-lucide="package-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No products found</p>
                            <p class="text-sm text-slate-400 mt-1 mb-4">Add a stock entry to get started.</p>
                            <button type="button" data-modal-open="stockInModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                                <i data-lucide="plus" class="w-4 h-4"></i> Add Stock
                            </button>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $p): ?>
                    <?php
                    $status = 'ok';
                    if ($p['current_stock'] == 0) $status = 'out';
                    elseif ($p['current_stock'] <= $p['min_stock']) $status = 'low';
                    ?>
                    <tr class="hover:bg-slate-50 transition duration-150 group">
                        <td class="px-6 py-4">
                            <?php if (!empty($p['image'])): ?>
                            <img src="<?= url($p['image']) ?>" alt="<?= e($p['name']) ?>" class="h-11 w-11 object-cover rounded-xl border border-slate-200 shadow-sm">
                            <?php else: ?>
                            <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400"><i data-lucide="image" class="w-4 h-4"></i></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800"><?= e($p['name']) ?></td>
                        <td class="px-6 py-4">
                            <span class="font-semibold <?= $status === 'out' ? 'text-red-600' : ($status === 'low' ? 'text-amber-600' : 'text-slate-700') ?>"><?= $p['current_stock'] ?></span>
                            <span class="text-xs text-slate-500"><?= e($p['unit']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?= $p['min_stock'] ?></td>
                        <td class="px-6 py-4">
                            <?php if ($status === 'ok'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700"><i data-lucide="check-circle" class="w-3 h-3"></i> OK</span>
                            <?php elseif ($status === 'low'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><i data-lucide="alert-triangle" class="w-3 h-3"></i> Low</span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700"><i data-lucide="ban" class="w-3 h-3"></i> Out</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <form action="<?= url('inventory/adjust/' . $p['id']) ?>" method="POST" class="flex items-center gap-2">
                                <?= \App\Core\Csrf::field() ?>
                                <input type="number" name="quantity" class="w-20 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 text-xs px-2 py-2" placeholder="qty">
                                <select name="type" class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 text-xs px-2 py-2"><option value="in">In</option><option value="out">Out</option></select>
                                <input type="text" name="reason" class="w-32 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 text-xs px-2 py-2" placeholder="reason">
                                <button class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition"><i data-lucide="save" class="w-3 h-3"></i> Save</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Low Stock Alerts</h2>
                <p class="text-xs text-slate-500">Products at or below minimum</p>
            </div>
        </div>
        <ul class="p-5 space-y-3 text-sm max-h-[400px] overflow-y-auto">
            <?php if (empty($lowStock)): ?>
                <li class="text-center py-8">
                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                    <p class="text-slate-500 font-medium">No low stock alerts</p>
                    <p class="text-xs text-slate-400 mt-1">All products are sufficiently stocked.</p>
                </li>
            <?php else: ?>
                <?php foreach ($lowStock as $p): ?>
                <li class="flex items-center gap-2 text-red-700 bg-red-50 px-3 py-2 rounded-xl">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i>
                    <span class="font-medium"><?= e($p['name']) ?></span>
                    <span class="text-xs text-red-500 ml-auto"><?= $p['current_stock'] ?>/<?= $p['min_stock'] ?></span>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Recent Movements -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="arrow-left-right" class="w-5 h-5 text-indigo-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Recent Movements</h2>
                <p class="text-xs text-slate-500">Stock-in and stock-out history</p>
            </div>
        </div>
        <div class="text-sm text-slate-500"><?= $movementCount ?> record(s)</div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="movementTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Type</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Qty</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Reason</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Staff</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($movements)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <i data-lucide="history" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No stock movements yet</p>
                        <p class="text-sm text-slate-400 mt-1">Adjustments will appear here once recorded.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($movements as $m): ?>
                <?php $isIn = $m['movement_type'] === 'in'; ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 font-medium text-slate-800"><?= e($m['product_name']) ?><?= $m['lot_id'] ? ' <span class="text-xs text-slate-500">(' . e($m['lot_id']) . ')</span>' : '' ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?= $isIn ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                            <i data-lucide="<?= $isIn ? 'arrow-down-left' : 'arrow-up-right' ?>" class="w-3 h-3"></i>
                            <?= e($m['movement_type']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold <?= $isIn ? 'text-green-600' : 'text-red-600' ?>"><?= $m['quantity'] ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($m['reason']) ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($m['staff_name'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php ob_start(); ?>
    <?php require __DIR__ . '/_stock_form.php'; ?>
<?php
$modalContent = ob_get_clean();
$modalId = 'stockInModal';
$modalTitle = 'Add Stock';
$modalIcon = 'plus';
$modalSubtitle = 'Record a new stock-in entry';
require __DIR__ . '/../components/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
