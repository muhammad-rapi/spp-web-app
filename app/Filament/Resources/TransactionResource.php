<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists\Components;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Nama Wajib Diisi",
                    ])->maxLength(255),
                Forms\Components\TextInput::make('amount_of_transaction')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Nominal Transaksi Wajib Diisi",
                    ])->numeric(),
                Forms\Components\Radio::make('type')
                    ->label('Tipe')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Tipe Wajib Diisi",
                    ])
                    ->default('Masuk')
                    ->options([
                        'Masuk' => 'Masuk',
                        'Keluar' => 'Keluar',
                    ]),
                Forms\Components\TextArea::make('description')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    // ->icons([
                    //     'Masuk'=> 'heroicon-o-arrow-down-circle',
                    //     'Keluar'=> 'heroicon-o-arrow-up-circle',
                    // ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_of_transaction')
                    ->money('IDR', locale: 'id')
                    // ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'Success' => 'success',
                        'Failed' => 'danger',
                    })
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
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
                Components\Section::make('Transaction')
                ->schema([
                    Components\Split::make([
                        Components\Grid::make(2)
                            ->schema([
                                Components\Group::make([
                                    TextEntry::make('transaction_number'),
                                    TextEntry::make('amount_of_transaction')
                                    ->money('IDR', locale:'id'),
                                    TextEntry::make('type'),
                                ]),
                                Components\Group::make([
                                    TextEntry::make('createdBy.name'),
                                    TextEntry::make('updatedBy.name')
                                    ->default('-'),
                                    TextEntry::make('status')
                                    ->badge()
                                        ->color('success')
                                        ->label('Status'),
                                ]),
                            ]),
                    ])->from('xl'),
                ]),
                Components\Section::make('History Transaction')
                ->schema([
                    Components\Split::make([
                        TextEntry::make('transactionHistoryLogs.note')
                        ->label('')
                            ->helperText(
                                fn (Transaction $record): string => 'Dibuat Oleh ' . $record->createdBy->name . ' | ' . $record->created_at . ' WIB'
                            ),
                    ])->from('xl'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
