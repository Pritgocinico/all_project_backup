<?php

namespace App\Http\Controllers\salemanager;
use App\Http\Controllers\Controller;
use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\StockRepositoryInterface;
use App\Interfaces\UserPermissionRepositoryInterface;
use Illuminate\Http\Request;

class SalesManagerController extends Controller
{
    protected $employeeRepository,$orderRepository,$stockRepository,$userPermissionRepository,$productRepository,$adminDashboardRepository;
    public function __construct(EmployeeRepositoryInterface $employeeRepository,OrderRepositoryInterface $orderRepository,StockRepositoryInterface $stockRepository,UserPermissionRepositoryInterface $userPermissionRepository, ProductRepositoryInterface $productRepository,AdminDashboardRepositoryInterface $adminDashboardRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->employeeRepository = $employeeRepository;
        $this->stockRepository = $stockRepository;
        $this->userPermissionRepository = $userPermissionRepository;
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->productRepository = $productRepository;
    }
    public function index(){
        $page = "Sale Manager Dashboard";
        $pendingCount = $this->adminDashboardRepository->totalOrderCount('1');
        $confirmCount = $this->adminDashboardRepository->totalOrderCount('2');
        $deliverCount = $this->adminDashboardRepository->totalOrderCount('3');
        $cancelCount = $this->adminDashboardRepository->totalOrderCount('4');
        $returnCount = $this->adminDashboardRepository->totalOrderCount('5');
        $completeCount = $this->adminDashboardRepository->totalOrderCount('6');
        $inOutStockCount = $this->stockRepository->getTotalInOutStock();
        $totalEmployee = $this->employeeRepository->getEmployeeCount();
        return view('sale_manager.index',compact('pendingCount','confirmCount','deliverCount','cancelCount','returnCount','completeCount','inOutStockCount','totalEmployee','page'));
    }
    public function dashboardOrderAjax(Request $request){
        $date = $request->date;
        $data['pendingCount'] = $this->adminDashboardRepository->totalOrderCount('1',$date);
        $data['confirmCount'] = $this->adminDashboardRepository->totalOrderCount('2',$date);
        $data['cancelCount'] = $this->adminDashboardRepository->totalOrderCount('4',$date);
        $data['returnCount'] = $this->adminDashboardRepository->totalOrderCount('5',$date);
        $data['completeCount'] = $this->adminDashboardRepository->totalOrderCount('6',$date);
        $data['inOutStockCount'] = $this->stockRepository->getTotalInOutStock($date);
        return response()->json($data);

    }
    public function orderList(){
        $page = "Sale Manager Dashboard";
        return view('sale_manager.order.index',compact('page'));
    }

    public function orderAjaxList(Request $request){
        $search = $request->search;
        $status = $request->status;

        $orderList = $this->orderRepository->getAllData($status, $search,'paginate');
        
        return view('sale_manager.order.ajax_list',compact('orderList'));
    }

    public function view(string $id)
    {
        $order = $this->orderRepository->getDetailById($id);
        $states = $this->orderRepository->getAllStates();
        return view('sale_manager.order.show', compact('order', 'states'));
    }

    public function inOutStockList(){
        $page = "In Out Stock List";
        return view('sale_manager.stock.index',compact('page'));
    }
    public function inOutStockAjax(Request $request){
        $search= $request->search;
        $date= $request->date;
        $stockList = $this->stockRepository->getAllStockDetail($search,$date);
        return view('sale_manager.stock.ajax_list',compact('stockList'));
    }

    public function employeeList(){
        $page = "Employee List";
        return view('sale_manager.employee.index',compact('page'));
    }
    public function employeeAjaxList(Request $request){
        $search = $request->search;
        $status = $request->status;

        $employeeList = $this->employeeRepository->getAllEmployeeSearch($search,$status);
        return view('sale_manager.employee.ajax_list',compact('employeeList'));
    }

    public function show(string $id)
    {
        $page = "Employee View";
        $employee = $this->employeeRepository->getDetailById($id);
        $permissionList = $this->userPermissionRepository->getAllUserPermission($id);
        $todayOrder = $this->orderRepository->getUserOrderData($id, 'daily');
        $allOrder = $this->orderRepository->getUserOrderData($id, '');
        $weekOrder = $this->orderRepository->getUserOrderData($id, 'weekly');
        $monthOrder = $this->orderRepository->getUserOrderData($id, 'month');
        $yearOrder = $this->orderRepository->getUserOrderData($id, 'year');
        return view('sale_manager.employee.show', compact('employee', 'permissionList', 'todayOrder', 'allOrder','weekOrder','monthOrder','yearOrder','page'));
    }

    public function getTopProductSale(){
        $page ="Top Ten Product Order";
        return view('sale_manager.product.index',compact('page'));
    }
    public function getTopProductSaleAjax(Request $request){
        $search = $request->search;
        $date = $request->date;
        $productList =$this->productRepository->getTopTenProduct($search,$date);
        return view('sale_manager.product.ajax_list',compact('productList'));
    }
}
