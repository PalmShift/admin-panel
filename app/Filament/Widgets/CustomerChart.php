<?php

namespace App\Filament\Widgets;

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
        $data = Trend::model(Customer::class)
        ->dateColumn('date')
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

    $labels = collect(range(1, 12))->map(fn($month) => now()->startOfYear()->addMonths($month - 1)->format('M'));

    \Log::info('Trend Data:', $data->toArray());

    return [
        'datasets' => [
            [
                'label' => 'Reservations',
                'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $labels,
    ];


    }

    protected function getType(): string
    {
        return 'bar';
    }
}
