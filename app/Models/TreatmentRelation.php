<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;


class TreatmentRelation extends Model
{
    use HasFactory;
    protected $fillable = array("area_id","category_id","sub_category_id","treatment_id");

    protected $appends = ['treatment_data'];

    public function getTreatmentDataAttribute(){
      $areaName = \App\Models\Area::where('id',$this->area_id)->pluck('title')->first();
      $categoryName = \App\Models\Category::where('id',$this->category_id)->pluck('title')->first();
      $subCategoryName = \App\Models\SubCategory::where('id',$this->sub_category_id)->pluck('title')->first();
      return [
        'area' => $areaName,
        'category' => $categoryName,
        'subcategory' => $subCategoryName
      ];
    }

    public function areas(){
      return $this->hasOne("\App\Models\Area","id","area_id");
    }

    public function categories()
    {
      return $this->hasOne("\App\Models\Category","id","category_id");
    }
    
    public function treatments()
    {
      return $this->hasOne("\App\Models\Treatment","id","treatment_id");
    }

}
