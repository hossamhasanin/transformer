<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class test_table extends Model 
{
	 protected $table = 'test_table';

     public function skskser_child(){
	return $this->hasMany('App\sksk' , "sksk1");
}

 public function relts_child(){
	return $this->hasMany('App\relt' , "relt1");
}

 //relationship places
}