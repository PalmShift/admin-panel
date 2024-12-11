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
        ->select('npeople', DB::raw('COUNT(*) as count'))
        ->groupBy('npeople')
        ->orderBy('npeople')
        ->get();

    return [
        'datasets' => [
            [
                'label' => 'Reservations by Number of People',
                'data' => $data->map(fn($item) => $item->count),
            ],
        ],
        'labels' => $data->map(fn($item) => $item->npeople),
    ];





    }


    protected function getType(): string
    {
        return 'line';
    }
}
