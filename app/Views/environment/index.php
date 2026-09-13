<?php
$logCount = count($logs);
$uniqueLots = count(array_unique(array_filter(array_column($logs, 'lot_id') ?? [])));
?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Environmental & Maintenance Logs</h1>
        <p class="text-sm text-slate-500 mt-0.5">Care activities and environmental readings per lot.</p>
    </div>
    <button type="button" data-modal-open="environmentModal" class="bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> Log Condition
    </button>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600">
            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Total Logs</div>
            <div class="text-xl font-bold text-slate-800"><?= $logCount ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600">
            <i data-lucide="layers" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Lots Tracked</div>
            <div class="text-xl font-bold text-slate-800"><?= $uniqueLots ?></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-md hover:shadow-lg transition duration-200 flex items-center gap-4 lg:col-span-2">
        <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
            <i data-lucide="cloud-sun" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-xs text-slate-500 font-medium">Tracking</div>
            <div class="text-sm font-medium text-slate-700">Light, water, pH, temperature, humidity and care activities</div>
        </div>
    </div>
</div>

<!-- Search -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" data-target="envTable" class="table-search w-full pl-10 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm" placeholder="Search by date, lot or activity...">
        </div>
        <div class="text-sm text-slate-500 flex items-center gap-2">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            <span id="envCount"><?= $logCount ?> log(s)</span>
        </div>
    </div>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="cloud-sun" class="w-5 h-5 text-sky-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Care & Environment Logs</h2>
                <p class="text-xs text-slate-500">Recorded conditions and activities</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="envTable">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3.5 font-semibold">Date</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Lot</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Activity</th>
                    <th class="text-left px-6 py-3.5 font-semibold">pH</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Temp</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Water (ml)</th>
                    <th class="text-left px-6 py-3.5 font-semibold">Staff</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-lucide="cloud-off" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p class="text-slate-500 font-medium">No environment logs yet</p>
                        <p class="text-sm text-slate-400 mt-1 mb-4">Log a condition or care activity to begin tracking.</p>
                        <button type="button" data-modal-open="environmentModal" class="inline-flex items-center gap-2 bg-mint-600 hover:bg-mint-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                            <i data-lucide="plus" class="w-4 h-4"></i> Log Condition
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $l): ?>
                <tr class="hover:bg-slate-50 transition duration-150 group">
                    <td class="px-6 py-4 text-slate-700"><?= e($l['log_date']) ?></td>
                    <td class="px-6 py-4 font-medium text-slate-800"><?= e($l['lot_id'] ?? '-') ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                            <i data-lucide="activity" class="w-3 h-3 mr-1"></i> <?= e($l['activity']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= e($l['soil_ph'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($l['temperature_c'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($l['water_ml'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-slate-600"><?= e($l['staff_name'] ?? '-') ?></td>
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
$modalId = 'environmentModal';
$modalTitle = 'Log Environmental Condition';
$modalIcon = 'cloud-sun';
$modalSubtitle = 'Record a new care or environment log';
require __DIR__ . '/../components/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
