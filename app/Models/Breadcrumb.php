<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breadcrumb extends Model
{
    public static function items($other_items=null)
    {
        $items = [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard')
            ]
        ];

        if ($other_items) {
            foreach($other_items as $item) {
                array_push($items, $item);
            }
        }

        return $items;
    }
}
