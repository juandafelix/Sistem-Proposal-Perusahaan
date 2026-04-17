<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'title',
        'description',
        'status',
        'submitted_by',
        'approved_by',
        'submitted_at',
        'approved_at',
        'notes',
    ];
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at'  => 'datetime',
        ];
    }
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isManager()) {
            return $query;
        }

        if ($user->isFinance()) {
            return $query->where('status', self::STATUS_APPROVED);
        }

        if ($user->isDivision()) {
            return $query->where(function (Builder $q) use ($user) {
                $q->where('submitted_by', $user->id)         
                  ->orWhere('status', self::STATUS_APPROVED); 
            });
        }

        return $query->whereRaw('0 = 1');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
