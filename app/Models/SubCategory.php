<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    
    protected $fillable = array("title","url","description");

    public function treatment() {
        return $this->belongsToMany(Treatment::class,'treatment_relations','sub_category_id','treatment_id')->withPivot('area_id', 'category_id','sub_category_id','treatment_id');
    }

    public function treatmentRelation() {
        return $this->hasOne(TreatmentRelation::class, 'sub_category_id', 'id')->select('id','area_id','category_id','sub_category_id','treatment_id')->groupBy('category_id', 'area_id', 'sub_category_id');
    }
}
