<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Requests\Inventories\inventoryRequest;

class SettingController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $items = Item::all();
        $safes = Bank::where('type', 0)->get();
        $banks = Bank::where('type', 1)->get();
        $expenses = Expense::all();
        return view('settings.index', compact('categories', 'items', 'safes', 'banks', 'expenses'));
    }

    public function item_qty()
    {
        $items = Item::all();
        $stocks = Stock::all();
        return view('settings.items.frist_time', compact('stocks', 'items'));
    }

    public function store_item_qty(inventoryRequest $request)
    {
        $total_price = 0;
        foreach ($request->stocks as $index => $stock) {
            if ($request->qty[$index] != '0') {
                $stock = Stock::find($stock);
                $item = Item::find($request->items[$index]);
                $stock->items()->syncWithoutDetaching([$request->items[$index] => ['qty' => $request->qty[$index]]]);
                $total_price += $item->purches_price * $request->qty[$index];
            }
        }

        Purchase::updateOrCreate([
            'inv_chack' => 2
        ], [
            'total_price' => $total_price,
            'user_id' => auth()->user()->id,
            'inv_chack' => '2'
        ]);
        return back()->with('success', 'نم الاضافة بنجاح');
    }

    public function tab(Request $request)
    {
        $setting = SiteSetting::first();
        $setting->update([
            'tab' => $request->id
        ]);
    }
}
