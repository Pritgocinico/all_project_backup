<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Image;
use App\Models\TitleContent;
use App\Models\ProductPackage;
use App\Models\DosageForm;
use App\Models\Category;
use App\Models\Lot;
use Illuminate\Support\Str;

class ProductDetailController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }

    public function create()
    {
        $product = new Product();
        $categories = Category::all(); // Assuming you have a Category model
        $dosageForms = DosageForm::all();
        // Display the form to create a new product detail
        return view('admin.product.create', compact('product', 'categories', 'dosageForms'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'sku' => 'required|string|max:255',
            'productname' => 'required|string|max:255',
            'inactive_ingredients' => 'nullable|string',
            'unit_size_type' => 'required|string|max:255',
            'package_size' => 'required|string|max:255',
            'product_code' => 'required|string|max:255',
            'ndc' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'slug' => 'required|unique:products',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'dosageForms' => 'nullable|array',
            'dosageForms.*' => 'exists:dosage_forms,id',
            'single_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add this line
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the allowed image types and size
            'titles' => 'nullable|array',      // New validation for titles
            'contents' => 'nullable|array',    // New validation for contents
            'titles.*' => 'nullable|string',   // New validation for individual titles
            'contents.*' => 'nullable|string', // New validation for individual contents
        ]);

        if($request->has('single_image') && $request->file('single_image') != null){
            $image1 = $request->file('single_image');
            $destinationPath1 = 'public/storage/images/';
            $rand1=rand(1,100);
            $docImage1 = date('YmdHis').$rand1."." . $image1->getClientOriginalExtension();
            $image1->move($destinationPath1, $docImage1);
            $img1=$docImage1;
        }else{
            $img1 = '';
        }
        // Create a new Product instance
        $product = Product::create([
            'sku' => $request->input('sku'),
            'productname' => $request->input('productname'),
            'inactive_ingredients' => $request->input('inactive_ingredients', ''),
            'unit_size_type' => $request->input('unit_size_type'),
            'package_size' => $request->input('package_size'),
            'product_code' => $request->input('product_code'),
            'meta_title' => $request->input('meta_title'),
            'keyword' => $request->input('keyword'),
            'description' => $request->input('description'),
            'slug' => str_slug($request->input('slug'),'-'),
            'ndc' => $request->input('ndc'),
            'storage' => $request->input('storage'),
            'price' => $request->input('price'),
            'tags' => $request->input('tags'),
            'vial_weight' => $request->input('vial_weight'),
            'medical_necessity' => $request->input('medical_necessity'),
            'preservative_free' => $request->input('preservative_free'),
            'sterile_type' => $request->input('sterile_type'),
            'controlled_state' => $request->input('controlled_state'),
            'cold_ship' => $request->input('cold_ship'),
            'max_order_qty' => $request->input('max_order_qty'),
            'stock' => $request->input('stock'),
            'single_image' => $img1, // Add this line

        ]);
        

        // Handle image uploads if there are any
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // $path = $image->store('images', 'public');
                if($request->has('images') && $request->file('images') != null){
                    $image1 = $image;
                    $destinationPath = 'public/storage/images';
                    $rand=rand(1,100);
                    $docImage = date('YmdHis'). $rand."." . $image1->getClientOriginalExtension();
                    $image1->move($destinationPath, $docImage);
                    $path=$docImage;
                }
                // Create a new Image instance and associate it with the product
                $product->images()->create([
                    'path' => $path,
                ]);
            }
        }

        // Add Product Package
        foreach ($request->varient_name as $key => $variant) {
            $productPackage = new ProductPackage();
            $productPackage->product_id = $product->id;
            $productPackage->varient_name = $variant;
            $productPackage->vial_price = $request->vial_price[$key];
            $productPackage->vial_quantity = $request->vial_quantity[$key];
            $productPackage->vial_total = $request->vial_total[$key];
            $productPackage->save();
        }
        
        if ($request->filled('titles') && $request->filled('contents')) {
            $titleContentPairs = array_combine($request->input('titles'), $request->input('contents'));
    
            foreach ($titleContentPairs as $title => $content) {
                // Check if title is not null before creating TitleContent
                if ($title !== null) {
                    // Create a new TitleContent instance and associate it with the product
                    $product->titleContents()->create([
                        'title' => $title,
                        'content' => $content,
                    ]);
                }
            }
        }
        
        if ($request->filled('categories')) {
            $product->categories()->attach($request->input('categories'));
        }

        // Attach dosage forms to the product
        if ($request->filled('dosageForms')) {
            $product->dosageForms()->attach($request->input('dosageForms'));
        }
        // Redirect back or to a success page
        return redirect()->route('admin.product-details.index')->with('success', 'Product created successfully');
    }
    public function index()
    {
        $products = Product::all(); // Fetch all products, adjust this based on your actual model/query

        return view('admin.product.index', compact('products'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
    
        // Delete associated titleContents
        $product->titleContents()->delete();
    
        // Delete associated images
        $product->images()->delete();
        $lots = Lot::where('product_id',$id)->delete();
        // Now delete the product
        $product->delete();
    
        return redirect()->route('admin.product-details.index')->with('success', 'Product deleted successfully');
    }
    

    public function edit($id)
    {
        $product = Product::where('slug', $id)->first();
        $product_packages = ProductPackage::where('product_id', $product->id)->get();
        $categories = Category::all(); // Assuming you have a Category model
        $dosageForms = DosageForm::all(); // Assuming you have a DosageForm model
    
        return view('admin.product.edit', compact('product', 'categories', 'dosageForms', 'product_packages'));
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'sku' => 'required',
            'productname' => 'required',
            'inactive_ingredients' => 'nullable',
            'unit_size_type' => 'nullable',
            'package_size' => 'nullable',
            'product_code' => 'nullable',
            'ndc' => 'nullable',
            'storage' => 'nullable',
            // 'slug' => 'required|unique:products',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'dosageForms' => 'nullable|array',
            'dosageForms.*' => 'exists:dosage_forms,id',
            'single_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Add validation rules for images
            'titles' => 'nullable|array',      // New validation for titles
            'contents' => 'nullable|array',    // New validation for contents
            'titles.*' => 'nullable|string',   // New validation for individual titles
            'contents.*' => 'nullable|string', // New validation for individual contents
        ]);
    
        $product = Product::where('slug', $id)->first();
        if($request->has('single_image') && $request->file('single_image') != null){
            $image1 = $request->file('single_image');
            $destinationPath1 = 'public/storage/images/';
            $rand1=rand(1,100);
            $docImage1 = date('YmdHis').$rand1."." . $image1->getClientOriginalExtension();
            $image1->move($destinationPath1, $docImage1);
            $img1=$docImage1;
        }else{
            $img1 = $product->single_image;
        }
        $product->update([
            'sku' => $request->sku,
            'productname' => $request->productname, // Change 'title' to 'productname'
            'inactive_ingredients' => $request->inactive_ingredients,
            'unit_size_type' => $request->unit_size_type,
            'package_size' => $request->package_size,
            'product_code' => $request->product_code,
            'meta_title' => $request->meta_title,
            'keyword' => $request->keyword,
            'description' => $request->description,
            'slug' => str_slug($request->slug,'-'),
            'ndc' => $request->ndc,
            'tags' => $request->tags,
            'storage' => $request->storage,
            'price' => $request->price,
            'single_image' => $img1,
            'vial_weight' => $request->input('vial_weight'),
            'medical_necessity' => $request->input('medical_necessity'),
            'preservative_free' => $request->input('preservative_free'),
            'sterile_type' => $request->input('sterile_type'),
            'controlled_state' => $request->input('controlled_state'),
            'cold_ship' => $request->input('cold_ship'),
            'max_order_qty' => $request->input('max_order_qty'),
            'stock' => $request->input('stock'),
         
        ]);

        if(!blank($request->varient_name)){
            // dd($product->id, $id);
            // Update Product Package
            $productPackage = ProductPackage::where('product_id',$product->id)->delete();
            foreach ($request->varient_name as $key => $variant) {
                $productPackage = new ProductPackage();
                $productPackage->product_id = $product->id;
                $productPackage->varient_name = $variant;
                $productPackage->vial_price = $request->vial_price[$key];
                $productPackage->vial_quantity = $request->vial_quantity[$key];
                $productPackage->vial_total = $request->vial_total[$key];
                $productPackage->save();
            }
        }
    
        // Handle image update if images are provided
        if ($request->hasFile('images')) {
            // Delete existing images associated with the product (optional, depending on your requirements)
            // $product->images()->delete();
    
            // Upload and associate new images
            foreach ($request->file('images') as $image) {
                // $path = $image->store('images', 'public');
                if($request->has('images') && $request->file('images') != null){
                    $image1 = $image;
                    $destinationPath = 'public/storage/images';
                    $rand=rand(1,100);
                    $docImage = date('YmdHis'). $rand."." . $image1->getClientOriginalExtension();
                    $image1->move($destinationPath, $docImage);
                    $path=$docImage;
                }
                // Create a new Image instance and associate it with the product
                $product->images()->create([
                    'path' => $path,
                ]);
            }
        }
        if ($request->filled('titles') && $request->filled('contents')) {
            $titleContentPairs = array_combine($request->input('titles'), $request->input('contents'));
    
            // Get existing titleContents associated with the product
            $existingTitleContents = $product->titleContents()->get();
    
            // Delete titleContents that are not present in the submitted data
            foreach ($existingTitleContents as $existingTitleContent) {
                if (!in_array($existingTitleContent->title, $request->input('titles'))) {
                    $existingTitleContent->delete();
                }
            }
    
            // Create or update titleContents from the submitted data
            foreach ($titleContentPairs as $title => $content) {
                $product->titleContents()->updateOrCreate(
                    ['title' => $title],
                    ['content' => $content]
                );
            }
        } else {
            // If titles and contents are not provided, delete all existing titleContents
            $product->titleContents()->delete();
        }

        if ($request->filled('categories')) {
            $product->categories()->sync($request->input('categories'));
        } else {
            // If no categories are selected, detach all existing categories
            $product->categories()->detach();
        }

        // Sync dosage forms for the product
        if ($request->filled('dosageForms')) {
            $product->dosageForms()->sync($request->input('dosageForms'));
        } else {
            // If no dosage forms are selected, detach all existing dosage forms
            $product->dosageForms()->detach();
        }

        return redirect()->route('admin.product-details.index')->with('success', 'Product updated successfully');
    }
    public function show($id)
    {
        $product = Product::where('slug', $id)->first();
        if(isset($product)){

            $product_packages = ProductPackage::where('product_id', $product->id)->get();
            $isDoctor = true;
            $images = $product->images;
            $titleContents = $product->titleContents;
            $accordionItems = $titleContents->map(function ($item) {
                return [
                    'title' => $item->title,
                    'content' => $item->content,
                    'target' => Str::slug($item->title),
                ];
            })->toArray();
            return view('admin.product.show', compact('product', 'product_packages', 'images', 'titleContents', 'isDoctor','accordionItems'));
        }
        return redirect()->route('admin.product-details.index');
    }
    public function getvariantPrice($id){
        
        $price = ProductPackage::where('id',$id)->first();
        return $price->vial_total;
    }

    public function removeProductPackage(Request $request){
        $id = $request->id;
        $deletePackage = ProductPackage::where('id',$id)->delete();
        return $deletePackage;
    }

    public function removeProductImage(Request $request){
        $id = $request->id;
        $delete = Image::where('id',$id)->delete();
        return $delete;
    }
}