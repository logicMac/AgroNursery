<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Quality Assessments</h1>
        <p class="text-sm text-slate-500 mt-0.5">Logged quality checks per batch.</p>
    </div>
    <button type="button" data-modal-open="qualityModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> Log Assessment
    </button>
</div>

<!-- Search -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="qualityTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search by lot, product, grade or staff...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
            <span id="qualityCount"><?= count($assessments) ?> assessment(s)</span>
        </div>
    </div>
</div>

<!-- AI Quality Prediction Card -->
<div class="bg-gradient-to-r from-amber-50 to-white rounded-2xl border border-amber-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800">AI Quality Grade Prediction</h2>
                <p class="text-xs text-slate-500">Predict a batch's quality grade from environmental data</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <select id="qualityBatchSelect" class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
                <option value="">Select a batch...</option>
                <?php foreach ($batches as $b): ?>
                <option value="<?= $b['id'] ?>"><?= e($b['lot_id']) ?> — <?= e($b['product_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="predictQuality()" id="predictQualityBtn" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 whitespace-nowrap">
                <i data-lucide="sparkles" class="w-4 h-4"></i> Predict Grade
            </button>
        </div>
    </div>
    <div id="qualityPredictionResult" class="hidden mt-4 pt-4 border-t border-amber-200"></div>
</div>

<!-- Quality Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="award" class="w-5 h-5 text-amber-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Quality Records</h2>
                <p class="text-xs text-slate-500">Grade assessments by staff</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="qualityTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Lot</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Product</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Grade</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Assessed</th>
                    <th class="text-left px-6 py-3.5 font-semibold">By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($assessments)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <i data-lucide="clipboard-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No quality assessments found</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Log a quality check to start tracking batch grades.</p>
                        <button type="button" data-modal-open="qualityModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> Log Assessment
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($assessments as $a): ?>
                <?php
                $grade = strtolower($a['grade'] ?? 'unknown');
                $gradeClass = match($grade) {
                    'excellent', 'healthy', 'a' => 'bg-green-100 text-green-700',
                    'good', 'b' => 'bg-slate-100 text-slate-700',
                    'fair', 'average', 'c' => 'bg-amber-100 text-amber-700',
                    'poor', 'bad', 'd' => 'bg-red-100 text-red-700',
                    default => 'bg-slate-100 text-slate-600'
                };
                $gradeIcon = match($grade) {
                    'excellent', 'healthy', 'a' => 'thumbs-up',
                    'good', 'b' => 'smile',
                    'fair', 'average', 'c' => 'meh',
                    'poor', 'bad', 'd' => 'thumbs-down',
                    default => 'help-circle'
                };
                ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 font-semibold text-slate-700"><?= e($a['lot_id']) ?></td>
                    <td class="px-6 py-4 text-slate-700"><?= e($a['product_name']) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?= $gradeClass ?>">
                            <i data-lucide="<?= $gradeIcon ?>" class="w-3 h-3"></i>
                            <?= e($a['grade']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-slate-600">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            <?= e($a['assessed_at']) ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-slate-600">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                            <?= e($a['staff_name'] ?? '-') ?>
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
$modalId = 'qualityModal';
$modalTitle = 'New Quality Assessment';
$modalIcon = 'clipboard-check';
$modalSubtitle = 'Log a quality check for a batch';
require __DIR__ . '/../components/modal.php';
?>

<script>
function predictQuality() {
    const batchId = document.getElementById('qualityBatchSelect').value;
    const result = document.getElementById('qualityPredictionResult');
    const btn = document.getElementById('predictQualityBtn');

    if (!batchId) {
        alert('Please select a batch first.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Predicting...';
    result.classList.remove('hidden');
    result.innerHTML = '<div class="text-center py-4"><i data-lucide="loader-2" class="w-6 h-6 mx-auto mb-2 text-amber-400 animate-spin"></i><p class="text-sm text-slate-500">Analyzing environmental data with AI...</p></div>';
    lucide.createIcons();

    const csrf = document.querySelector('input[name="csrf_token"]')?.value;

    fetch('<?= url("ai/quality/") ?>' + batchId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'csrf_token=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            result.innerHTML = '<div class="p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-center gap-2"><i data-lucide="alert-circle" class="w-4 h-4"></i> ' + data.error + '</div>';
        } else {
            const p = data.prediction;
            const gradeColors = { 'Excellent': 'green', 'Good': 'emerald', 'Fair': 'amber', 'Poor': 'red' };
            const color = gradeColors[p.predicted_grade] || 'slate';

            result.innerHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-${color}-50 border border-${color}-200">
                        <p class="text-xs text-slate-500 font-medium">Predicted Grade</p>
                        <p class="text-xl font-bold text-${color}-700 mt-1">${p.predicted_grade || 'N/A'}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-xs text-slate-500 font-medium">Confidence</p>
                        <p class="text-xl font-bold text-slate-700 mt-1">${p.confidence || 0}%</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-xs text-slate-500 font-medium">Key Factors</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            ${(p.key_factors || []).map(f => '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">' + f + '</span>').join('')}
                        </div>
                    </div>
                </div>
                ${p.reasoning ? '<div class="mt-3 p-3 rounded-xl bg-slate-50 border border-slate-200"><p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Reasoning</p><p class="text-sm text-slate-700">' + p.reasoning + '</p></div>' : ''}
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
        btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4"></i> Predict Grade';
        lucide.createIcons();
    });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
