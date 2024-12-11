<?php

namespace App\Filament\Widgets;

namespace App\Filament\Widgets;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;


class CustomerTime extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $data = DB::table('customers')
        ->select(
            DB::raw('CASE
                WHEN time BETWEEN "10:00:00" AND "12:00:00" THEN "Morning"
                WHEN time BETWEEN "12:00:01" AND "17:00:00" THEN "Afternoon"
                WHEN time BETWEEN "17:00:01" AND "21:00:00" THEN "Evening"
                ELSE "Night"
            END as time_of_day'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('time_of_day')
        ->get();
        return [
            'datasets' => [
                [
                    'label' => 'Reservations by Time of Day',
                    'data' => $data->map(fn($item) => $item->count),
                ],
            ],
            'labels' => $data->map(fn($item) => $item->time_of_day),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
