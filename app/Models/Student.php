<?php

namespace App\Models;

use App\Events\Student\StudentCreated;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Student extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        // self::created(function ($student) {
        //     event(new StudentCreated($student));
        // });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
    protected $fillable = [
        'class_id',
        'user_id',
        'name',
        'birth_date',
        'gender',
        'status',
        'nisn',
        'nis',
        'image',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'name' => 'string',
        'user_id' => 'string',
        'class_id' => 'string',
        'birth_date' => 'date',
        'gender' => 'string',
        'status' => 'integer',
        'nisn' => 'string',
        'nis' => 'string',
        'image' => 'string',
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
    public function classes(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
