<?php

namespace App\Http\Controllers\Supervisor\Reports;

use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
}
