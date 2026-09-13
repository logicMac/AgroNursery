<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\PricingRule;
use App\Models\Product;
use App\Models\AuditLog;

class PricingController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('pricing');
        $this->view('pricing/index', [
            'rules' => (new PricingRule())->all(),
            'products' => (new Product())->all(),
            'title' => 'Pricing Rules'
        ]);
    }

    public function create(): void
    {
        $this->requirePermission('pricing');
        $this->view('pricing/create', ['products' => (new Product())->all(), 'title' => 'New Pricing Rule']);
    }

    public function store(): void
    {
        $this->requirePermission('pricing');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/pricing/create');
        }
        $validator = new Validator();
        $validator->required($_POST, ['product_id', 'multiplier']);
        $validator->numeric($_POST, ['product_id', 'multiplier']);
        if ($validator->fails()) {
            flash('error', 'Please complete the form.');
            $this->redirect('/pricing/create');
        }
        $id = (new PricingRule())->create([
            'product_id' => $_POST['product_id'],
            'grade' => $_POST['grade'] ?? '*',
            'size_cm_min' => $_POST['size_cm_min'] ?: null,
            'size_cm_max' => $_POST['size_cm_max'] ?: null,
            'age_days_min' => $_POST['age_days_min'] ?: null,
            'age_days_max' => $_POST['age_days_max'] ?: null,
            'season' => $_POST['season'] ?: null,
            'multiplier' => $_POST['multiplier'],
            'fixed_amount' => $_POST['fixed_amount'] ?: null,
            'priority' => $_POST['priority'] ?? 0,
        ]);
        (new AuditLog())->log($this->userId(), 'pricing_rule_created', 'pricing', $id, []);
        flash('success', 'Pricing rule created.');
        $this->redirect('/pricing');
    }

    public function delete(int $id): void
    {
        $this->requirePermission('pricing');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/pricing');
        }
        (new PricingRule())->deleteRule($id);
        (new AuditLog())->log($this->userId(), 'pricing_rule_deleted', 'pricing', $id, []);
        flash('success', 'Pricing rule deleted.');
        $this->redirect('/pricing');
    }
}
