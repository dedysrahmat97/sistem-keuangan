<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipeAkunResource\Pages;
use App\Models\TipeAkun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TipeAkunResource extends Resource
{
    protected static ?string $model = TipeAkun::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Tipe Akun';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'tipe-akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_tipe')
                    ->label('Nama Tipe')
                    ->required(),
                Forms\Components\TextInput::make('kode_tipe')
                    ->label('Kode Tipe')
                    ->default(function (): string {
                        $latest = TipeAkun::latest('id')->first();
                        $id = $latest?->id ?? 0;

                        return $id + 1;
                    })
                    ->readOnly()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_tipe')
                    ->label('Kode Tipe')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_tipe')
                    ->label('Nama Tipe')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('akun_count')
                    ->label('Jumlah Akun')
                    ->counts('akun')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListTipeAkuns::route('/'),
            'create' => Pages\CreateTipeAkun::route('/create'),
            'edit' => Pages\EditTipeAkun::route('/{record}/edit'),
        ];
    }
}