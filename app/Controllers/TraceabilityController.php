<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Traceability;
use App\Models\Batch;

class TraceabilityController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('traceability', 'read');
        $this->view('traceability/index', ['batches' => (new Batch())->all(), 'title' => 'Traceability']);
    }

    public function show(string $lot): void
    {
        $this->requirePermission('traceability', 'read');
        $chain = (new Traceability())->findByLot($lot);
        if (!$chain) {
            http_response_code(404);
            echo 'Lot not found';
            return;
        }
        $this->view('traceability/show', ['lot' => $lot, 'chain' => $chain, 'title' => 'Lot ' . $lot]);
    }
}
