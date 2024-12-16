<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Expense;
use App\Models\Money_trans;
use Illuminate\Http\Request;
use App\Models\Wared_monserf;
use Illuminate\Support\Facades\Auth;

class MonserfController extends Controller
{
    public function index()
    {
        $monserfes = Wared_monserf::whereNotNull('from')->whereYear('created_at', now()->year)->get();
        $expenses = Expense::all();
        $banks = Bank::where('type', 1)->get();
        $safes = Bank::where('type', 0)->get();
        return view('monserfes.index', compact('monserfes', 'expenses', 'safes', 'banks'));
    }

    public function store(Request $request)
    {
        if ($request->cash == 1) {

            $save = Money_trans::where('bank_id', $request->safe)->get();
            $total_save = total_money($save);
            if ($total_save - $request->from < 0) {
                return back()->withErrors('لا يتوفر هذا المبلغ لديك');
            }
        } elseif ($request->cash == 2) {
            $save = Money_trans::where('bank_id', $request->bank)->get();
            $total_save = total_money($save);

            if ($total_save - $request->from < 0) {
                return back()->withErrors('لا يتوفر هذا المبلغ لديك');
            }
        }

        $bank = $request->cash == 2 ? $request->bank : $request->safe;
        Wared_monserf::create([
            'user_id' => Auth::user()->id,
            'from' => $request->from,
            'cash' => $request->cash,
            'type' => $request->type,
            'bank_id' => $bank,
        ]);

        Money_trans::create([
            'user_id' => auth()->user()->id,
            'from' => $request->from,
            'type' => $request->cash,
            'bank_id' => $bank,
            'note' => 'منصرفات'
        ]);

        return back()->with(['success' => 'تمت الاضافة بنجاح']);
    }
}
