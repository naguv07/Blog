<?php

namespace App\Http\Controllers;

use App\Blog;
use Auth;
use App\User;
use Validator;
use App\category; 
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $blogs = Blog::all();
        return view('blogs.index')->with('blogs',$blogs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'body' => 'required',
            'categories' => 'required',
            'photo' => 'image',
        ];
        $messages = [
            'title.required' => 'Title cant be empty',
            'body.required' => 'Body cant be empty',
            'categories.required' => 'Select one or more category',
            'photo.image' => 'Please upload JPEG, JPG or PNG',
        ];
        $validator = Validator::make($request->input(),$rules,$messages)->validate();
        $blog = new Blog;
        $blog->title = $request->input('title');
        $blog->user_id = Auth::user()->id;
        $blog->body = $request->input('body');
        $file = $request->file('photo')->getClientOriginalName();
        $path = base_path();
        $path = str_replace("laravel","public_html",$path);
        $path = $path . '/public/images';
        $request->file('photo')->move($path,$file);
        $blog->image = $file;
        $a = $blog->save();
        $blog->categories()->attach($request->input('categories'));
        if($a){
            return redirect('blogs')->with('success','Blog is successpully posted');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
       return view('blogs.show')->with('blog',$blog);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit')->with('blog',$blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $rules = [
            'title' => 'required',
            'body' => 'required',
            'categories' => 'required',
            'photo' => 'image',
        ];
        $messages = [
            'title.required' => 'Title cant be empty',
            'body.required' => 'Body cant be empty',
            'categories.required' => 'Select one or more category',
            'photo.image' => 'Please upload JPEG, JPG or PNG',
        ];
        $validator = Validator::make($request->input(),$rules,$messages)->validate();
        $blog->title = $request->input('title');
        $blog->body = $request->input('body');
        if($request->file('photo')){
            $file = $request->file('photo')->getClientOriginalName();
            $path = base_path();
            $path = str_replace("laravel","public_html",$path);
            $path = $path . '/public/images';
            $request->file('photo')->move($path,$file);
            $blog->image = $file;
        }
        $a = $blog->save();
        $blog->categories()->sync($request->input('categories'));
        if($a){
            return redirect('blogs')->with('success','Blog is successpully updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if(Auth()->user()->id !== $blog->user_id){
            return redirect('\blogs')->with('error','Unauthorized access');
        }

        // if($post->cover_image != 'noimage.jpg'){
        //     Storage::delete('/public/cover_images/'.$post->cover_image);
        // }
        $blog->delete();
        return redirect('/blogs')->with('success','Post Deleted');
    }

    public function search(Request $request){
        $date =date("Y-m-d", strtotime($request->input('date')));
        $author = $request->input('author');
        $user_id = User::Where('name','like',$author."%")->first();
        $blogs = Blog::orWhere('user_id',$user_id->id)
                    ->orWhere('id',$request->input('categories'))
                    ->orWhere('created_at',$date)
                    ->get();
       $msg= "";
        foreach($blogs as $blog){
            $msg .= '<div class="row blog">';
            $msg.='            <div class="col-md-4">';
            $msg.='                <img src="/images/test.jpeg" class="img img-fluid"/>';
            $msg.='            </div>';
            $msg.= '           <div class="col-md-8">';
            $msg.='                <div class="post-info">';
                                    foreach($blog->categories as $cat){
            $msg.='                        <span class="post-tag">';
            $msg.=                             Category::find($cat->pivot->category_id)->name;
            $msg.='                        </span>';
                                    }
            $msg.='                    <h2 class="post-title">';
            $msg.='                        <a href="/blogs/'.$blog->id.'"> '.$blog->title.' </a>';
            $msg.='                    </h2>';
            $msg.='                    <div class="post-meta">';
            $msg.='                        <span class="post-author"><em>by</em>';
            $msg .=                            User::find($blog->user_id)->name;
            $msg.='                        </span>';
            $msg.='                        <span class="post-date published" datetime="'.$blog->created_at.'">'.date("F-d-Y", strtotime($blog->created_at)).'</span>';
            $msg.='                    </div>'; 
            $msg.='                    <div class="post-snippet">'.$blog->body.'<a href="/blogs/'.$blog->id.'">Read More</a></div>';    
            $msg.='                </div>';
            $msg.='            </div>';
            $msg.='        </div>';
        }
//        $msg = User::find($user_id)->pluck('name');
        return response()->json(array('msg'=> $msg), 200);
    }
}

