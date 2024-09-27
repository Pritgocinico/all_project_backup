<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\DoctorPrice;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPdfDetail;
use App\Models\OrderProductPdfDetail;
use Carbon\Carbon;
use Nnjeim\World\World;
use Mpdf\Mpdf;

class OrderController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function orders(Request $request)
    {
        $orders = Order::orderBy('id', 'DESC')->get();
        return view('admin.orders.orders', compact('orders'));
    }

    public function create()
    {
        $usaCities = World::cities([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaCities->success) {
            $cities = $usaCities->data;
        }
        $usaStates = World::states([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaStates->success) {
            $states = $usaStates->data;
        }
        $products = Product::all();
        return view('admin.orders.create', compact('states', 'products'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if (Order::count() > 0) {
            $id = Order::all()->last()->id;
        } else {
            $id = 0;
        }
        $id = $id + 1;
        $str_length = 5;
        $date = Carbon::now();
        $monthName = $date->format('M');
        $str = substr("00000{$id}", -$str_length);
        $number = str_pad($id, 5, '0', STR_PAD_LEFT);
        $order_id = 'MR-' . $monthName . '-' . $number;
        if ($request->has('save_info') && $request) {
            $save_info = 1;
        } else {
            $save_info = 0;
        }
        $order = new Order();
        $order->order_id            = $order_id;
        $order->user_id             = Auth::guard('admin')->user()->id;
        $order->email               = $request->email;
        $order->phone               = $request->phone;
        $order->first_name          = $request->first_name;
        $order->last_name           = $request->last_name;
        $order->address             = $request->address;
        $order->billing_address     = $request->billing_address;
        $order->address1            = $request->address1;
        $order->billing_address1    = $request->billing_address1;
        $order->city                = $request->city;
        $order->billing_city        = $request->billing_city;
        $order->state               = $request->state;
        $order->billing_state       = $request->billing_state;
        $order->zip_code            = $request->zip_code;
        $order->billing_zip_code    = $request->billing_zip_code;
        $order->total               = $request->total;
        $order->save_info           = $save_info;
        $order->save();

        $package = ProductPackage::where('id', $request->product_package)->first();
        $product = Product::where('id', $request->product)->first();
        $doctor = DoctorPrice::where('doctor_id', Auth()->user()->id)
            ->where('package_id', $request->product_package)
            ->first();
        if (isset($product)) {

            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->product_id = $request->product;
            $order_item->quantity = $request->quantity;
            $order_item->price = isset($doctor) ? $doctor->price : $request->product_price;
            $order_item->total = $request->total;
            $order_item->product_name = $product->productname;
            $order_item->product_image = $product->singleimage;
            $order_item->package_name = $package->varient_name;
            $order_item->product_price = $product->price;
            $order_item->save();
        }

        return redirect()->route('admin.orders')->with('success', 'Order Placed successfully');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $order_item = OrderItem::where('order_id', $id)->first();
        $usaCities = World::cities([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaCities->success) {
            $cities = $usaCities->data;
        }
        $usaStates = World::states([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaStates->success) {
            $states = $usaStates->data;
        }
        $products = Product::all();

        return view('admin.orders.edit', compact('order', 'order_item', 'states', 'products'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $order = Order::find($id);
        $order->user_id             = Auth::guard('admin')->user()->id;
        $order->email               = $request->email;
        $order->phone               = $request->phone;
        $order->first_name          = $request->first_name;
        $order->last_name           = $request->last_name;
        $order->address             = $request->address;
        $order->billing_address     = $request->billing_address;
        $order->address1            = $request->address1;
        $order->billing_address1    = $request->billing_address1;
        $order->city                = $request->city;
        $order->billing_city        = $request->billing_city;
        $order->state               = $request->state;
        $order->billing_state       = $request->billing_state;
        $order->zip_code            = $request->zip_code;
        $order->billing_zip_code    = $request->billing_zip_code;
        $order->total               = $request->total;
        $order->update();

        $package = ProductPackage::where('id', $request->product_package)->first();
        $product = Product::where('id', $request->product)->first();
        $order_item = OrderItem::where('order_id', $id)->first();
        $order_item->product_id = $request->product;
        $order_item->quantity = $request->quantity;
        $order_item->price = $request->product_price;
        $order_item->total = $request->total;
        $order_item->product_name = $product->productname;
        $order_item->product_image = $product->singleimage;
        $order_item->package_name = isset($package) ? $package->varient_name : "";
        $order_item->product_price = $product->price;
        $order_item->update();

        return redirect()->route('admin.orders')->with('success', 'Order Updated successfully');
    }

    public function productPackage(Request $request, $id)
    {
        $product_packages = ProductPackage::where('product_id', $id)->get();
        return response()->json($product_packages);
    }

    public function packagePrice(Request $request, $id)
    {
        $package_data = ProductPackage::where('id', $id)->first();
        return response()->json($package_data);
    }

    public function changeStatus(Request $request)
    {
        $order = Order::where('id', $request->order_id)->first();
        $order->status = $request->status;
        $order->update();
        return response()->json(['success' => true], 200);
        // dd($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        $order_items = OrderItem::where('order_id', $id)->delete();

        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully');
    }

    public function viewOrder(Request $request, $id = NULL)
    {
        $order = Order::where('id', $id)->orderBy('id', 'DESC')->first();
        $order_item = OrderItem::where('order_id', $id)->get();
        return view('admin.orders.view_order', compact('order', 'order_item'));
    }

    public function generateInvoice(Request $request, $id)
    {
        if($request->type == 'function'){
            $this->orderDetailPdfFunction($request);
        }
        $orders = Order::with('orderItemDetail','orderItemDetail.orderProductItemDetail', 'orderItemDetail.productDetail','orderPdfDetail')->findOrFail($id);
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            // 'orientation' => 'L'
        ]);
        if (isset($orders)) {
            $viewFile = view('admin.pdf.invoice_pdf', compact('orders'))->render();
            $pdf->WriteHTML($viewFile);
        } else {
            $pdf->WriteHTML('<h5 style="text-align: center;">No Order Available.</h5>');
        }
        $pdf->Output($orders->order_id . '-Invoice.pdf', 'D');
    }

    public function generatePackageSlip(Request $request, $id)
    {
        $orders = Order::with('orderItemDetail', 'orderItemDetail.productDetail')->findOrFail($id);
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            // 'orientation' => 'L'
        ]);
        if (isset($orders)) {
            $viewFile = view('admin.pdf.packageslip_pdf', compact('orders'))->render();
            $pdf->WriteHTML($viewFile);
        } else {
            $pdf->WriteHTML('<h5 style="text-align: center;">No Order Available.</h5>');
        }
        $pdf->Output($orders->order_id . '-packgeslip.pdf', 'D');
    }

    public function orderDetailPdfForm($id)
    {
        $orderItem = OrderItem::with('productDetail')->where('order_id', $id)->get();
        $orderPdf = OrderPdfDetail::with('orderProductPdfDetail')->where('order_id', $id)->first();
        return view('admin.orders.order_pdf_detail', compact('orderItem', 'id', 'orderPdf'));
    }

    public function submitOrderDetailPdfForm(Request $request)
    {
        $this->orderDetailPdfFunction($request);
        
        return redirect()->route('admin.orders')->with('success', 'Order Pdf Detail Added successfully');
    }
    public function orderDetailPdfFunction($request){
        $orderPdf = OrderPdfDetail::with('orderProductPdfDetail')->where('order_id', $request->order_id)->first();
        $orderDetail = Order::where('id',$request->order_id)->first();
        $data = [
            'p_o_number' => $request->p_o_number,
            'terms' => $request->terms,
            'rep' => $request->rep,
            'account_number' => $request->account_number,
            'requested_ship' => $request->requested_ship,
            'ship_via' => $request->ship_via,
        ];
        if (isset($orderPdf)) {
            $log = $orderDetail->order_id. " Order Pdf Detail Updated Successfully";
            $where['order_id'] = $request->order_id;
            $insert = OrderPdfDetail::where($where)->update($data);
        } else {
            $data['order_id'] = $request->order_id;
            $log = $orderDetail->order_id. " Order Pdf Detail inserted Successfully";
            $insert = OrderPdfDetail::create($data);
        }
        if ($insert) {
            if($request->product_id !== null){
                foreach ($request->product_id as $key => $product) {
                    $updateDetail = [
                        'product_id' => $product,
                        'package_name' => $request->package_name[$key],
                        'lot_number' => $request->lot_number[$key],
                        'lot' => $request->lot[$key],
                    ];
                    if (isset($orderPdf)) {
                        $where = [
                            'order_pdf_detail_id' => $orderPdf->id,
                            'order_id'=>$orderPdf->order_id,
                            'order_item_id' => $request->order_item_id[$key],
                        ];
                        OrderProductPdfDetail::where($where)->update($updateDetail);
                    } else {
                        $updateDetail['order_pdf_detail_id'] = $insert->id;
                        $updateDetail['order_id'] = $insert->order_id;
                        $updateDetail['order_item_id'] = $request->order_item_id[$key];
                        
                        OrderProductPdfDetail::create($updateDetail);
                    }
                }
            }
            UserLogHelper::storeLog('order',$log);
        }
    }
}
