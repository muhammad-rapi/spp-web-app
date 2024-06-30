<?php

namespace App\Listeners;

use App\Events\PaymentCreated;
use App\Models\Transaction;
use App\Models\TransactionHistoryLog;
use Illuminate\Support\Facades\DB;

class TransactionCreatedListener
{
    protected $transactionHistoryLogs;

    public function __construct(TransactionHistoryLog $transactionHistoryLogs)
    {
        $this->transactionHistoryLogs = $transactionHistoryLogs;
    }

    public function handle(\App\Events\Transaction\TransactionCreated $event)
    {
        DB::transaction(function () use ($event) {
            $transaction = $event->transaction;
            $this->transactionHistoryLogs->create([
                'transaction_id' => $transaction->id,
                'amount_of_transaction' => $transaction->amount_of_transaction,
                'note' => 'Transaksi Baru Berhasil Dibuat',
                'status' => $transaction->status
            ]);
        });
    }
}
