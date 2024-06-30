<?php

namespace App\Filament\Resources\PaymentHistoryLogsResource\Pages;

use App\Filament\Resources\PaymentHistoryLogsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentHistoryLogs extends ListRecords
{
    protected static string $resource = PaymentHistoryLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
