<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sksk extends Model 
{
	 protected $table = 'sksk';

	 public function skskser_parent(){
	return $this->belongsTo('App\test_table' , "sksk1");
}

 //relationship places}
