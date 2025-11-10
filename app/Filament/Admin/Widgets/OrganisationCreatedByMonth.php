<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Organisation;
use DB;
use Filament\Widgets\ChartWidget;

class OrganisationCreatedByMonth extends ChartWidget
{
    protected ?string $heading = 'New clinics created by month';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Organisation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        ];


        $counts = [];
        foreach ($months as $num => $name) {
            $counts[] = $data[$num] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total created',
                    'data' => $counts,
                ],
            ],
            'labels' => array_values($months),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
