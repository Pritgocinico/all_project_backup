<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Lot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;



class LotController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.lot.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
           
            'lot_number' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'description' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // $file = $request->file('file');
        // $fileName = time() . '_' . $file->getClientOriginalName();
        // $file->storeAs('public/lot-files', $fileName);
            if($request->has('file') && $request->file('file') != null){
                $image1 = $request->file('file');
                $destinationPath = 'public/storage/lot-files';
                $rand=rand(1,100);
                $docImage = date('YmdHis'). $rand."." . $image1->getClientOriginalExtension();
                $image1->move($destinationPath, $docImage);
                $path=$docImage;
            }
                
        Lot::create([
         
            'lot_number' => $request->lot_number,
            'product_id' => $request->product_id,
            'description' => $request->description,
            'file' => $path,
        ]);

        return redirect()->route('admin.lots.index')->with('success', 'Lot created successfully.');
    }

    public function index()
    {
        $lots = Lot::orderBy('id','desc')->get(); // You can customize this query based on your requirements
        return view('admin.lot.index', compact('lots'));
    }





public function edit(Lot $lot)
{
    $products = Product::all(); // Assuming you want to populate a dropdown with products
    return view('admin.lot.edit', compact('lot', 'products'));
}


// LotController.php

public function update(Request $request, Lot $lot)
{
    $request->validate([
        'lot_number' => 'required|string|max:255',
        'product_id' => 'required|exists:products,id',
        'description' => 'required|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);

    if ($request->hasFile('file')) {
        if($request->has('file') && $request->file('file') != null){
            $image1 = $request->file('file');
            $destinationPath = 'public/storage/lot-files';
            $rand=rand(1,100);
            $docImage = date('YmdHis'). $rand."." . $image1->getClientOriginalExtension();
            $image1->move($destinationPath, $docImage);
            $fileName=$docImage;
        }
        // $file = $request->file('file');
        // $fileName = time() . '_' . $file->getClientOriginalName();
        // $file->storeAs('public/lot-files', $fileName);
    } else {
        $fileName = $lot->file; // Keep the existing file name if no new file is uploaded
    }

    $lot->update([
        'lot_number' => $request->lot_number,
        'product_id' => $request->product_id,
        'description' => $request->description,
        'file' => $fileName,
    ]);

    return redirect()->route('admin.lots.index')->with('success', 'Lot updated successfully.');
}


public function destroy(Lot $lot)
{
    // Delete the lot
    $lot->delete();

    // Redirect back with success message
    return redirect()->route('admin.lots.index')->with('success', 'Lot deleted successfully.');
}


public function showFiles()
{
    $lots = Lot::all();

    return view('admin.lot.index_files', compact('lots'));
}


}
