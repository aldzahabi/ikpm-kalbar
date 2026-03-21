<?php

namespace App\Exports;

use App\Models\FinanceTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FinanceTransactionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $transactions;
    protected $accountName;

    public function __construct($transactions, $accountName = null)
    {
        $this->transactions = $transactions;
        $this->accountName = $accountName;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Akun',
            'Kategori',
            'Tipe',
            'Keterangan',
            'Reference ID',
            'Nominal (Rp)',
            'Input By',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->account->name,
            $transaction->category->name,
            $transaction->category->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
            $transaction->description ?? '-',
            $transaction->reference_id ?? '-',
            number_format($transaction->amount, 0, ',', '.'),
            $transaction->user->name,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 20,
            'C' => 20,
            'D' => 15,
            'E' => 40,
            'F' => 20,
            'G' => 18,
            'H' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '15803d']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
