<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Batch;
use App\Models\Forecast;
use App\Models\EnvironmentalLog;
use App\Models\AuditLog;

class ForecastController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('forecast', 'read');
        $this->view('forecast/index', ['batches' => (new Batch())->all(), 'title' => 'Ready-Date Forecasts']);
    }

    public function batch(int $id): void
    {
        $this->requirePermission('forecast', 'read');
        $batch = (new Batch())->find($id);
        if (!$batch) {
            http_response_code(404);
            echo 'Not found';
            return;
        }

        $age = max(0, (new \DateTime())->diff(new \DateTime($batch['plant_date']))->days);
        $avg = (new EnvironmentalLog())->averagesByBatch($id) ?? [];
        $ph = (float) ($avg['avg_ph'] ?? 6.5);
        $water = (float) ($avg['avg_water'] ?? 1500);
        $temp = (float) ($avg['avg_temp'] ?? 30);

        $baseDays = match ($batch['growth_stage']) {
            'Seedling' => 120,
            'Vegetative' => 90,
            'Juvenile' => 60,
            'Ready' => 0,
            default => 90,
        };

        $adjustment = 0;
        if ($ph < 5.5 || $ph > 7.5) {
            $adjustment += 15;
        }
        if ($water < 1000) {
            $adjustment += 10;
        }
        if ($temp < 20 || $temp > 35) {
            $adjustment += 10;
        }

        $projectedDays = max(0, $baseDays - $age + $adjustment);
        $readyDate = (new \DateTime())->modify("+$projectedDays days")->format('Y-m-d');
        $confidence = max(0, min(100, 90 - $adjustment));

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Csrf::verify($_POST['csrf_token'] ?? null)) {
            (new Forecast())->create([
                'batch_id' => $id,
                'projected_ready_date' => $readyDate,
                'confidence_score' => $confidence,
                'growth_stage_at_forecast' => $batch['growth_stage'],
                'rules_used' => json_encode(['base_days' => $baseDays, 'age' => $age, 'ph_adjustment' => $ph, 'water_adjustment' => $water, 'temp_adjustment' => $temp]),
            ]);
            (new AuditLog())->log($this->userId(), 'forecast_created', 'forecast', $id, ['ready_date' => $readyDate]);
            flash('success', 'Forecast saved.');
            $this->redirect('/forecast/batch/' . $id);
        }

        $this->view('forecast/batch', [
            'batch' => $batch,
            'readyDate' => $readyDate,
            'confidence' => $confidence,
            'avg' => $avg,
            'forecasts' => (new Forecast())->byBatch($id),
            'title' => 'Forecast',
        ]);
    }
}
