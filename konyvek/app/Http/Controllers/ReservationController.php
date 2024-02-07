<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = response()->json(Reservation::all());

        return $reservations;
    }

    public function show($book_id, $user_id, $start)
    {
        $res = Reservation::where('book_id', $book_id)
            ->where('user_id', $user_id)
            ->where('start', $start)
            ->first();

        return $res;
    }

    public function store(Request $request)
    {
        $res = new Reservation();
        $res->fill($request->all());

        $res->save();
    }

    public function update(Request $request, $book_id, $user_id, $start)
    {
        $res = $this->show($book_id, $user_id, $start);
        $res->fill($request->all());
        $res->save();
    }

    public function destroy($book_id, $user_id, $start)
    {
        $this->show($book_id, $user_id, $start)->delete();
    }
}
