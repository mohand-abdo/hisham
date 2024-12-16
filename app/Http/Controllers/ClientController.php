<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Cli_account;
use App\Models\Money_trans;
use App\Models\Client_trans;
use Illuminate\Http\Request;
use App\Models\Cli_account_det;
use App\Models\Cli_account_main;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Clients\ClientRequest;
use App\Http\Requests\Clients\GetMoneyRequest;
use App\models\Sup_account;
use App\Models\Sup_account_main;
use App\Models\Supplier;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        $safes = Bank::where('type', 0)->get();
        $banks = Bank::where('type', 1)->get();
        return view('clients.index', compact('clients', 'safes', 'banks'));
    }

    public function create()
    {
        $client = new client();
        return view('clients.create', compact('client'));
    }

    public function store(ClientRequest $request)
    {
        $data = $request->all();
        $request->friend ? $data['friend'] = 1 : $data['friend'] = 2;
        $data['phone'] = array_filter($request->phone);
        $client = Client::create($data);
        $data['client_id'] = $client->id;
        $supplier = Supplier::create($data);
        $client->update(['supplier_id' => $supplier->id]);
        if ($request->price != "") {
            $this->cli_account_main($client, $request);
            $this->cli_account($client, $request);
            $this->client_trans($client, $request);
        }
        return redirect()->route('client.index')->with('success', 'تمت الاضافة بنجاح');
    }

    public function show(Client $client)
    {
        return $client;
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $data = $request->all();
        $data['phone'] = array_filter($request->phone);
        $request->friend ? $data['friend'] = 1 : $data['friend'] = 2;
        $client->update($data);
        $supplier = Supplier::where('client_id', $client->id)->first();
        $data['client_id'] = $client->id;
        $supplier->update($data);
        return redirect()->route('client.index')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy(Client $client)
    {
        $supplier = Supplier::where('client_id', $client->id)->first();
        $supplier->delete();
        $client->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function get_money(GetMoneyRequest $request)
    {
        if ($request->move_type == 1) {
            if ($request->inv_type == 1) {
                $save = Money_trans::where('bank_id', $request->safe)->get();
                $total_save = total_money($save);
                if ($total_save - $request->price < 0) {
                    return back()->withErrors('لا يتوفر هذا المبلغ لديك');
                }
            } elseif ($request->inv_type == 2) {
                $save = Money_trans::where('bank_id', $request->bank)->get();
                $total_save = total_money($save);

                if ($total_save - $request->price < 0) {
                    return back()->withErrors('لا يتوفر هذا المبلغ لديك');
                }
            }
            $client = Client::findOrFail($request->client_id);
            $this->cli_account_main($client, $request);
            $this->cli_account($client, $request);
            $this->client_trans($client, $request);
            $this->money_trans($request);
        } elseif ($request->move_type == 2) {
            $supplier = Supplier::where('client_id', $request->client_id)->firstOrFail();
            $this->sup_account_main($supplier, $request);
            $this->sup_account($supplier, $request);
            $this->supplier_trans($supplier, $request);
            $this->money_trans1($request);
        }


        return back()->with('success', 'تمت العملية بنجاح');
    }

    public function give_me($id)
    {
        $cli_account = Cli_account::with('client')->findOrFail($id);
        $pay_det = Cli_account_det::where('cli_account', $cli_account->id)->get();
        $safes = Bank::where('type', 0)->get();
        $banks = Bank::where('type', 1)->get();
        return view('clients.pay', compact('cli_account', 'pay_det', 'safes', 'banks'));
    }

    public function account_pay(Request $request)
    {
        $cli_account = Cli_account::findOrFail($request->cli_account_id);
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
        $cli_account_det->cli_account = $cli_account->id;
        $cli_account_det->price = $request->price;
        $cli_account_det->inv_type = $request->inv_type;
        $cli_account_det->save();

        Client_trans::create([
            'client_id' => $cli_account->client_id,
            // 'sale_id' => $request->sale_id,
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
        }

        Money_trans::create([
            'user_id' => Auth::user()->id,
            'to' => $request->price, // غيرتها
            'type' => $request->inv_type,
            'bank_id' => $request->inv_type == 1 ? $request->safe : $request->bank,
            'note' => 'سداد دين'
        ]);

        return redirect()->back();
    }

    public function account_friend(Client $client)
    {
        if ($client->friend != 1)
            return back()->withErrors('هذا العميل ليس من اصحاب الدكاكين');
        $sup_account_main = Sup_account_main::where(['supplier_id' => $client->supplier_id, 'acc_check' => 0])->first();
        $cli_account_main = Cli_account_main::where(['client_id' => $client->id, 'acc_check' => 0])->first();
        return view('clients.friend', compact('client', 'sup_account_main', 'cli_account_main'));
    }

    private function cli_account_main($client, $request)
    {
        $cli_account_main = Cli_account_main::where('client_id', $request->client_id)->where('acc_check', 0)->first();
        if ($cli_account_main) {
            $cli_account_main->update([
                'total_price' => $cli_account_main->total_price + $request->price,
                'sub_price' => $cli_account_main->total_price + $request->price - $cli_account_main->pay_price,
            ]);
        } else {
            $cli_account_main = new Cli_account_main();
            $cli_account_main->client_id = $client->id;
            $cli_account_main->total_price = $request->price;
            $cli_account_main->sub_price = $request->price;
            $cli_account_main->save();
        }
        return $cli_account_main;
    }

    private function cli_account($client, $request)
    {
        $cli_account = new Cli_account();
        $cli_account->client_id = $client->id;
        $cli_account->total_price = $request->price;
        $cli_account->sub_price = $request->price;
        $cli_account->save();
        return $cli_account;
    }

    private function client_trans($client, $request)
    {
        $client_trans = new Client_trans();
        $client_trans->client_id = $client->id;
        $client_trans->to = $request->price;
        $client_trans->save();
        return $client_trans;
    }

    private function money_trans($request)
    {
        $moeny_trans = new money_trans();
        $moeny_trans->user_id = Auth::user()->id;
        $moeny_trans->from = $request->price; //changed
        $moeny_trans->type = $request->inv_type;
        $request->inv_type == 1 ? $moeny_trans->bank_id = $request->safe : $moeny_trans->bank_id = $request->bank;
        $moeny_trans->note = 'دين لعميل';
        $moeny_trans->save();
        return $moeny_trans;
    }

    private function sup_account_main($supplier, $request)
    {
        $sup_account_main = Sup_account_main::where('supplier_id', $supplier->id)->where('acc_check', 0)->first();
        if ($sup_account_main) {
            $sup_account_main->update([
                'total_price' => $sup_account_main->total_price + $request->price,
                'sub_price' => $sup_account_main->total_price + $request->price - $sup_account_main->pay_price,
            ]);
        } else {
            $sup_account_main = new Sup_account_main();
            $sup_account_main->supplier_id = $supplier->id;
            $sup_account_main->total_price = $request->price;
            $sup_account_main->sub_price = $request->price;
            $sup_account_main->save();
        }
        return $sup_account_main;
    }

    private function sup_account($supplier, $request)
    {
        $sup_account = new Sup_account();
        $sup_account->supplier_id = $supplier->id;
        $sup_account->total_price = $request->price;
        $sup_account->sub_price = $request->price;
        $sup_account->save();
        return $sup_account;
    }

    private function supplier_trans($supplier, $request)
    {
        $client_trans = new Client_trans();
        $client_trans->client_id = $supplier->client_id;
        $client_trans->from = $request->price;
        $client_trans->save();
        return $client_trans;
    }

    private function money_trans1($request)
    {
        $moeny_trans = new money_trans();
        $moeny_trans->user_id = Auth::user()->id;
        $moeny_trans->to = $request->price; //changed
        $moeny_trans->type = $request->inv_type;
        $request->inv_type == 1 ? $moeny_trans->bank_id = $request->safe : $moeny_trans->bank_id = $request->bank;
        $moeny_trans->note = 'دين لعميل';
        $moeny_trans->save();
        return $moeny_trans;
    }
}
