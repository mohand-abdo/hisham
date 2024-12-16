<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Money_trans;
use App\models\Sup_account;
use Illuminate\Http\Request;
use App\Models\supplier_trans;
use App\Models\Sup_account_det;
use App\Models\Sup_account_main;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Purchases\purchaseRequest;

class PurchaseController extends Controller
{

    public function index()
    {
        $purchases = Purchase::where('inv_chack', '1')->whereYear('created_at', now()->year)->orderBy('id', 'desc')->get();
        $purchase = Purchase::where('inv_chack', '1')->whereYear('created_at', now()->year)->get()->last();
        return view('purchases.index', compact('purchases', 'purchase'));
    }

    public function uncash()
    {
        $purchases = Purchase::where('inv_chack', '0')->orderBy('id', 'desc')->get();
        $purchase = Purchase::where('inv_chack', '0')->get()->last();
        return view('purchases.uncash', compact('purchases', 'purchase'));
    }

    public function create()
    {
        $banks = Bank::where('type', 1)->get();
        $safes = Bank::where('type', 0)->get();
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('purchases.create', compact('banks', 'safes', 'suppliers', 'items'));
    }

    public function store(purchaseRequest $request)
    {
        if ($request->inv_type == 1) {

            $save = Money_trans::where('bank_id', $request->safe)->get();
            $total_save = total_money($save);
            if ($total_save - $request->total_price < 0) {
                return back()->withErrors('لا يتوفر هذا المبلغ لديك');
            }
        } elseif ($request->inv_type == 2) {
            $save = Money_trans::where('bank_id', $request->bank_id)->get();
            $total_save = total_money($save);

            if ($total_save - $request->total_price < 0) {
                return back()->withErrors('لا يتوفر هذا المبلغ لديك');
            }
        }

        // save in purchases table
        $purchase = $this->purchase($request);

        $stock = Stock::findOrFail(1);
        foreach ($request->id_item as $index => $id) {
            // insert into item_purchase table
            $purchase->items()->attach([$id => ['qty' => $request->item_qty[$index], 'item_price' => $request->item_price[$index]]]);
            // increse qty in stock 1 in items_stocks table
            $stock->items()->syncWithoutDetaching([$id => ['qty' => $request->total_qty[$index]]]);
        }

        if ($request->inv_type == 3) {
            $this->supplier_account_main($purchase, $request);
            $this->sup_account($purchase, $request);
        } else {
            $this->money_trans($request);
        }


        $request->supplier_id != '' ? $supplier_trans = $this->supplier_trans($purchase, $request) : '';

        return redirect()->route('purchase.print', $purchase->id);
    }

    public function show(Purchase $purchase)
    {
        return view('purchases.show', compact('purchase'));
    }

    public function print(Purchase $purchase)
    {
        return view('purchases.pdf', compact('purchase'));
    }

    public function pay(Purchase $purchase)
    {
        $pay_det = Sup_account_det::where('purchase_id', $purchase->id)->get();
        $safes = Bank::where('type', 0)->get();
        $banks = Bank::where('type', 1)->get();
        return view('purchases.pay', compact('purchase', 'pay_det', 'safes', 'banks'));
    }

    public function account_pay(Request $request)
    {
        if ($request->inv_type == 1) {

            $save = Money_trans::where('bank_id', $request->safe)->get();
            $total_save = total_money($save);
            if ($total_save - $request->total_price < 0) {
                return back()->withErrors('لا يتوفر هذا المبلغ لديك');
            }
        } elseif ($request->inv_type == 2) {
            $save = Money_trans::where('bank_id', $request->bank)->get();
            $total_save = total_money($save);

            if ($total_save - $request->price < 0) {
                return back()->withErrors('لا يتوفر هذا المبلغ لديك');
            }
        }

        $sup_account = Purchase::find($request->purchase_id)->sup_account;
        if ($sup_account->sub_price == 0 || $sup_account->sub_price - $request->price < 0) {
            return back()->withErrors('يجب ان لا يكون السعر المدفوع اكبر  من  اجمالي الفاتورة');
        }

        $sup_account_main = Sup_account_main::where('supplier_id', $sup_account->supplier_id)->where('acc_check', 0)->first();
        $sup_account_main->update([
            'pay_price' => $sup_account_main->pay_price + $request->price,
            'sub_price' => $sup_account_main->total_price - ($sup_account_main->pay_price + $request->price)
        ]);

        if ($sup_account_main->sub_price == 0) {
            $sup_account_main->update([
                'acc_check' => 1,
            ]);
        }

        $sup_account_det = new Sup_account_det();
        $sup_account_det->purchase_id = $request->purchase_id;
        $sup_account_det->price = $request->price;
        $sup_account_det->inv_type = $request->inv_type;
        $sup_account_det->save();

        supplier_trans::create([
            'supplier_id' => $request->supplier_id,
            'purchase_id' => $request->purchase_id,
            'to' => $request->price
        ]);

        $sup_account->update([
            'pay_price' => $sup_account->pay_price + $request->price,
            'sub_price' => $sup_account->sub_price - $request->price,
        ]);

        if ($sup_account->sub_price == 0) {
            $sup_account->update([
                'acc_check' => 1,
            ]);

            $purchase = Purchase::find($request->purchase_id);
            $purchase->update([
                'inv_chack' => 1
            ]);
        }

        Money_trans::create([
            'user_id' => Auth::user()->id,
            'from' => $request->price,
            'type' => $request->inv_type,
            'bank_id' => $request->inv_type == 1 ? $request->safe : $request->bank,
            'note' => ' His-' . $request->purchase_id . '-RT' . 'متاخرات فاتورة مشتربات بالرقم'
        ]);

        return redirect()->back();
    }

    private function purchase($request)
    {
        $purchase = new Purchase();
        $purchase->inv_no = $request->inv_no;
        $purchase->total_price = $request->total_price;
        $purchase->inv_date = $request->inv_date;
        $purchase->user_id = auth()->user()->id;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->inv_type = $request->inv_type;
        $request->inv_type == 2 ? $purchase->bank_id = $request->bank_id : null;
        if ($request->inv_type == 3) {
            $purchase->uncash_type = $request->uncash_type;
            $purchase->pay_date = $request->pay_date;
            $purchase->inv_chack = 0;
        }
        $purchase->save();
        return $purchase;
    }

    private function supplier_account_main($purchase, $request)
    {
        $sup_account_main = Sup_account_main::where('supplier_id', $request->supplier_id)->where('acc_check', 0)->first();
        if ($sup_account_main) {
            $sup_account_main->update([
                'total_price' => $sup_account_main->total_price + $purchase->total_price,
                'sub_price' => $sup_account_main->total_price + $purchase->total_price - $sup_account_main->pay_price,
            ]);
        } else {
            $sup_account_main = new Sup_account_main();
            $sup_account_main->supplier_id = $request->supplier_id;
            $sup_account_main->total_price = $purchase->total_price;
            $sup_account_main->sub_price = $purchase->total_price;
            $sup_account_main->save();
        }
        return $sup_account_main;
    }

    private function sup_account($purchase, $request)
    {
        $sup_account = new Sup_account();
        $sup_account->purchase_id = $purchase->id;
        $sup_account->supplier_id = $request->supplier_id;
        $sup_account->total_price = $purchase->total_price;
        $sup_account->sub_price = $purchase->total_price;
        $request->uncash_type == 2 ? $sup_account->cash_type = 1 : '';
        $sup_account->save();
        return $sup_account;
    }

    private function money_trans($request)
    {
        $moeny_trans = new money_trans();
        $moeny_trans->user_id = Auth::user()->id;
        $moeny_trans->from = $request->total_price;
        $request->bank_id == '' ? $moeny_trans->bank_id = 1 : $moeny_trans->bank_id = $request->bank_id;
        $request->inv_type == 1 ? $moeny_trans->type = 1 : $moeny_trans->type = 2;
        $moeny_trans->note = 'فاتورة مشتريات';
        $moeny_trans->save();
        return $moeny_trans;
    }

    private function supplier_trans($purchase, $request)
    {
        $supplier_trans = new supplier_trans();
        $supplier_trans->supplier_id = $request->supplier_id;
        $supplier_trans->purchase_id = $purchase->id;
        $request->inv_type != 3 ? $supplier_trans->to = $request->total_price : $supplier_trans->from = $request->total_price;
        $supplier_trans->save();
        return $supplier_trans;
    }

    public function time_out_close()
    {
        $purchases = Purchase::whereBetween('pay_date', [today(), today()->addDays(7)])->where('inv_chack', 0)->orderBy('pay_date', 'asc')->get();
        $supplier = Purchase::whereBetween('pay_date', [today(), today()->addDays(7)])->where('inv_chack', 0)->orderBy('pay_date', 'asc')->first();
        return view('purchases.time_out_close', compact('purchases', 'supplier'));
    }

    public function time_out_over()
    {
        $purchases = Purchase::where('pay_date', '<', today())->where('inv_chack', 0)->orderBy('pay_date', 'asc')->get();
        $supplier = Purchase::where('pay_date', '<', today())->where('inv_chack', 0)->orderBy('pay_date', 'asc')->first();
        return view('purchases.time_out_over', compact('purchases', 'supplier'));
    }

    public function supplier(Purchase $purchase)
    {
        return view('purchases.supplier', compact('purchase'));
    }
}
