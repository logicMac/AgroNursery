<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Batch;
use App\Models\PricingRule;
use App\Models\StockMovement;
use App\Models\AuditLog;

class SalesController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('sales');
        $this->view('sales/index', [
            'sales' => (new Sale())->all(),
            'products' => (new Product())->all(),
            'batches' => (new Batch())->all(),
            'receiptNumber' => (new Sale())->nextReceiptNumber(),
            'title' => 'Sales'
        ]);
    }

    public function create(): void
    {
        $this->requirePermission('sales');
        $this->view('sales/create', [
            'products' => (new Product())->all(),
            'batches' => (new Batch())->all(),
            'receiptNumber' => (new Sale())->nextReceiptNumber(),
            'title' => 'New Sale',
        ]);
    }

    public function store(): void
    {
        $this->requirePermission('sales');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/sales/create');
        }

        $validator = new Validator();
        $validator->required($_POST, ['customer_name', 'product_id', 'quantity']);
        $validator->numeric($_POST, ['product_id', 'quantity']);

        if ($validator->fails()) {
            flash('error', 'Please fill all required fields.');
            $this->redirect('/sales/create');
        }

        $productId = (int) $_POST['product_id'];
        $batchId = !empty($_POST['batch_id']) ? (int) $_POST['batch_id'] : null;
        $quantity = (int) $_POST['quantity'];

        $productModel = new Product();
        $product = $productModel->find($productId);
        if (!$product || $product['current_stock'] < $quantity) {
            flash('error', 'Insufficient stock.');
            $this->redirect('/sales/create');
        }

        $grade = null;
        $batch = null;
        $ageDays = null;
        $size = null;
        $pricing = new PricingRule();
        if ($batchId) {
            $batchModel = new Batch();
            $batch = $batchModel->find($batchId);
            $grade = (new \App\Models\QualityAssessment())->latestGrade($batchId)['grade'] ?? 'Good';
            $ageDays = max(0, (int) (new \DateTime())->diff(new \DateTime($batch['plant_date']))->format('%r%a'));
            $size = $_POST['size_cm'] ?? null;
        }

        $season = $_POST['season'] ?? '';
        $unitPrice = $pricing->computePrice($productId, $grade ?? 'Good', $size ? (float) $size : null, $ageDays, $season, (float) $product['base_price']);
        $total = round($unitPrice * $quantity, 2);

        $saleModel = new Sale();
        $receiptNumber = $saleModel->nextReceiptNumber();
        $saleId = $saleModel->create([
            'receipt_number' => $receiptNumber,
            'customer_name' => $_POST['customer_name'],
            'customer_contact' => $_POST['customer_contact'] ?? null,
            'total_amount' => $total,
            'sold_by' => $this->userId(),
            'sale_date' => $_POST['sale_date'] ?? date('Y-m-d H:i:s'),
        ]);

        $saleModel->createItem([
            'sale_id' => $saleId,
            'product_id' => $productId,
            'batch_id' => $batchId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'quality_grade' => $grade,
        ]);

        $productModel->updateStock($productId, -$quantity);
        (new StockMovement())->create([
            'product_id' => $productId,
            'batch_id' => $batchId,
            'movement_type' => 'out',
            'quantity' => $quantity,
            'reason' => 'Sale ' . $receiptNumber,
            'staff_id' => $this->userId(),
        ]);

        (new AuditLog())->log($this->userId(), 'sale_created', 'sales', $saleId, ['receipt' => $receiptNumber, 'amount' => $total]);

        flash('success', 'Sale recorded: ' . $receiptNumber);
        $this->redirect('/sales');
    }

    public function show(int $id): void
    {
        $this->requirePermission('sales');
        $sale = (new Sale())->find($id);
        if (!$sale) {
            http_response_code(404);
            echo 'Not found';
            return;
        }
        $this->view('sales/show', ['sale' => $sale, 'items' => (new Sale())->items($id), 'title' => 'Receipt']);
    }
}
