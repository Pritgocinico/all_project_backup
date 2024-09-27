<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\CategoryRepositoryInterface;
use App\Http\Requests\CreateCategoryRequest;
use App\Interfaces\ProductRepositoryInterface;


use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository, $productRepository = "";
    public function __construct(CategoryRepositoryInterface $categoryRepository, ProductRepositoryInterface $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Product Category";
        return view('admin.category.index',compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategory = $this->categoryRepository->getAllParentCategory();
        $page = "Category Create";
        return view('admin.category.create', compact('parentCategory','page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        $data = [
            'name' => $request->name,
            'parent_category_id' => $request->parent_category_id,
        ];
        if ($request->hasfile('category_image')) {
            $file = $request->file('category_image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/category/', $filename);
            $data['category_image'] = 'category/' . $filename;
        }
        $insert = $this->categoryRepository->store($data);
        if ($insert) {
            $log = 'Category (' . ucfirst($request->name) . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Category', $log);
            return redirect('admin/category')->with('success', 'Category Created Successfully.');
        }
        return redirect('admin/category/create')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = $this->categoryRepository->getDetailById($id);
        $parentCategory = $this->categoryRepository->getAllParentCategory();
        $page = "Edit Category";
        return view('admin.category.edit', compact('parentCategory', 'category','page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCategoryRequest $request, string $id)
    {
        $data = [
            'name' => $request->name,
            'parent_category_id' => $request->parent_category_id,
        ];
        if ($request->hasfile('category_image')) {
            $file = $request->file('category_image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/category/', $filename);
            $data['category_image'] = 'category/' . $filename;
        }
        $where = ['id' => $id];
        $update = $this->categoryRepository->update($data, $where);

        if ($update) {
            $log = 'Category (' . ucfirst($request->name) . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Category', $log);
            return redirect('admin/category')->with('success', 'Category Updated Successfully.');
        }
        return redirect('admin/category/' . $id . '/edit')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = $this->categoryRepository->getDetailById($id);
        $checkCategory = $this->categoryRepository->checkCategoryIsExist($id);
        $product = $this->productRepository->checkCategoryInProduct($id);
        if ($checkCategory == 0 && $product == 0) {
            $delete = $this->categoryRepository->delete($id);
            if ($delete) {
                $log = 'Category (' . ucfirst($category->name) . ') Deleted by ' . ucfirst(Auth()->user()->name);
                UserLogHelper::storeLog('Category', $log);
                return response()->json(['data' => '', 'message' => 'Category Deleted Successfully', 'status' => 1], 200);
            }
            return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
        }
        return response()->json(['data' => '', 'message' => 'This category is already used.', 'status' => 1], 500);
    }

    public function ajaxList(Request $request)
    {
        $search = $request->search;
        $categoryList = $this->categoryRepository->getAllCategory($search);
        return view('admin.category.ajax_list', compact('categoryList'));
    }
}
