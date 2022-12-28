<?php

namespace App\Models;

use App\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['code', 'name', 'description', 'image', 'name_en', 'description_en'];

	/**
	 * @return HasMany
	 */
	public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }
}
