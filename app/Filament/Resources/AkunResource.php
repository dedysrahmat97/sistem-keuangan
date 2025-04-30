<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AkunResource\Pages;
use App\Models\Akun;
use App\Models\TipeAkun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AkunResource extends Resource
{
    protected static ?string $model = Akun::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Akun';

    protected static ?string $slug = 'akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipe_akun_id')
                    ->label('Tipe Akun')
                    ->relationship('tipeAkun', 'nama_tipe')
                    ->required()
                    ->reactive()
                    ->searchable()
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $tipeAkunId = $get('tipe_akun_id');
                        if (! $tipeAkunId) {
                            return;
                        }

                        $tipeAkun = TipeAkun::find($tipeAkunId);
                        $kodeTipe = $tipeAkun->kode_tipe;

                        // Ambil kode_akun terakhir yang dimulai dengan kode_tipe
                        $lastKodeAkun = Akun::where('kode_akun', 'like', $kodeTipe.'%')
                            ->latest('id')
                            ->value('kode_akun');

                        if (! $lastKodeAkun || strlen($lastKodeAkun) === strlen($kodeTipe)) {
                            $nextNumber = 1;
                        } else {
                            $lastPart = (int) substr($lastKodeAkun, strlen($kodeTipe));
                            $nextNumber = $lastPart + 1;
                        }

                        // Loop untuk mencari kode_akun yang belum digunakan
                        do {
                            $newKodeAkun = $kodeTipe.$nextNumber;
                            $exists = Akun::where('kode_akun', $newKodeAkun)->exists();
                            $nextNumber++;
                        } while ($exists);

                        // Set kode_akun yang tersedia
                        $set('kode_akun', $newKodeAkun);
                    }),
                Forms\Components\TextInput::make('kode_akun')
                    ->label('Kode Akun')
                    ->reactive() // penting supaya default-nya bisa bereaksi
                    ->readOnly()
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('nama_akun')
                    ->label('Nama Akun')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('pos_saldo')
                    ->label('Posisi Saldo')
                    ->options([
                                            'debet' => 'Debet',
                                            'kredit' => 'Kredit',
                                        ])
                    ->required(),
                Forms\Components\TextInput::make('saldo_awal')
                    ->label('Saldo Awal')
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->default(0),
                Forms\Components\Select::make('pos_laporan')
                    ->label('Posisi Laporan')
                    ->options([
                                            'neraca' => 'Neraca',
                                            'laba_rugi' => 'Laba Rugi',
                                        ])
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_akun')
                    ->label('Kode Akun')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_akun')
                    ->label('Nama Akun')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipeAkun.nama_tipe')
                    ->label('Tipe Akun')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pos_saldo')
                    ->label('Posisi Saldo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pos_laporan')
                    ->label('Posisi Laporan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('saldo_awal')
                    ->label('Saldo Awal')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))
                    ->summarize(Sum::make()->formatStateUsing(fn ($state) => 'Rp '.number_format($state, 0, ',', '.'))),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_akun_id')
                    ->relationship('tipeAkun', 'nama_tipe')
                    ->label('Tipe Akun')
                    ->searchable()
                    ->multiple()
                    ->options(function () {
                        return TipeAkun::pluck('name', 'id');
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()->hidden(fn () => !auth()->user()->hasRole('admin')),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Ekspor'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAkuns::route('/'),
            'create' => Pages\CreateAkun::route('/create'),
            'edit' => Pages\EditAkun::route('/{record}/edit'),
        ];
    }
}