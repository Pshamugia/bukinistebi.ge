<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UserTransactionsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
{
    protected ?Carbon $from;
    protected ?Carbon $to;

    protected float $totalRevenue = 0;
    protected float $totalCost = 0;

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
        $query = Order::query()
            ->with(['orderItems.book', 'user'])
            ->whereIn('status', ['delivered', 'Succeeded'])
            ->orderByDesc('created_at');

        if ($this->from) {
            $query->where('created_at', '>=', $this->from->startOfDay());
        }

        if ($this->to) {
            $query->where('created_at', '<=', $this->to->endOfDay());
        }

        return $query->get()
            ->flatMap(function (Order $order) {
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

            'მიტანა / გადახდა', // ✅ NEW COLUMN
        ];
    }

    /**
     * =========================
     * ROW MAPPING
     * =========================
     */
    public function map($row): array
    {
        $order = $row['order'];
        $item  = $row['item'];
        $book  = $row['book'];

        $sellingPrice = (float) $item->price;
        $acquisition  = (float) ($book->acquisition_price ?? 0);
        $qty          = (int) $item->quantity;

        $revenue = $sellingPrice * $qty;
        $cost    = $acquisition * $qty;

        // ✅ accumulate totals
        $this->totalRevenue += $revenue;
        $this->totalCost    += $cost;

        // ✅ delivery / payment type
        $deliveryType = match ($order->payment_method) {
            'courier'        => 'კურიერი',
            'bank_transfer' => 'ბანკი',
            default          => $order->payment_method ?? '—',
        };

        return [
            $order->id,
            optional($order->created_at)->format('Y-m-d H:i'),
            $order->name ?? optional($order->user)->name ?? 'Guest',
            $book->title ?? '—',

            number_format($sellingPrice, 2),
            $book->acquisition_price !== null ? number_format($acquisition, 2) : '',

            $qty,

            number_format($revenue, 2),
            number_format($cost, 2),

            $deliveryType, // ✅ instead of profit
        ];
    }

    /**
     * =========================
     * TOTAL ROW
     * =========================
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow() + 1;

                // Label
                $sheet->setCellValue("A{$lastRow}", 'TOTAL');

                // Totals
                $sheet->setCellValue("H{$lastRow}", number_format($this->totalRevenue, 2));
                $sheet->setCellValue("I{$lastRow}", number_format($this->totalCost, 2));

                // Styling
                $sheet->getStyle("A{$lastRow}:J{$lastRow}")
                    ->getFont()
                    ->setBold(true);
            },
        ];
    }
}
