<?php

namespace App\Http\Controllers\Owener;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;

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
        $imageFile = $request->image;
        if(!is_null($imageFile) && $imageFile->isValid() ){
            // Storage::putFile('public/shops',$imageFile); //リサイズなしの場合
         $filename = uniqid(rand().'_');
         $extension = $imageFile->extension();
         $filenameToStore = $filename. '.'.$extension;
         $resizedImage = InterventionImage::make($imageFile)->resize(1920,1080)->encode();  
        //  dd($imageFile, $resizedImage);

         Storage::put('public/shops/'.$filenameToStore, $resizedImage);
        }

        return redirect()->route('owener.shops.index');
    }
}
