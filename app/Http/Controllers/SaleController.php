<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Client;
use App\Models\Cli_account;
use App\Models\Money_trans;
use App\Models\Client_trans;
use Illuminate\Http\Request;
use App\Models\Cli_account_det;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Sales\SaleRequest;
use App\Models\Cli_account_main;

class SaleController extends Controller
{

    public function index()
    {
        $sales = Sale::where('inv_chack', '1')->whereYear('created_at', now()->year)->orderBy('id', 'desc')->get();
        $sale = Sale::where('inv_chack', '1')->get()->last();
        return view('sales.index', compact('sales', 'sale'));
    }

    public function uncash()
    {
        $sales = Sale::where('inv_chack', '0')->orderBy('id', 'desc')->get();
        $sale = Sale::where('inv_chack', '0')->get()->last();
        return view('sales.uncash', compact('sales', 'sale'));
    }

    public function create()
    {
        $banks = Bank::where('id', '!=', 1)->get();
        $clients = Client::all();
        $items = Item::all();
        return view('sales.create', compact('banks', 'clients', 'items'));
    }

    public function store(SaleRequest $request)
    {
        // save in sales table
        $sale = $this->sale($request);

        $stock = Stock::findOrFail(2);
        foreach ($request->id_item as $index => $id) {
            // insert into item_sale table
            $sale->items()->attach([$id => ['qty' => $request->item_qty[$index], 'item_price' => $request->item_price[$index]]]);
            // increse qty in stock 2 in items_stocks table
            $stock->items()->syncWithoutDetaching([$id => ['qty' => $request->total_qty[$index]]]);
        }

        if ($request->inv_type == 3) {
            $this->cli_account_main($sale, $request);
            $this->cli_account($sale, $request);
        } else {
            $this->money_trans($request);
        }

        $request->client_id != '' ? $client_trans = $this->client_trans($sale, $request) : '';

        return redirect()->route('sale.print', $sale->id);
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function print(Sale $sale)
    {
        return view('sales.pdf', compact('sale'));
    }

    public function pay(Sale $sale)
    {
        $pay_det = Cli_account_det::where('sale_id', $sale->id)->get();
        $safes = Bank::where('type', 0)->get();
        $banks = Bank::where('type', 1)->get();
        return view('sales.pay', compact('sale', 'pay_det', 'safes', 'banks'));
    }

    public function account_pay(Request $request)
    {
        $cli_account = Sale::find($request->sale_id)->cli_account;
        if ($cli_account->sub_price == 0 || $cli_account->sub_price - $request->price < 0) {
            return back()->withErrors('يجب ان لا يكون السعر المدفوع اكبر  من  اجمالي الفاتورة');
        }

        $cli_account_main = Cli_account_main::where('client_id', $cli_account->client_id)->where('acc_check', 0)->first();
        $cli_account_main->update([
            'pay_price' => $cli_account_main->pay_price + $request->price,
            'sub_price' => $cli_account_main->total_price - ($cli_account_main->pay_price + $request->price)
        ]);

        if ($cli_account_main->sub_price == 0) {
            $cli_account_main->update([
                'acc_check' => 1,
            ]);
        }

        $cli_account_det = new Cli_account_det();
        $cli_account_det->sale_id = $request->sale_id;
        $cli_account_det->price = $request->price;
        $cli_account_det->inv_type = $request->inv_type;
        $cli_account_det->save();

        Client_trans::create([
            'client_id' => $request->client_id,
            'sale_id' => $request->sale_id,
            'from' => $request->price // غيرتها 
        ]);

        $cli_account->update([
            'pay_price' => $cli_account->pay_price + $request->price,
            'sub_price' => $cli_account->sub_price - $request->price,
        ]);

        if ($cli_account->sub_price == 0) {
            $cli_account->update([
                'acc_check' => 1,
            ]);

            $sale = Sale::find($request->sale_id);
            $sale->update([
                'inv_chack' => 1
            ]);
        }

        Money_trans::create([
            'user_id' => Auth::user()->id,
            'to' => $request->price, // غيرتها
            'type' => $request->inv_type,
            'bank_id' => $request->inv_type == 1 ? $request->safe : $request->bank,
            'note' => ' His-' . $request->sale_id . '-RT' . 'متاخرات فاتورة مبيعات بالرقم'
        ]);

        return redirect()->back();
    }

    private function sale($request)
    {
        $sale = new Sale();
        $sale->total_price = $request->total_price;
        $sale->user_id = auth()->user()->id;
        $sale->client_id = $request->client_id;
        $sale->inv_type = $request->inv_type;
        $request->inv_type == 2 ? $sale->bank_id = $request->bank_id : null;
        if ($request->inv_type == 3) {
            $sale->uncash_type = $request->uncash_type;
            $sale->pay_date = $request->pay_date;
            $sale->inv_chack = 0;
        }
        $sale->save();
        return $sale;
    }

    private function cli_account_main($sale, $request)
    {
        $cli_account_main = Cli_account_main::where('client_id', $request->client_id)->where('acc_check', 0)->first();
        if ($cli_account_main) {
            $cli_account_main->update([
                'total_price' => $cli_account_main->total_price + $sale->total_price,
                'sub_price' => $cli_account_main->total_price + $sale->total_price - $cli_account_main->pay_price,
            ]);
        } else {
            $cli_account_main = new Cli_account_main();
            $cli_account_main->client_id = $request->client_id;
            $cli_account_main->total_price = $sale->total_price;
            $cli_account_main->sub_price = $sale->total_price;
            $cli_account_main->save();
        }
        return $cli_account_main;
    }

    private function cli_account($sale, $request)
    {
        $cli_account = new Cli_account();
        $cli_account->sale_id = $sale->id;
        $cli_account->client_id = $request->client_id;
        $cli_account->total_price = $sale->total_price;
        $cli_account->sub_price = $sale->total_price;
        $cli_account->cash_type = $request->uncash_type;
        $cli_account->save();
        return $cli_account;
    }

    private function money_trans($request)
    {
        $moeny_trans = new money_trans();
        $moeny_trans->user_id = Auth::user()->id;
        $moeny_trans->to = $request->total_price; //changed
        $request->inv_type == 1 ? $moeny_trans->type = 1 : $moeny_trans->type = 2;
        $request->bank_id == '' ? $moeny_trans->bank_id = 1 : $moeny_trans->bank_id = $request->bank_id;
        $moeny_trans->note = 'فاتورة مبيعات';
        $moeny_trans->save();
        return $moeny_trans;
    }

    private function client_trans($sale, $request)
    {
        $client_trans = new Client_trans();
        $client_trans->client_id = $request->client_id;
        $client_trans->sale_id = $sale->id;
        $request->inv_type != 3 ? $client_trans->from = $request->total_price : $client_trans->to = $request->total_price;
        $client_trans->save();
        return $client_trans;
    }

    public function time_out_close()
    {
        $sales = Sale::whereBetween('pay_date', [today(), today()->addDays(7)])->where('inv_chack', 0)->orderBy('pay_date', 'asc')->get();
        $client = Sale::whereBetween('pay_date', [today(), today()->addDays(7)])->where('inv_chack', 0)->orderBy('pay_date', 'asc')->first();
        return view('sales.time_out_close', compact('sales', 'client'));
    }

    public function time_out_over()
    {
        $sales = Sale::where('pay_date', '<', today())->where('inv_chack', 0)->orderBy('pay_date', 'asc')->get();
        $client = Sale::where('pay_date', '<', today())->where('inv_chack', 0)->orderBy('pay_date', 'asc')->first();
        return view('sales.time_out_over', compact('sales', 'client'));
    }

    public function client(Sale $sale)
    {
        return view('sales.client', compact('sale'));
    }
}
