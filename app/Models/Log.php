<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source',
        'source_id',
        'message'
    ];

    public static $TABLE_USERS = 'users';
    public static $TABLE_LOGS = 'logs';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
