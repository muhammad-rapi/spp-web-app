<?php

namespace App\Models;

use App\Events\Transaction\TransactionCreated;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    // Transaction Tipe
    const MASUK = 'Masuk';
    const KELUAR = 'Keluar';

    // Transaction Status
    const SUCCES = 'Success';
    const FAILED = 'Failed';

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->status = Transaction::SUCCES;
            $model->transaction_number = (string) self::generateTransactionNumber();
        });

        self::created(function ($model) {
            event(new TransactionCreated($model));
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public static function generateTransactionNumber()
    {
        $last = self::orderBy('created_at', 'desc')->count();
        $value = $last + 1;
        $transactionNumber = strtoupper(date('ymd') . str_pad($value, 4, '0', STR_PAD_LEFT));
        $model = self::select(DB::raw('count(1) as count'))->where('transaction_number', $transactionNumber)->first();
        while ($model->count == 1) {
            $value++;
            $transactionNumber = strtoupper(date('ymd') . str_pad($value, 4, '0', STR_PAD_LEFT));
            $model = self::select(DB::raw('count(1) as count'))->where('transaction_number', $transactionNumber)->first();
        }
        return 'TR' . $transactionNumber;
    }

    protected $fillable = [
        'transaction_number',
        'name',
        'amount_of_transaction',
        'type',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'transaction_number' => 'string',
        'name' => 'string',
        'amount_of_transaction' => 'integer',
        'type' => 'string',
        'description' => 'string',
        'status' => 'string',
        'month' => 'array',
        'year' => 'string',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    // public function student(): BelongsTo
    // {
    //     return $this->belongsTo(Student::class, 'student_id');
    // }


    public function transactionHistoryLogs(): HasMany
    {
        return $this->hasMany(TransactionHistoryLog::class);
    }
}
