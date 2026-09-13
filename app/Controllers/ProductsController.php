<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Product;
use App\Models\AuditLog;

class ProductsController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('products', 'read');
        $this->view('products/index', ['products' => (new Product())->all(), 'title' => 'Products']);
    }

    public function create(): void
    {
        $this->requirePermission('products');
        $this->view('products/create', ['title' => 'New Product']);
    }

    public function store(): void
    {
        $this->requirePermission('products');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/products/create');
        }
        $validator = new Validator();
        $validator->required($_POST, ['name', 'category', 'base_price', 'min_stock']);
        $validator->numeric($_POST, ['base_price', 'min_stock']);
        if ($validator->fails()) {
            flash('error', 'Validation failed.');
            $this->redirect('/products/create');
        }
        $image = $this->handleImageUpload();
        $id = (new Product())->create([
            'name' => $_POST['name'],
            'category' => $_POST['category'],
            'description' => $_POST['description'] ?? null,
            'image' => $image,
            'base_price' => $_POST['base_price'],
            'current_stock' => $_POST['current_stock'] ?? 0,
            'min_stock' => $_POST['min_stock'],
            'unit' => $_POST['unit'] ?? 'pc',
        ]);
        (new AuditLog())->log($this->userId(), 'product_created', 'products', $id, ['name' => $_POST['name']]);
        flash('success', 'Product created.');
        $this->redirect('/products');
    }

    public function edit(int $id): void
    {
        $this->requirePermission('products');
        $product = (new Product())->find($id);
        $this->view('products/edit', ['product' => $product, 'title' => 'Edit Product']);
    }

    public function update(int $id): void
    {
        $this->requirePermission('products');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/products/' . $id . '/edit');
        }
        $data = [
            'name' => $_POST['name'],
            'category' => $_POST['category'],
            'description' => $_POST['description'] ?? null,
            'base_price' => $_POST['base_price'],
            'min_stock' => $_POST['min_stock'],
            'unit' => $_POST['unit'] ?? 'pc',
        ];
        $image = $this->handleImageUpload();
        if ($image !== null) {
            $data['image'] = $image;
        }
        (new Product())->updateProduct($id, $data);
        (new AuditLog())->log($this->userId(), 'product_updated', 'products', $id, ['name' => $_POST['name']]);
        flash('success', 'Product updated.');
        $this->redirect('/products');
    }

    private function handleImageUpload(): ?string
    {
        if (empty($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $file = $_FILES['image'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed, true)) {
            return null;
        }
        $root = dirname(__DIR__, 2);
        $dir = $root . '/public/uploads/products/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = uniqid('prod_', true) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
            return null;
        }
        return 'uploads/products/' . $filename;
    }
}
