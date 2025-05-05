<?php

// app/Exports/UserTransactionsExport.php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserTransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Fetch the users and their order data
        $users = User::with(['orders.orderItems.book'])
            ->get()
            ->map(function ($user) {
                return [
                    'User Name' => $user->name,
                    'Total Orders' => $user->orders->count(),
                    'Total Amount' => $user->orders->sum('total'),
                    'Last Order Status' => $user->orders->isNotEmpty() ? $user->orders->last()->status : 'N/A',
                    'Last Order Date' => $user->orders->isNotEmpty() ? $user->orders->last()->created_at->format('Y-m-d') : 'N/A',
                ];
            });

        return $users;
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
