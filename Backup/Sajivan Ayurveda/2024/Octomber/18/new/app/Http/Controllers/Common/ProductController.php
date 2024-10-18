<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\CustomerProduct;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Setting;
use PDF;

class ProductController extends Controller
{
    private $products;

    public function __construct()
    {
        $this->middleware('auth');
        $page = "Product";
        view()->share('page', $page);
        $this->products = resolve(Product::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.product.index');
    }

    public function productAjaxList(Request $request)
    {
        $search = $request->search;
        $productList = $this->products
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();
        return view('admin.product.ajax_list', compact('productList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;

        $productList = $this->products
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhere('sku', 'like', '%' . $search . '%')
                        ->orWhere('quantity', 'like', '%' . $search . '%')
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('category', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Product.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Product Name', 'SKU', 'Category', 'Quantity', 'Created By');
            $callback = function () use ($productList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($productList as $product) {
                    $category = isset($product->category) ? $product->category->name : "-";
                    $createdBy = isset($product->userDetail) ? $product->userDetail->name : "-";
                    fputcsv($file, array($product->name, $product->sku, $category, $product->quantity, $createdBy));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.product', ['productList' => $productList, 'setting' => $setting]);
            return $pdf->download('Product.pdf');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $product = new Product();
        $product->created_by = auth()->user()->id;
        $product->name = $request->name;
        $product->category_id = $request->category;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->without_tax_price = $request->without_tax_price;
        $product->mfg_date = $request->mfg_date;
        $product->batch_number = $request->batch_number;
        $product->mfg_lic_number = $request->mfg_lic_number;
        $product->medicine_type = $request->medicine_type;
        $product->powder_unit = $request->powder_unit;
        $product->tablet_unit = $request->tablet_unit;
        $product->capsule_unit = $request->capsule_unit;
        $product->hsm_sac = $request->hsm_sac;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('product_image', $newFilename, 'public');
            $product->image = $pathLogo;
        }
        $product = $product->save();
        if ($product) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Product',
                'description' => auth()->user()->name . " created a product named '" . $request->name . "'"
            ]);
            return redirect()->route('product.index')->with('success', 'Product has been created successfully.');
        }
        return redirect()->route('product.create')->with('error', 'Something went wrong.');
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
        $product = Product::where('id', $id)->first();
        $categories = Category::get();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateProductRequest $request, string $id)
    {
        // dd($request->all(), $id);
        $product = Product::where('id', $id)->first();
        if ($product) {
            $product->created_by = auth()->user()->id;
            $product->name = $request->name;
            $product->sku = $request->sku;
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->description = $request->description;
            $product->stock = $request->stock;
            $product->without_tax_price = $request->without_tax_price;
            $product->mfg_date = $request->mfg_date;
            $product->batch_number = $request->batch_number;
            $product->mfg_lic_number = $request->mfg_lic_number;
            $product->status = $request->status == "on" ? 1 : 0;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $pathLogo = $file->storeAs('product_image', $newFilename, 'public');
                $product->image = $pathLogo;
            }
            $update = $product->save();
            if ($update) {
                Log::create([
                    'user_id' => auth()->user()->id,
                    'module' => 'Product',
                    'description' => auth()->user()->name . " updated a product named '" . $request->name . "'"
                ]);
                return redirect()->route('product.index')->with('success', 'Product has been updated successfully.');
            }
            return redirect()->route('product.edit', $id)->with('error', 'Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->products->find($id);
        $delete = $product->delete();
        if ($delete) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Product',
                'description' => auth()->user()->name . " Deleted a Product named '" . $product->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Product has been deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }

    public function removeProductImage(Request $request)
    {
        $id = $request->id;
        $column = $request->type;
        $product = Product::find($id);
        $product->$column = null;
        $product->save();
        return $product;
    }

    public function getProduct(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);
        if ($product) {
            return response()->json(['status' => 1, 'data' => $product], 200);
        }
        return response()->json(['status' => 1, 'message' => "Product Not Found."], 200);
    }
    public function removeProductInvoice(Request $request)
    {
        $id = $request->id;
        $product = CustomerProduct::find($id);
        if ($product) {
            $product->invoice = null;
            $update = $product->save();
            if ($update) {
                return response()->json(['status' => 1, 'message' => "Product Invoice has been removed successfully."], 200);
            }
            return response()->json(['status' => 0, 'message' => "Something went to wrong."], 500);
        }
        return response()->json(['status' => 0, 'message' => "Something went to wrong."], 500);
    }
}
