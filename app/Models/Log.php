<?php

namespace App\Models;

use Carbon\Carbon;
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
    public static $TABLE_COPYRIGHTS = 'copyrights';
    public static $TABLE_TRANSLATIONS = 'translations';

    private $displayDateFormat = 'H:i:s d-m-Y';
    private $dbDateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getCreatedAt()
    {
        if ($this->created_at) {
            $created_at = Carbon::createFromFormat($this->dbDateFormat, $this->created_at);
            return $created_at->format($this->displayDateFormat);
        } else {
            return '';
        }
    }

    public function getUpdatedAt()
    {
        if ($this->created_at) {
            $created_at = Carbon::createFromFormat($this->dbDateFormat, $this->updated_at);
            return $created_at->format($this->displayDateFormat);
        } else {
            return '';
        }
    }
}
