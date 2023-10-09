<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Inventory::select('id', 'code', 'name', 'price', 'stock')->get();
            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    $btn = '<a href="javascript:void(0);" onClick="editInventory(' . $data->id . ');" class="btn btn-warning btn-sm me-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0);" onClick="deleteInventory(' . $data->id . ');" class="btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })->addIndexColumn()->rawColumns(['action'])->make(true);
        }

        return view('inventory.index');
    }

    public function store(Request $request)
    {
        $inventoryId = $request->id;

        $existingInventory = Inventory::find($inventoryId);
        if ($existingInventory) {
            $code = $existingInventory->code;
        } else {
            $code = strtoupper(Str::random(3)) . rand(100, 999);
        }

        $inventory = Inventory::updateOrCreate(
            [
                'id' => $inventoryId
            ],
            [
                'code'  => $code,
                'name'  => $request->name,
                'price' => $request->price,
                'stock' => $request->stock
            ]
        );

        return Response()->json($inventory);
    }

    public function edit(Request $request, Inventory $inventory)
    {
        $where = array('id' => $request->id);
        $inventory = $inventory->where($where)->first();
        return Response()->json($inventory);
    }

    public function destroy(Inventory $inventory)
    {
        $inventory = $inventory->delete();
        return Response()->json($inventory);
    }
}
