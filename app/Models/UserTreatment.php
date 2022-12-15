<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Models\TreatmentRelation;
use App\Models\UserAddress;
use Auth;

class UserTreatment extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","area_id","category_id","sub_category_id","treatment_id"];
    
    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,"sub_category_id");
    }

    public function treatment(){
        return $this->belongsTo(Treatment::class);
    }
}
