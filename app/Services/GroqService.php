<?php

namespace App\Services;

use App\Models\Setting;

class GroqService
{
    private ?string $apiKey;
    private string $model;
    private string $endpoint = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $setting = new Setting();
        $this->apiKey = $setting->get('groq_api_key');
        $this->model = $setting->get('groq_model', 'llama-3.3-70b-versatile');
    }

    public function isEnabled(): bool
    {
        return !empty($this->apiKey);
    }

    public function chat(array $messages, ?string $model = null, float $temperature = 0.3): ?array
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $payload = [
            'model' => $model ?? $this->model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => 1024,
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            return ['error' => 'cURL error: ' . $error];
        }

        $data = json_decode($response, true);
        if ($code !== 200) {
            return ['error' => $data['error']['message'] ?? "HTTP $code"];
        }

        return $data;
    }

    public function getCompletion(array $messages, float $temperature = 0.3): ?string
    {
        $result = $this->chat($messages, null, $temperature);
        if (!$result || isset($result['error'])) {
            return null;
        }
        return $result['choices'][0]['message']['content'] ?? null;
    }

    public function getJsonCompletion(array $messages, float $temperature = 0.2): ?array
    {
        $content = $this->getCompletion($messages, $temperature);
        if (!$content) {
            return null;
        }
        // Extract JSON from the response (handles code blocks and extra text)
        $content = trim($content);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $m)) {
            $content = $m[1];
        }
        $content = trim($content);
        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
