<?php

namespace App\Http\Controllers\Owener;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use App\Models\PrimaryCategory;
use App\Models\Owener;
use App\Models\Shop;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\DB; //QueryBuilder クエリービルダ


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
        $shops = Shop::where('owener_id', Auth::id())
        ->select('id', 'name')
        ->get();

        $images = Image::where('owener_id', Auth::id())
        ->select('id','title','filename')
        ->orderBy('updated_at','desc')
        ->get();

        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owener.products.create',compact('shops','images','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'information' => 'required|string|max:1000',
            'price' => 'required|Integer',
            'sort_order' => 'nullable|integer',
            'quantity' => 'required|integer',
            'shop_id' => 'required|exists:shops,id',
            'category' => 'required|exists:secondary_categories,id',
            'image1' => 'nullable|exists:images,id',
            'image2' => 'nullable|exists:images,id',
            'image3' => 'nullable|exists:images,id',
            'image4' => 'nullable|exists:images,id',
            'is_selling' => 'required'
        ]);

        try{
            DB::transaction(function () use($request){
                $product = product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' =>$request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);
            },2);
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }




        return redirect()
        ->route('owener.products.index')
        ->with(['message'=>'商品登録をしました',
    'status'=>'info']);
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
