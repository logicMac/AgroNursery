<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Forecast: <?= e($batch['lot_id']) ?></h1>
            <p class="text-sm text-slate-500 mt-0.5">Ready-date prediction for this batch.</p>
        </div>
        <a href="<?= url('forecast') ?>" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-3 py-1.5 rounded-lg text-sm font-medium transition duration-150">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="sparkles" class="w-5 h-5 text-cyan-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Batch Conditions</h2>
                <p class="text-xs text-slate-500">Averages from logged environment data</p>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div><span class="text-slate-500">Product:</span> <span class="font-medium text-slate-800"><?= e($batch['product_name']) ?></span></div>
                <div><span class="text-slate-500">Growth Stage:</span> <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= e($batch['growth_stage']) ?></span></div>
                <div><span class="text-slate-500">Avg pH:</span> <span class="text-slate-700"><?= number_format($avg['avg_ph'] ?? 0, 2) ?></span></div>
                <div><span class="text-slate-500">Avg Temp:</span> <span class="text-slate-700"><?= number_format($avg['avg_temp'] ?? 0, 1) ?>°C</span></div>
                <div><span class="text-slate-500">Avg Water:</span> <span class="text-slate-700"><?= number_format($avg['avg_water'] ?? 0, 0) ?> ml</span></div>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 mb-4">
                <p class="text-sm text-slate-500 flex items-center gap-1.5"><i data-lucide="calendar-check" class="w-4 h-4 text-slate-400"></i> Projected Ready Date (Rule-based)</p>
                <p class="text-3xl font-bold text-slate-800 mt-1"><?= e($readyDate) ?></p>
                <p class="text-sm text-slate-500 mt-1">Confidence: <span class="font-medium text-slate-700"><?= $confidence ?>%</span></p>
            </div>
            <div class="flex items-center gap-3">
                <form action="" method="POST" class="inline">
                    <?= \App\Core\Csrf::field() ?>
                    <button type="submit" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Forecast
                    </button>
                </form>
                <button type="button" onclick="aiForecast(<?= $batch['id'] ?>)" id="aiForecastBtn" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2 shadow-sm hover:shadow-md transition duration-200">
                    <i data-lucide="sparkles" class="w-4 h-4"></i> AI Forecast
                </button>
            </div>
            <div id="aiForecastResult" class="hidden mt-4 pt-4 border-t border-slate-200"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-slate-500"></i>
                <div>
                    <h2 class="font-semibold text-slate-800">Saved Forecasts</h2>
                    <p class="text-xs text-slate-500">Previous predictions for this lot</p>
                </div>
            </div>
            <div class="text-sm text-slate-500"><?= count($forecasts) ?> record(s)</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600">
                    <tr>
                        <th class="text-left px-6 py-3.5 font-semibold">Projected Date</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Confidence</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Stage</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Saved</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($forecasts)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                            <p class="text-slate-500 font-medium">No saved forecasts</p>
                            <p class="text-sm text-slate-400 mt-1">Save the current projection to keep a record.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($forecasts as $f): ?>
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 font-medium text-slate-800"><?= e($f['projected_ready_date']) ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700"><?= $f['confidence_score'] ?>%</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?= e($f['growth_stage_at_forecast']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= e($f['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function aiForecast(batchId) {
    const result = document.getElementById('aiForecastResult');
    const btn = document.getElementById('aiForecastBtn');

    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Forecasting...';
    result.classList.remove('hidden');
    result.innerHTML = '<div class="text-center py-4"><i data-lucide="loader-2" class="w-6 h-6 mx-auto mb-2 text-slate-400 animate-spin"></i><p class="text-sm text-slate-500">AI is analyzing batch conditions...</p></div>';
    lucide.createIcons();

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="csrf_token"]')?.value;

    fetch('<?= url("ai/forecast/") ?>' + batchId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'csrf_token=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            result.innerHTML = '<div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-600 flex items-center gap-2"><i data-lucide="alert-circle" class="w-4 h-4"></i> ' + data.error + '</div>';
        } else {
            const f = data.forecast;
            result.innerHTML = `
                <div class="p-4 rounded-xl bg-mint-50 border border-mint-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs text-slate-500 font-medium flex items-center gap-1"><i data-lucide="sparkles" class="w-3 h-3 text-mint-700"></i> AI Projected Ready Date</p>
                            <p class="text-2xl font-bold text-mint-700 mt-1">${f.projected_ready_date || 'N/A'}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 font-medium">Days Until Ready</p>
                            <p class="text-2xl font-bold text-mint-700">${f.days_until_ready ?? 'N/A'}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-xs text-slate-500 font-medium mb-1">Confidence: ${f.confidence || 0}%</p>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-mint-500 rounded-full h-2" style="width: ${f.confidence || 0}%"></div>
                        </div>
                    </div>
                    ${f.key_factors ? '<div class="mb-3"><p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Key Factors</p><div class="flex flex-wrap gap-2">' + f.key_factors.map(k => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">' + k + '</span>').join('') + '</div></div>' : ''}
                    ${f.reasoning ? '<div class="mb-2 p-3 rounded-xl bg-white border border-slate-200"><p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Reasoning</p><p class="text-sm text-slate-700">' + f.reasoning + '</p></div>' : ''}
                    ${f.recommendation ? '<div class="p-3 rounded-xl bg-white border border-mint-200"><p class="text-xs font-medium text-mint-700 uppercase tracking-wider mb-1">Care Recommendation</p><p class="text-sm text-slate-700">' + f.recommendation + '</p></div>' : ''}
                </div>
            `;
        }
        lucide.createIcons();
    })
    .catch(() => {
        result.innerHTML = '<div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-600">Request failed.</div>';
        lucide.createIcons();
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4"></i> AI Forecast';
        lucide.createIcons();
    });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
