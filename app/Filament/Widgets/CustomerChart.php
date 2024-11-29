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
        ->select(
            DB::raw('MONTH(date) as month'), // Get month number
            DB::raw('COUNT(*) as aggregate') // Count reservations per month
        )
        ->whereBetween('date', [now()->startOfYear(), now()->endOfYear()])
        ->groupBy(DB::raw('MONTH(date)')) // Group by month
        ->orderBy(DB::raw('MONTH(date)')) // Order by month
        ->get();

    // Define the custom month labels
    $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Prepare the data for the chart
    $monthlyCounts = array_fill(0, 12, 0); // Initialize an array with 12 months, all counts set to 0

    // Fill the array with actual counts for months that have data
    foreach ($data as $item) {
        $monthlyCounts[$item->month - 1] = $item->aggregate; // Adjust month (1-based index to 0-based)
    }

    // Return the data to the view or response
    return [
        'datasets' => [
            [
                'label' => 'Reservations',
                'data' => $monthlyCounts,
            ],
        ],
        'labels' => $monthLabels,
    ];





    }


    protected function getType(): string
    {
        return 'bar';
    }
}
