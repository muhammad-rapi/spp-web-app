<?php

namespace App\Filament\Resources\TransactionHistoryLogsResource\Pages;

use App\Filament\Resources\TransactionHistoryLogsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionHistoryLogs extends ListRecords
{
    protected static string $resource = TransactionHistoryLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
