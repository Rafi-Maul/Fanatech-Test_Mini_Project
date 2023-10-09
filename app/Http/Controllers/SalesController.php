<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\Sales;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Sales::with('user')->select('id', 'users_id', 'number', 'date')->get();
            return Datatables::of($data)
                ->addColumn('user', function ($data) {
                    return $data->user->name;
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="javascript:void(0);" onClick="editSales(' . $data->id . ');" class="btn btn-warning btn-sm me-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0);" onClick="deleteSales(' . $data->id . ');" class="btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })->addIndexColumn()->rawColumns(['action'])->make(true);
        }

        $inventories = Inventory::all();
        return view('sales.index', compact('inventories'));
    }

    public function store(Request $request)
    {
        $number = '';
        $length = 8;

        for ($i = 0; $i < $length; $i++) {
            $number .= mt_rand(0, 9);
        }

        $sales = Sales::create([
            'number' => $number,
            'date' => Carbon::today()->format('Y-m-d'),
            'users_id'  => Auth::user()->id
        ]);

        foreach ($request->inventories_id as $inventoryId) {
            $inventory = Inventory::find($inventoryId);

            $priceWithoutCurrency = str_replace(['Rp', ' ', '.'], '', $inventory->price);
            $priceAsFloat = (float)$priceWithoutCurrency;

            $sales->details()->updateOrCreate([
                'id' => $request->id
            ], [
                'sales_id' => $sales->id,
                'inventories_id' => $inventory->id,
                'qty' => $request->qty,
                'price' => $priceAsFloat * $request->qty
            ]);

            $inventory->decrement('stock', $request->qty);
        }

        return Response()->json($sales);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function destroy(Sales $sales)
    {
        if ($sales) {
            $sales->details()->delete();
            $sales->delete();
            return Response()->json($sales);
        }
    }
}
