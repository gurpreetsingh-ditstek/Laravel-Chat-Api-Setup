<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestFile extends Model
{
    use HasFactory;

    protected $appends = ['file_url','delete_for'];

    public function getFileUrlAttribute()
    {
        if (isset($this->attributes['document_url'])) {
          return $this->attributes['file_url'] = asset('storage/'.str_replace('public/', '' ,$this->attributes['document_url']));
        }
    }

    public function getDeleteForAttribute()
    {
        return $value = 'UserRequestFile';
    }
}