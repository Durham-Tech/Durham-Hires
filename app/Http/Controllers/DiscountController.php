<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Http\Requests\NewDiscount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $site = Request()->get('_site');
        $codes = Discount::where('site', $site->id)->get();

        return view('settings.discounts.index')->with(['codes' => $codes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = Request()->get('_site');
        return view('settings.discounts.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewDiscount $request)
    {
        $site = Request()->get('_site');

        $discount = new Discount;
        if (empty($request->name)) {
            $discount->name = 'Discount';
        } else {
            $discount->name = $request->name;
        }
        $discount->code = $request->code;
        $discount->site = $site->id;
        $discount->value = $request->discValue;
        $discount->type = $request->discType;

        $discount->save();
        return redirect('/'.$site->slug.'/settings/discounts');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function edit($s, Discount $discount)
    {
        $site = Request()->get('_site');
        if ($site->id != $discount->site) {
            return redirect('/'.$site->slug.'/settings/discounts');
        }
        return view('settings.discounts.edit')->with(['old' => $discount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Discount            $discount
     * @return \Illuminate\Http\Response
     */
    public function update($s, Request $request, Discount $discount)
    {
        $site = Request()->get('_site');
        if ($site->id == $discount->site) {

            if (empty($request->name)) {
                $discount->name = 'Discount';
            } else {
                $discount->name = $request->name;
            }
            $discount->code = $request->code;
            $discount->value = $request->discValue;
            $discount->type = $request->discType;
            $discount->save();
        }

        return redirect('/'.$site->slug.'/settings/discounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy($site, Discount $discount)
    {
        $site = Request()->get('_site');
        if ($site->id == $discount->site) {
            $discount->delete();
        }
        return redirect()->route('discounts.index', $site->slug);
    }
}
