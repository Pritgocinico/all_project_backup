<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Helpers\UtilityHelper;
use PDF;

class ProductController extends Controller
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
        $page = "Product List";
        $categoryList = $this->categoryRepository->getAllData();
        return view('admin.product.index',compact('page','categoryList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $page = "Create Product";
        $lastInsertId = $this->productRepository->getLastInsertId()+1;
        return view('admin.product.create', compact('categoryList','lastInsertId','page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'product_name' => $request->name,
            'sku_name' => $request->sku,
            'category_id' => $request->category_id,
            'c_gst' => $request->c_gst,
            's_gst' => $request->s_gst,
            'description' => $request->description,
            'product_type' => $request->product_type
        ];
        if ($request->hasfile('product_image')) {
            $file = $request->file('product_image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/product/image', $filename);
            $data['product_image'] = 'product/image/' . $filename;
        }
        $insert = $this->productRepository->store($data);
        if ($insert) {
            $log = 'Product (' .ucfirst($insert->product_name) . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Product', $log);
            foreach ($request->sku_name as $key => $sku) {
                $variant = [
                    'sku_name' => $sku,
                    'capacity' => $request->capacity[$key],
                    'price' => $request->price[$key],
                    'price_without_tax' => $request->price_without_tax[$key],
                    'stock' => $request->stock[$key],
                    'product_id' => $insert->id,
                ];
                $insertItem = $this->productRepository->storeVariant($variant);
            }
            return redirect('admin/product')->with('success', 'Product Created Successfully.');
        }
        return redirect('admin/product/create')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productRepository->getDetailById($id);
        $page = "View Product";
        return view('admin.product.show',compact('product','page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->productRepository->getDetailById($id);
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $page = "Edit Product";
        return view('admin.product.edit', compact('categoryList', 'product','page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'product_name' => $request->name,
            'sku_name' => $request->sku,
            'category_id' => $request->category_id,
            'c_gst' => $request->c_gst,
            's_gst' => $request->s_gst,
            'description' => $request->description,
            'product_type' => $request->product_type,
            'status' => $request->status == "on" ? '1' : '0',
        ];
        if ($request->hasfile('product_image')) {
            $file = $request->file('product_image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/product/image', $filename);
            $data['product_image'] = 'product/image/' . $filename;
        }
        $where = ['id' => $id];
        $update = $this->productRepository->update($data, $where);
        if ($update) {
            $log = 'Product (' .ucfirst($request->name) . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Product', $log);
            $where = ['product_id' => $id];
            if ($request->sku_name !== null) {

                foreach ($request->sku_name as $key => $sku) {
                    $variant = [
                        'sku_name' => $sku,
                        'capacity' => $request->capacity[$key],
                        'price' => $request->price[$key],
                        'price_without_tax' => $request->price_without_tax[$key],
                        'stock' => $request->stock[$key],
                        'product_id' => $id,
                    ];
                    $productVariantExist = $this->productRepository->checkProductVariant($request->ids[$key], $id);
                    if (isset($productVariantExist)) {
                        $where['id'] = $productVariantExist->id;
                        $this->productRepository->updateProductVariant($variant, $where);
                    } else {
                        if ($sku !== null && $request->capacity[$key] !== null && $request->price[$key] !== null && $request->price_without_tax[$key] !== null) {
                            $this->productRepository->storeVariant($variant);
                        }
                    }
                }
            }
            return redirect('admin/product')->with('success', 'Product Updated Successfully.');
        }
        return redirect('admin/product/' . $id . '/edit')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->productRepository->getDetailById($id);
        $delete = $this->productRepository->delete($id);
        if ($delete) {
            $log = 'Product (' .ucfirst($product->product_name) . ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Product', $log);
            return response()->json(['data' => '', 'message' => 'Product Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function ajaxList(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        $categoryId = $request->categoryId;
        $productList = $this->productRepository->getAllProduct($status, $search, 'paginate',$categoryId);
        return view('admin.product.ajax_list', compact('productList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        $productList = $this->productRepository->getAllProduct('', $search, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Product Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Product Name', 'SKU', 'Category Name', 'Status', 'Created At');
            $callback = function () use ($productList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($productList as $product) {
                    $category = isset($product->categoryDetail) ? $product->categoryDetail->name : '';
                    $status = "Inactive";
                    if ($product->status == 1) {
                        $status = "Active";
                    }
                    $date = "";
                    if (isset($product->created_at)) {
                        $date = UtilityHelper::convertFullDateTime($product->created_at);
                    }
                    fputcsv($file, array($product->product_name, $product->sku_name, $category, $status, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {

            // return view('admin.pdf.product',compact('productList'));
            $pdf = PDF::loadView('admin.pdf.product', ['productList' => $productList]);
            return $pdf->download('Product.pdf');
        }
    }

    public function productVariantDelete(Request $request)
    {
        $where = ['id' => $request->id];
        $variant = $this->productRepository->getVariantDetailById($request->id);
        $delete = $this->productRepository->deleteVariant($where);
        if ($delete) {
            $log = 'Product Variant (' . $variant->sku_name . ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Product Variant', $log);
            return response()->json(['data' => '', 'message' => 'Product Variant Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
