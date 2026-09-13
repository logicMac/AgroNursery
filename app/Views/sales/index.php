<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Sales</h1>
        <p class="text-sm text-slate-500 mt-0.5">Recorded nursery sales transactions.</p>
    </div>
    <button type="button" data-modal-open="saleModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> New Sale
    </button>
</div>

<!-- Search -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="salesTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search by receipt, customer or seller...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="receipt" class="w-4 h-4"></i>
            <span id="salesCount"><?= count($sales) ?> sale(s)</span>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="banknote" class="w-5 h-5 text-emerald-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Sales Transactions</h2>
                <p class="text-xs text-slate-500">All recorded sales</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="salesTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Receipt</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Customer</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Date</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Amount</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Status</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Seller</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($sales)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-lucide="receipt" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No sales found</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Record your first sale to see it here.</p>
                        <button type="button" data-modal-open="saleModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> New Sale
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($sales as $s): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                            <i data-lucide="hash" class="w-3 h-3"></i>
                            <?= e($s['receipt_number']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="font-medium text-slate-700"><?= e($s['customer_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-slate-600">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            <?= e($s['sale_date']) ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-slate-700">₱<?= number_format($s['total_amount'], 2) ?></td>
                    <td class="px-6 py-4">
                        <?php $paid = $s['status'] === 'paid'; ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?= $paid ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' ?>">
                            <i data-lucide="<?= $paid ? 'check-circle-2' : 'clock' ?>" class="w-3 h-3"></i>
                            <?= e($s['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user-circle" class="w-3.5 h-3.5 text-slate-400"></i>
                            <?= e($s['seller_name']) ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= url('sales/' . $s['id']) ?>" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                            <i data-lucide="eye" class="w-3 h-3"></i> View
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
$modalId = 'saleModal';
$modalTitle = 'New Sale';
$modalIcon = 'receipt';
$modalSubtitle = 'Record a new nursery sale';
require __DIR__ . '/../components/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
