<?php

namespace App\Filament\Widgets;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class CustomerChart extends ChartWidget
{
    protected static ?string $heading = 'Reservations';
    protected static string $color = 'info';


    protected function getData(): array
    {

        $data = DB::table('customers')
        ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('COUNT(*) as aggregate'))
        ->whereBetween('date', [now()->startOfYear(), now()->endOfYear()])
        ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'))
        ->orderBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'))
        ->get();

    // Format the result into a structure that matches what Trend expects
    return [
        'datasets' => [
            [
                'label' => 'Reservations',
                'data' => $data->map(fn ($item) => $item->aggregate),
            ],
        ],
        'labels' => $data->map(fn ($item) => $item->month),
    ];




    }


    protected function getType(): string
    {
        return 'bar';
    }
}
