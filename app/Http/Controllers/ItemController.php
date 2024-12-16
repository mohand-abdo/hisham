<?php

namespace App\Http\Controllers;

use App\Http\Requests\Items\CreateItemsRequest;
use App\Http\Requests\Items\UpadteItemsRequest;
use App\models\Category;
use App\Models\Item;
use App\Models\Stock;

class ItemController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Item;
        $categories = Category::all();
        return view('settings.items.create', compact('item', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateItemsRequest $request)
    {
        $data = $request->all();
        $item = Item::create($data);
        // $item->stocks()->syncWithoutDetaching([1 => ['qty' => $request->qty]]);
        return redirect()->route('setting')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('settings.items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(UpadteItemsRequest $request, Item $item)
    {
        $item->update($request->all());
        return redirect()->route('setting')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if ($item->stocks()->count() > 0) {
            return back()->with('error', 'عذرا هذا الصنف لا يمكن حذفه لانه توجد له كمية في المخازن');
        }
        $item->delete();
        return back()->with(['success' => 'تم الحذف بنجاح']);
    }
}
