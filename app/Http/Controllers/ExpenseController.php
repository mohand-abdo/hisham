<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Requests\Expensess\CreateExpensessRequest;
use App\Http\Requests\Expensess\UpdateExpensessRequest;

class ExpenseController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense = new Expense;
        return view('settings.expenses.create', compact('expense'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateExpensessRequest $request)
    {
        Expense::create($request->all());
        return redirect()->route('setting')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        return view('settings.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExpensessRequest $request, Expense $expense)
    {
        $expense->update($request->all());
        return redirect()->route('setting')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with(['success' => 'تم الحذف بنجاح']);
    }
}
