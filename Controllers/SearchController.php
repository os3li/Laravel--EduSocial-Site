<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use DB;

class SearchController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }


    public function getResult(Request $request)
    {
        $query = $request->input('query');

        if(!$query){
            return redirect()->route('home');
        }
        
        $users = user::where('name','LIKE',"%{$query}%")
        ->orWhere('link','LIKE',"%{$query}%")
        ->get();

        return view('search.result')->with('users',$users);
    }
}
