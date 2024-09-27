<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\DosageForm;

class DosageController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }

    public function create()
    {
        return view('admin.dosage.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DosageForm::create($request->all());

        return redirect()->route('dosage.index')->with('success', 'Dosage form created successfully.');
    }

    public function index()
    {
        $dosageForms = DosageForm::orderBy('id','desc')->get();
        return view('admin.dosage.index', compact('dosageForms'));
    }

    public function edit($id)
    {
        $dosageForm = DosageForm::findOrFail($id);

        return view('admin.dosage.edit', compact('dosageForm'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $dosageForm = DosageForm::findOrFail($id);
        $dosageForm->update($request->all());

        return redirect()->route('dosage.index')->with('success', 'Dosage form updated successfully.');
    }

    public function destroy($id)
    {
        $dosageForm = DosageForm::findOrFail($id);
        $dosageForm->delete();

        return redirect()->route('dosage.index')->with('success', 'Dosage form deleted successfully.');
    }
    
}