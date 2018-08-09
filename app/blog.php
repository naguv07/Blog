<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class blog extends Model
{
    //
    public function categories(){
        return $this->belongsToMany('App\Category');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function blog_ids($categories){
        return $this->belongsToMany('App\Category')->wherePivotIn('id', $categories);
    }
}
