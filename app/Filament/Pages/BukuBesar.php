<?php

namespace App\Filament\Pages;

use App\Models\Akun;
use App\Models\TipeAkun;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class BukuBesar extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.buku-besar';

    protected static ?int $navigationSort = 5;

    // Define the table configuration
    protected function getTableQuery(): Builder
    {
        return \App\Models\JurnalUmumDetail::query()
            ->with('akun') // Tambahkan ini
            ->selectRaw("
            jurnal_umum_detail.id, 
            jurnal_umum.tanggal, 
            jurnal_umum.bukti_transfer, 
            jurnal_umum.keterangan, 
            jurnal_umum_detail.tipe, 
            akun.nama_akun,
            akun.pos_saldo,
            CASE
                WHEN akun.pos_saldo = 'debet' AND jurnal_umum_detail.tipe = 'debet' THEN jurnal_umum_detail.nominal
                WHEN akun.pos_saldo = 'debet' AND jurnal_umum_detail.tipe = 'kredit' THEN -jurnal_umum_detail.nominal
                WHEN akun.pos_saldo = 'kredit' AND jurnal_umum_detail.tipe = 'debet' THEN -jurnal_umum_detail.nominal
                WHEN akun.pos_saldo = 'kredit' AND jurnal_umum_detail.tipe = 'kredit' THEN jurnal_umum_detail.nominal
                ELSE 0
            END AS nominal
        ")
            ->join('jurnal_umum', 'jurnal_umum.id', '=', 'jurnal_umum_detail.jurnal_umum_id')
            ->join('akun', 'akun.id', '=', 'jurnal_umum_detail.akun_id')
            ->orderBy('jurnal_umum_detail.id', 'asc'); // Urutkan berdasarkan tanggal
    }

    // Define the table columns
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date('d-m-Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('nama_akun')
                ->label('Nama Akun')
                ->description(fn ($record): string => 'Pos Saldo: '.ucfirst($record->pos_saldo))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('keterangan')
                ->label('Keterangan')
                ->searchable(),
            Tables\Columns\TextColumn::make('tipe')
                ->label('Tipe')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('nominal')
                ->label('Nominal')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))
                ->summarize(Sum::make()->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))),
        ];
    }

    public function getTableBulkActions()
    {
        return [
            ExportBulkAction::make(),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            DateRangeFilter::make('tanggal'),
            Tables\Filters\SelectFilter::make('tipe_akun_id')
                ->relationship('akun.tipeAkun', 'nama_tipe')
                ->label('Tipe Akun')
                ->multiple()
                ->searchable()
                ->options(function () {

                    return TipeAkun::pluck('nama_tipe', 'id');
                }),
            Tables\Filters\SelectFilter::make('nama_akun')
                ->relationship('akun', 'nama_akun')
                ->label('Nama Akun')
                ->searchable()
                ->options(function () {

                    return Akun::pluck('nama_akun', 'id');
                }),
            // Filter untuk memilih akun berdasarkan posisi laporan
            Tables\Filters\SelectFilter::make('pos_laporan')
                ->options([
                    'neraca' => 'Neraca',
                    'laba_rugi' => 'Laba Rugi',
                ])
                ->label('Posisi Laporan'),
            // Filter untuk memilih akun berdasarkan posisi saldo
            Tables\Filters\SelectFilter::make('pos_saldo')
                ->options([
                    'debet' => 'Debet',
                    'kredit' => 'Kredit',
                ])
                ->label('Posisi Saldo'),
        ];
    }
}