<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryLog extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
    protected $fillable = [
        'transaction_id',
        'amount_of_transaction',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'transaction_id' => 'string',
        'amount_of_transaction' => 'integer',
        'note' => 'string',
        'status' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
