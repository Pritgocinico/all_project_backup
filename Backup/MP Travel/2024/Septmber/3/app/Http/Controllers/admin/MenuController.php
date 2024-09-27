<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function reorder(Request $request)
    {
        $menuItems = $request->input('menuItems');

        foreach ($menuItems as $order => $id) {
            Menu::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}
