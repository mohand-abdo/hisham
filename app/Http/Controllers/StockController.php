<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventories\inventoryRequest;
use App\Http\Requests\Stocks\CreateStocksRequest;
use App\Http\Requests\Stocks\UpdateStockRequest;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Item;

class StockController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stocks = Stock::all();
        return view('stocks.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stock = new Stock();
        return view('stocks.create', compact('stock'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStocksRequest $request)
    {
        $stock = Stock::create($request->all());
        return redirect()->route('stock.index')->with(['success' => 'تمت الاضافة بنجاح']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        return view('stocks.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $stock->update($request->all());
        return redirect()->route('stock.index')->with(['success' => 'تم التعديل بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        if ($stock->items()->count() > 0) {
            return back()->with('error', 'عذرا لا يمكن حذف هذا المخزن لانه يحتوي على اصناف بداخله');
        }
        $stock->delete();
        return back()->with(['success' => 'تم الحذف بنجاح']);
    }

    // public function show(Stock $stock)
    // {
    //     $items = $stock->items;
    //     $stocks = Stock::all();
    //     return view('stocks.show', compact('stock', 'items','stocks'));
    // }

    public function transform_form()
    {
        $stocks = Stock::all();
        return view('stocks.transform', compact('stocks'));
    }

    public function transform_ajax(Request $request)
    {
        $stocks = Stock::where('id', '!=', $request->stock_id)->get();
        $re =  view('includes.ajax.transform', ['request' => $request, 'stocks' => $stocks]);
        return $re;
    }

    // public function item_qty(Request $request)
    // {
    //     $item = item::find($request->id);
    //     $check_item = $item->stocks()->where('stock_id', $request->stock)->exists();
    //     if ($check_item) {
    //         $qty = $item->stocks()->find($request->stock)->pivot->qty;
    //         return ['qty' => $qty, 'value' => $qty - 1];
    //     }
    // }

    public function select_qty(Request $request)
    {
        $item = item::find($request->product);
        $check_item = $item->stocks()->where('stock_id', $request->id)->exists();
        if ($check_item) {
            $qty = $item->stocks()->find($request->id)->pivot->qty;
            return ['old_qty' => $qty, 'new_qty' => $qty + 1];
        } else {
            return ['old_qty' => 0, 'new_qty' => 1];
        }
    }

    public function transform(Request $request)
    {
        $items = $request->from_product_id;
        foreach ($items as $index => $item) {
            $item = Item::find($item);
            $item->stocks()->syncWithoutDetaching([$request->from_stock_id[$index] => ['qty' => $request->from_qty[$index]]]);
            $item->stocks()->syncWithoutDetaching([$request->to_stock_id[$index] => ['qty' => $request->to_qty[$index]]]);
        }

        return back()->with('success', 'نم التحويل بنجاح');
    }

    public function inventory()
    {
        $stocks = Stock::all();
        return view('stocks.inventory', compact('stocks'));
    }

    public function inventory_store(inventoryRequest $request)
    {
        foreach ($request->stocks as $index => $stock) {
            $stock = Stock::find($stock);
            $stock->items()->syncWithoutDetaching([$request->items[$index] => ['qty' => $request->qty[$index]]]);
        }
        return back()->with('success', 'نم الجرد بنجاح');
    }

    public function decrase()
    {
        $items = Item::get();
        foreach ($items as $item) {
            $check = $item->stocks()->where('qty', '<', 5)->get();
            foreach ($check as $val) {
                $stock_name[] = $val->name;
                $item_name[] = Item::find($val->pivot->item_id)->name;
                $qty[] = $val->pivot->qty;
            }
        }

        return view('stocks.decrase',compact('stock_name','item_name','qty'));
    }
}
