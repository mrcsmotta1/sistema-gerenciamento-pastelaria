<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Model
 * @package  App\Models
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItem
 *
 * Represents a Order in the system.
 *
 * @category Model
 * @package  App\Models
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class Order extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
    ];

    protected $guarded = [
        'customer_id',
    ];

    /**
     * Get the customer associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the items associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
