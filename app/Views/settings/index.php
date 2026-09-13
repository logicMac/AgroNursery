<?php require __DIR__ . '/../layouts/main.php'; ?>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">AI Settings</h1>
        <p class="text-sm text-slate-500 mt-0.5">Configure Groq API key and enable AI features.</p>
    </div>
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium <?= $groqConnected ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
        <span class="w-2 h-2 rounded-full <?= $groqConnected ? 'bg-green-500' : 'bg-red-500' ?>"></span>
        <?= $groqConnected ? 'API Connected' : 'Not Connected' ?>
    </span>
</div>

<?php if (!$groqConnected): ?>
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
    <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 mt-0.5 shrink-0"></i>
    <div>
        <p class="text-sm font-medium text-amber-800">No API Key Configured</p>
        <p class="text-xs text-amber-700 mt-1">Get a free API key at <a href="https://console.groq.com/keys" target="_blank" class="underline font-medium">console.groq.com/keys</a>, then paste it below to enable AI features.</p>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- API Configuration -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="key" class="w-5 h-5 text-indigo-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">Groq API Configuration</h2>
                <p class="text-xs text-slate-500">API key and model selection</p>
            </div>
        </div>
        <form action="<?= url('settings/update') ?>" method="POST" class="p-5 space-y-5">
            <?= \App\Core\Csrf::field() ?>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">API Key</label>
                <div class="relative">
                    <input type="password" name="groq_api_key" id="apiKeyInput" value="<?= e($apiKey) ?>" placeholder="gsk_..." class="w-full rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm pr-10">
                    <button type="button" onclick="toggleKeyVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i data-lucide="eye" class="w-4 h-4" id="eyeIcon"></i>
                    </button>
                </div>
                <p class="text-xs text-slate-500 mt-1">Your key is stored in the database and never exposed in the UI.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Model</label>
                <select name="groq_model" class="w-full rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-0 px-4 py-2.5 text-sm">
                    <option value="llama-3.3-70b-versatile" <?= $model === 'llama-3.3-70b-versatile' ? 'selected' : '' ?>>Llama 3.3 70B Versatile (recommended)</option>
                    <option value="llama-3.1-8b-instant" <?= $model === 'llama-3.1-8b-instant' ? 'selected' : '' ?>>Llama 3.1 8B Instant (fast)</option>
                    <option value="mixtral-8x7b-32768" <?= $model === 'mixtral-8x7b-32768' ? 'selected' : '' ?>>Mixtral 8x7B</option>
                    <option value="gemma2-9b-it" <?= $model === 'gemma2-9b-it' ? 'selected' : '' ?>>Gemma 2 9B</option>
                </select>
            </div>

            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                <input type="checkbox" name="ai_enabled" id="aiEnabled" <?= $aiEnabled ? 'checked' : '' ?> class="w-4 h-4 rounded border-gray-300 text-mint-600 focus:ring-0">
                <label for="aiEnabled" class="text-sm font-medium text-slate-700 cursor-pointer flex-1">Enable AI features globally</label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-mint-600 hover:bg-mint-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-md hover:shadow-lg transition duration-200">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Settings
                </button>
                <button type="button" id="testBtn" onclick="testConnection()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition duration-200">
                    <i data-lucide="wifi" class="w-4 h-4"></i> Test Connection
                </button>
            </div>
            <div id="testResult" class="hidden"></div>
        </form>
    </div>

    <!-- Feature Toggles -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
            <i data-lucide="sparkles" class="w-5 h-5 text-fuchsia-600"></i>
            <div>
                <h2 class="font-semibold text-slate-800">AI Features</h2>
                <p class="text-xs text-slate-500">Toggle individual features</p>
            </div>
        </div>
        <form action="<?= url('settings/update') ?>" method="POST" class="p-5 space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <input type="hidden" name="groq_api_key" value="<?= e($apiKey) ?>">
            <input type="hidden" name="groq_model" value="<?= e($model) ?>">
            <input type="hidden" name="ai_enabled" value="1" <?= $aiEnabled ? '' : 'disabled' ?>>

            <?php
            $features = [
                'shrinkage_risk' => ['Shrinkage Risk Prediction', 'Flag batches likely to fail before losses happen', 'trending-down', 'text-rose-600 bg-rose-100'],
                'quality_prediction' => ['Quality Grade Prediction', 'Predict grade from environment logs', 'clipboard-check', 'text-amber-600 bg-amber-100'],
                'dynamic_pricing' => ['Smart Dynamic Pricing', 'ML-optimized prices from sales history', 'tags', 'text-fuchsia-600 bg-fuchsia-100'],
                'yield_forecast' => ['AI Yield Forecasting', 'AI-powered ready-date predictions', 'calendar', 'text-cyan-600 bg-cyan-100'],
            ];
            foreach ($features as $key => [$label, $desc, $icon, $color]):
            ?>
            <label class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition cursor-pointer border border-slate-100">
                <input type="checkbox" name="feature_<?= $key ?>" <?= !empty($aiFeatures[$key]) ? 'checked' : '' ?> class="w-4 h-4 mt-0.5 rounded border-gray-300 text-mint-600 focus:ring-0">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-lg <?= $color ?> flex items-center justify-center">
                            <i data-lucide="<?= $icon ?>" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-800"><?= $label ?></span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1"><?= $desc ?></p>
                </div>
            </label>
            <?php endforeach; ?>

            <button type="submit" class="w-full bg-mint-600 hover:bg-mint-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition duration-200">
                <i data-lucide="save" class="w-4 h-4"></i> Save Feature Settings
            </button>
        </form>
    </div>
</div>

<!-- Info Card -->
<div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
        <i data-lucide="info" class="w-5 h-5 text-sky-600"></i>
        <div>
            <h2 class="font-semibold text-slate-800">About AI Features</h2>
            <p class="text-xs text-slate-500">How each feature uses your data</p>
        </div>
    </div>
    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="flex items-start gap-3 p-3 rounded-xl bg-rose-50 border border-rose-100">
            <i data-lucide="trending-down" class="w-5 h-5 text-rose-600 mt-0.5 shrink-0"></i>
            <div>
                <p class="font-medium text-slate-800">Shrinkage Risk Prediction</p>
                <p class="text-xs text-slate-600 mt-1">Analyzes batch environment, health, age, and loss history to predict risk level (low/medium/high) with a score and recommendation.</p>
            </div>
        </div>
        <div class="flex items-start gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
            <i data-lucide="clipboard-check" class="w-5 h-5 text-amber-600 mt-0.5 shrink-0"></i>
            <div>
                <p class="font-medium text-slate-800">Quality Grade Prediction</p>
                <p class="text-xs text-slate-600 mt-1">Uses environmental logs (pH, temp, humidity, water) to predict the quality grade before manual assessment.</p>
            </div>
        </div>
        <div class="flex items-start gap-3 p-3 rounded-xl bg-fuchsia-50 border border-fuchsia-100">
            <i data-lucide="tags" class="w-5 h-5 text-fuchsia-600 mt-0.5 shrink-0"></i>
            <div>
                <p class="font-medium text-slate-800">Smart Dynamic Pricing</p>
                <p class="text-xs text-slate-600 mt-1">Analyzes sales velocity, stock levels, and grade performance to suggest optimal prices per product.</p>
            </div>
        </div>
        <div class="flex items-start gap-3 p-3 rounded-xl bg-cyan-50 border border-cyan-100">
            <i data-lucide="calendar" class="w-5 h-5 text-cyan-600 mt-0.5 shrink-0"></i>
            <div>
                <p class="font-medium text-slate-800">AI Yield Forecasting</p>
                <p class="text-xs text-slate-600 mt-1">Predicts ready dates using environmental conditions, growth stage, and batch age instead of static rules.</p>
            </div>
        </div>
    </div>
</div>

<script>
function toggleKeyVisibility() {
    const input = document.getElementById('apiKeyInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}

function testConnection() {
    const btn = document.getElementById('testBtn');
    const result = document.getElementById('testResult');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Testing...';
    lucide.createIcons();

    const csrf = document.querySelector('input[name="csrf_token"]').value;

    fetch('<?= url("settings/test") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'csrf_token=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(data => {
        result.classList.remove('hidden');
        if (data.success) {
            result.className = 'mt-3 p-3 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 flex items-center gap-2';
            result.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> ' + data.message;
        } else {
            result.className = 'mt-3 p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-center gap-2';
            result.innerHTML = '<i data-lucide="x-circle" class="w-4 h-4"></i> ' + data.message;
        }
        lucide.createIcons();
    })
    .catch(() => {
        result.classList.remove('hidden');
        result.className = 'mt-3 p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700';
        result.textContent = 'Request failed. Check your network.';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="wifi" class="w-4 h-4"></i> Test Connection';
        lucide.createIcons();
    });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
