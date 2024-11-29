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

        $data = DB::table('customers')
        ->selectRaw('MONTH(`date`) as month, COUNT(*) as total')
        ->whereYear('date', date('Y'))
        ->groupByRaw('MONTH(`date`)')
        ->get();

        $monthlyData = collect(range(1, 12))->map(function ($month) use ($data) {
            return $data->firstWhere('month', $month)?->total ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Reservation',
                    'data' => $monthlyData,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ];



    }

    protected function getType(): string
    {
        return 'bar';
    }
}
