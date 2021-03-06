<?php

namespace App\Models;

use App\Events\VerseCreated;
use App\Events\VerseDeleted;
use App\Events\VerseUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Verse extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'created' => VerseCreated::class,
        'updated' => VerseUpdated::class,
        'deleted' => VerseDeleted::class
    ];

    private $dateFormats = [
        'db' => 'Y-m-d H:i:s',
        'show' => 'H:i:s d-m-Y'
    ];

    public static function getVerse(Chapter $chapter, string $number)
    {
        return Verse::where([
            'chapter_id' => $chapter->id,
            'number' => $number
        ])->first();
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\Chapter');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function truncatePassage()
    {
        return Str::words($this->passage, 50);
    }

    public function getChapterNumber()
    {
        return $this->chapter ? $this->chapter->number : '';
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
