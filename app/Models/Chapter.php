<?php

namespace App\Models;

use App\Events\ChapterCreated;
use App\Events\ChapterDeleted;
use App\Events\ChapterUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'book_id',
        'verse_limit',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'book_id' => 'integer',
        'number' => 'integer',
        'verse_limit' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    protected $dispatchesEvents = [
        'created' => ChapterCreated::class,
        'updated' => ChapterUpdated::class,
        'deleted' => ChapterDeleted::class
    ];

    private $dateFormats = [
        'db' => 'Y-m-d H:i:s',
        'show' => 'H:i:s d-m-Y'
    ];

    public static function getChapter(Book $book, int $number)
    {
        return Chapter::where([
            'book_id' => $book->id,
            'number' => $number
        ])->first();
    }

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function verses()
    {
        return $this->hasMany('App\Models\Verse');
    }

    public function getBookName()
    {
        return $this->book ? $this->book->name : '';
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
