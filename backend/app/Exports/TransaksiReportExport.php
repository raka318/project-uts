<?php
namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiReportExport implements FromQuery, WithHeadings, WithMapping
{
    protected $userId;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($userId, $dateFrom, $dateTo)
    {
        $this->userId = $userId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function query()
    {
        // Query disesuaikan dengan model Transaksi dan filter id_user
        return Transaksi::with(['kategori', 'dompet'])
            ->where('id_user', $this->userId)
            ->whereDate('tanggal', '>=', $this->dateFrom)
            ->whereDate('tanggal', '<=', $this->dateTo)
            ->orderBy('tanggal', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal',
            'Judul',
            'Jenis',
            'Kategori',
            'Dompet',
            'Jumlah (Rp)',
            'Deskripsi',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->id_transaksi,
            $transaksi->tanggal,
            $transaksi->judul,
            ucfirst($transaksi->jenis),
            $transaksi->kategori->nama_kategori ?? '-',
            $transaksi->dompet->nama_dompet ?? '-',
            $transaksi->jumlah,
            $transaksi->deskripsi ?? '-',
        ];
    }
}