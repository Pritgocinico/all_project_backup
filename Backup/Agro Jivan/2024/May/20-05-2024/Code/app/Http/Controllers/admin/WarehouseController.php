<?php
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;

class WarehouseController extends Controller
{
    protected $batchRepository,$employeeRepository;
    public function __construct(EmployeeRepositoryInterface $employeeRepository,BatchRepositoryInterface $batchRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->batchRepository = $batchRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function billDownload(){
        $driverList = $this->employeeRepository->getAllDriver();
        $page = "Batch List";
        return view('admin.warehouse.bill_download',compact('driverList','page'));
    }
    
    public function billDownloadAjax(Request $request){
        $search = $request->search;
        $date = $request->date;
        $driverId = $request->driverId;
        $batchList = $this->batchRepository->getAllDeliveredBatchList($search, $date, $driverId,'paginate');
        return view('admin.warehouse.bill_download_ajax',compact('batchList'));
    }
}
