<?php

namespace App\Models;

use App\Models\Traits\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Category
 *
 * @property int                       $id
 * @property string                    $name
 * @property string                    $code
 * @property string|null               $description
 * @property string|null               $image
 * @property float                     $price
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property-read Collection|Product[] $products
 * @property-read int|null             $products_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCode($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereDescription($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereImage($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category wherePrice($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['code', 'name', 'description', 'image', 'name_en', 'description_en'];

    public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }
}
