<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'display_name',
        'hash_name',
        'path',
        'mime_type',
        'size',
        'task_id',
        'user_id',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($attachment) {
            $attachment->uuid = (string)Str::uuid();
        });
    }

    /**
     * Tarea a la que pertenece el archivo
     * @return BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Usuario que subió el archivo
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * URL publica del archivo
     * @return string
     */
    public function url(): string
    {
        return Storage::url($this->path);
    }
}
