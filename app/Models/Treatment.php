<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Treatment extends Model
{
    use HasFactory;

    protected $appends = ['IsAlreadyInWishlist'];

    public function subCategory()
    {
        return $this->belongsToMany(SubCategory::class, "treatment_relations", "treatment_id", "sub_category_id");
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, "treatment_relations", "treatment_id", "category_id");
    }

    public function area()
    {
        return $this->belongsToMany(Area::class, "treatment_relations", "treatment_id", "area_id");
    }
}
