<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserTransactionsExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
{
    $ordersQuery = \App\Models\Order::query();

    if ($this->from) {
        $ordersQuery->whereDate('created_at', '>=', $this->from);
    }

    if ($this->to) {
        $ordersQuery->whereDate('created_at', '<=', $this->to);
    }

    $totalAmount = $ordersQuery->sum('total');

    $users = \App\Models\User::with(['orders' => function ($query) {
            if ($this->from) {
                $query->whereDate('created_at', '>=', $this->from);
            }
            if ($this->to) {
                $query->whereDate('created_at', '<=', $this->to);
            }
        }])
        ->get()
        ->filter(function ($user) {
            return $user->orders->isNotEmpty();
        })
        ->map(function ($user) {
            return [
                'User Name' => $user->name,
                'Total Orders' => $user->orders->count(),
                'Total Amount' => $user->orders->sum('total'),
                'Last Order Status' => $user->orders->last()->status ?? 'N/A',
                'Last Order Date' => optional($user->orders->last()->created_at)->format('Y-m-d') ?? 'N/A',
            ];
        });

    // Add the total row at the top manually
    return collect([
        ['სულ თანხა:', $totalAmount . ' ლარი'],
        [],
    ])->merge($users);
}

    public function headings(): array
    {
        return [
            'User Name',
            'Total Orders',
            'Total Amount',
            'Last Order Status',
            'Last Order Date',
        ];
    }
}
