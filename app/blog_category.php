<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class blog_category extends Model
{
    public function blogs(){
        return $this->belongsToMany('App\Blog','App\Category');
    }
}
