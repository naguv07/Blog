@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Blog</h1>
            {!! Form::open(['action' => ['BlogController@update',$blog->id],'method' => 'POST','files'=>true]) !!}
                <div class="form-group">
                    {{Form::label('title','Title')}}
                    {{Form::text('title',$blog->title,['class'=> 'form-control','placeholder' => 'Title'])}}
                </div>    
                <div class="form-group">
                        {{Form::label('body','Body')}}
                        {{Form::textarea('body',$blog->body,['id'=>'article-ckeditor','class'=> 'form-control','placeholder' => 'Body'])}}
                </div>
                <div class="form-group">
                        {!! Form::select('categories[]',App\Category::pluck('name','id'),$blog->categories,['class'=>'form-control', 'multiple'=>'multiple']) !!}
                </div>
                <div class="form-group">
                        {{Form::file('photo')}}
                </div>
                {{Form::hidden('_method','PUT')}}
                {{Form::submit('Submit',['class'=>'btn btn-primary'])}}
            {!! Form::close() !!}
        </div>
        
    </div>
</div>
    
@endsection