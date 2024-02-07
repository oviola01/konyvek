<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Copy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(){
        $books = response()->json(Book::all());
        return $books;
    }

    public function show($id){
        $book = response()->json(Book::find($id));
        return $book;
    }

    public function store(Request $request){
        $Book = new Book();
        $Book->author = $request->author;
        $Book->title = $request->title;
        $Book->save();
    }

    public function update(Request $request, $id){
        $Book = Book::find($id);
        $Book->author = $request->author;
        $Book->title = $request->title;
        $Book->save();
    }
    public function destroy($id)
    {
        //find helyett a paraméter
        Book::find($id)->delete();
    }

    public function titleCount($title){
        $copies = DB::table('books as b')
            ->join('copies as c' ,'b.book_id','=','c.book_id') //kapcsolat leírása, akár több join is lehet
            ->where('b.title','=', $title) 	//esetleges szűrés
            ->count();				//esetleges aggregálás; ha select, akkor get() a vége
        return $copies;

    }

    public function hardAuthorTitle($hardcov){
        $books=DB::table('copies as c')
            ->select('author', 'title')
            ->join('books as b', 'c.book_id','=','b.book_id')
            ->where('hardcovered', $hardcov)
            ->get();
            return $books;
    }

    public function copiesInYear($year){
        $copies=Copy::whereYear('publication', $year) //csak az évet hasonlítja össze, akkor is, ha hónap, nap, stb is meg van adva
            ->join('books', 'copies.book_id','=','books.book_id')
            ->select('copy_id', 'author', 'title')
            ->get();
            return response()->json($copies);
    }

    public function authorsWithMoreBooks(){
        return DB::table('books')
            ->selectRaw('author, count(*)') //Raw szót ha a parancs végére teszem, akkor sql kódot írhatok a zárójelbe, aposztrófok közé
            ->groupBy('author')
            ->having('count(*)', '>', 1) //akár ide is tehetném azt, hogy raw, akkor nem kéne különszedni
            ->get();
    }

}