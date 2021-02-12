<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    //
    protected $table = 'products';
    public $timestamps = false;
    protected $fillable = [
        'category', 'name', 'short_desc', 'description', 'images', 'status','created_by'
    ];
    public function categoryrl()
    {
        return $this->hasOne('App\Category', 'id', 'category');
    }
}
