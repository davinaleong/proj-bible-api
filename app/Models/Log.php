<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public static $TABLE_USERS = 'users';
    public static $TABLE_LOGS = 'logs';
    public static $TABLE_COPYRIGHTS = 'copyrights';
    public static $TABLE_TRANSLATIONS = 'translations';
    public static $TABLE_BOOKS = 'books';
    public static $TABLE_CHAPTERS = 'chapters';

    private $dateFormats = [
        'db' => 'Y-m-d H:i:s',
        'show' => 'H:i:s d-m-Y'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getCreatedAt()
    {
        if ($this->created_at) {
            $dt = Carbon::createFromFormat($this->dateFormats['db'], $this->created_at);
            return $dt->format($this->dateFormats['show']);
        } else {
            return '';
        }
    }

    public function getUpdatedAt()
    {
        if ($this->created_at) {
            $dt = Carbon::createFromFormat($this->dateFormats['db'], $this->updated_at);
            return $dt->format($this->dateFormats['show']);
        } else {
            return '';
        }
    }
}
