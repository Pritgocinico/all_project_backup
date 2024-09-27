<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\ParentCategory;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface 
{
    public function getAllParentCategory(){
        return Category::whereNull('parent_category_id')->latest()->get();
    }

    public function store($data){
        return Category::create($data);
    }

    public function getAllCategory($search){
        return Category::with('categoryDetail')
        ->when($search,function($query) use($search){
            $query->where('name', 'like', '%'.$search.'%');
        })->latest()->paginate(15);
    }

    public function getDetailById($id){
        return Category::where('id',$id)->first();
    }

    public function update($data,$where){
        return Category::where($where)->update($data);
    }

    public function delete($id){
        return Category::where('id',$id)->delete();
    }

    public function checkCategoryIsExist($id){
        return Category::where('parent_category_id',$id)->count();
    }

    public function getAllCategoryWithChild(){
        return Category::with('childCategoryDetails')->whereNull('parent_category_id')->latest()->get();
    }

    public function getAllCategoryExport($search){
        return Category::with('categoryDetail')
        ->when($search,function($query) use($search){
            $query->where('name', 'like', '%'.$search.'%');
        })->latest()->get();
    }
    public function getAllData(){
        return Category::latest()->get();
    }

}