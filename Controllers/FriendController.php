<?php
namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Reqquest;

class FriendController extends Controller {

	public function getIndex(){
	
		$friends = Auth::user()->friends();
		$requests = Auth::user()->friendRequests();
		return view('friends.index')
				->with('friends', $friends)
				->with('requests', $requests);
				
	}

	/**
	 *  SEND  a friend request
	 */
	public function getAdd( $link ) 
	{
		// get the requested user's DB object
		$user = User::where('link', $link)->first();
		// A user can't send himself a request...
		if ( Auth::user()->id === $user->id ) {
			return redirect()
				->route('home')
				->with('info', 'Are you bl7? :)');
		}
		// check if there was a request pending
		if (!$user) {
			return redirect()
				->route('home')
				->with('info', 'No friend request found to this user');
		}
		// check if there exists already a friend request between the two
		if ( Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user()) ) {
			return redirect()
				->route('profile.index', ['link' => $link])
				->with('info', 'Friend request already pending');
		}
		// check if they are already friends
		if ( Auth::user()->isFriendsWith($user) ) {
			return redirect()
				->route('profile.index', ['link' => $link])
				->with('info', 'You are already friends');
		}
		// now create the friend request
		Auth::user()->addFriend( $user );
		return redirect()
			->route('profile.index', ['link' => $link])
			->with('info', 'Friend request sent.');
	}
	
	/**
	 *  ACCEPT  a friend request
	 */
	


public function getAccept($link){
		$user = User::where('link', $link)->first();

		if(!$user) {
			return redirect()->route('home')->with('info', 'That user can not be found.');
		}
		if(!Auth::user()->hasFriendRequestRecieved($user)) {
			return redirect()->route('home');
		}
		Auth::user()->acceptFriendRequest($user);
		return redirect()->route('profile.index', ['link' => $user->link])
			->with('info', 'Friend Request accepted.');
	}



/**
	 *  Delete  a friend 
	 */
	

public function postDelete($link) {
		$user = User::where('link', $link)->first();
		if(!Auth::user()->isFriendsWith($user)) {
			redirect()->back();
		}
		Auth::user()->deleteFriend($user);
		return redirect()->back()->with('info', 'Friend Deleted !');
	}


}

?>