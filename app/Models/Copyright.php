<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copyright extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'text'
    ];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getUserName()
    {
        return $this->user ? $this->user->name : '';
    }
}
