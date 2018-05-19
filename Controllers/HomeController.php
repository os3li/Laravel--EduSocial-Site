<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Status;
use Auth;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::check()){
            $statuses = Status::notReply()->where(function($query){
                return $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('user_id', Auth::user()->friends()->lists('id'));
            })->orderBy('created_at', 'desc')
              ->paginate(5);
            return view('timeline.index')->with('statuses', $statuses);
            //dd($statuses);
        }
        
        return view('home');
    }
}
