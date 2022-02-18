<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function index()
    {
        $data['posts'] = Post::orderBy('id','desc')->paginate(5);
    
        return view('posts.index', $data);
    }
    public function create()
    {
        return view('posts.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'description' => 'required',
        ]);
        $path = $request->file('image')->store('public/images');
        $post = new Post;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->image = $path;
        $post->save();
     
        return redirect()->route('posts.index')
                        ->with('success','Post has been created successfully.');
    }

    public function edit(Post $post)
    {
        return view('posts.edit',compact('post'));
    }

    public function destroy(Post $post)
    {
        $post->delete();
    
        return redirect()->route('posts.index')
                        ->with('success','Post has been deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        
        $post = Post::find($id);
        if($request->hasFile('image')){
            $request->validate([
              'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $path = $request->file('image')->store('public/images');
            $post->image = $path;
        }
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();
    
        return redirect()->route('posts.index')
                        ->with('success','Post updated successfully');
    }

}
