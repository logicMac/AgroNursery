<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Services\GroqService;

class SettingsController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('settings');
        $setting = new Setting();
        $settings = $setting->getMany([
            'groq_api_key',
            'groq_model',
            'ai_enabled',
            'ai_features',
        ]);

        $aiFeatures = json_decode($settings['ai_features'] ?? '{}', true);

        $this->view('settings/index', [
            'apiKey' => $settings['groq_api_key'] ?? '',
            'model' => $settings['groq_model'] ?? 'llama-3.3-70b-versatile',
            'aiEnabled' => ($settings['ai_enabled'] ?? '0') === '1',
            'aiFeatures' => $aiFeatures,
            'groqConnected' => (new GroqService())->isEnabled(),
            'title' => 'AI Settings',
        ]);
    }

    public function update(): void
    {
        $this->requirePermission('settings');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/settings');
        }

        $setting = new Setting();

        $apiKey = trim($_POST['groq_api_key'] ?? '');
        $model = trim($_POST['groq_model'] ?? 'llama-3.3-70b-versatile');
        $aiEnabled = isset($_POST['ai_enabled']) ? '1' : '0';

        $features = [
            'shrinkage_risk' => isset($_POST['feature_shrinkage_risk']),
            'quality_prediction' => isset($_POST['feature_quality_prediction']),
            'dynamic_pricing' => isset($_POST['feature_dynamic_pricing']),
            'yield_forecast' => isset($_POST['feature_yield_forecast']),
        ];

        $setting->set('groq_api_key', $apiKey);
        $setting->set('groq_model', $model);
        $setting->set('ai_enabled', $aiEnabled);
        $setting->set('ai_features', json_encode($features));

        (new AuditLog())->log($this->userId(), 'settings_updated', 'settings', 0, ['ai_enabled' => $aiEnabled]);

        flash('success', 'AI settings saved successfully.');
        $this->redirect('/settings');
    }

    public function test(): void
    {
        $this->requirePermission('settings');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 400);
        }

        $groq = new GroqService();
        if (!$groq->isEnabled()) {
            $this->json(['success' => false, 'message' => 'No API key configured.']);
        }

        $result = $groq->chat([
            ['role' => 'system', 'content' => 'You are a helpful assistant. Respond briefly.'],
            ['role' => 'user', 'content' => 'Say "Connection successful" in exactly 3 words.'],
        ], null, 0.1);

        if (!$result) {
            $this->json(['success' => false, 'message' => 'No response from Groq API.']);
        }

        if (isset($result['error'])) {
            $this->json(['success' => false, 'message' => $result['error']]);
        }

        $content = $result['choices'][0]['message']['content'] ?? '';
        $this->json(['success' => true, 'message' => 'Connected: ' . trim($content)]);
    }
}
