<?php

namespace App\Models;

use App\Events\TranslationCreated;
use App\Events\TranslationDeleted;
use App\Events\TranslationUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'created' => TranslationCreated::class,
        'updated' => TranslationUpdated::class,
        'deleted' => TranslationDeleted::class
    ];

    private $dateFormats = [
        'db' => 'Y-m-d H:i:s',
        'show' => 'H:i:s d-m-Y'
    ];

    public function copyright()
    {
        return $this->belongsTo('App\Models\Copyright');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }

    public function getCopyrightText()
    {
        return $this->copyright ? $this->copyright->text : '';
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
