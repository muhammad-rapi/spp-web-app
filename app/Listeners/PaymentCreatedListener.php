<?php

namespace App\Listeners;

use App\Events\PaymentCreated;
use App\Models\Payment;
use App\Models\PaymentHistoryLogs;
use Illuminate\Support\Facades\DB;

class PaymentCreatedListener
{
    protected $paymentHistoryLogs;

    public function __construct(PaymentHistoryLogs $paymentHistoryLogs)
    {
        $this->paymentHistoryLogs = $paymentHistoryLogs;
    }

    public function handle(\App\Events\Payment\PaymentCreated $event)
    {
        DB::transaction(function () use ($event) {
            $payment = $event->payment;
            $existingPayment = Payment::where('id', $payment->id)->first();

            if ($existingPayment && !$existingPayment->trashed()) {
                $this->paymentHistoryLogs->create([
                    'payment_id' => $payment->id,
                    'amount_of_payments' => $payment->amount_of_payment,
                    'note' => 'Pembayaran Baru Berhasil Dibuat',
                    'status' => $payment->status,
                ]);
            }
        });
    }
}
