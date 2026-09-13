<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Services\GroqService;
use App\Models\Setting;

class ChatbotController extends Controller
{
    public function send(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json');

        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            echo json_encode(['error' => 'Invalid request.']);
            return;
        }

        $message = trim($_POST['message'] ?? '');
        if ($message === '') {
            echo json_encode(['error' => 'Message is empty.']);
            return;
        }

        $groq = new GroqService();
        if (!$groq->isEnabled()) {
            $setting = new Setting();
            if (!$setting->get('ai_enabled')) {
                echo json_encode(['error' => 'AI is not enabled. Ask the owner to enable it in Settings.']);
            } else {
                echo json_encode(['error' => 'No Groq API key configured. Ask the owner to add one in Settings.']);
            }
            return;
        }

        $user = $this->session();
        $role = $user['role'] ?? 'staff';
        $name = $user['name'] ?? 'User';

        $systemPrompt = $this->buildSystemPrompt($role, $name);

        // Build conversation history from POST (optional, limited)
        $history = [];
        $rawHistory = $_POST['history'] ?? '';
        if ($rawHistory) {
            $decoded = json_decode($rawHistory, true);
            if (is_array($decoded)) {
                foreach (array_slice($decoded, -10) as $h) {
                    if (isset($h['role']) && isset($h['content'])) {
                        $history[] = [
                            'role' => $h['role'] === 'user' ? 'user' : 'assistant',
                            'content' => substr($h['content'], 0, 1000),
                        ];
                    }
                }
            }
        }

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($history as $h) {
            $messages[] = $h;
        }
        $messages[] = ['role' => 'user', 'content' => $message];

        $reply = $groq->getCompletion($messages, 0.5);

        if ($reply === null) {
            echo json_encode(['error' => 'AI could not respond. Try again.']);
            return;
        }

        echo json_encode(['reply' => $reply]);
    }

    private function buildSystemPrompt(string $role, string $name): string
    {
        $base = "You are AgroBot, a friendly and helpful AI assistant for the Agro Nursery Farm: Product Sales and Quality Tracking System (ANFPSQTS). "
              . "The nursery is located in Polomolok, South Cotabato, Philippines and sells fruit tree seedlings and nursery products. "
              . "You help users with questions about sales, inventory, batches, quality grading, shrinkage tracking, forecasting, pricing, and general nursery management. "
              . "Keep responses concise, practical, and friendly. Use Philippine Peso (₱) for any currency references. "
              . "If you don't know something specific about the user's data, guide them to the relevant page in the system.";

        if ($role === 'owner') {
            $base .= "\n\nThe current user is {$name}, the nursery owner. They have full access including analytics, user management, AI settings, and all features. "
                   . "You can provide strategic advice on revenue, inventory planning, quality improvement, and business decisions.";
        } else {
            $base .= "\n\nThe current user is {$name}, a staff member. They focus on daily tasks like recording sales, logging environmental conditions, quality checks, and shrinkage records. "
                   . "Provide practical guidance on day-to-day nursery operations.";
        }

        return $base;
    }
}
