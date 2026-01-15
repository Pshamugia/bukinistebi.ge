<?php
// app/Exports/PublisherSalesExport.php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
 

class PublisherSalesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private int $publisherId,
        private ?string $start,
        private ?string $end
    ) {}

    public function headings(): array
    {
return [
    'Date',
    'Book ID',
    'Title',
    'Unit Price',
    'Acquisition Price',
    'Qty',
    'Line Total',
    'Cost',
    'Profit'
];

    }

    public function collection()
    {
        $q = DB::table('order_items as oi')
            ->join('books as b', 'b.id', '=', 'oi.book_id')
           ->selectRaw(
    'DATE(oi.created_at) as date,
     b.id as book_id,
     b.title,
     oi.price as unit_price,
     b.acquisition_price as acquisition_price,
     oi.quantity,
     (oi.price * oi.quantity) as line_total'
)

            ->where('b.uploader_id', $this->publisherId);

        if ($this->start && $this->end) {
            $q->whereBetween('oi.created_at', [$this->start, $this->end]);
        }

        // Optional status filter via orders:
        // ->join('orders as o','o.id','=','oi.order_id')
        // ->where('o.status','paid')

        return $q->orderBy('oi.created_at')->get();
    }


    public function map($row): array
{
    $acquisition = (float) ($row->acquisition_price ?? 0);
    $qty         = (int) $row->quantity;
    $cost        = $acquisition * $qty;
    $profit      = (float) $row->line_total - $cost;

    return [
        (string) $row->date,
        (int)    $row->book_id,
        (string) $row->title,
        (float)  $row->unit_price,
        $row->acquisition_price !== null ? (float) $row->acquisition_price : null,
        $qty,
        (float)  $row->line_total,
        $cost,
        $profit,
    ];
}

}
