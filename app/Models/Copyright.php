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
        'name',
        'text',
        'created_by',
        'updated_by'
    ];

    protected $dispatchesEvents = [
        'created' => CopyrightCreated::class,
        'updated' => CopyrightUpdated::class
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
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
