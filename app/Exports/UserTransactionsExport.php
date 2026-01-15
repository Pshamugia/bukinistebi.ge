<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserTransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $from;
    protected $to;

    public function __construct(?Carbon $from = null, ?Carbon $to = null)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * =========================
     * DATA SOURCE
     * =========================
     */
    public function collection()
    {
        $query = Order::with([
            'orderItems.book'
        ])->orderBy('created_at', 'desc');

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->where('created_at', '<=', $this->to);
        }

        return $query->get()
            ->flatMap(function ($order) {
                return $order->orderItems->map(function ($item) use ($order) {
                    return [
                        'order' => $order,
                        'item'  => $item,
                        'book'  => $item->book,
                    ];
                });
            });
    }

    /**
     * =========================
     * TABLE HEADERS
     * =========================
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Order Date',
            'Customer',
            'Book title',

            'Selling price (₾)',
            'Acquisition price (₾)',

            'Quantity',

            'Revenue (₾)',
            'Cost (₾)',
            'Profit (₾)',
        ];
    }

    /**
     * =========================
     * ROW MAPPING (IMPORTANT)
     * =========================
     */
    public function map($row): array
    {
        $order = $row['order'];
        $item  = $row['item'];
        $book  = $row['book'];

        $sellingPrice    = (float) $item->price;
        $acquisition     = (float) ($book->acquisition_price ?? 0);
        $qty             = (int) $item->quantity;

        $revenue = $sellingPrice * $qty;
        $cost    = $acquisition * $qty;
        $profit  = $revenue - $cost;

        return [
            $order->id,
            optional($order->created_at)->format('Y-m-d H:i'),
            $order->name ?? $order->user->name ?? 'Guest',
            $book->title ?? '—',

            number_format($sellingPrice, 2),
            $book->acquisition_price !== null ? number_format($acquisition, 2) : '',

            $qty,

            number_format($revenue, 2),
            number_format($cost, 2),
            number_format($profit, 2),
        ];
    }
}
