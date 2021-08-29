<?php

namespace App\Http\Controllers\Owener;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use App\Models\SecondaryCategory;
use App\Models\Owener;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:oweners');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('product');
            if(!is_null($id)){
                $productsOwenerId = Product::findOrFail($id)->shop->owener->id;
                $productId = (int)$productsOwenerId;
                if($productId !== Auth::id()){
                    abort(404);
                }
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products = Owener::findOrFail(Auth::id())->shop->product;
        $owenerInfo = Owener::with('shop.product.imageFirst')
        ->where('id',Auth::id())->get();
        // dd($owenerInfo);
        // foreach($owenerInfo as $owener){
        //     // dd($owener->shop->product);
        //     foreach($owener->shop->product as $product){
        //         dd($product->imageFirst->filename);
        //     }
        // }

        return view('owener.products.index',compact('owenerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
