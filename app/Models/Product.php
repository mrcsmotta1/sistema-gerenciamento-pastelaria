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
 * Class Product
 *
 * Represents a Product in the system.
 *
 * @category Model
 * @package  App\Models
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class Product extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_type_id',
        'name',
        'price',
        'photo',
    ];

    /**
     * Define the relationship between Customer and Order (one-to-many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }
}
