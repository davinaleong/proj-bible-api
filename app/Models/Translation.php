<?php

namespace App\Models;

use App\Events\TranslationCreated;
use App\Events\TranslationDeleted;
use App\Events\TranslationUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbr',
        'copyright_id',
        'created_by',
        'updated_by'
    ];

    protected $dispatchesEvents = [
        'created' => TranslationCreated::class,
        'updated' => TranslationUpdated::class,
        'deleted' => TranslationDeleted::class
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
}
