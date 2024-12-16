<?php

namespace App\Http\Controllers;

use App\Http\Requests\Suppliers\SupplierRequest;
use App\Models\Supplier;
use App\Models\Sup_account_main;
use App\Models\Sup_account;
use App\Models\supplier_trans;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::whereNull('client_id')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supplier = new supplier();
        return view('suppliers.create', compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        $data = $request->all();
        $data['phone'] = array_filter($request->phone);
        $supplier = Supplier::create($data);

        if ($request->money != "") {

            $this->supplier_account_main($supplier, $request);
            $this->sup_account($supplier, $request);
            $this->supplier_trans($supplier, $request);
        }
        return redirect()->route('supplier.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return $supplier;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $data = $request->all();
        $data['phone'] = array_filter($request->phone);
        $supplier->update($data);
        return redirect()->route('supplier.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    private function supplier_account_main($supplier, $request)
    {
        $sup_account_main = new Sup_account_main();
        $sup_account_main->supplier_id = $supplier->id;
        $sup_account_main->total_price = $request->money;
        $sup_account_main->sub_price = $request->money;
        $sup_account_main->save();
        return $sup_account_main;
    }

    private function sup_account($supplier, $request)
    {
        $sup_account = new Sup_account();
        $sup_account->supplier_id = $supplier->id;
        $sup_account->total_price = $request->money;
        $sup_account->sub_price = $request->money;
        $sup_account->save();
        return $sup_account;
    }

    private function supplier_trans($supplier, $request)
    {
        $supplier_trans = new supplier_trans();
        $supplier_trans->supplier_id = $supplier->id;
        $supplier_trans->from = $request->money;
        $supplier_trans->save();
        return $supplier_trans;
    }
}
