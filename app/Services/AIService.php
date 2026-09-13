<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Batch;
use App\Models\EnvironmentalLog;
use App\Models\ShrinkageRecord;
use App\Models\QualityAssessment;
use App\Models\Sale;
use App\Models\Product;
use App\Models\PricingRule;

class AIService
{
    private GroqService $groq;

    public function __construct()
    {
        $this->groq = new GroqService();
    }

    public function isAvailable(): bool
    {
        return $this->groq->isEnabled();
    }

    public function isFeatureEnabled(string $feature): bool
    {
        $features = json_decode((new Setting())->get('ai_features', '{}'), true);
        return !empty($features[$feature]);
    }

    // =====================================================
    // 1. SHRINKAGE RISK PREDICTION
    // =====================================================
    public function predictShrinkageRisk(array $batch): ?array
    {
        if (!$this->isAvailable() || !$this->isFeatureEnabled('shrinkage_risk')) {
            return null;
        }

        $batchId = (int) $batch['id'];

        // Gather environment averages
        $envModel = new EnvironmentalLog();
        $avg = $envModel->averagesByBatch($batchId) ?? [];

        // Gather shrinkage history for this batch
        $shrinkageModel = new ShrinkageRecord();
        $shrinkage = $shrinkageModel->byBatch($batchId);

        // Gather quality assessments
        $qualityModel = new QualityAssessment();
        $qualities = $qualityModel->byBatch($batchId);

        // Compute age in days
        $ageDays = 0;
        if (!empty($batch['plant_date'])) {
            try {
                $ageDays = max(0, (new \DateTime())->diff(new \DateTime($batch['plant_date']))->days);
            } catch (\Exception $e) {
                $ageDays = 0;
            }
        }

        $context = [
            'batch' => [
                'lot_id' => $batch['lot_id'] ?? '',
                'product_name' => $batch['product_name'] ?? '',
                'growth_stage' => $batch['growth_stage'] ?? '',
                'health_status' => $batch['health_status'] ?? '',
                'seedling_count' => $batch['seedling_count'] ?? 0,
                'age_days' => $ageDays,
                'location' => $batch['location'] ?? '',
            ],
            'environment' => [
                'avg_ph' => round((float) ($avg['avg_ph'] ?? 0), 2),
                'avg_temp' => round((float) ($avg['avg_temp'] ?? 0), 1),
                'avg_humidity' => round((float) ($avg['avg_humidity'] ?? 0), 1),
                'avg_water' => round((float) ($avg['avg_water'] ?? 0), 0),
            ],
            'shrinkage_history' => array_map(fn($s) => [
                'quantity' => $s['quantity'] ?? 0,
                'reason' => $s['reason'] ?? '',
                'cause_category' => $s['cause_category'] ?? '',
                'recorded_at' => $s['recorded_at'] ?? '',
            ], array_slice($shrinkage, 0, 10)),
            'quality_history' => array_map(fn($q) => [
                'grade' => $q['grade'] ?? '',
                'assessed_at' => $q['assessed_at'] ?? '',
            ], array_slice($qualities, 0, 5)),
        ];

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an agricultural AI assistant specializing in nursery plant loss prediction. Analyze batch data and predict shrinkage risk. Respond ONLY with valid JSON.'
            ],
            [
                'role' => 'user',
                'content' => "Analyze this nursery batch and predict shrinkage risk. Consider environmental stress (pH outside 5.5-7.5, temp outside 20-35C, low water), health status, age, and past loss history.\n\nBatch data:\n" . json_encode($context, JSON_PRETTY_PRINT) . "\n\nRespond with JSON in this exact format:\n{\"risk_level\": \"low|medium|high\", \"risk_score\": 0-100, \"confidence\": 0-100, \"primary_factors\": [\"factor1\", \"factor2\"], \"recommendation\": \"brief actionable advice\"}"
            ]
        ];

        return $this->groq->getJsonCompletion($messages);
    }

    // =====================================================
    // 2. QUALITY GRADE PREDICTION
    // =====================================================
    public function predictQualityGrade(array $batch): ?array
    {
        if (!$this->isAvailable() || !$this->isFeatureEnabled('quality_prediction')) {
            return null;
        }

        $batchId = (int) $batch['id'];

        $envModel = new EnvironmentalLog();
        $avg = $envModel->averagesByBatch($batchId) ?? [];
        $logs = $envModel->byBatch($batchId);

        $ageDays = 0;
        if (!empty($batch['plant_date'])) {
            try {
                $ageDays = max(0, (new \DateTime())->diff(new \DateTime($batch['plant_date']))->days);
            } catch (\Exception $e) {
                $ageDays = 0;
            }
        }

        $recentEnv = array_map(fn($e) => [
            'date' => $e['log_date'] ?? '',
            'ph' => $e['soil_ph'] ?? null,
            'temp' => $e['temperature_c'] ?? null,
            'humidity' => $e['humidity_pct'] ?? null,
            'water' => $e['water_ml'] ?? null,
            'activity' => $e['activity'] ?? '',
        ], array_slice($logs, 0, 10));

        $context = [
            'batch' => [
                'lot_id' => $batch['lot_id'] ?? '',
                'product_name' => $batch['product_name'] ?? '',
                'growth_stage' => $batch['growth_stage'] ?? '',
                'health_status' => $batch['health_status'] ?? '',
                'age_days' => $ageDays,
            ],
            'environment_avg' => [
                'avg_ph' => round((float) ($avg['avg_ph'] ?? 0), 2),
                'avg_temp' => round((float) ($avg['avg_temp'] ?? 0), 1),
                'avg_humidity' => round((float) ($avg['avg_humidity'] ?? 0), 1),
                'avg_water' => round((float) ($avg['avg_water'] ?? 0), 0),
            ],
            'recent_logs' => $recentEnv,
        ];

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an agricultural AI assistant that predicts plant quality grades based on environmental conditions. Respond ONLY with valid JSON.'
            ],
            [
                'role' => 'user',
                'content' => "Predict the quality grade for this nursery batch based on its environmental data. Grades are: Excellent, Good, Fair, Poor. Ideal conditions: pH 5.5-7.5, temp 20-32C, humidity 60-80%, adequate water.\n\nBatch data:\n" . json_encode($context, JSON_PRETTY_PRINT) . "\n\nRespond with JSON:\n{\"predicted_grade\": \"Excellent|Good|Fair|Poor\", \"confidence\": 0-100, \"reasoning\": \"brief explanation\", \"key_factors\": [\"factor1\", \"factor2\"]}"
            ]
        ];

        return $this->groq->getJsonCompletion($messages);
    }

    // =====================================================
    // 3. SMART DYNAMIC PRICING
    // =====================================================
    public function suggestPrice(int $productId): ?array
    {
        if (!$this->isAvailable() || !$this->isFeatureEnabled('dynamic_pricing')) {
            return null;
        }

        $product = (new Product())->find($productId);
        if (!$product) {
            return null;
        }

        $saleModel = new Sale();
        $topProducts = $saleModel->topProducts(20);
        $gradeSales = $saleModel->gradeVsSales();

        // Find this product's sales data
        $productSales = array_filter($topProducts, fn($p) => $p['name'] === $product['name']);

        $pricingModel = new PricingRule();
        $rules = $pricingModel->rulesForProduct($productId);

        $context = [
            'product' => [
                'name' => $product['name'],
                'category' => $product['category'] ?? '',
                'base_price' => (float) $product['base_price'],
                'current_stock' => (int) $product['current_stock'],
                'min_stock' => (int) $product['min_stock'],
                'unit' => $product['unit'] ?? '',
            ],
            'existing_rules' => array_map(fn($r) => [
                'grade' => $r['grade'],
                'multiplier' => (float) $r['multiplier'],
                'priority' => (int) $r['priority'],
            ], $rules),
            'sales_performance' => [
                'total_qty_sold' => array_sum(array_column($productSales, 'qty')),
                'total_revenue' => array_sum(array_column($productSales, 'revenue')),
            ],
            'grade_sales' => array_map(fn($g) => [
                'grade' => $g['quality_grade'],
                'sold' => $g['sold'],
                'avg_price' => round((float) $g['avg_price'], 2),
            ], $gradeSales),
        ];

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an agricultural pricing AI assistant. Suggest optimal prices for nursery products based on sales data, stock levels, and grade performance. Respond ONLY with valid JSON.'
            ],
            [
                'role' => 'user',
                'content' => "Analyze this product and suggest optimal pricing. Consider: base price, current stock vs min stock (low stock = higher price), sales velocity, and grade-based price differences.\n\nProduct data:\n" . json_encode($context, JSON_PRETTY_PRINT) . "\n\nRespond with JSON:\n{\"suggested_price\": number, \"price_multiplier\": number (relative to base_price), \"confidence\": 0-100, \"reasoning\": \"brief explanation\", \"factors\": [\"factor1\", \"factor2\"], \"recommendation\": \"brief advice on pricing strategy\"}"
            ]
        ];

        return $this->groq->getJsonCompletion($messages);
    }

    // =====================================================
    // 4. AI YIELD / READY-DATE FORECASTING
    // =====================================================
    public function forecastReadyDate(array $batch): ?array
    {
        if (!$this->isAvailable() || !$this->isFeatureEnabled('yield_forecast')) {
            return null;
        }

        $batchId = (int) $batch['id'];

        $envModel = new EnvironmentalLog();
        $avg = $envModel->averagesByBatch($batchId) ?? [];
        $logs = $envModel->byBatch($batchId);

        $qualityModel = new QualityAssessment();
        $qualities = $qualityModel->byBatch($batchId);

        $ageDays = 0;
        if (!empty($batch['plant_date'])) {
            try {
                $ageDays = max(0, (new \DateTime())->diff(new \DateTime($batch['plant_date']))->days);
            } catch (\Exception $e) {
                $ageDays = 0;
            }
        }

        $today = date('Y-m-d');

        $context = [
            'batch' => [
                'lot_id' => $batch['lot_id'] ?? '',
                'product_name' => $batch['product_name'] ?? '',
                'growth_stage' => $batch['growth_stage'] ?? '',
                'health_status' => $batch['health_status'] ?? '',
                'seedling_count' => $batch['seedling_count'] ?? 0,
                'age_days' => $ageDays,
                'plant_date' => $batch['plant_date'] ?? '',
                'today' => $today,
            ],
            'environment' => [
                'avg_ph' => round((float) ($avg['avg_ph'] ?? 0), 2),
                'avg_temp' => round((float) ($avg['avg_temp'] ?? 0), 1),
                'avg_humidity' => round((float) ($avg['avg_humidity'] ?? 0), 1),
                'avg_water' => round((float) ($avg['avg_water'] ?? 0), 0),
            ],
            'recent_env_logs' => array_map(fn($e) => [
                'date' => $e['log_date'] ?? '',
                'ph' => $e['soil_ph'] ?? null,
                'temp' => $e['temperature_c'] ?? null,
                'water' => $e['water_ml'] ?? null,
                'activity' => $e['activity'] ?? '',
            ], array_slice($logs, 0, 10)),
            'quality_history' => array_map(fn($q) => [
                'grade' => $q['grade'] ?? '',
                'assessed_at' => $q['assessed_at'] ?? '',
            ], array_slice($qualities, 0, 5)),
        ];

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an agricultural AI assistant that predicts when nursery seedlings will be ready for sale. Consider growth stage, environmental conditions, age, and health. Respond ONLY with valid JSON.'
            ],
            [
                'role' => 'user',
                'content' => "Predict the ready date for this nursery batch. Typical growth periods: Seedling ~120 days, Vegetative ~90 days, Juvenile ~60 days, Ready ~0 days. Adjust based on environmental stress (poor pH, extreme temp, low water delays growth). Today is {$today}.\n\nBatch data:\n" . json_encode($context, JSON_PRETTY_PRINT) . "\n\nRespond with JSON:\n{\"projected_ready_date\": \"YYYY-MM-DD\", \"days_until_ready\": number, \"confidence\": 0-100, \"reasoning\": \"brief explanation\", \"key_factors\": [\"factor1\", \"factor2\"], \"recommendation\": \"brief care advice to optimize growth\"}"
            ]
        ];

        return $this->groq->getJsonCompletion($messages);
    }
}
