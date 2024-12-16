<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Money_trans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Banks\CreateBankRequest;
use App\Http\Requests\Banks\UpdateBankRequest;

class SafeController extends Controller
{
    public function create()
    {
        $safe = new bank();
        return view('settings.safes.create', compact('safe'));
    }

    public function store(CreateBankRequest $request)
    {
        $safe = Bank::create([
            'name' => $request->name,
            'type' => 0
        ]);

        $moeny_trans = new money_trans();
        $moeny_trans->user_id = Auth::user()->id;
        $moeny_trans->to = $request->money; //changed
        $moeny_trans->type = 1;
        $moeny_trans->bank_id = $safe->id;
        $moeny_trans->note = 'المبلغ المبدئي';
        $moeny_trans->save();

        return redirect()->route('setting')->with(['success' => 'تمت الاضافة بنجاح']);
    }

    public function edit(Bank $safe)
    {
        return view('settings.safes.edit', compact('safe'));
    }

    public function update(UpdateBankRequest $request, Bank $safe)
    {
        $safe->update($request->all());
        return redirect()->route('setting')->with(['success' => 'تم التعديل بنجاح']);
    }

    public function destroy(Bank $safe)
    {
        $safe->delete();
        return back()->with(['success' => 'تم الحذف بنجاح']);
    }
}
