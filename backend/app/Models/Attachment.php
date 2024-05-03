<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        return route('v1.attachments.download', $this->uuid);
    }

    /**
     * Tipo de archivo
     * @return string
     * @throws Exception
     */
    public function type(): string
    {
        return match (trim(Str::of($this->display_name)->after('.'))) {
            'jpg', 'jpeg', 'png', 'gif' => 'image',
            'mp3', 'wav', 'ogg', 'm4a' => 'audio',
            'mp4', 'mov', 'avi', 'mkv' => 'video',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' => 'document',
            'zip', 'rar', 'tar', 'gz' => 'compressed',
            default => 'file',
        };
    }
}
