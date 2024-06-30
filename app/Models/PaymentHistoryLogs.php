<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryLogs extends Model
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
        'payment_id',
        'amount_of_payments',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_id' => 'string',
        'amount_of_payments' => 'integer',
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
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
