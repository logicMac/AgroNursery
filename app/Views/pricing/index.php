<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Pricing Rules</h1>
        <p class="text-sm text-slate-500 mt-0.5">Dynamic price multipliers by grade, size and age.</p>
    </div>
    <button type="button" data-modal-open="pricingModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto"><i data-lucide="plus" class="w-4 h-4"></i> New Rule</button>
</div>

<!-- AI Dynamic Pricing Card -->
<div class="bg-gradient-to-r from-fuchsia-50 to-white rounded-2xl border border-fuchsia-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-fuchsia-100 flex items-center justify-center text-fuchsia-600">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800">AI Smart Pricing</h2>
                <p class="text-xs text-slate-500">Get AI-suggested optimal prices based on sales history and stock</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <select id="pricingProductSelect" class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
                <option value="">Select a product...</option>
                <?php foreach ($products as $p): ?>
                <option value="<?= $p['id'] ?>"><?= e($p['name']) ?> (₱<?= number_format($p['base_price'], 2) ?>)</option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="suggestPrice()" id="suggestPriceBtn" class="bg-fuchsia-600 hover:bg-fuchsia-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 whitespace-nowrap">
                <i data-lucide="sparkles" class="w-4 h-4"></i> Suggest Price
            </button>
        </div>
    </div>
    <div id="priceSuggestionResult" class="hidden mt-4 pt-4 border-t border-fuchsia-200"></div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="tags" class="w-5 h-5 text-fuchsia-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Active Rules</h2>
                <p class="text-xs text-slate-500"><?= count($rules) ?> rule(s) configured</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Grade</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Size (cm)</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Age (days)</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Multiplier</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Priority</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($rules)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-lucide="tags" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No pricing rules found</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Create a rule to adjust prices automatically.</p>
                        <button type="button" data-modal-open="pricingModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> New Rule
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($rules as $r): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 font-medium text-slate-800"><?= e($r['product_name']) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($r['grade']) ?></span>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= $r['size_cm_min'] ?? 0 ?>-<?= $r['size_cm_max'] ?? '∞' ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= $r['age_days_min'] ?? 0 ?>-<?= $r['age_days_max'] ?? '∞' ?></td>
                    <td class="px-6 py-4 text-right">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700"><?= $r['multiplier'] ?>x</span>
                    </td>
                    <td class="px-6 py-4 text-right text-slate-600"><?= $r['priority'] ?></td>
                    <td class="px-6 py-4 text-right">
                        <form action="<?= url('pricing/' . $r['id'] . '/delete') ?>" method="POST" class="inline" data-confirm="Delete this rule?">
                            <?= \App\Core\Csrf::field() ?>
                            <button class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150"><i data-lucide="trash-2" class="w-3 h-3"></i> Delete</button>
                        </form>
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
$modalId = 'pricingModal';
$modalTitle = 'New Pricing Rule';
$modalIcon = 'tags';
$modalSubtitle = 'Create a dynamic pricing rule';
require __DIR__ . '/../components/modal.php';
?>

<script>
function suggestPrice() {
    const productId = document.getElementById('pricingProductSelect').value;
    const result = document.getElementById('priceSuggestionResult');
    const btn = document.getElementById('suggestPriceBtn');

    if (!productId) {
        alert('Please select a product first.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Analyzing...';
    result.classList.remove('hidden');
    result.innerHTML = '<div class="text-center py-4"><i data-lucide="loader-2" class="w-6 h-6 mx-auto mb-2 text-fuchsia-400 animate-spin"></i><p class="text-sm text-slate-500">Analyzing sales data with AI...</p></div>';
    lucide.createIcons();

    const csrf = document.querySelector('input[name="csrf_token"]')?.value;

    fetch('<?= url("ai/pricing/") ?>' + productId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'csrf_token=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            result.innerHTML = '<div class="p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-center gap-2"><i data-lucide="alert-circle" class="w-4 h-4"></i> ' + data.error + '</div>';
        } else {
            const s = data.suggestion;
            result.innerHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-fuchsia-50 border border-fuchsia-200">
                        <p class="text-xs text-slate-500 font-medium">Suggested Price</p>
                        <p class="text-2xl font-bold text-fuchsia-700 mt-1">₱${Number(s.suggested_price || 0).toFixed(2)}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-xs text-slate-500 font-medium">Multiplier</p>
                        <p class="text-2xl font-bold text-slate-700 mt-1">${s.price_multiplier || 1}x</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-xs text-slate-500 font-medium">Confidence</p>
                        <p class="text-2xl font-bold text-slate-700 mt-1">${s.confidence || 0}%</p>
                    </div>
                </div>
                ${s.factors ? '<div class="mt-3"><p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Factors</p><div class="flex flex-wrap gap-2">' + s.factors.map(f => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">' + f + '</span>').join('') + '</div></div>' : ''}
                ${s.reasoning ? '<div class="mt-3 p-3 rounded-xl bg-slate-50 border border-slate-200"><p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Reasoning</p><p class="text-sm text-slate-700">' + s.reasoning + '</p></div>' : ''}
                ${s.recommendation ? '<div class="mt-2 p-3 rounded-xl bg-fuchsia-50 border border-fuchsia-200"><p class="text-xs font-medium text-fuchsia-600 uppercase tracking-wider mb-1">Recommendation</p><p class="text-sm text-slate-700">' + s.recommendation + '</p></div>' : ''}
            `;
        }
        lucide.createIcons();
    })
    .catch(() => {
        result.innerHTML = '<div class="p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">Request failed.</div>';
        lucide.createIcons();
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4"></i> Suggest Price';
        lucide.createIcons();
    });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
