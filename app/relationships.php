<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class relationships extends Model
{
    //
    public function fields(){
        return $this->belongsTo("App\\fields" , "field_id");
    }
    public function parent_table(){
        return $this->belongsTo("App\\a_Tables" , "parent_id");
    }
    public function child_table(){
        return $this->belongsTo("App\\a_Tables" , "child_id");
    }
}
