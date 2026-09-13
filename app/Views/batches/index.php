<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Batches & Lots</h1>
        <p class="text-sm text-slate-500 mt-0.5">Track seedling batches, growth stages, and expected ready dates.</p>
    </div>
    <button type="button" data-modal-open="batchModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> New Batch
    </button>
</div>

<!-- Search -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="batchTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search by lot, product, stage or health...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="layers" class="w-4 h-4"></i>
            <span id="batchCount"><?= count($batches) ?> batch(es)</span>
        </div>
    </div>
</div>

<!-- Batches Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center gap-2">
            <i data-lucide="sprout" class="w-5 h-5 text-violet-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Batch & Lot Registry</h2>
                <p class="text-xs text-slate-500">All active seedling batches and their current status</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="batchTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Photo</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Lot ID</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Count</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Stage</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Health</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Ready Date</th>
                    <th class="text-left px-6 py-3.5 font-semibold">AI Risk</th>
                    <th class="text-right px-6 py-3.5 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($batches)): ?>
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <i data-lucide="sprout" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No batches found</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Create your first batch to start tracking seedlings.</p>
                        <button type="button" data-modal-open="batchModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> Create Batch
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($batches as $b): ?>
                <?php
                $readyDate = !empty($b['expected_ready_date']) ? strtotime($b['expected_ready_date']) : null;
                $today = strtotime(date('Y-m-d'));
                $readyBadge = null;
                if ($readyDate) {
                    if ($readyDate < $today) {
                        $readyBadge = ['bg-red-100 text-red-700', 'alert-circle', 'Overdue'];
                    } elseif ($readyDate <= strtotime('+7 days', $today)) {
                        $readyBadge = ['bg-amber-100 text-amber-700', 'clock', 'Soon'];
                    }
                }
                ?>
                <tr class="hover:bg-slate-50/60 transition duration-150 group">
                    <td class="px-6 py-4">
                        <?php if (!empty($b['product_image'])): ?>
                        <img src="<?= url($b['product_image']) ?>" alt="<?= e($b['product_name']) ?>" class="h-11 w-11 object-cover rounded-xl border border-slate-200 shadow-sm">
                        <?php else: ?>
                        <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400"><i data-lucide="image" class="w-4 h-4"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-800"><?= e($b['lot_id']) ?></td>
                    <td class="px-6 py-4 text-slate-700"><?= e($b['product_name']) ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="font-semibold text-slate-800"><?= $b['seedling_count'] ?></div>
                        <div class="text-xs text-slate-500">seedlings</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                            <?= e($b['growth_stage']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                        $health = strtolower($b['health_status'] ?? 'unknown');
                        $healthClass = match($health) {
                            'excellent', 'good', 'healthy' => 'bg-green-100 text-green-700',
                            'fair', 'average' => 'bg-amber-100 text-amber-700',
                            'poor', 'bad', 'critical' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-600'
                        };
                        $healthIcon = match($health) {
                            'excellent', 'good', 'healthy' => 'heart-pulse',
                            'poor', 'bad', 'critical' => 'alert-circle',
                            default => 'activity'
                        };
                        ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?= $healthClass ?>">
                            <i data-lucide="<?= $healthIcon ?>" class="w-3 h-3"></i>
                            <?= e($b['health_status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-slate-700 whitespace-nowrap">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            <?= e($b['expected_ready_date'] ?? '-') ?>
                        </div>
                        <?php if ($readyBadge): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium <?= $readyBadge[0] ?> mt-1">
                            <i data-lucide="<?= $readyBadge[1] ?>" class="w-3 h-3"></i> <?= $readyBadge[2] ?>
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div id="risk-<?= $b['id'] ?>" class="min-w-[100px]">
                            <button type="button" onclick="predictRisk(<?= $b['id'] ?>)" class="inline-flex items-center gap-1 text-mint-700 hover:text-mint-600 hover:bg-mint-50 px-2.5 py-1 rounded-lg text-xs font-medium transition duration-150">
                                <i data-lucide="sparkles" class="w-3 h-3"></i> Predict
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= url('batches/' . $b['id']) ?>" class="inline-flex items-center gap-1.5 text-slate-700 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View
                            </a>
                            <a href="<?= url('batches/' . $b['id'] . '/edit') ?>" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                            </a>
                        </div>
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
$modalId = 'batchModal';
$modalTitle = 'New Batch / Lot';
$modalIcon = 'sprout';
$modalSubtitle = 'Create a new product batch';
require __DIR__ . '/../components/modal.php';
?>

<!-- AI Shrinkage Risk Modal -->
<div id="riskModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(15, 23, 42, 0.4);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-mint-100 flex items-center justify-center text-mint-700">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">AI Shrinkage Risk Prediction</h3>
                    <p class="text-xs text-slate-500" id="riskModalBatch">Batch analysis</p>
                </div>
            </div>
            <button type="button" onclick="closeRiskModal()" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-5" id="riskModalBody">
            <div class="text-center py-8">
                <i data-lucide="loader-2" class="w-8 h-8 mx-auto mb-3 text-slate-400 animate-spin"></i>
                <p class="text-sm text-slate-500">Analyzing batch data with AI...</p>
            </div>
        </div>
    </div>
</div>

<script>
function predictRisk(batchId) {
    const modal = document.getElementById('riskModal');
    const body = document.getElementById('riskModalBody');
    const batchLabel = document.getElementById('riskModalBatch');

    batchLabel.textContent = 'Batch #' + batchId;
    body.innerHTML = '<div class="text-center py-8"><i data-lucide="loader-2" class="w-8 h-8 mx-auto mb-3 text-slate-400 animate-spin"></i><p class="text-sm text-slate-500">Analyzing batch data with AI...</p></div>';
    lucide.createIcons();

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="csrf_token"]')?.value;

    fetch('<?= url("ai/shrinkage/") ?>' + batchId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'csrf_token=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            body.innerHTML = '<div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-600 flex items-center gap-2"><i data-lucide="alert-circle" class="w-4 h-4"></i> ' + data.error + '</div>';
        } else {
            const p = data.prediction;
            const score = p.risk_score || 0;

            // Update inline badge
            const inline = document.getElementById('risk-' + batchId);
            if (inline) {
                inline.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-mint-100 text-mint-700"><i data-lucide="activity" class="w-3 h-3"></i> ' + (p.risk_level || 'N/A').charAt(0).toUpperCase() + (p.risk_level || '').slice(1) + ' (' + score + ')</span>';
            }

            body.innerHTML = `
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-xl bg-mint-50 border border-mint-200">
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Risk Level</p>
                            <p class="text-2xl font-bold text-mint-700 capitalize">${p.risk_level || 'N/A'}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 font-medium">Risk Score</p>
                            <p class="text-2xl font-bold text-mint-700">${score}/100</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1.5">Confidence: ${p.confidence || 0}%</p>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-mint-500 rounded-full h-2" style="width: ${p.confidence || 0}%"></div>
                        </div>
                    </div>
                    ${p.primary_factors ? `
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Primary Factors</p>
                        <div class="flex flex-wrap gap-2">
                            ${p.primary_factors.map(f => `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">${f}</span>`).join('')}
                        </div>
                    </div>` : ''}
                    ${p.recommendation ? `
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Recommendation</p>
                        <p class="text-sm text-slate-700">${p.recommendation}</p>
                    </div>` : ''}
                </div>
            `;
        }
        lucide.createIcons();
    })
    .catch(() => {
        body.innerHTML = '<div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-600">Request failed. Check your network.</div>';
        lucide.createIcons();
    });
}

function closeRiskModal() {
    const modal = document.getElementById('riskModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
