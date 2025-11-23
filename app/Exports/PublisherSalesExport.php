<?php
// app/Exports/PublisherSalesExport.php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PublisherSalesExport implements FromCollection, WithHeadings
{
    public function __construct(
        private int $publisherId,
        private ?string $start,
        private ?string $end
    ) {}

    public function headings(): array
    {
        return ['Date', 'Book ID', 'Title', 'Unit Price', 'Qty', 'Line Total'];
    }

    public function collection()
    {
        $q = DB::table('order_items as oi')
            ->join('books as b', 'b.id', '=', 'oi.book_id')
            ->selectRaw('DATE(oi.created_at) as date, b.id as book_id, b.title,
                         oi.price as unit_price, oi.quantity,
                         (oi.price * oi.quantity) as line_total')
            ->where('b.uploader_id', $this->publisherId);

        if ($this->start && $this->end) {
            $q->whereBetween('oi.created_at', [$this->start, $this->end]);
        }

        // Optional status filter via orders:
        // ->join('orders as o','o.id','=','oi.order_id')
        // ->where('o.status','paid')

        return $q->orderBy('oi.created_at')->get();
    }
}
