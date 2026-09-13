<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Batch;
use App\Models\Product;
use App\Services\AIService;

class AIController extends Controller
{
    private AIService $ai;

    public function __construct()
    {
        $this->ai = new AIService();
    }

    public function shrinkageRisk(int $id): void
    {
        $this->requireAuth();
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            $this->json(['error' => 'Invalid request.'], 400);
        }

        try {
            $batch = (new Batch())->find($id);
            if (!$batch) {
                $this->json(['error' => 'Batch not found.'], 404);
            }

            if (!$this->ai->isAvailable()) {
                $this->json(['error' => 'AI is not configured. Add a Groq API key in Settings.'], 503);
            }

            $result = $this->ai->predictShrinkageRisk($batch);
            if (!$result) {
                $this->json(['error' => 'AI prediction failed. Try again.'], 500);
            }

            $this->json(['success' => true, 'prediction' => $result]);
        } catch (\Throwable $e) {
            $this->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function qualityGrade(int $id): void
    {
        $this->requireAuth();
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            $this->json(['error' => 'Invalid request.'], 400);
        }

        try {
            $batch = (new Batch())->find($id);
            if (!$batch) {
                $this->json(['error' => 'Batch not found.'], 404);
            }

            if (!$this->ai->isAvailable()) {
                $this->json(['error' => 'AI is not configured. Add a Groq API key in Settings.'], 503);
            }

            $result = $this->ai->predictQualityGrade($batch);
            if (!$result) {
                $this->json(['error' => 'AI prediction failed. Try again.'], 500);
            }

            $this->json(['success' => true, 'prediction' => $result]);
        } catch (\Throwable $e) {
            $this->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function dynamicPrice(int $id): void
    {
        $this->requireAuth();
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            $this->json(['error' => 'Invalid request.'], 400);
        }

        try {
            $product = (new Product())->find($id);
            if (!$product) {
                $this->json(['error' => 'Product not found.'], 404);
            }

            if (!$this->ai->isAvailable()) {
                $this->json(['error' => 'AI is not configured. Add a Groq API key in Settings.'], 503);
            }

            $result = $this->ai->suggestPrice($id);
            if (!$result) {
                $this->json(['error' => 'AI prediction failed. Try again.'], 500);
            }

            $this->json(['success' => true, 'suggestion' => $result]);
        } catch (\Throwable $e) {
            $this->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function forecastDate(int $id): void
    {
        $this->requireAuth();
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            $this->json(['error' => 'Invalid request.'], 400);
        }

        try {
            $batch = (new Batch())->find($id);
            if (!$batch) {
                $this->json(['error' => 'Batch not found.'], 404);
            }

            if (!$this->ai->isAvailable()) {
                $this->json(['error' => 'AI is not configured. Add a Groq API key in Settings.'], 503);
            }

            $result = $this->ai->forecastReadyDate($batch);
            if (!$result) {
                $this->json(['error' => 'AI prediction failed. Try again.'], 500);
            }

            $this->json(['success' => true, 'forecast' => $result]);
        } catch (\Throwable $e) {
            $this->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
