<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }

    public function create()
    {
        $categories = Category::orderBy('id','desc')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    $categoryData = $request->except('img');

    if ($request->hasFile('img')) {
        // $image = $request->file('img');
        // $imageName = time() . '.' . $image->getClientOriginalExtension();
        // $image->storeAs('public/storage/categories', $imageName);
         $image = $request->file('img');
        $destinationPath = 'public/storage/categories';
        $rand=rand(1,100);
        $docImage = date('YmdHis'). $rand."." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $docImage);
        $img=$docImage;
        // Upload the new image
        // $image = $request->file('img');
        $imageName = $docImage;
        $categoryData['img'] = $imageName;
    }

    $category = Category::create($categoryData);

    return redirect()->route('categories.index')->with('success', 'Category created successfully.');
}

    public function index()
    {
        $categories = Category::all();

        return view('admin.categories.index', compact('categories'));
    }

    public function edit($id)
{
    $category = Category::findOrFail($id);
    $categories = Category::all(); // You may adjust this based on your needs
    
    return view('admin.categories.edit', compact('category', 'categories'));
}
public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255',
        // 'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    // Update other fields
    $category->update([
        'name' => $request->name,
        'description' => $request->description,
        'parent_id' => $request->parent_id,
    ]);

    // Update image if a new one is provided
    if ($request->hasFile('img')) {
        // Delete the old image if it exists
        Storage::delete('public/categories/' . $category->img);

        $image = $request->file('img');
        $destinationPath = 'public/storage/categories';
        $rand=rand(1,100);
        $docImage = date('YmdHis'). $rand."." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $docImage);
        $img=$docImage;
        // Upload the new image
        // $image = $request->file('img');
        $imageName = $docImage;
        // $image->storeAs('public/storage/categories', $imageName);

        // Update the 'img' field in the database
        $category->update(['img' => $imageName]);
    }

    return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
}

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }


}
