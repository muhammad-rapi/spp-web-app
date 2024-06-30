<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function afterCreate(): void
    {
        $studentId = $this->data['student_id'];
        $amountOfPayment = $this->data['amount_of_payment'];
        $months = $this->data['month']; 
        $year = $this->data['year'];
        $description = $this->data['description'];

        $this->record->delete();

        foreach ($months as $month) {
            Payment::create([
                'student_id' => $studentId,
                'amount_of_payment' => $amountOfPayment,
                'month' => $month,
                'year' => $year,
                'description' => $description,
            ]);
        }

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
