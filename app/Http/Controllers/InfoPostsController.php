<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\InfoPost;
use App\User;


class InfoPostsController extends Controller
{

    public function index(){
        $user = \Auth::user();
        if($user){
            $info_posts = InfoPost::whereHas('User',function($query) use ($user) {$query->where('team', $user->team);})
                                    ->orderBy('created_at','desc')
                                    ->get();
            return view('index' , ['info_posts' => $info_posts]);
        } else {
            return redirect()->route('home');
        }
    }

    public function show(Request $request){
        $info_post = InfoPost::find($request->id);
        $user = \Auth::user();
        if ($user){
          $login_user_id = $user->id;
        } else {
          $login_user_id = "";
        }
        return view('show', ['info_post' =>$info_post, 'login_user_id'=>$login_user_id]);
    }


    public function create(){
        return view('create');
    }

    public function store(Request $request){
        $info_post = new InfoPost;
        $user = \Auth::user();
        $info_post->title = request('title');
        $info_post->body = request('body');
        $info_post->user_id = $user->id;
        $info_post->save();
        return redirect()->route('index');
    }

    public function edit(Request $request){
        $info_post = InfoPost::find($request->id);
        return view('edit', ['info_post'=>$info_post]);
    }

    public function update(Request $request){
        $info_post = InfoPost::find($request->id);
        $info_post->title = request('title');
        $info_post->body = request('body');
        $info_post->save();
        return redirect()->route('show', ['id'=>$info_post->id]);
    }

    public function delete(Request $request){
        $info_post = InfoPost::find($request->id);
        $info_post->delete();
        return redirect()->route('index');
    }

}
