<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\Setting;
use PDF;

class CategoryController extends Controller
{
    private $categories;
    public function __construct()
    {
        $page = "Category";
        $this->categories = resolve(Category::class);
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.category.index');
    }

    public function categoryAjaxList(Request $request)
    {
        $search = $request->search;
        $categoryList = $this->categories
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
        return view('admin.category.ajax_list', compact('categoryList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;

        $categoryList = $this->categories
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
            })->latest()->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Category.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Category Name', 'created_by', 'Created At');
            $callback = function () use ($categoryList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($categoryList as $category) {
                    $date = "";
                    if (isset($category->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($category->created_at);
                    }
                    $createdBy = isset($category->userDetail) ? $category->userDetail->name : "-";
                    fputcsv($file, array($category->name, $createdBy, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.category', ['categoryList' => $categoryList,'setting' =>$setting]);
            return $pdf->download('Category.pdf');
        }
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
    public function store(CreateCategoryRequest $request)
    {
        $data = [
            'created_by' => auth()->user()->id,
            'name' => $request->name,
        ];
        $category = $this->categories->create($data);
        if ($category) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Category',
                'description' => auth()->user()->name . " created a category named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Category created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
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
    public function edit(Category $category)
    {
        return response()->json(['status' => 1, 'data' => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCategoryRequest $request, Category $category)
    {
        $update = $category->update([
            'name' => $request->name,
            'status' => $request->status == "on" ? 1 : 0,
            'updated_by' => auth()->user()->id,
        ]);
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Category',
                'description' => auth()->user()->name . " updated a category named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Category updated successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $delete = $category->delete();
        if ($delete) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Category',
                'description' => auth()->user()->name . " deleted a category named '" . $category->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Category deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
