<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\EnvironmentalLog;
use App\Models\Batch;
use App\Models\AuditLog;

class EnvironmentController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('environment');
        $this->view('environment/index', [
            'logs' => (new EnvironmentalLog())->all(),
            'batches' => (new Batch())->all(),
            'title' => 'Environmental Logs'
        ]);
    }

    public function create(): void
    {
        $this->requirePermission('environment');
        $this->view('environment/create', ['batches' => (new Batch())->all(), 'title' => 'Log Conditions']);
    }

    public function store(): void
    {
        $this->requirePermission('environment');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/environment/create');
        }
        $validator = new Validator();
        $validator->required($_POST, ['log_date', 'activity']);
        if ($validator->fails()) {
            flash('error', 'Please fill required fields.');
            $this->redirect('/environment/create');
        }
        $batchId = !empty($_POST['batch_id']) ? (int) $_POST['batch_id'] : null;
        $id = (new EnvironmentalLog())->create([
            'batch_id' => $batchId,
            'log_date' => $_POST['log_date'],
            'light_hours' => $_POST['light_hours'] ?: null,
            'water_ml' => $_POST['water_ml'] ?: null,
            'soil_ph' => $_POST['soil_ph'] ?: null,
            'fertilizer' => $_POST['fertilizer'] ?? null,
            'pest_control' => $_POST['pest_control'] ?? null,
            'temperature_c' => $_POST['temperature_c'] ?: null,
            'humidity_pct' => $_POST['humidity_pct'] ?: null,
            'activity' => $_POST['activity'],
            'staff_id' => $this->userId(),
            'notes' => $_POST['notes'] ?? null,
        ]);
        (new AuditLog())->log($this->userId(), 'environment_log_created', 'environment', $id, ['batch' => $batchId]);
        flash('success', 'Environmental log recorded.');
        $this->redirect('/environment');
    }
}
