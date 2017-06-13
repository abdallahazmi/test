<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name'];

    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }


    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

}
