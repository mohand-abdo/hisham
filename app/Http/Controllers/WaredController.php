<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Expense;
use App\Models\Money_trans;
use Illuminate\Http\Request;
use App\Models\Wared_monserf;
use Illuminate\Support\Facades\Auth;

class WaredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wareds = Wared_monserf::whereNotNull('to')->whereYear('created_at', now()->year)->get();
        $banks = Bank::where('type', 1)->get();
        $safes = Bank::where('type', 0)->get();
        return view('wareds.index', compact('wareds', 'safes', 'banks'));
    }


    public function store(Request $request)
    {
        $bank = $request->cash == 2 ? $request->bank : $request->safe;
        Wared_monserf::create([
            'user_id' => Auth::user()->id,
            'to' => $request->to,
            'cash' => $request->cash,
            'note' => $request->note,
            'bank_id' => $bank,

        ]);

        Money_trans::create([
            'user_id' => auth()->user()->id,
            'to' => $request->to,
            'type' => $request->cash,
            'bank_id' => $bank,
            'note' => 'ايرادات'
        ]);

        return redirect()->back()->with(['success' => 'تمت الاضافة بنجاح']);
    }
}
