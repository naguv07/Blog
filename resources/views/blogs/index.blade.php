@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<style>
.post-info {
    display: block;
    overflow: hidden;
}
.post-tag {
    position: relative;
    display: inline-block;
    height: 18px;
    background-color: #e6c45d;
    color: #fff;
    font-size: 10px;
    font-weight: 600;
    line-height: 19px;
    text-transform: uppercase;
    padding: 0 7px;
    margin: 0 0 10px;
}
.post-info > h2 {
    font-size: 18px;
    font-weight: 600;
    line-height: 1.5em;
    margin: 0 0 10px;
}
.post-meta {
    color: #aaaaaa;
    font-size: 12px;
    font-weight: 400;
    line-height: 18px;
    padding: 0 1px;
}
.post-meta .post-author, .post-meta .post-date {
    display: inline-block;
    margin: 0 7px 0 0;
}
.post-snippet {
    position: relative;
    display: block;
    overflow: hidden;
    font-size: 15px;
    color: #aaa;
    line-height: 1.5em;
    font-weight: 400;
    margin: 10px 0 0;
}
.blog{
    margin-bottom: 15px;
}
.float-center {
  float: right;

  position: relative;
  left: -50%; /* or right 50% */
  text-align: left;
}
.myhead{
    font-size: 1.65em !important;
}
.my{
    margin-bottom: 10px;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-header">
                    <span class='myhead'> Dashboard</span>
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#blogModal">
                            New Post
                        </button>
                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 my">
                            By Date <br> {{ Form::text('date', '', array('id' => 'datepicker','onBlur'=>'ajaxCall()')) }}
                        </div>
                        <div class="col-md-4 my">
                            By Category <br> {!! Form::select('categories',App\Category::pluck('name','id'),null,['id'=>'categories', 'multiple'=>'multiple','onBlur'=>'ajaxCall()']) !!}
                        </div>
                        <div class="col-md-4 my">
                            By Author <br>  {!! Form::text('author','',['id'=>'author','onBlur'=>'ajaxCall()'])!!}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div id="blogs">
                         @foreach($blogs as $blog)
                    <div class="row blog">
                        <div class="col-md-4">
                            <img src="/images/{{$blog->image}}" class="img img-fluid"/>
                        </div>
                        <div class="col-md-8">
                            <div class="post-info">
                                @foreach($blog->categories as $cat)
                                <span class="post-tag">
                                    {{ App\Category::find($cat->pivot->category_id)->name}}
                                </span>
                                @endforeach
                                <h2 class="post-title">
                                    <a href="/blogs/{{$blog->id}}"> {{$blog->title}} </a>
                                </h2>
                                <div class="post-meta">
                                    <span class="post-author"><em>by</em>
                                        {{App\User::find($blog->user_id)->name}}
                                    </span>
                                    <span class="post-date published" datetime="{{$blog->updated_at}}">{{date('F-d-Y', strtotime($blog->created_at))}}</span>
                                </div>
                                <div class="post-snippet">{!! Illuminate\Support\Str::words($blog->body, $words = 70, $end = '...') !!}<a href="/blogs/{{$blog->id}}">Read More</a></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="blogModal" tabindex="-1" role="dialog" aria-labelledby="blogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            {!! Form::open(['action'=> 'BlogController@store','method'=>'post' ,'files'=>true])!!}
                <div class="modal-header">
                <h5 class="modal-title" id="blogModalLabel">Create a new Blog</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="title">Title</label>  
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
                        <input id="title" name="title" placeholder="title of the blog" class="form-control input-md" type="text">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="body">Body</label>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">                     
                        <textarea class="form-control" id="body" name="body">default text</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="category">Categories</label>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
                            {!! Form::select('categories[]',App\Category::pluck('name','id'),null,['class'=>'form-control', 'multiple'=>'multiple']) !!}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Image</label>
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
                        <input id="filebutton" name="photo" class="input-file" type="file">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn  btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn btn-success">Submit</button>
                </div>
            {!! Form::close()!!}
          </div>
        </div>
      </div>
</div>
@endsection
@section('scripts')
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
$(function() {
  $( "#datepicker" ).datepicker();
});
function ajaxCall(){
    var name = $("input[name=author]").val();
    var date = $("input[name=date]").val();
    var fld = document.getElementById('categories');
    var values = [];
    for (var i = 0; i < fld.options.length; i++) {
        if (fld.options[i].selected) {
            values.push(fld.options[i].value);
        }
    }
    $.ajax({  
            type:'POST',
            url:'/search',
            data:{"_token": "{{ csrf_token() }}","author":name,categories:values,date:date},
            success:function(data){
                // alert(data.msg);
               $("#blogs").html(data.msg);
            },
            // error: function(xhr, status, error) {
            // var err = eval("(" + xhr.responseText + ")");
            // alert(err.Message);
// }
        });
}
</script>

@endsection