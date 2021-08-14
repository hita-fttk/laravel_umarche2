<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owener; //Eloquent エロくアント
use Illuminate\Support\Facades\DB; //QueryBuilder クエリービルダ
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;



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
        $oweners = Owener::select('id','name','email','created_at')->get();
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
        Owener::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        return redirect()
        ->route('admin.oweners.index')
        ->with('message','オーナー登録を実施しました');

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
        ->with('message','オーナー情報を更新しました。');
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
