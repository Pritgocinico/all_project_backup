<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use PDF;

class EmployeeCategoryController extends Controller
{
    protected $categoryRepository,$productRepository = "";
    public function __construct(CategoryRepositoryInterface $categoryRepository, ProductRepositoryInterface $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function  category(){
        $page = "All Category";
        return view('employee.category.index',compact('page'));
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $categoryList = $this->categoryRepository->getAllCategory($search);
        return view('employee.category.ajax_list',compact('categoryList'));
    }

    public function product(){
        $page = "All Product";
        return view('employee.product.index',compact('page'));
    }

    public function productAjaxList(Request $request){
        $search = $request->search;
        $productList = $this->productRepository->getAllProduct('1',$search,'paginate');
        return view('employee.product.ajax_list',compact('productList'));
    }

    public function exportProductCSV(Request $request){
        $search = $request->search;
        $productList = $this->productRepository->getAllProduct('1',$search,'export');
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
            $pdf = PDF::loadView('admin.pdf.product', ['productList' => $productList]);
            return $pdf->download('Product.pdf');
        }
    }

    public function exportCategoryCSV(Request $request){
        $search = $request->search;
        $categoryList = $this->categoryRepository->getAllCategoryExport($search);
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Category Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Category Name', 'Parent Category Name', 'Created At');
            $callback = function () use ($categoryList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($categoryList as $category) {
                $categoryName = isset($category->categoryDetail) ? $category->categoryDetail->name : '';
                $date = "";

                if($category->created_at !== ""){
                    $date = UtilityHelper::convertDmyWith12HourFormat($category->created_at);
                }
                    fputcsv($file, array($category->name, $categoryName, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.category', ['categoryList' => $categoryList]);
            return $pdf->download('Category.pdf');
        }
    }

    public function productView(string $id){
        $page = "Product Detail";
        $product = $this->productRepository->getDetailById($id);
        return view('employee.product.show',compact('product','page'));
    }
}
