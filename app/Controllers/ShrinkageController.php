<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\ShrinkageRecord;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockMovement;
use App\Models\AuditLog;

class ShrinkageController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('shrinkage');
        $this->view('shrinkage/index', [
            'records' => (new ShrinkageRecord())->all(),
            'causes' => (new ShrinkageRecord())->totalsByCause(),
            'byBatch' => (new ShrinkageRecord())->shrinkageByBatch(),
            'products' => (new Product())->all(),
            'batches' => (new Batch())->all(),
            'title' => 'Shrinkage',
        ]);
    }

    public function create(): void
    {
        $this->requirePermission('shrinkage');
        $this->view('shrinkage/create', [
            'products' => (new Product())->all(),
            'batches' => (new Batch())->all(),
            'title' => 'Record Shrinkage',
        ]);
    }

    public function store(): void
    {
        $this->requirePermission('shrinkage');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/shrinkage/create');
        }
        $productId = (int) $_POST['product_id'];
        $batchId = !empty($_POST['batch_id']) ? (int) $_POST['batch_id'] : null;
        $qty = (int) $_POST['quantity'];
        $id = (new ShrinkageRecord())->create([
            'batch_id' => $batchId,
            'product_id' => $productId,
            'quantity' => $qty,
            'reason' => $_POST['reason'],
            'cause_category' => $_POST['cause_category'] ?? null,
            'recorded_by' => $this->userId(),
        ]);
        (new Product())->updateStock($productId, -$qty);
        (new StockMovement())->create([
            'product_id' => $productId,
            'batch_id' => $batchId,
            'movement_type' => 'damage',
            'quantity' => $qty,
            'reason' => $_POST['reason'],
            'staff_id' => $this->userId(),
        ]);
        (new AuditLog())->log($this->userId(), 'shrinkage_recorded', 'shrinkage', $id, ['quantity' => $qty]);
        flash('success', 'Shrinkage recorded.');
        $this->redirect('/shrinkage');
    }
}
