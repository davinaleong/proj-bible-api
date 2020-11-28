<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'number', 'verse_limit', 'created_by', 'updated_by'];

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
}
