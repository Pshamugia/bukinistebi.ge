<?php

// app/Exports/UserTransactionsExport.php
namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize
};
use Maatwebsite\Excel\Events\AfterSheet;

class UserTransactionsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    public float $grandTotal = 0;

    public function __construct(public $from = null, public $to = null) {}

    public function collection(): Collection
    {
        $q = Order::query()
            ->with('user')
            // exclude unwanted statuses
            ->whereNotIn('status', ['pending', 'failed', 'expired']);

        if ($this->from) $q->whereDate('created_at', '>=', $this->from);
        if ($this->to)   $q->whereDate('created_at', '<=', $this->to);

        // compute grand total with SAME filters
        $this->grandTotal = (clone $q)->sum('total');

        return $q->orderBy('created_at', 'desc')->get();
    }

    public function startCell(): string
    {
        return 'A3'; // leave room for the header lines
    }

    public function headings(): array
    {
        return ['Order #','Customer','Phone','Payment Method','Delivery Status','Total (GEL)','Created At'];
    }

    public function map($order): array
    {
        return [
            $order->order_id ?? $order->id,
            optional($order->user)->name ?? ($order->name ?? 'Guest'),
            $order->phone,
            $order->payment_method,
            $order->status,
            (float) $order->total,
            optional($order->created_at)?->format('Y-m-d H:i:s'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Header totals
                $sheet->setCellValue('A1', 'საერთო ჯამი (GEL):');
                $sheet->setCellValue('B1', $this->grandTotal);

                // Optional date range on row 2
                $period = [];
                if ($this->from) $period[] = 'დან: '.$this->from;
                if ($this->to)   $period[] = 'მდე: '.$this->to;
                if ($period) {
                    $sheet->setCellValue('A2', implode('   ', $period));
                }

                // Style
                $sheet->getStyle('A1:B1')->getFont()->setBold(true);
            },
        ];
    }
}
