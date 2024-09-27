<?php

namespace App\Http\Controllers;

use App\Models\CardDetail;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\User;
use App\Models\DosageForm;
use App\Models\Order;
use App\Models\DoctorPrice;
use App\Models\OrderItem;
use Auth;
use App\Models\ProductPackage;
use Nnjeim\World\World;
use App\Rules\PasswordMatchesCurrentPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\Compilers\BladeCompiler;

class FrontController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();

        view()->share('setting', $setting);
    }
    public function index()
    {
        $products = Product::all();
        return view('frontend.index', compact('products'));
    }
    public function product()
    {
        $products = Product::all();
        $categories = Category::all();
        $dosageForms = DosageForm::all();
        return view('frontend.pages.product', compact('products', 'categories', 'dosageForms'));
    }
    public function about($id = "")
    {
        $scrollTo  = $id;
        return view('frontend.pages.about', compact('scrollTo'));
    }
    public function surgery()
    {
        return view('frontend.pages.surgery');
    }
    public function orderForm()
    {
        return view('frontend.pages.order-form');
    }
    public function cookiePolicy()
    {
        return view('frontend.pages.cookie_policy');
    }
    public function help()
    {
        return view('frontend.pages.help');
    }
    public function contactus()
    {
        $productList = Product::latest()->get();
        return view('frontend.pages.contactus',compact('productList'));
    }
    public function clinicalTrials()
    {
        return view('frontend.pages.clinical_trials');
    }
    public function catalog()
    {
        $products = Product::all();
        return view('frontend.pages.catalog', compact('products'));
    }
    public function events()
    {
        return view('frontend.pages.events');
    }
    public function productdetail($id)
    {
        // Fetch a product and image based on the provided ID
        // $product = Product::find($id);
        $product = Product::where('slug', $id)->first();
        // dd($product->id);
        if(isset($product)){

            $image = Image::find($product->id);
            
            // Retrieve associated titleContents
            $titleContents = $product->titleContents;
            
            // Convert titleContents to an array of accordion items
            $accordionItems = $titleContents->map(function ($item) {
                return [
                    'title' => $item->title,
                    'content' => $item->content,
                    'target' => Str::slug($item->title),
                ];
            })->toArray();
            $product_packages = ProductPackage::where('product_id', $product->id)->get();
            return view('frontend.pages.product-detail', compact('product', 'image', 'accordionItems', 'product_packages'));
        } else {
            return redirect()->route('product');
        }
    }

    public function getvariantPrice(Request $request)
    {
        // dd($request->all());
        if ($request->has('doctor_id')) {
            $package = DoctorPrice::where('doctor_id', $request->doctor_id)->where('package_id', $request->id)->first();

            // dd($price);
            if ($package) {
                return '$' . $package->price;
                // dd($price)
            } else {
                $price = ProductPackage::where('id', $request->id)->first();
                return '$' . $price->vial_total;
            }
        } else {
            $price = ProductPackage::where('id', $request->id)->first();
            return '$' . $price->vial_total;
        }
    }

    public function login()
    {
        return view('frontend.login');
    }

    public function logindoctor()
    {
        return view('frontend.logindoctor');
    }

    public function terms()
    {
        return view('frontend.pages.terms_and_condition');
    }
    public function privacyPolicy()
    {
        return view('frontend.pages.privacy_policy');
    }
    public function jump()
    {
        return view('frontend.pages.jump');
    }

    public function qualityAssurance()
    {
        return view('frontend.pages.qualityAssurance');
    }

    public function profile()
    {
        return view('frontend.pages.profile');
    }
    public function methylcobalamin()
    {
        return view('frontend.pages.methylcobalamin');
    }

    public function methylcobalamin5ml()
    {
        return view('frontend.pages.methylcobalamin5ml');
    }

    public function glutathione()
    {
        return view('frontend.pages.glutathione');
    }

    public function faq()
    {
        return view('frontend.pages.faq');
    }

    public function orders(Request $request)
    {
        $orders = Order::where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'DESC')->get();
        return view('frontend.pages.myaccount.orders', compact('orders'));
    }
    public function viewOrder(Request $request, $id = NULL)
    {
        $order = Order::where('id', $id)->first();
        $order_items = OrderItem::where('order_id', $id)->get();
        return view('frontend.pages.myaccount.view_order', compact('order', 'order_items'));
    }
    public function cancelOrder(Request $request, $id = NULL)
    {
        $order = Order::where('id', $id)->first();
        if ($order) {
            $order->status = 3;
            $order->save();
            $request->session()->flash('notification_message', 'Order Cancelled');
        }
        return redirect()->route('orders');
    }
    public function myaccount(Request $request)
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
        $user = User::where('id', Auth::guard('web')->user()->id)->first();
        return view('frontend.pages.myaccount.myaccount', compact('user', 'cities', 'states'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'organization_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'speciality' => 'required',
            'practice_address_street' => 'required',
            'city' => 'required|not_in:0',
            'state' => 'required|not_in:0',
            'zip_code' => 'required'
        ]);
        $user = Auth::guard('web')->user();
        $update =[
            'organization_name' => $request->organization_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'npi' => $request->npi,
            'business_license_number' => $request->business_license_number,
            'prescriber_state_license_number' => $request->prescriber_state_license_number,
            'dea_number' => $request->dea_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'speciality' => $request->speciality,
            'practice_address_street' => $request->practice_address_street,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code
        ];
        if($request->speciality == "other"){
            $update['speciality'] = $request->other_speciality;
        }
        $user->update($update);
        return redirect()->route('myaccount');
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required', new PasswordMatchesCurrentPassword],
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::guard('web')->user();
        $hashedPassword = Hash::make($request->new_password);
        $user->update(['password' => $hashedPassword, 'password1' => $request->new_password]);

        return redirect()->route('myaccount');
    }
    public function cityByState(Request $request, $id = NULL)
    {
        $action_cities = World::states([
            'filters' => [
                'state_id' => $id,
            ],
        ]);
        if ($action_cities->success) {
            $cities = $action_cities->data;
        } else {
            $cities = '';
        }
        // dd($id);
        $html = '';
        $html .= '<option value="0">Select City...</option>';
        if (!blank($cities)) {
            foreach ($cities as $city) {
                $select = "";
                if (Auth()->user() !== null && Auth()->user()->city == $city['name']) {
                    $select = "selected";
                }
                $html .= '<option value="' . $city['name'] . '"' . $select . '>' . $city['name'] . '</option>';
            }
        }
        return response()->json($html);
    }

    public function orderNewPage()
    {
        $products = Product::all();
        return view('frontend.pages.order-new', compact('products'));
    }
    public function hippaCompliancePage()
    {
        $products = Product::all();
        return view('frontend.pages.hippa-compliance', compact('products'));
    }

    public function thankYouPage()
    {
        return view('frontend.pages.thank-you');
    }

    public function reOrder(Request $request, $id = NULL)
    {
        if (!blank($id)) {
            $orderItem = OrderItem::where('order_id', $id)->get();
            foreach ($orderItem as $key => $item) {
                $product = Product::where('id', $item->product_id)->first();
                $product_package = ProductPackage::where('varient_name', $item->package_name)->first();
                $cartItem = CartItem::where('product_id', $item->product_id)->where('user_id', Auth()->guard('web')->user()->id)->where('package_name', $product_package->varient_name)->first();
                if (blank($cartItem)) {
                    if (isset($product)) {
                        if ($product_package->vial_quantity > $product->stock) {
                            return redirect()->back()->withErrors(['msg' => 'Your selected order quantity is greater than our existing stock. Please expect a delay of up to 2 weeks for our stock to be replenished.']);
                        }
                    }
                    $cart_item = new CartItem();
                    $cart_item->user_id = Auth()->guard('web')->user()->id;
                    $cart_item->product_id = $item->product_id;
                    $cart_item->quantity = 1;
                    $cart_item->price = $product_package->vial_quantity;
                    $cart_item->total = $item->total;
                    $cart_item->package_name    = $product_package->varient_name;
                    $cart_item->package_qty     = $product_package->vial_quantity;
                    $cart_item->package_price   = $product_package->vial_price;
                    $cart_item->package_total   = $item->total;
                    $cart_item->medical_necessity   = $request->medical_necessity;
                    $cart_item->save();
                } else {
                    $cart_item = CartItem::where('product_id', $item->product_id)->where('user_id', Auth()->guard('web')->user()->id)->where('package_name', $product_package->varient_name)->first();
                    $qty = $cart_item->quantity + 1;
                    $cart_item->quantity = $qty;
                    $cart_item->total = $qty * $cart_item->package_total;
                    $cart_item->save();
                }
            }
        }
        return redirect()->route('cart');
    }

    public function defaultCard($id){
        $update = User::where('id',Auth()->guard('web')->user()->id)->update(['card_id'=>$id]);
        return redirect()->route('card-detail');
    }
    public function userCardDetail(Request $request){
        $cardDetail = CardDetail::where('user_id', Auth()->guard('web')->user()->id)->latest()->get();
        return view('frontend.pages.myaccount.card', compact('cardDetail'));
    }

    public function leadPharmacy(){
        return view('frontend.pages.meet-our-lead-pharmacis');
    }

    public function pharmacy(){
        return view('frontend.pages.pharmacy_agreement');
    }
}
