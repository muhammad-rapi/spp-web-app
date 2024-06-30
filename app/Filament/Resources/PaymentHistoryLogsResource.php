<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentHistoryLogsResource\Pages;
use App\Filament\Resources\PaymentHistoryLogsResource\RelationManagers;
use App\Models\PaymentHistoryLogs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentHistoryLogsResource extends Resource
{
    protected static ?string $model = PaymentHistoryLogs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment.payment_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment.month')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment.year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_of_payments')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'unpaid' => 'warning',
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
            'index' => Pages\ListPaymentHistoryLogs::route('/'),
            // 'view' => Pages\ListPaymentHistoryLogs::route('/'),
        ];
    }
}
