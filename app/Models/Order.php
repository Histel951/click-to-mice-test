<?php

namespace App\Models;

use App\Services\OrderService\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasUuids;

    protected $table = 'orders';

    /**
     * Так как primary key — uuid
     */
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';
    public $incrementing = false;

    /**
     * Разрешённые для mass-assignment поля
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'status',
        'price',
        'tax',
        'gross',
        'external_services_uuids',
    ];

    protected $casts = [
        'external_services_uuids' => 'array',
        'status' => OrderStatusEnum::class,
        'price' => 'integer',
        'tax' => 'integer',
        'gross' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
