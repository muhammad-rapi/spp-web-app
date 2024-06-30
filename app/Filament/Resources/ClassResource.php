<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassResource\Pages;
use App\Filament\Resources\ClassResource\RelationManagers;
use App\Models\Classes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Position;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassResource extends Resource
{
    protected static ?string $model = Classes::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('Nama Kelas...')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Nama Wajib Diisi",
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('class_code')
                    ->placeholder('Kode Kelas...')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Kode Kelas Wajib Diisi",
                    ])
                    ->maxLength(13),
                Forms\Components\TextArea::make('description')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->description(fn (Classes $record): string => $record->class_code),
                Tables\Columns\TextColumn::make('description')
                    ->default('Tidak Ada Deskripsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Diubah Oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diubah')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions(
                [
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->tooltip('Ubah Data Kelas Ini'),
                    Tables\Actions\DeleteAction::make(),
                ],
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('class_code'),
                TextEntry::make('description')
                    ->default('-'),
                TextEntry::make('createdBy.name'),
                TextEntry::make('updatedBy.name'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ])
            ->columns(1)
            ->inlineLabel();
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
            'index' => Pages\ListClass::route('/'),
            'create' => Pages\CreateClass::route('/create'),
            'edit' => Pages\EditClass::route('/{record}/edit'),
        ];
    }
}
