<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Cli_account;
use App\Models\Money_trans;
use App\models\Sup_account;
use App\Models\Client_trans;
use Illuminate\Http\Request;
use App\Models\Wared_monserf;
use App\Models\supplier_trans;
use App\Models\Cli_account_main;
use App\Models\Sup_account_main;

class ReportController extends Controller
{
    public function purchase()
    {
        $purchases = Purchase::whereDate('created_at', today())->where('inv_chack', '!=', 2)->get();
        return view('reports.purchases.today', compact('purchases'));
    }

    public function cash_purchase()
    {
        $purchases = Purchase::where('inv_chack', '1')->whereDate('created_at', today())->orderBy('id', 'desc')->get();
        $cash_type = '';
        return view('reports.purchases.cash', compact('purchases', 'cash_type'));
    }

    public function purchase_search(Request $request)
    {
        $first_date = Purchase::select('created_at')->where('inv_chack', '1')->first();
        $from = !empty($request->from_date) ? $request->from_date : date_format($first_date->created_at, 'Y-m-d');
        $to = !empty($request->to_date) ? $request->to_date : date_format(today(), 'Y-m-d');
        $cash_type = $request->cash_type;
        if (empty($request->from_date) && empty($request->to_date) && empty($request->cash_type)) {
            $purchases = Purchase::where('inv_chack', '1')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->orderBy('id', 'desc')->get();
            return view('reports.purchases.cash', compact('purchases', 'from', 'to', 'cash_type'));
        }
        $purchases = Purchase::where('inv_chack', '1')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)
            ->when(!empty($cash_type), function ($q) use ($cash_type) {
                return $q->where('inv_type', $cash_type);
            })->orderBy('id', 'desc')->get();
        return view('reports.purchases.cash', compact('purchases', 'from', 'to', 'cash_type'));
    }

    public function uncash_purchase()
    {
        $purchases = Purchase::where('inv_chack', '0')->orderBy('id', 'desc')->get();
        return view('reports.purchases.uncash', compact('purchases'));
    }

    public function sale()
    {
        $sales = Sale::whereDate('created_at', today())->get();
        return view('reports.sales.today', compact('sales'));
    }

    public function cash_sale()
    {
        $sales = Sale::where('inv_chack', '1')->whereDate('created_at', today())->orderBy('id', 'desc')->get();
        $cash_type = '';
        return view('reports.sales.cash', compact('sales', 'cash_type'));
    }

    public function sale_search(Request $request)
    {
        $first_date = Sale::select('created_at')->where('inv_chack', '1')->first();
        $from = !empty($request->from_date) ? $request->from_date : date_format($first_date->created_at, 'Y-m-d');
        $to = !empty($request->to_date) ? $request->to_date : date_format(today(), 'Y-m-d');
        $cash_type = $request->cash_type;

        if (empty($request->from_date) && empty($request->to_date) && empty($request->cash_type)) {
            $sales = Sale::where('inv_chack', '1')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->orderBy('id', 'desc')->get();
            return view('reports.sales.cash', compact('sales', 'from', 'to', 'cash_type'));
        }
        $sales = Sale::where('inv_chack', '1')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)
            ->when(!empty($cash_type), function ($q) use ($cash_type) {
                return $q->where('inv_type', $cash_type);
            })->orderBy('id', 'desc')->get();
        return view('reports.sales.cash', compact('sales', 'from', 'to', 'cash_type'));
    }

    public function uncash_sale()
    {
        $sales = Sale::where('inv_chack', '0')->orderBy('id', 'desc')->get();
        return view('reports.sales.uncash', compact('sales'));
    }

    public function clients_to()
    {
        $clients = Cli_account_main::where('acc_check', 0)->get();

        foreach ($clients as  $client) {
            foreach (sales($client) as $sal) {
                if ($sal->uncash_type != 1)
                    client_account($sal);
            }
        }
        $clients = Cli_account_main::where('acc_check', 0)->get();

        return view('reports.clients.money', compact('clients'));
    }

    public function client_to($id)
    {
        $clients = Cli_account::with('client')->where('acc_check', 0)->where('client_id', $id)->get();
        return view('reports.clients.money_det', compact('clients'));
    }

    public function suppliers_from()
    {
        $suppliers = Sup_account_main::where('acc_check', 0)->get();

        foreach ($suppliers as  $supplier) {
            foreach (purchases($supplier) as $pur) {
                if ($pur->uncash_type != 1)
                    supplier_account($pur);
            }
        }

        $suppliers = Sup_account_main::where('acc_check', 0)->get();

        return view('reports.suppliers.money', compact('suppliers'));
    }

    public function supplier_to($id)
    {
        $suppliers = Sup_account::with('supplier')->where('acc_check', 0)->where('supplier_id', $id)->get();
        return view('reports.suppliers.money_det', compact('suppliers'));
    }

    public function clients_trans()
    {
        $clients_trans = Client_trans::whereYear('created_at', now()->year)->get();
        foreach ($clients_trans as  $client_trans) {
            foreach (client_trans($client_trans) as $sal) {
                if ($sal->uncash_type != 1)
                    client_account($sal);
            }
        }

        $clients_trans = Client_trans::whereYear('created_at', now()->year)->get();
        $clients = Client::select('id', 'name')->get();
        return view('reports.clients.trans', compact('clients_trans', 'clients'));
    }

    public function client_trans(Request $request)
    {
        $client_trans = Client_trans::where('client_id', $request->id)->whereYear('created_at', now()->year)->get();
        $clients = Client::select('id', 'name')->get();
        if ($request->id == 0) {
            $client_trans = Client_trans::whereYear('created_at', now()->year)->get();
        }
        return view('includes.ajax.reports.client.trans', compact('client_trans', 'clients'));
    }

    public function suppliers_trans()
    {
        $suppliers_trans = supplier_trans::whereYear('created_at', now()->year)->get();
        foreach ($suppliers_trans as  $supplier_trans) {
            foreach (supplier_trans($supplier_trans) as $pur) {
                if ($pur->uncash_type != 1)
                    supplier_account($pur);
            }
        }

        $suppliers_trans = supplier_trans::whereYear('created_at', now()->year)->get();
        $suppliers = supplier::select('id', 'name')->get();
        return view('reports.suppliers.trans', compact('suppliers_trans', 'suppliers'));
    }

    public function supplier_trans(Request $request)
    {
        $supplier_trans = supplier_trans::where('supplier_id', $request->id)->whereYear('created_at', now()->year)->get();
        $suppliers = supplier::select('id', 'name')->get();
        if ($request->id == 0) {
            $supplier_trans = supplier_trans::whereYear('created_at', now()->year)->get();
        }
        return view('includes.ajax.reports.supplier.trans', compact('supplier_trans', 'suppliers'));
    }

    public function save()
    {
        $money = Money_trans::where('type', 1)->whereYear('created_at', now()->year)->get();
        $from =  date('Y') . '-01-01';
        $to = date_format(today(), 'Y-m-d');
        $total = total_money($money);
        return view('reports.money.save', compact('money', 'from', 'to', 'total'));
    }

    public function save_search(Request $request)
    {
        $from = !empty($request->from_date) ? $request->from_date : date('Y') . '-01-01';
        $to = !empty($request->to_date) ? $request->to_date : date_format(today(), 'Y-m-d');
        if (empty($request->from_date) && empty($request->to_date)) {
            $money = Money_trans::where('type', 1)->whereYear('created_at', now()->year)->get();
            $total = total_money($money);
            return view('reports.money.save', compact('money', 'from', 'to', 'total'));
        }
        $money = Money_trans::where('type', 1)->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->orderBy('id', 'desc')->get();
        $total = total_money($money);
        return view('reports.money.save', compact('money', 'from', 'to', 'total'));
    }

    public function bank()
    {
        $money = Money_trans::where('type', 2)->whereYear('created_at', now()->year)->get();
        $from =  date('Y') . '-01-01';
        $to = date_format(today(), 'Y-m-d');
        $bank_type = '';
        $banks = Bank::where('id', '!=', 1)->get();
        $total = total_money($money);
        return view('reports.money.bank', compact('money', 'from', 'to', 'bank_type', 'banks', 'total'));
    }

    public function bank_search(Request $request)
    {
        $from = !empty($request->from_date) ? $request->from_date : date('Y') . '-01-01';
        $to = !empty($request->to_date) ? $request->to_date : date_format(today(), 'Y-m-d');
        $bank_type = $request->bank_type;
        $banks = Bank::where('id', '!=', 1)->get();
        if (empty($request->from_date) && empty($request->to_date) && empty($request->bank_type)) {
            $money = Money_trans::where('type', '2')->whereYear('created_at', now()->year)->get();
            $total = total_money($money);
            return view('reports.money.bank', compact('money', 'from', 'to', 'bank_type', 'banks', 'total'));
        }
        $money = Money_trans::where('type', '2')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)
            ->when(!empty($bank_type), function ($q) use ($bank_type) {
                return $q->where('bank_id', $bank_type);
            })->orderBy('id', 'desc')->get();

        $total = total_money($money);
        return view('reports.money.bank', compact('money', 'from', 'to', 'bank_type', 'banks', 'total'));
    }

    public function wared()
    {
        $wareds = Wared_monserf::whereNotNull('to')->whereMonth('created_at', now()->month)->get();
        $from =  date('Y') . '-' . date('m') . '-01';
        $to =  date_format(today(), 'Y-m-d');
        $total = total_wared($wareds);
        return view('reports.war_mon.wared', compact('wareds', 'from', 'to', 'total'));
    }

    public function wared_search(Request $request)
    {
        $from = !empty($request->from_date) ? $request->from_date : date('Y') . '-01-01';
        $to = !empty($request->to_date) ? $request->to_date : date_format(today(), 'Y-m-d');
        if (empty($request->from_date) && empty($request->to_date)) {
            $wareds = Wared_monserf::whereNotNull('to')->whereMonth('created_at', now()->month)->get();
            $total = total_wared($wareds);
            return view('reports.war_mon.wared', compact('wareds', 'from', 'to', 'total'));
        }
        $wareds = Wared_monserf::whereNotNull('to')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->orderBy('id', 'desc')->get();

        $total = total_wared($wareds);
        return view('reports.war_mon.wared', compact('wareds', 'from', 'to', 'total'));
    }

    public function monserfe()
    {
        $monserfes = Wared_monserf::whereNotNull('from')->whereMonth('created_at', now()->month)->get();
        $from =  date('Y') . '-' . date('m') . '-01';
        $to =  date_format(today(), 'Y-m-d');
        $note = '';
        $expenses = Expense::all();
        $total = total_monserfe($monserfes);
        return view('reports.war_mon.monserfe', compact('monserfes', 'from', 'to', 'note', 'expenses', 'total'));
    }

    public function monserfe_search(Request $request)
    {
        $from = !empty($request->from_date) ? $request->from_date : date('Y') . '-01-01';
        $to = !empty($request->to_date) ? $request->to_date : date_format(today(), 'Y-m-d');
        $note = $request->note;
        $expenses = Expense::all();
        if (empty($request->from_date) && empty($request->to_date) && empty($request->note)) {
            $monserfes = Wared_monserf::whereNotNull('from')->whereMonth('created_at', now()->month)->get();
            $total = total_monserfe($monserfes);
            return view('reports.war_mon.monserfe', compact('monserfes', 'from', 'to', 'note', 'expenses', 'total'));
        }
        $monserfes = Wared_monserf::whereNotNull('from')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)
            ->when(!empty($request->note), function ($q) use ($note) {
                return $q->where('type', $note);
            })->orderBy('id', 'desc')->get();

        $total = total_monserfe($monserfes);
        return view('reports.war_mon.monserfe', compact('monserfes', 'from', 'to', 'note', 'expenses', 'total'));
    }
}
