<?php

namespace App\Models;

use App\Models\Traits\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Product
 *
 * @property int                $id
 * @property int                $category_id
 * @property string             $name
 * @property string             $code
 * @property string|null        $description
 * @property string|null        $image
 * @property float              $price
 * @property Carbon|null        $created_at
 * @property Carbon|null        $updated_at
 * @property-read Category|null $category
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCode($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImage($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Product extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['name', 'code', 'price', 'category_id', 'description', 'image', 'name_en', 'description_en'];

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getPriceForCount():float|int
    {
        if (!is_null($this->pivot)) {
            return $this->pivot->count * $this->price;
        }
        return $this->price;
    }
}
