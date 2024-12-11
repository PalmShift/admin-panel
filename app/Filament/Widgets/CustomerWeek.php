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

class CustomerWeek extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $data = DB::table('customers')
        ->select(
            DB::raw('DAYNAME(date) as day'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('day')
        ->orderByRaw('FIELD(DAYNAME(date), "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday")')
        ->get();

    return [
        'datasets' => [
            [
                'label' => 'Reservations by Day of the Week',
                'data' => $data->map(fn($item) => $item->count),
            ],
        ],
        'labels' => $data->map(fn($item) => $item->day),
    ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
