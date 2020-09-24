<?php

namespace App\Models;

use App\Events\CopyrightCreated;
use App\Events\CopyrightUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copyright extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'text'
    ];

    protected $dispatchesEvents = [
        'created' => CopyrightCreated::class,
        'updated' => CopyrightUpdated::class
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getUserName()
    {
        return $this->user ? $this->user->name : '';
    }
}
