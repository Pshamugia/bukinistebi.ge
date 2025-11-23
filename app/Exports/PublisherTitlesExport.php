<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class PublisherTitlesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
{
    public float $grandTotal = 0.0;

    /** @var \Illuminate\Support\Collection */
    protected Collection $rows;

    public function __construct(
        public int $publisherId,
        public ?string $start,
        public ?string $end
    ) {}

    /** Start writing table at A3, like your working export */
    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return ['Title', 'Unit Price', 'Qty', 'Line Total', 'Sold At'];
    }

    /** Build & cache the dataset and grand total */
    public function collection(): Collection
    {
        $q = DB::table('order_items as oi')
            ->join('books as b', 'b.id', '=', 'oi.book_id')
            ->selectRaw(
                'b.title as title, oi.price as unit_price, oi.quantity as qty, ' .
                '(oi.price * oi.quantity) as line_total, oi.created_at as sold_at'
            )
            ->where('b.uploader_id', $this->publisherId);

        if ($this->start && $this->end) {
            $q->whereBetween('oi.created_at', [$this->start, $this->end]);
        }

        $this->rows = $q->orderBy('oi.created_at')->get();

        // same filter set for the total
        $this->grandTotal = (float) $this->rows->sum('line_total');

        return $this->rows;
    }

    /** Always return a simple array of scalars */
    public function map($row): array
    {
        // $row is stdClass from the query; map to array explicitly
        return [
            (string) $row->title,
            (float)  $row->unit_price,
            (int)    $row->qty,
            (float)  $row->line_total,
            (string) optional($row->sold_at)->format('Y-m-d H:i:s') ?? (string) $row->sold_at,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Put totals above the table (safe, like your working export)
                $sheet->setCellValue('A1', 'საერთო ჯამი (GEL):');
                $sheet->setCellValue('B1', $this->grandTotal);
                $sheet->getStyle('A1:B1')->getFont()->setBold(true);

                // Optional: date period on row 2
                $period = [];
                if ($this->start) $period[] = 'დან: '.$this->start;
                if ($this->end)   $period[] = 'მდე: '.$this->end;
                if ($period) {
                    $sheet->setCellValue('A2', implode('   ', $period));
                }
            },
        ];
    }
}
