<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use DB;
use Exception;
use Auth;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_request_id', 'sender_id', 'receiver_id', 'attachment', 'message', 'type', 'details', 'is_read'];

    protected function getSenderNameAttribute()
    {
        try {
            return User::where('id', $this->sender_id)->pluck('name')->first();
        } catch (Exception $e) {
            return null;
        }
    }

    protected function getReceiverNameAttribute()
    {
        try {
            return User::where('id', $this->receiver_id)->pluck('name')->first();
        } catch (Exception $e) {
            return null;
        }
    }

    protected function getSenderImageAttribute()
    {
        try {
            $image = User::where('id', $this->sender_id)->pluck('profile_photo_path')->first();
            return asset('storage/' . str_replace('public/', '', $image));
        } catch (Exception $e) {
            return null;
        }
    }

    protected function getReceiverImageAttribute()
    {
        try {
            $image = User::where('id', $this->receiver_id)->pluck('profile_photo_path')->first();
            return asset('storage/' . str_replace('public/', '', $image));
        } catch (Exception $e) {
            return null;
        }
    }

    public function getFileUrlAttribute()
    {
        if (isset($this->attributes['attachment'])) {
            return $this->attributes['file_url'] = asset('storage/' . str_replace('public/', '', $this->attributes['attachment']));
        }
    }

    public function getTreatmentAttribute()
    {
        try {
            $treatment_id = UserRequest::where('id', $this->user_request_id)->pluck('treatment_id')->first();
            if ($treatment_id != null)
                return Treatment::where('id', $treatment_id)->pluck('title')->first();
            return 'N/A';
        } catch (Exception $e) {
            return null;
        }
    }

    public function getMessageStatusAttribute()
    {
        try {
            if ($this->sender_id == Auth::id())
                return 'Sent';
            else
                return 'Received';
        } catch (Exception $e) {
            return null;
        }
    }

    public function UserRequest()
    {
        return $this->hasOne(UserRequest::class, 'id', 'user_request_id')->select('id', 'unique_code', 'posted_on');
    }
}
