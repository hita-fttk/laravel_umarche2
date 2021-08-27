<?php

namespace App\Http\Controllers\Owener;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:oweners');

        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter('shop')); //文字列
            // dd(Auth::id()); //数値

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
        // phpinfo();
        $owenerId = Auth::id();
        $shops = Shop::where('owener_id', $owenerId)->get();

        return view('owener.shops.index',compact('shops'));
    }
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);

        return view('owener.shops.edit',compact('shop'));
        // dd(Shop::findOrFail($id));
    }
    public function update(UploadImageRequest $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'information' => 'required|string|max:1000',
            'is_selling' => 'required',
        ]);

        $imageFile = $request->image;
        if(!is_null($imageFile) && $imageFile->isValid() ){
        $fileNameToStore = ImageService::upload($imageFile,'shops');
        }

        $shop = Shop::FindOrFail($id);
        $shop->name = $request->name;
        $shop->information = $request->information;
        $shop->is_selling = $request->is_selling;
        if(!is_null($imageFile) && $imageFile->isValid()){
            $shop->filename = $fileNameToStore;
        }

        $shop->save();

        return redirect()->route('owener.shops.index')
        ->with(['message'=> '店舗情報を更新しました。',
                'status' => 'info']);
    }
}
