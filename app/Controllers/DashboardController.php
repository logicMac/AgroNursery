<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Sale;
use App\Models\EnvironmentalLog;
use App\Models\QualityAssessment;
use App\Models\ShrinkageRecord;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = $this->session();

        $productModel = new Product();
        $batchModel = new Batch();
        $saleModel = new Sale();
        $envModel = new EnvironmentalLog();
        $qualityModel = new QualityAssessment();

        $allQuality = $qualityModel->all();
        $qualityGrades = [];
        foreach ($allQuality as $q) {
            $g = $q['grade'];
            $qualityGrades[$g] = ($qualityGrades[$g] ?? 0) + 1;
        }

        $data = [
            'user' => $user,
            'isOwner' => $user['role'] === 'owner',
            'lowStock' => $productModel->lowStock(),
            'readySoon' => $batchModel->readySoon(),
            'revenue' => $saleModel->revenueByDay(30),
            'topProducts' => $saleModel->topProducts(5),
            'gradeSales' => $saleModel->gradeVsSales(),
            'shrinkage' => (new ShrinkageRecord())->shrinkageByBatch(),
            'dueToday' => $envModel->dueToday($user['id']),
            'pendingQuality' => $allQuality,
            'qualityGrades' => $qualityGrades,
            'title' => 'Dashboard',
        ];

        $this->view('dashboard/index', $data);
    }
}
