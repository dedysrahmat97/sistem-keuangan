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
                    ->label('Tanggal'),
                Forms\Components\Textarea::make('keterangan')
                    ->required()
                    ->label('Keterangan'),
                Forms\Components\FileUpload::make('bukti_transfer')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->directory('bukti_transfer')
                    ->visibility('public'),
                Forms\Components\Repeater::make('jurnalUmumDetail')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('akun_id')
                            ->relationship('akun', 'nama_akun')
                            ->searchable()
                            ->required()
                            ->label('Akun'),
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'debet' => 'Debet',
                                'kredit' => 'Kredit',
                            ])
                            ->required()
                            ->label('Tipe'),
                        Forms\Components\TextInput::make('nominal')
                            ->label('Nominal')
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->dateTime(),
                Tables\Columns\ImageColumn::make('bukti_transfer')
                    ->label('Bukti Transfer'),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->label('Keterangan'),
                Tables\Columns\TextColumn::make('jurnalUmumDetail.akun.nama_akun')
                    ->label('Nama Akun'),

            ])
            ->filters([
                DateRangeFilter::make('tanggal'),
                Tables\Filters\SelectFilter::make('nama_akun')
                    ->relationship('jurnalUmumDetail.akun', 'nama_akun')
                    ->label('Nama Akun')
                    ->options(function () {

                        return Akun::pluck('nama_akun', 'id');
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
            'index' => Pages\ListJurnalUmums::route('/'),
            'create' => Pages\CreateJurnalUmum::route('/create'),
            'edit' => Pages\EditJurnalUmum::route('/{record}/edit'),
        ];
    }
}
