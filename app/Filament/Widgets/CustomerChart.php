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
            ->count('*');
            $data->each(function ($trend) {
                // You can add a `groupBy()` here to fix the issue manually if needed
                // or modify how TrendBuilder constructs the query.
                $trend->date = \Carbon\Carbon::parse($trend->date)->format('Y-m');
            });


        return [
            'datasets' => [
                [
                    'label' => 'Reservation',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];



    }


    protected function getType(): string
    {
        return 'bar';
    }
}
