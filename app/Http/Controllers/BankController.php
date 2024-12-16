<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Money_trans;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Banks\CreateBankRequest;
use App\Http\Requests\Banks\UpdateBankRequest;

class BankController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bank = new bank();
        return view('settings.banks.create', compact('bank'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBankRequest $request)
    {
        $bank = Bank::create([
            'name' => $request->name,
            'type' => 1
        ]);

        $moeny_trans = new money_trans();
        $moeny_trans->user_id = Auth::user()->id;
        $moeny_trans->to = $request->money; //changed
        $moeny_trans->type = 2;
        $moeny_trans->bank_id = $bank->id;
        $moeny_trans->note = 'المبلغ المبدئي';
        $moeny_trans->save();

        return redirect()->route('setting')->with(['success' => 'تمت الاضافة بنجاح']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        return view('settings.banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankRequest $request, Bank $bank)
    {
        $bank->update($request->all());
        return redirect()->route('setting')->with(['success' => 'تم التعديل بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        return back()->with(['success' => 'تم الحذف بنجاح']);
    }
}
