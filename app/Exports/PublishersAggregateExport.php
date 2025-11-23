<?php
// app/Exports/PublishersAggregateExport.php
namespace App\Exports; 
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PublishersAggregateExport implements FromCollection, WithHeadings
{
    public function __construct(private ?string $start, private ?string $end) {}

    public function headings(): array
    {
        return ['Publisher ID', 'Publisher Name', 'Total Sold Qty', 'Total Revenue'];
    }

    public function collection()
    {
        $q = DB::table('users as u')
            ->selectRaw('u.id, u.name,
                COALESCE(SUM(oi.quantity),0) as total_qty,
                COALESCE(SUM(oi.quantity * oi.price),0) as total_revenue')
            ->leftJoin('books as b', 'b.uploader_id', '=', 'u.id')
            ->leftJoin('order_items as oi', 'oi.book_id', '=', 'b.id')
            ->where('u.role', 'publisher');

        if ($this->start && $this->end) {
            $q->whereBetween('oi.created_at', [$this->start, $this->end]);
        }

        // optionally filter by order status if you have it:
        // ->join('orders as o', 'o.id', '=', 'oi.order_id')
        // ->where('o.status', 'paid')

        $q->groupBy('u.id', 'u.name')
          ->orderByDesc('total_revenue');

        return $q->get();
    }
}
