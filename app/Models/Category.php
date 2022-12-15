<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = array("title","url","description","quote","author");

    protected $appends = ['url'];

    /**
     * Get File URL.
     *
     * @return bool
     */
    public function getUrlAttribute()
    {
        if (isset($this->attributes['url'])) {
          return $this->attributes['url'] = asset(str_replace("public","storage",$this->attributes['url']));
        }
    }

    public function subcategory() {
        return $this->belongsToMany(SubCategory::class,'treatment_relations','category_id','sub_category_id')->withPivot('area_id', 'category_id','sub_category_id');
    }

    public function treatmentRelation() {
        return $this->hasOne(TreatmentRelation::class, 'category_id', 'id')->select('id','area_id','category_id','sub_category_id','treatment_id')->groupBy('category_id');
    }
}
