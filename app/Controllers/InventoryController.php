<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockMovement;
use App\Models\AuditLog;

class InventoryController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('inventory', 'read');
        $this->view('inventory/index', [
            'products' => (new Product())->all(),
            'lowStock' => (new Product())->lowStock(),
            'movements' => (new StockMovement())->all(),
            'title' => 'Inventory',
        ]);
    }

    public function stockIn(): void
    {
        $this->requirePermission('inventory', 'adjust');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/inventory');
        }
        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['quantity'] ?? 0);
        if ($productId === 0 || $qty <= 0) {
            flash('error', 'Select a product and enter a positive quantity.');
            $this->redirect('/inventory');
        }
        $product = (new Product())->find($productId);
        if (!$product) {
            flash('error', 'Product not found.');
            $this->redirect('/inventory');
        }
        (new Product())->updateStock($productId, $qty);
        (new StockMovement())->create([
            'product_id' => $productId,
            'batch_id' => null,
            'movement_type' => 'in',
            'quantity' => $qty,
            'reason' => $_POST['reason'] ?? 'Stock in',
            'staff_id' => $this->userId(),
        ]);
        (new AuditLog())->log($this->userId(), 'stock_in', 'inventory', $productId, ['quantity' => $qty]);
        flash('success', 'Stock added.');
        $this->redirect('/inventory');
    }

    public function adjust(int $id): void
    {
        $this->requirePermission('inventory', 'adjust');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/inventory');
        }
        $product = (new Product())->find($id);
        $delta = (int) ($_POST['quantity'] ?? 0);
        $type = $_POST['type'] ?? 'adjustment';
        if ($delta === 0) {
            flash('error', 'No change entered.');
            $this->redirect('/inventory');
        }
        $batchId = !empty($_POST['batch_id']) ? (int) $_POST['batch_id'] : null;
        (new Product())->updateStock($id, $type === 'in' ? $delta : -$delta);
        (new StockMovement())->create([
            'product_id' => $id,
            'batch_id' => $batchId,
            'movement_type' => $type,
            'quantity' => abs($delta),
            'reason' => $_POST['reason'] ?? 'Manual stock adjustment',
            'staff_id' => $this->userId(),
        ]);
        (new AuditLog())->log($this->userId(), 'stock_adjusted', 'inventory', $id, ['delta' => $delta, 'type' => $type]);
        flash('success', 'Stock adjusted.');
        $this->redirect('/inventory');
    }
}
