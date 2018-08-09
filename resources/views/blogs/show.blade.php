@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{$blog->title}}</h1>
            <img  style="width: 100%" src="/images/test.jpeg" class="img img-responsive"/>
            <small>Written On {{date('F-d-Y', strtotime($blog->created_at))}}  by {{$blog->user->name}} </small>
            <div>
                {!!$blog->body!!}
            </div>
            <div>
                @if(!Auth::guest())
                @if(Auth::user()->id == $blog->user_id)
                {!! Form::open(['action' => ['BlogController@destroy',$blog->id],'method'=>'POST']) !!}
                <a href="/blogs/{{$blog->id}}/edit" class="btn btn-secondary btn-lg">Edit</a>
                {{Form::hidden('_method','DELETE')}}
                {{Form::submit('Delete',['class'=>'btn btn-danger btn-lg float-right'])}}
                {!!Form::close()!!}
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection