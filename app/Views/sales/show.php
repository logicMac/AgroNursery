<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sale Details</h1>
            <p class="text-sm text-slate-500 mt-0.5">Receipt and line items for this transaction.</p>
        </div>
        <a href="<?= url('sales') ?>" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-sm font-medium transition duration-150">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="receipt" class="w-5 h-5 text-emerald-600"></i>
                <div>
                    <h2 class="font-semibold text-slate-800">Receipt #<?= e($sale['receipt_number']) ?></h2>
                    <p class="text-xs text-slate-500"><?= e($sale['sale_date']) ?></p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?= $sale['status'] === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' ?>">
                <i data-lucide="<?= $sale['status'] === 'paid' ? 'check-circle-2' : 'clock' ?>" class="w-3 h-3"></i>
                <?= e($sale['status']) ?>
            </span>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-slate-500">Customer:</span> <span class="font-medium text-slate-800"><?= e($sale['customer_name']) ?></span></div>
                <div><span class="text-slate-500">Date:</span> <span class="text-slate-700"><?= e($sale['sale_date']) ?></span></div>
                <div><span class="text-slate-500">Seller:</span> <span class="text-slate-700"><?= e($sale['seller_name']) ?></span></div>
                <div><span class="text-slate-500">Contact:</span> <span class="text-slate-700"><?= e($sale['customer_contact'] ?? '-') ?></span></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="list" class="w-5 h-5 text-indigo-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Items</h2>
                <p class="text-xs text-slate-500"><?= count($items) ?> line item(s)</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Batch</th>
                        <th class="text-right px-6 py-3.5 font-semibold">Qty</th>
                        <th class="text-right px-6 py-3.5 font-semibold">Price</th>
                        <th class="text-right px-6 py-3.5 font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i data-lucide="package-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No items on this sale</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($items as $i): ?>
                    <?php
                    $qty = (int) ($i['quantity'] ?? 0);
                    $price = (float) ($i['unit_price'] ?? 0);
                    ?>
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-800"><?= e($i['product_name'] ?? 'Deleted product') ?></span><br>
                            <span class="text-xs text-slate-500">Grade: <?= e($i['quality_grade'] ?? '-') ?></span>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?= e($i['lot_id'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-right text-slate-700"><?= $qty ?></td>
                        <td class="px-6 py-4 text-right text-slate-600">₱<?= number_format($price, 2) ?></td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-700">₱<?= number_format($qty * $price, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
            <span class="text-sm text-slate-500">Total Amount</span>
            <span class="text-xl font-bold text-slate-800">₱<?= number_format($sale['total_amount'], 2) ?></span>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
