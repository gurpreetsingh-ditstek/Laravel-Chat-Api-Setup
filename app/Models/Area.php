<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
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

    public function category() {
        return $this->belongsToMany(Category::class,'treatment_relations','area_id','category_id');
    }

    public function categoryface() {
        return $this->belongsToMany(Category::class,'treatment_relations','area_id','category_id')->groupBy('categories.id')->orderByRaw(
            "CASE WHEN categories.id IN (9,3,7,10) then 0 else 1 end,FIELD(categories.id, 9,3,7,10)"
       )->limit(4);
    }

    public function categorybody() {
        return $this->belongsToMany(Category::class,'treatment_relations','area_id','category_id')->groupBy('categories.id')->orderByRaw(
            "CASE WHEN categories.id IN (12,13,14) then 0 else 1 end,FIELD(categories.id, 12,13,14)"
       )->limit(3);
    }

    public function categoryhair() {
        return $this->belongsToMany(Category::class,'treatment_relations','area_id','category_id')->limit(3);
    }

    public function categoryskin() {
        return $this->belongsToMany(Category::class,'treatment_relations','area_id','category_id')->groupBy('categories.id')->orderByRaw(
            "CASE WHEN categories.id IN (22,27,24,25) then 0 else 1 end,FIELD(categories.id, 22,27,24,25)"
       )->limit(4);
    }
}
