<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'category';

    protected $guarded = ['id'];

    public function categoryDetail(){
        return $this->hasOne(Category::class,'id','parent_category_id');
    }
    public function childCategoryDetails(){
        return $this->hasMany(Category::class,'parent_category_id','id');
    }
}
