<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    public static $TABLE_USERS = 'users';
    public static $TABLE_LOGS = 'logs';
    public static $TABLE_COPYRIGHTS = 'copyrights';
    public static $TABLE_TRANSLATIONS = 'translations';
    public static $TABLE_BOOKS = 'books';
    public static $TABLE_CHAPTERS = 'chapters';
    public static $TABLE_VERSES = 'verses';
}
