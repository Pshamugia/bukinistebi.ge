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

     public float $sumUnitPrice = 0.0;
    public float $sumAcquisitionPrice = 0.0;
    public int $lastDataRow = 0;

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
        return [
            'Title',
            'Unit Price',
            'Acquisition Price',
            'Qty',
            'Line Total',
            'Cost',
            'Profit',
            'Sold At'
        ];
    }

    /** Build & cache the dataset and grand total */
    public function collection(): Collection
{
   $q = DB::table('order_items as oi')
    ->join('orders as o', 'o.id', '=', 'oi.order_id')  
    ->join('books as b', 'b.id', '=', 'oi.book_id')
    ->selectRaw(
        'b.title as title,
         oi.price as unit_price,
         b.acquisition_price as acquisition_price,
         oi.quantity as qty,
         (oi.price * oi.quantity) as line_total,
         o.created_at as sold_at'
    )
    ->where('b.uploader_id', $this->publisherId);


    if ($this->start && $this->end) {
$q->whereBetween('o.created_at', [$this->start, $this->end]);
    }

    $this->rows = $q->orderBy('oi.created_at')->get();

    // 🔢 CALCULATE MANUAL-LIKE TOTALS (no qty!)
    $this->sumUnitPrice = (float) $this->rows->sum('unit_price');
    $this->sumAcquisitionPrice = (float) $this->rows->sum(function ($r) {
        return $r->acquisition_price ?? 0;
    });

    // Track last row index (A3 start + headings)
$this->lastDataRow = 4 + $this->rows->count();  

    return $this->rows;
}


    /** Always return a simple array of scalars */
    public function map($row): array
{
    $acq   = (float) ($row->acquisition_price ?? 0);
    $qty   = (int) $row->qty;
    $cost  = $acq * $qty;
    $profit = (float) $row->line_total - $cost;

    return [
        (string) $row->title,
        (float)  $row->unit_price,
        $row->acquisition_price !== null ? (float) $row->acquisition_price : null,
        $qty,
        (float)  $row->line_total,
        $cost,
        $profit,
        (string) optional($row->sold_at)->format('Y-m-d H:i:s') ?? (string) $row->sold_at,
    ];
}


    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet;

            // Totals row (below data)
            $unitPriceCell       = 'B' . $this->lastDataRow;
            $acquisitionCell     = 'C' . $this->lastDataRow;

            // Write totals
            $sheet->setCellValue($unitPriceCell, $this->sumUnitPrice);
            $sheet->setCellValue($acquisitionCell, $this->sumAcquisitionPrice);

            // Yellow background like your screenshot
            $sheet->getStyle($unitPriceCell . ':' . $acquisitionCell)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFFFFF00');

            // Bold totals
            $sheet->getStyle($unitPriceCell . ':' . $acquisitionCell)
                ->getFont()
                ->setBold(true);
        },
    ];
}

}
