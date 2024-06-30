<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionHistoryLogsResource\Pages;
use App\Models\TransactionHistoryLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionHistoryLogsResource extends Resource
{
    protected static ?string $model = TransactionHistoryLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.transaction_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_of_transaction')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'Success' => 'success',
                        'Failed' => 'warning',
                    })
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListTransactionHistoryLogs::route('/'),
            // 'view' => Pages\ListTransactionHistoryLogs::route('/'),
        ];
    }
}
