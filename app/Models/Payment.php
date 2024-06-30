<?php

namespace App\Models;

use App\Events\Payment\PaymentCreated;
use App\Events\StudentCreated;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    // Payment Status
    const UNPAID = 'unpaid';
    const PAID = 'lunas';

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->payment_number = (string) self::generatePaymentNumber();
            $model->status = Payment::PAID;
        });

        self::created(function ($model) {
            // dd($model);
            event(new PaymentCreated($model));
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($payment) {
            PaymentHistoryLogs::where('payment_id', $payment->id)->delete();
        });
    }

    public static function generatePaymentNumber()
    {
        $last = self::orderBy('created_at', 'desc')->count();
        $value = $last + 1;
        $payment_number = strtoupper(date('ymd') . str_pad($value, 4, '0', STR_PAD_LEFT));
        $model = self::select(DB::raw('count(1) as count'))->where('payment_number', $payment_number)->first();
        while ($model->count == 1) {
            $value++;
            $payment_number = strtoupper(date('ymd') . str_pad($value, 4, '0', STR_PAD_LEFT));
            $model = self::select(DB::raw('count(1) as count'))->where('payment_number', $payment_number)->first();
        }
        return 'SPP' . $payment_number;
    }
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'payment_number',
        'student_id',
        'amount_of_payment',
        'description',
        'status',
        'month',
        'year',
        'snap_token',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_number' => 'string',
        'student_id' => 'string',
        'amount_of_payment' => 'integer',
        'description' => 'string',
        'status' => 'string',
        'month' => 'array',
        'year' => 'string',
        'snap_token' => 'string',
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
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function paymentHistoryLogs(): HasMany
    {
        return $this->hasMany(PaymentHistoryLogs::class);
    }
}
