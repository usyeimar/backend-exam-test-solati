<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'completed',
        'completed_at',
        'due_at',
        'user_id',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($task) {
            $task->uuid = (string)Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeCompleted($query)
    {
        return $query->where('completed');
    }

    public function scopePending($query)
    {
        return $query->where('completed', false);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_at', now()->toDateString());
    }

    public function scopeDueTomorrow($query)
    {
        return $query->whereDate('due_at', now()->addDay()->toDateString());
    }

    public function scopeDueAfterTomorrow($query)
    {
        return $query->whereDate('due_at', now()->addDays(2)->toDateString());
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_at', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString(),
        ]);
    }

    public function scopeDueNextWeek($query)
    {
        return $query->whereBetween('due_at', [
            now()->addWeek()->startOfWeek()->toDateString(),
            now()->addWeek()->endOfWeek()->toDateString(),
        ]);
    }

    public function scopeDueThisMonth($query)
    {
        return $query->whereBetween('due_at', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ]);
    }
}
