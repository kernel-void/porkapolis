<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\FilterBulanRequest;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index()
    {
        return view('admin.dashboard', $this->dashboardService->summary(session('dashboard_bulan')));
    }

    public function setBulan(FilterBulanRequest $request)
    {
        session(['dashboard_bulan' => $request->validated()['bulan']]);

        return redirect()->route('admin.dashboard');
    }
}
