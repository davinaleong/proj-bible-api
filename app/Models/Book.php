<?php

namespace App\Models;

use App\Events\BookCreated;
use App\Events\BookDeleted;
use App\Events\BookUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'translation_id',
        'chapter_limit',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'number' => 'integer'
    ];

    protected $dispatchesEvents = [
        'created' => BookCreated::class,
        'updated' => BookUpdated::class,
        'deleted' => BookDeleted::class
    ];

    private $dateFormats = [
        'db' => 'Y-m-d H:i:s',
        'show' => 'H:i:s d-m-Y'
    ];

    public static function getBook(Translation $translation, int $number)
    {
        return Book::where([
            'translation_id' => $translation->id,
            'number' => $number
        ])->first();
    }

    public function translation()
    {
        return $this->belongsTo('App\Models\Translation');
    }

    public function chapters()
    {
        return $this->hasMany('App\Models\Chapter');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function getTranslationAbbr()
    {
        return $this->translation ? $this->translation->abbr : '';
    }

    public function getCreatorName()
    {
        return $this->creator ? $this->creator->name : '';
    }

    public function getUpdaterName()
    {
        return $this->updater ? $this->updater->name : '';
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
