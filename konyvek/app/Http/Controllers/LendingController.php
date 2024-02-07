<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = response()->json(Lending::all());
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lending = new Lending();
        $lending->user_id = $request->user_id;
        $lending->copy_id = $request->copy_id;
        $lending->start = $request->start;
        $lending->end = $request->end;
        $lending->extension = $request->extension;
        $lending->notice = $request->notice;
        //ezek helyett írhatnám ezt is:
        //$res->fill($request->all()); - ezzel végigmegy a fillable mezőkön
        $lending->save();
    }

    /**
     * Display the specified resource.
     */
    public function show ($user_id, $copy_id, $start)
    {
        $lending = Lending::where('user_id', $user_id)->where('copy_id', $copy_id)->where('start', $start)->get();
        return $lending[0]; //get mindig listával tér vissza, ezért kell a nulladik eleme
        //....->first() a get helyett, akkor az első elemmel tér vissza, ami ha nincs, akkor null érték lesz, míg a get hibára fut
    }

    /**
     * Update the specified resource in storage.
     */
    //egyelőre ezt nincs értelme
    /* public function update(Request $request, $user_id, $copy_id, $start)
    {
        $lending = $this->show($user_id, $copy_id, $start);
        
    } */


    public function update(Request $request, $user_id, $copy_id, $start)
    {
        $res = $this->show($user_id, $copy_id, $start);
        $res->fill($request->all());
        $res->save();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id, $copy_id, $start)
    {
        Lending::where('user_id', $user_id)
        ->where('copy_id', $copy_id)
        ->where('start', $start)
        ->delete();
    }

    public function allLendingUserCopy(){
        //a modellben megírt függvények 
        //neveit használom
        $datas = Lending::with(['users', 'copies'])
        ->get();
        return $datas;
    }

    public function lendingsCountByUser()
    {
        $user = Auth::user();	//bejelentkezett felhasználó
        $lendings = Lending::with('users')->where('user_id','=', $user->id)->count();
        return $lendings;
    }

    public function broughtBackToday(){
        return DB::table('lendings as l')
            ->join('copies as c', 'l.copy_id', '=', 'c.copy_id')
            ->join('books as b', 'c.book_id', '=', 'b.book_id')
            ->whereDate('end', now()) //megadható, hogy '=', de ez az alapértelmezett
            ->get();
    }
}
