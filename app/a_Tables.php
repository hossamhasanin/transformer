<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class a_Tables extends Model
{
    protected $table = "a_tables";


    public function fields()
    {
        return $this->hasMany("App\\fields" , "table_id");
    }

    public function parent_relation(){
        return $this->hasMany("App\\relationships" , "parent_id");
    }

    public function child_relation(){
        return $this->hasMany("App\\relationships" , "child_id");
    }

}
