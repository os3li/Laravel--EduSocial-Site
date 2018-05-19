<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;


class profileController extends Controller
{
   


    public function getProfile($link)
    {
      
      $user = User::where('link',$link)->get()->first();
      if(!$user)
            abort(404);

        $statuses = $user->statuses()->notReply()->get();
        $articles = $user->articles()->notReply()->get();
        $portifilios = $user->portifilios()->notReply()->get();



              return view('profile.index')->with('user',$user)
              ->with('statuses', $statuses)
              ->with('articles', $articles)
              ->with('portifilios', $portifilios)
              ->with('authUserIsFriend', Auth::user()->isFriendsWith($user));

    }


    public function getEdit()
    {
    	 return view('profile.edit',['user' => Auth::user()]);

    }
    
     public function postEdit(Request $request)
    {
    	$this->validate($request,[
    	    	'name' => 'max:255',
            'link' => 'max:255',   
            'email' => 'email|max:255',
            'profileimage' => 'max:255',  
    		]);





        $data;

 if(Input::hasFile('image'))
        {
            $location='images/';
             $extension = Input::file('image')->getClientOriginalExtension();
             $name=str_random(6).'.'.$extension;
             Input::file('image')->move($location,$name);
             $data=$location.$name;
        }


    	Auth::User()->update([
    		'name'=>$request->input('name'),
    		'link'=>$request->input('link'),
    		'email'=>$request->input('email'),
        'profileimage'=> $data,

    		]);



/*
if (Input::hasFile('image')){

$location='images/';
$name=str_random(6);
Input::file('image')->move($location,$name);


}*/



    	return redirect()->route('profile.edit')
			   ->with('info', 'Your profile has been updated.');
    }




    public function getUserImage($filename)
    {
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }


}
