<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\QualityAssessment;
use App\Models\Batch;
use App\Models\AuditLog;

class QualityController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('quality');
        $this->view('quality/index', [
            'assessments' => (new QualityAssessment())->all(),
            'batches' => (new Batch())->all(),
            'title' => 'Quality Assessments'
        ]);
    }

    public function create(): void
    {
        $this->requirePermission('quality');
        $this->view('quality/create', ['batches' => (new Batch())->all(), 'title' => 'New Assessment']);
    }

    public function store(): void
    {
        $this->requirePermission('quality');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/quality/create');
        }
        $validator = new Validator();
        $validator->required($_POST, ['batch_id', 'grade']);
        if ($validator->fails()) {
            flash('error', 'Please select batch and grade.');
            $this->redirect('/quality/create');
        }
        $batch = (new Batch())->find($_POST['batch_id']);
        $id = (new QualityAssessment())->create([
            'batch_id' => $_POST['batch_id'],
            'product_id' => $batch['product_id'],
            'grade' => $_POST['grade'],
            'criteria' => json_encode([
                'height_cm' => $_POST['height_cm'] ?? null,
                'leaf_count' => $_POST['leaf_count'] ?? null,
                'color' => $_POST['color'] ?? null,
                'pests' => $_POST['pests'] ?? null,
            ]),
            'assessed_by' => $this->userId(),
            'assessed_at' => $_POST['assessed_at'] ?? date('Y-m-d H:i:s'),
            'notes' => $_POST['notes'] ?? null,
        ]);
        (new AuditLog())->log($this->userId(), 'quality_logged', 'quality', $id, ['batch' => $batch['lot_id'], 'grade' => $_POST['grade']]);
        flash('success', 'Quality assessment recorded.');
        $this->redirect('/quality');
    }
}
