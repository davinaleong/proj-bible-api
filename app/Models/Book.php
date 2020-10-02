<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function translation()
    {
        return $this->belongsTo('App\Models\Translation');
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
}
