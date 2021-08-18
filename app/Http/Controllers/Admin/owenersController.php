<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owener; //Eloquent エロくアント
use Illuminate\Support\Facades\DB; //QueryBuilder クエリービルダ
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Models\Shop;

class owenersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date_now = Carbon::now();
        $date_parse = Carbon::parse(now());
        echo $date_now->year;
        echo $date_parse;

        // $e_all =  Owener::all();
        // $q_get = DB::table('oweners')->select('name','created_at')->get();
        // $q_first = DB::table('oweners')->select('name')->first();

        // $c_test = collect([
        //     'name' => 'ｒうぇあと'
        // ]);

        //
        // dd($e_all, $q_get, );
        $oweners = Owener::select('id','name','email','created_at')
        ->paginate(3);
        return view('admin.oweners.index', compact('oweners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.oweners.create');
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        try{
            DB::transaction(function () use($request){
                $owener = Owener::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                Shop::create([
                    'owener_id' => $owener->id,
                    'name' => '店名を入力してください',
                    'information' => '',
                    'filename' => '',
                    'is_selling' => true 
                ]);
            },2);
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }




        return redirect()
        ->route('admin.oweners.index')
        ->with(['message'=>'オーナー登録を実施しました',
    'status'=>'info']);

        //$request->name;
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
        $owener = Owener::findOrFail($id);
        // dd($owener);
        return view('admin.oweners.edit',compact('owener'));

        
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
        $owener = Owener::FindOrFail($id);
        $owener->name = $request->name;
        $owener->email = $request->email;
        $owener->password = Hash::make($request->password);
        $owener->save();

        return redirect()->route('admin.oweners.index')
        ->with(['message'=>'オーナー情報を更新しました。',
    'status' =>'info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd('削除処理');
        Owener::FindOrFail($id)->delete();
        return redirect()->route('admin.oweners.index')
        ->with(['message' => 'オーナー情報を削除しました。',
    'status' => 'alert']);
    }
    public function expiredOwenerIndex(){
        $expiredOweners = Owener::onlyTrashed()->get();
        return view('admin.expired-oweners',compact('expiredOweners'));
    }

    public function expiredOwenerDestroy($id){
        Owener::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.expired-oweners.index');
    }
}
