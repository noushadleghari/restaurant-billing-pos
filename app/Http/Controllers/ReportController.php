<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reports)
    {
        $range = $request->get('range', 'today');

        [$from, $to] = match ($range) {
            'yesterday' => [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'custom' => [
                Carbon::parse($request->get('from', now()))->startOfDay(),
                Carbon::parse($request->get('to', now()))->endOfDay(),
            ],
            default => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
        };

        $summary = $reports->summary($from, $to);
        $salesByDay = $reports->salesByDay($from, $to);
        $topProducts = $reports->topProducts($from, $to, 10);
        $paymentBreakdown = $reports->paymentMethodBreakdown($from, $to);

        return view('reports.index', compact('summary', 'salesByDay', 'topProducts', 'paymentBreakdown', 'range', 'from', 'to'));
    }
}
