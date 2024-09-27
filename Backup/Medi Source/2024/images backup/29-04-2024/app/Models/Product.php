<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sku',
        'productname',  // Make sure this matches your actual field name
        'inactive_ingredients',
        'unit_size_type',
        'package_size',
        'product_code',
        'ndc',
        'storage',
        'price',
        'single_image',
        'meta_title',
        'keyword',
        'description',
        'slug',
        'tags',
        'stock',
        'vial_weight',
        'medical_necessity',
        'preservative_free',
        'sterile_type',
        'controlled_state',
        'cold_ship',
        'max_order_qty',

    ];
    

    // Define the relationship with images
    public function images()
    {
        return $this->hasMany(Image::class);
    }
   
    
    public function titleContents()
    {
        return $this->hasMany(TitleContent::class);
    }

    public function productPackage()
    {
        return $this->hasMany(ProductPackage::class);
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Define the relationship with dosageForms
    public function dosageForms()
    {
        return $this->belongsToMany(DosageForm::class);
    }

}
