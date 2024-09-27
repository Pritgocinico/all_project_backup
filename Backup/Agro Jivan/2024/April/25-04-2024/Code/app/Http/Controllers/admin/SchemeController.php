<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSchemeRequest;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\SchemeRepositoryInterface;
use App\Models\Discount;

class SchemeController extends Controller
{
    protected $schemeRepository, $productRepository = "";
    public function __construct(SchemeRepositoryInterface $schemeRepository, ProductRepositoryInterface $productRepository)
    {
        $this->schemeRepository = $schemeRepository;
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discountType = $this->schemeRepository->getAllDiscountTypeData();
        $page = "Scheme List";
        return view('admin.scheme.index', compact('discountType','page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSchemeRequest $request)
    {
        $data = [
            'discount_type_id' => $request->id,
            'discount_code' => $request->scheme_code,
        ];
        if ($request->id == 1) {
            $data['discount_percentage'] = $request->discount_percentage;
        }
        $insert = $this->schemeRepository->store($data);
        if ($insert) {
            foreach($request->product as $key=>$product){
                $dataItem['product_id'] = $product;
                $dataItem['free_product_id'] = $request->free_product[$key];
                $dataItem['discount_id'] = $insert->id;
                $this->schemeRepository->storeDiscountItem($dataItem);
            }
            $log =  "Scheme ". $request->scheme_code . ' Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Scheme', $log);
            return redirect('admin/scheme')->with('success', 'Discount Created Successfully.');
        }
        return redirect('admin/discount-type-form/'.$request->id)->with('error', 'Something Went To Wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $scheme = $this->schemeRepository->getDetailById($id);
        $productList = $this->productRepository->getAllProduct("", "", 'export');
        $page = "Edit Scheme";
        return view('admin.scheme.edit', compact('scheme','productList','page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data['discount_code'] = $request->scheme_code;
        $data['discount_percentage'] = 0;
        if ($request->discount_type_id == 1) {
            $data['discount_percentage'] = $request->discount_percentage;
        }
        $where['id'] = $id;
        $discountID = $id;
        $update = $this->schemeRepository->update($data,$where);
        if ($update) {
            foreach($request->ids as $key=>$id){
                $discountItem = $this->schemeRepository->getDiscountItemById($id);
                $dataItem['product_id'] = $request->product[$key];
                $dataItem['free_product_id'] = $request->free_product[$key];
                if(isset($discountItem)){
                    $whereItem['id'] =$id;
                    $this->schemeRepository->updateDiscountItem($dataItem,$whereItem);
                }
                else{
                    $dataItem['discount_id'] = $discountID;
                    $this->schemeRepository->storeDiscountItem($dataItem);
                }
            }
            $log =  "Scheme ". $request->code . ' Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Scheme', $log);
            return redirect('admin/scheme')->with('success', 'Discount Updated Successfully.');
        }
        return redirect('admin/scheme/'.$id.'/edit')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $scheme = $this->schemeRepository->getDetailById($id);
        $delete = $this->schemeRepository->delete($id);
        if($delete){
            $log =  "Scheme ". $scheme->code . ' Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Scheme', $log);
            return response()->json(['data' => '', 'message' => 'Scheme Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function discountTypeForm($id)
    {
        $productList = $this->productRepository->getAllProduct("", "", 'export');
        $page = "Create Scheme";
        return view('admin.scheme.create_discount', compact('productList', 'id','page'));
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $schemeList = $this->schemeRepository->getAllScheme($search,"paginate");
        return view('admin.scheme.ajax_list', compact('schemeList'));
    }
    
    public function schemeDetail(Request $request){
        $code = $request->code;
        $schemeList = $this->schemeRepository->getDetailByCode($code);
        return $schemeList;
    }

    public function schemeDetailProduct(Request $request){
        $code = $request->code;
        $productId = $request->productId;
        $scheme = $this->schemeRepository->getDetailByCode($code);
        $schemeDetail = $this->schemeRepository->getSchemeDetailByProduct($productId,$scheme->id);
        return $schemeDetail;
    }
}
