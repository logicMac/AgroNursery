<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Batch;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\AuditLog;

class BatchesController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('batches', 'read');
        $this->view('batches/index', [
            'batches' => (new Batch())->all(),
            'products' => (new Product())->all(),
            'title' => 'Batches'
        ]);
    }

    public function create(): void
    {
        $this->requirePermission('batches');
        $this->view('batches/create', ['products' => (new Product())->all(), 'title' => 'New Batch']);
    }

    public function store(): void
    {
        $this->requirePermission('batches');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/batches/create');
        }
        $validator = new Validator();
        $validator->required($_POST, ['product_id', 'lot_id', 'seedling_count', 'plant_date']);
        if ($validator->fails()) {
            flash('error', 'Please complete required fields.');
            $this->redirect('/batches/create');
        }
        $batch = new Batch();
        $id = $batch->create([
            'product_id' => $_POST['product_id'],
            'lot_id' => $_POST['lot_id'],
            'seed_source' => $_POST['seed_source'] ?? null,
            'seedling_count' => $_POST['seedling_count'],
            'plant_date' => $_POST['plant_date'],
            'expected_ready_date' => $_POST['expected_ready_date'] ?? null,
            'growth_stage' => $_POST['growth_stage'] ?? 'Seedling',
            'health_status' => $_POST['health_status'] ?? 'Healthy',
            'location' => $_POST['location'] ?? null,
            'staff_id' => $this->userId(),
            'notes' => $_POST['notes'] ?? null,
        ]);
        (new StockMovement())->create([
            'product_id' => $_POST['product_id'],
            'batch_id' => $id,
            'movement_type' => 'in',
            'quantity' => $_POST['seedling_count'],
            'reason' => 'Batch ' . $_POST['lot_id'] . ' initial stock',
            'staff_id' => $this->userId(),
        ]);
        (new Product())->updateStock($_POST['product_id'], (int) $_POST['seedling_count']);
        (new AuditLog())->log($this->userId(), 'batch_created', 'batches', $id, ['lot' => $_POST['lot_id']]);
        flash('success', 'Batch created.');
        $this->redirect('/batches');
    }

    public function show(int $id): void
    {
        $this->requirePermission('batches', 'read');
        $this->view('batches/show', ['batch' => (new Batch())->find($id), 'title' => 'Batch']);
    }

    public function edit(int $id): void
    {
        $this->requirePermission('batches');
        $this->view('batches/edit', ['batch' => (new Batch())->find($id), 'products' => (new Product())->all(), 'title' => 'Edit Batch']);
    }

    public function update(int $id): void
    {
        $this->requirePermission('batches');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/batches/' . $id . '/edit');
        }
        (new Batch())->updateBatch($id, [
            'growth_stage' => $_POST['growth_stage'],
            'health_status' => $_POST['health_status'],
            'expected_ready_date' => $_POST['expected_ready_date'] ?? null,
            'location' => $_POST['location'] ?? null,
            'notes' => $_POST['notes'] ?? null,
        ]);
        (new AuditLog())->log($this->userId(), 'batch_updated', 'batches', $id, []);
        flash('success', 'Batch updated.');
        $this->redirect('/batches/' . $id);
    }
}
