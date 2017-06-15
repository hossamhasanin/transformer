<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = "permissions";
    
    public function user()
    {
        return $this->hasOne("App\\User" , "perm_id");
    }

}
