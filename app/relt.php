<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class relt extends Model 
{
	 protected $table = 'relt';

	 public function relts_parent(){
		 return $this->belongsTo('App\test_table' , "relt1");
	}

 //relationship places 

}
