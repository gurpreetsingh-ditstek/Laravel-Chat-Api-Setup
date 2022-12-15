<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Carbon\Carbon;

class UserRequest extends Model
{
    use \Awobaz\Compoships\Compoships;

    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
 
    protected $fillable = ['area_id', 'category_id', 'sub_category_id', 'treatment_id', 'user_id', 'fluency', 'visitor_local', 'preferred', 'time_preference', 'language_preference', 'personal_note', 'description', 'previous_personal_note', 'previous_description', 'status', 'payment_status', 'comments', 'terms_of_use', 'feedback', 'preferred_language', 'user_experience', 'expire_in', 'unique_code', 'posted_on', 'expired_date', 'updated_on'];

    protected $appends = ['language', 'req_exp', 'doctor_status', 'quote_exp'];

    protected function getlanguageAttribute()
    {
        $language = trim($this->language_preference, '"');
        return \App\Models\Language::where('language_code', $language)->pluck('language_name')->first();
    }

    protected function getReqExpAttribute()
    {
        if ($this->status == 1) {
            return 'Draft';
        } elseif ($this->status == 2) {
            return 'Active';
        } elseif ($this->status == 6) {
            return 'Expired';
        } else {
            return 'N/A';
        }
    }

    public function treatmentRelations()
    {
        return $this->hasOne(UserTreatment::class, ["area_id", "category_id", "sub_category_id"], ["area_id", "category_id", "sub_category_id"])
            ->where("user_id", Auth::user()->id);
    }
    public function postRequestTreatmentRelations()
    {
        return $this->hasMany(UserTreatment::class, ["area_id", "category_id", "sub_category_id"], ["area_id", "category_id", "sub_category_id"])->select(['id', 'user_id', 'area_id', 'category_id', 'sub_category_id', 'treatment_id']);
    }

    public function area()
    {
        return $this->belongsTo(Area::class)->select('id', 'title', 'description');
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->select('id', 'title', 'description');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class)->select('id', 'title', 'description');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class)->select('id', 'title', 'description');
    }

    public function UserRequestFiles()
    {
        return $this->hasMany(UserRequestFile::class)->select('id', 'user_request_id', 'name', 'document_url');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'first_name', 'last_name', 'unique_code');
    }
}
