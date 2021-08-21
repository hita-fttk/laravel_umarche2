<?php

namespace App\Http\Controllers\Owener;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:oweners');

        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter('shop')); //文字列
            dd(Auth::id()); //数値

            $id = $request->route()->parameter('shop');
            if(!is_null($id)){
                $shopOwenerId = Shop::findOrFail($id)->owener->id;
                $shopId = (int)$shopOwenerId;
                $owenerId = Auth::id();
                if($shopId !== $owenerId){
                    abort(404);
                }
            }
            return $next($request);
        });
    }
    public function index()
    {
        $owenerId = Auth::id();
        $shops = Shop::where('owener_id', $owenerId)->get();

        return view('owener.shops.index',compact('shops'));
    }
    public function edit($id)
    {
        // dd(Shop::findOrFail($id));
    }
    public function update(Request $request, $id)
    {

    }
}
