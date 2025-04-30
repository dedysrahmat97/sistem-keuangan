<?php

namespace App\Filament\Pages;

use App\Models\Akun;
use App\Models\TipeAkun;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class LaporanNeraca extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.laporan-neraca';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 7;

    protected function getTableQuery(): Builder
    {
        return Akun::query()
            ->with('tipeAkun')
            ->where('pos_laporan', 'neraca')
            ->select('akun.*')
            ->addSelect([
                'saldo_berjalan' => function ($query) {
                    $query->selectRaw(
                        "CASE WHEN akun.pos_saldo = 'debet' 
                            THEN (akun.saldo_awal + 
                                COALESCE(SUM(CASE WHEN jurnal_umum_detail.tipe = 'debet' THEN jurnal_umum_detail.nominal ELSE 0 END), 0) - 
                                COALESCE(SUM(CASE WHEN jurnal_umum_detail.tipe = 'kredit' THEN jurnal_umum_detail.nominal ELSE 0 END), 0))
                            ELSE (akun.saldo_awal + 
                                COALESCE(SUM(CASE WHEN jurnal_umum_detail.tipe = 'kredit' THEN jurnal_umum_detail.nominal ELSE 0 END), 0) - 
                                COALESCE(SUM(CASE WHEN jurnal_umum_detail.tipe = 'debet' THEN jurnal_umum_detail.nominal ELSE 0 END), 0))
                        END"
                    )
                        ->from('jurnal_umum_detail')
                        ->join('jurnal_umum', 'jurnal_umum.id', '=', 'jurnal_umum_detail.jurnal_umum_id')
                        ->whereColumn('jurnal_umum_detail.akun_id', 'akun.id');
                },
            ]);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('tipeAkun.nama_tipe')
                ->label('Tipe Akun')
                ->sortable(),

            Tables\Columns\TextColumn::make('nama_akun')
                ->label('Nama Akun')
                ->description(fn (Akun $record) => 'Pos Saldo: '.ucfirst($record->pos_saldo).' | Saldo Awal: Rp '.number_format($record->saldo_awal, 0, ',', '.'))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('saldo_berjalan')
                ->label('Total')
                ->numeric()
                ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))
                ->summarize(
                    Sum::make()
                        ->label('Total Saldo')
                        ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))
                ),
        ];
    }

    public function getTableBulkActions(): array
    {
        return [
            ExportBulkAction::make(),
        ];
    }

    public function getTableFilters(): array
    {
        return [

            Tables\Filters\SelectFilter::make('tipe_akun_id')
                ->relationship('tipeAkun', 'nama_tipe')
                ->label('Tipe Akun')
                ->multiple()
                ->searchable()
                ->options(fn () => TipeAkun::pluck('nama_tipe', 'id')),

            Tables\Filters\SelectFilter::make('pos_saldo')
                ->options([
                    'debet' => 'Debet',
                    'kredit' => 'Kredit',
                ])
                ->label('Posisi Saldo'),
        ];
    }
}
