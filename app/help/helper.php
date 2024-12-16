<?php

use App\Models\Item;
use App\Models\Stock;
use App\Models\SiteSetting;

function client_account($sal)
{
    $tot_price = 0;
    $old_total_price = $sal->total_price;

    foreach ($sal->items as $item) {
        $unit_price = $item->sale_price * $item->pivot->qty;
        $tot_price += $unit_price;
    }

    $cli_account_main = $sal->client->cli_account_mains()->where('acc_check', 0)->first();

    if ($tot_price > $old_total_price) {
        $cli_account_main->update([
            'total_price' => $tot_price - $old_total_price + $cli_account_main->total_price,
            'sub_price' => $tot_price - $old_total_price + $cli_account_main->sub_price
        ]);
    } elseif ($tot_price < $old_total_price) {
        $cli_account_main->update([
            'total_price' => $cli_account_main->total_price - ($old_total_price - $tot_price),
            'sub_price' => $cli_account_main->sub_price - ($old_total_price - $tot_price)
        ]);
    }

    $sal->update([
        'total_price' => $tot_price
    ]);

    $sal->client_trans()->first()->update([
        'to' => $tot_price
    ]);

    $sal->cli_account()->update([
        'total_price' => $tot_price,
        'sub_price' => $tot_price - $sal->cli_account->pay_price
    ]);
}

function sales($client)
{
    return $client->client->sales()->where('inv_chack', 0)->get();
}

function supplier_account($pur)
{
    $tot_price = 0;
    $old_total_price = $pur->total_price;

    foreach ($pur->items as $item) {
        $unit_price = $item->purches_price * $item->pivot->qty;
        $tot_price += $unit_price;
    }

    $sup_account_main = $pur->supplier->sup_account_mains()->where('acc_check', 0)->first();

    if ($tot_price > $old_total_price) {
        $sup_account_main->update([
            'total_price' => $tot_price - $old_total_price + $sup_account_main->total_price,
            'sub_price' => $tot_price - $old_total_price + $sup_account_main->sub_price
        ]);
    } elseif ($tot_price < $old_total_price) {
        $sup_account_main->update([
            'total_price' => $sup_account_main->total_price - ($old_total_price - $tot_price),
            'sub_price' => $sup_account_main->sub_price - ($old_total_price - $tot_price)
        ]);
    }

    $pur->update([
        'total_price' => $tot_price
    ]);

    $pur->supplier_trans()->first()->update([
        'from' => $tot_price
    ]);

    $pur->sup_account()->update([
        'total_price' => $tot_price,
        'sub_price' => $tot_price - $pur->sup_account->pay_price
    ]);
}

function purchases($supplier)
{
    return $supplier->supplier->purchases()->where('inv_chack', 0)->get();
}

function client_trans($client_trans)
{
    return $client_trans->sale()->where('inv_chack', 0)->get();
}

function supplier_trans($supplier_trans)
{
    return $supplier_trans->purchase()->where('inv_chack', 0)->get();
}

function total_money($money)
{
    $from_save = 0;
    $to_save = 0;
    foreach ($money as $value) {
        $from_save += $value->from;
        $to_save += $value->to;
    }
    return  $to_save - $from_save;
}

function total_wared($wared)
{
    $total = 0;
    foreach ($wared as $value) {
        $total += $value->to;
    }
    return $total;
}

function total_monserfe($mon)
{
    $total = 0;
    foreach ($mon as $value) {
        $total += $value->from;
    }
    return $total;
}

function total_cal_uncash_purchase($purchases)
{
    $total = 0;
    foreach ($purchases as $purchase) {
        if ($purchase->uncash_type == 1) {
            $total_purchase = $purchase->total_price;
        } else {
            supplier_account($purchase);
            $total_purchase = $purchase->total_price;
        }
        $total += $total_purchase;
    }
    return $total;
}

function total_cal($money)
{
    $total = 0;
    foreach ($money as $value) {
        $tot_money = $value->total_price;
        $total += $tot_money;
    }
    return $total;
}

function total_cal_uncash_sale($sales)
{
    $total = 0;
    foreach ($sales as $sale) {
        if ($sale->uncash_type == 1) {
            $total_sale = $sale->total_price;
        } else {
            client_account($sale);
            $total_sale = $sale->total_price;
        }
        $total += $total_sale;
    }
    return $total;
}

function total_pay($mon)
{
    $total = 0;
    foreach ($mon as $value) {
        $total += $value->sub_price;
    }
    return $total;
}

function slider_move()
{
    $setting = SiteSetting::first();
    return $setting->slide == 1 ? 'toggled' : '';
}

function tab()
{
    $setting = SiteSetting::first();

    return $setting->tab;
}

function route_tab()
{
    $setting = SiteSetting::first();
    if ($setting->tab == 1) {
        return route('category.create');
    } elseif ($setting->tab == 2) {
        return route('item.create');
    } elseif ($setting->tab == 3) {
        return route('safe.create');
    } elseif ($setting->tab == 4) {
        return route('bank.create');
    } elseif ($setting->tab == 5) {
        return route('expense.create');
    }
}

function alert_item()
{
    $items = Item::get();
    $stock_name = [];
    $item_name = [];
    foreach ($items as $item) {
        $check = $item->stocks()->where('qty', '<', 5)->get();
        foreach ($check as $val) {
            $stock_name[] = $val->name;
            $item_name[] = Item::find($val->pivot->item_id)->name;
        }
    }
    return [$stock_name, $item_name];
}

function friend_total($purchases, $sales)
{
    // cli_account->sub_price
    $total_purchase = 0;
    $total_sale = 0;
    foreach ($purchases as $purchase) {

        $total_purchase += $purchase->sup_account->sub_price;
    }

    foreach ($sales as $sale) {

        $total_sale += $sale->cli_account->sub_price;
    }

    $total = $total_sale - $total_purchase;
    return [$total , $total_sale , $total_purchase];
}
