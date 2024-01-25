<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;

    const STATUS_IS_COMPLETED = 1;
    const STATUS_IS_OPEN = 0;

    const STATUS_IS_DELETED = 1;
    const STATUS_IS_NOT_DELETED = 0;

    const FORMAT_CREATED_AT = 'Y-m-d H:i:s';

    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'is_deleted'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    protected $hidden = [
        'updated_at',
    ];

    /**
     * Override the route model binding to handle not found tasks.
     *
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $task = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

        if (!$task) {
            throw new ModelNotFoundException("Task with ID {$value} not found.");
        }

        return $task;
    }
}
