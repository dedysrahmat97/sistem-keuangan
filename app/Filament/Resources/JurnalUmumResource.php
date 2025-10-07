<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalUmumResource\Pages;
use App\Models\Akun;
use App\Models\JurnalUmum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class JurnalUmumResource extends Resource
{
    protected static ?string $model = JurnalUmum::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Jurnal Umum';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'jurnal-umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal')
                    ->default(now()),
                Forms\Components\Textarea::make('keterangan')
                    ->required()
                    ->label('Keterangan')
                    ->rows(3),
                Forms\Components\FileUpload::make('bukti_transfer')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->directory('bukti_transfer')
                    ->visibility('public')
                    ->image()
                    ->maxSize(2048), // Limit file size
                Forms\Components\Repeater::make('jurnalUmumDetail')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('akun_id')
                            ->relationship('akun', 'nama_akun')
                            ->searchable()
                            ->required()
                            ->label('Akun')
                            ->options(function () {
                                // Cache options
                                return cache()->remember('akun_form_options', 3600, function () {
                                    return Akun::orderBy('nama_akun')->pluck('nama_akun', 'id');
                                });
                            })
                            ->preload(), // Preload untuk performa
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'debet' => 'Debet',
                                'kredit' => 'Kredit',
                            ])
                            ->required()
                            ->label('Tipe')
                            ->default('debet'),
                        Forms\Components\TextInput::make('nominal')
                            ->label('Nominal')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->defaultItems(1)
                    ->minItems(1)
                    ->maxItems(20), // Batasi maksimum items
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(JurnalUmum::with(['jurnalUmumDetail.akun'])) // EAGER LOADING
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(), // Tambahkan sorting
                Tables\Columns\ImageColumn::make('bukti_transfer')
                    ->label('Bukti Transfer')
                    ->toggleable(), // Bisa di-toggle untuk performance
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->label('Keterangan')
                    ->limit(50), // Limit karakter untuk performa
                Tables\Columns\TextColumn::make('jurnalUmumDetail.akun.nama_akun')
                    ->label('Nama Akun')
                    ->formatStateUsing(function ($record) {
                        // Ambil semua nama akun sekaligus
                        return $record->jurnalUmumDetail
                            ->pluck('akun.nama_akun')
                            ->unique()
                            ->implode(', ');
                    }),
            ])
            ->filters([
                DateRangeFilter::make('tanggal'),
                Tables\Filters\SelectFilter::make('nama_akun')
                    ->relationship('jurnalUmumDetail.akun', 'nama_akun')
                    ->label('Nama Akun')
                    ->searchable() // Tambahkan searchable
                    ->preload() // Preload options
                    ->options(function () {
                        // Cache hasil query
                        return cache()->remember('akun_options', 3600, function () {
                            return Akun::pluck('nama_akun', 'id');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('Ekspor'),
                ]),
            ])
            ->defaultSort('tanggal', 'desc') // Default sorting
            ->deferLoading(); // Defer loading untuk data besar
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
            'index' => Pages\ListJurnalUmums::route('/'),
            'create' => Pages\CreateJurnalUmum::route('/create'),
            'edit' => Pages\EditJurnalUmum::route('/{record}/edit'),
        ];
    }
}