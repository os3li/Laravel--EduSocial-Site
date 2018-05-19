<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller {

	public function postArticle(Request $req,$link){
	

      $user = User::where('link',$link)->get()->first();


  	$this->validate($req, [
		  'body' => 'max:255',
		]);


$post = Article::create([
      'body'  => $req->input('article'),
    ])->user()->associate(Auth::user());
    $user->repliess()->save($post);

/*
		Auth::user()->articles()->create([
			'body'  => $req->input('article'), 


		])->articleOf()->attach($user->id);
    */
		return redirect()->back()->with('info','article Posted.')
    ->with('user',$user)
    ->with('authUserIsFriend', Auth::user()->isFriendsWith($user));
	}





 public function getArticle($link)
    {
      
      $user = User::where('link',$link)->get()->first();
      if(!$user)
            abort(404);

        $articles = $user->articles()->get();


              return view('profile.articles')->with('user',$user)
              ->with('articles', $articles)
              ->with('authUserIsFriend', Auth::user()->isFriendsWith($user));

    }



 public function wArticle($link)
    {
      
      $user = User::where('link',$link)->get()->first();
      if(!$user)
            abort(404);

        $articles = $user->articles()->get();


              return view('profile.wArticles')->with('user',$user)
              ->with('articles', $articles)
              ->with('authUserIsFriend', Auth::user()->isFriendsWith($user));

    }



	
}

?>