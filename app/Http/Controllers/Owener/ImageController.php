<?php

namespace App\Http\Controllers\Owener;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;



class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:oweners');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('image');
            if(!is_null($id)){
                $imagesOwenerId = Image::findOrFail($id)->owener->id;
                $imageId = (int)$imagesOwenerId;
                $owenerId = Auth::id();
                if($imageId !== $owenerId){
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
        // phpinfo();
        $owenerId = Auth::id();
        $images = Image::where('owener_id', $owenerId)
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        return view('owener.images.index',compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owener.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
       $imageFiles = $request->file('files');
       if(!is_null($imageFiles)){
           foreach($imageFiles as $imageFile){
            $fileNameToStore = ImageService::upload($imageFile,'product');
            Image::create([
                'owener_id' => Auth::id(),
                'filename' => $fileNameToStore,
            ]);
           }
       }
       return redirect()->route('owener.images.index')
       ->with(['message'=> '画像登録を実施しました。',
               'status' => 'info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('owener.images.edit',compact('image'));
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
        $request->validate([
            'title' => 'string|max:50'
        ]);

        $image = Image::FindOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()->route('owener.images.index')
        ->with(['message'=> '画像情報を更新しました。',
                'status' => 'info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        $imageInProducts = Product::where('image1',$image->id)
        ->orwhere('image2', $image->id)
        ->orwhere('image3', $image->id)
        ->orwhere('image4', $image->id)
        ->get();

        if($imageInProducts){
            $imageInProducts->each(function($product) use($image){
                if($product->image1 === $image->id){
                    $product->image1 = null;
                    $product->save();
                }
                if($product->image2 === $image->id){
                    $product->image2 = null;
                    $product->save();
                }
                if($product->image3 === $image->id){
                    $product->image3 = null;
                    $product->save();
                }
                if($product->image4 === $image->id){
                    $product->image4 = null;
                    $product->save();
                }
            });
        }

        $filePath = 'public/product'.$image->filename;

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        Image::FindOrFail($id)->delete();
        return redirect()->route('owener.images.index')
        ->with(['message' => '画像を削除しました。',
                'status' => 'alert']);
    }
}
