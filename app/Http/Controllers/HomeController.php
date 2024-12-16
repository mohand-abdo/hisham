<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Money_trans;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\Cli_account_main;
use App\Models\Sup_account_main;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $purchases_cash = Purchase::where('inv_chack', '1')->whereMonth('created_at', now()->month)->get();
        $total_purchase_cash = total_cal($purchases_cash);
        $purchases_uncash = Purchase::where('inv_chack', '0')->whereMonth('created_at', now()->month)->get();
        $total_purchase_uncash = total_cal_uncash_purchase($purchases_uncash);
        $sale_cash = Sale::where('inv_chack', '1')->whereMonth('created_at', now()->month)->get();
        $total_sale_cash = total_cal($sale_cash);
        $sale_uncash = Sale::where('inv_chack', '0')->whereMonth('created_at', now()->month)->get();
        $total_sale_uncash = total_cal_uncash_sale($sale_uncash);
        $suppliers = Sup_account_main::where('acc_check', 0)->get();
        $total_suppliers = total_pay($suppliers);
        $clients = Cli_account_main::where('acc_check', 0)->get();
        $total_clients = total_pay($clients);
        $save = Money_trans::where('type', 1)->get();
        $total_save = total_money($save);
        $bank = Money_trans::where('type', 2)->get();
        $total_bank = total_money($bank);

        $setting = SiteSetting::first();
        if ($setting->linechart == 0) {
            $charts = Sale::whereYear('created_at', now()->year)->select(DB::raw('month(created_at) as month, sum(total_price) as price'))->groupBy('month')->orderBy('month', 'asc')->get()->toArray();
        } else {
            $charts = Purchase::whereYear('created_at', now()->year)->select(DB::raw('month(created_at) as month, sum(total_price) as price'))->groupBy('month')->orderBy('month', 'asc')->get()->toArray();
            // dd($charts[1]);
        }

        $array = [];
        $array1 = [];
        if (is_array($charts) && !empty($charts)) {
            for ($i = 1; $i < $charts[0]['month']; $i++)
                $array[$i] = 0;
            if (is_array($array) && !empty($array)) {
                $charts = array_merge($array, $charts);
            }
        }

        // dd($charts[1]);


        if ($setting->piechart == 0) {
            $cash = Sale::where('inv_chack', 1)->whereYear('created_at', now()->year)->get()->count();
            $uncash = Sale::where('inv_chack', 0)->whereYear('created_at', now()->year)->get()->count();
        } else {
            $cash = Purchase::where('inv_chack', 1)->whereYear('created_at', now()->year)->get()->count();
            $uncash = Purchase::where('inv_chack', 0)->whereYear('created_at', now()->year)->get()->count();
        }
        if ($cash != 0 || $uncash != 0) {
            $count_cash = $cash * 100 / ($cash + $uncash);
            $count_uncash = $uncash * 100 / ($cash + $uncash);
        } else {
            $count_cash = 0;
            $count_uncash = 0;
        }
        return view('home', compact('total_purchase_cash', 'total_purchase_uncash', 'total_sale_cash', 'total_sale_uncash', 'total_suppliers', 'total_clients', 'total_save', 'total_bank', 'charts', 'setting', 'count_cash', 'count_uncash'));
    }

    public function chart_line()
    {
        $setting = SiteSetting::first();
        $charts = Purchase::where('inv_type', '!=', 2)->whereYear('created_at', now()->year)->select(DB::raw('month(created_at) as month, sum(total_price) as price'))->groupBy('month')->orderBy('month', 'asc')->get()->toArray();
        $setting->update([
            'linechart' => 1
        ]);
        $array = [];
        if (is_array($charts) && !empty($charts)) {
            for ($i = 1; $i < $charts[0]['month']; $i++)
                $array[$i] = 0;
            if (is_array($array) && !empty($array)) {
                $charts = array_merge($array, $charts);
            }
        }

        return view('includes.ajax.chart_line', compact('charts'));
    }

    public function chart_line1()
    {
        $setting = SiteSetting::first();
        $charts = Sale::whereYear('created_at', now()->year)->select(DB::raw('month(created_at) as month, sum(total_price) as price'))->groupBy('month')->orderBy('month', 'asc')->get()->toArray();
        $setting->update([
            'linechart' => 0
        ]);
        $array = [];
        if (is_array($charts) && !empty($charts)) {
            for ($i = 1; $i < $charts[0]['month']; $i++)
                $array[$i] = 0;
            if (is_array($array) && !empty($array)) {
                $charts = array_merge($array, $charts);
            }
        }
        return view('includes.ajax.chart_line', compact('charts'));
    }

    public function chart_pie()
    {
        $setting = SiteSetting::first();
        $cash = Purchase::where('inv_chack', 1)->whereYear('created_at', now()->year)->get()->count();
        $uncash = Purchase::where('inv_chack', 0)->whereYear('created_at', now()->year)->get()->count();
        if ($cash != 0 || $uncash != 0) {
            $count_cash = $cash * 100 / ($cash + $uncash);
            $count_uncash = $uncash * 100 / ($cash + $uncash);
        } else {
            $count_cash = 0;
            $count_uncash = 0;
        }
        $setting->update([
            'piechart' => 1
        ]);

        return view('includes.ajax.chart_pie', compact('count_cash', 'count_uncash', 'setting'));
    }

    public function chart_pie1()
    {
        $setting = SiteSetting::first();
        $cash = Sale::where('inv_chack', 1)->whereYear('created_at', now()->year)->get()->count();
        $uncash = Sale::where('inv_chack', 0)->whereYear('created_at', now()->year)->get()->count();
        if ($cash != 0 || $uncash != 0) {
            $count_cash = $cash * 100 / ($cash + $uncash);
            $count_uncash = $uncash * 100 / ($cash + $uncash);
        } else {
            $count_cash = 0;
            $count_uncash = 0;
        }
        $setting->update([
            'piechart' => 0
        ]);

        return view('includes.ajax.chart_pie', compact('count_cash', 'count_uncash', 'setting'));
    }

    public function slide()
    {
        $setting = SiteSetting::first();
        if ($setting->slide == 0) {
            $setting->update([
                'slide' => 1
            ]);
        } else {
            $setting->update([
                'slide' => 0
            ]);
        }
    }
}
