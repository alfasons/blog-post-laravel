<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function search($term)
    {
      $posts=  Post::search($term)->get();
        $posts->load('user:id,username,avartar');
        return $posts;
    }
    //
    public function _form()
    {
     return view('/posts/_form');
    }
    public function create(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
       $post= Post::Create($incomingFields);
        return redirect("/posts/{$post->id}")->with('success','Post created!!');

    }
    public function singlePost(Post $post)
    {
        $post['body'] =strip_tags(Str::markdown($post['body']),'<p><ol><li><em><h3><br><hr><i><strong>');
        return view('/posts/single-post', ['post' => $post]);
    }


    /*public function delete_uses_policy(Post $post){
        if (auth()->user()->cannot('delete', $post)) {
            abort(403);
        }


        $post->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success', 'post deleted successfully');

    }*/
    public function delete(Post $post){

        $post->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success', 'post deleted successfully');

    }
    public function show_edit_form(Post $post)
    {
        return view('posts/show_edit_form',['post'=>$post]);
    }
    public function update_post(Post $post,Request $request)
    {

        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return back()->with('success', 'posted successfully updated!!!');


    }
}
