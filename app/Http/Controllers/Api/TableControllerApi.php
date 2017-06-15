<?php
/**
 * Created by PhpStorm.
 * User: saif
 * Date: 23/03/2017
 * Time: 08:28 م
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\a_Tables;
use App\fields;
use League\Flysystem\Exception;

class TableControllerApi extends Controller
{
    public function DeleteField(Request $request){
        $field = fields::find($request->id);
        $id = $field->id;
        $name = $field->field_name;
        $table_id = $field->table_id;
        $table_name = a_Tables::find($table_id)->table;
        $type = $field->field_type;
        $nullable = $field->field_nullable;
        $visibility = $field->visibility;
        $default_value = $field->default_value;
        $label_name = $field->label_name;
        $field->delete();
        try {
            DB::statement("ALTER TABLE " . $table_name . " DROP " . $name . ";");
        }catch (Exception $e){
            echo $e;
        }
        return response(["id" => $id,"name" => $name , "type" => $type , "nullable" => $nullable , "visibility" => $visibility , "default_value" => $default_value , "label_name" => $label_name , "table_id" => $table_id] , 200);
        //return "sent";
    }

    public function DeleteRelationship($relation_table , $child_table_id , $relation_id){
        // get the parent model of the relationship
        $parent_model = a_Tables::where("table" , $relation_table)->first()->module_name;

        $relationship = relationships::find($relation_id);

        $relation_old_parent_name = $relationship->relation_name . "_child";
        $relation_old_child_name = $relationship->relation_name . "_parent";

        $child_model = a_Tables::find($child_table_id)->module_name;
        if ($parent_model && $child_model) {

            $child_table = a_Tables::find($child_table_id)->table;

            $child_model_edit = '/public\s*function\s*' . $relation_old_child_name . '\s*\(\)\s*\n*\{\s*\n*return\s+\$this->belongsTo\(.*\);\s*\n*\}/';

            // get the child model content
            try{
                $child_model_file = file_get_contents(app_path() . "/$child_model.php");
            }catch(Exception $e){
                echo "Error while opening the file , The error is : " . $e;
            }

            try{
                $child_model_file = preg_replace($child_model_edit, "", $child_model_file);

                file_put_contents(app_path() . "/$child_model.php", $child_model_file);

            }catch(Exception $e){
                echo "Error while modifing the file , The error is : " . $e;
            }

            $parent_model_edit = '/public\s*function\s*' . $relation_old_parent_name . '\s*\(\)\s*\n*\{\s*\n*return\s+\$this->hasMany\(.*\);\s*\n*\}/';
            // parent modifing
            try{
                $parent_model_file = file_get_contents(app_path() . "/$parent_model.php");
            }catch(Exception $e){
                echo "Error while opening the file , The error is : " . $e;
            }

            try {
                $parent_model_file = preg_replace($parent_model_edit, "", $parent_model_file);

                file_put_contents(app_path() . "/$parent_model.php", $parent_model_file);
            }catch(Exception $e){
                echo "Error while modifing the file , The error is : " . $e;
            }

            $delete_relationship = relationships::find($relation_id);
            $delete_relationship->delete();

            return response("success :)", 201);
        } else {
            return response("Faild error pro :( , i dont find the files" , 404);
        }

    }

    public function RestoreField(Request $request){
        $restore_field = new fields();
        $restore_field->field_name = $request->deleted_field["name"];
        $name = $request->deleted_field["name"];
        $restore_field->table_id = $request->deleted_field["table_id"];
        $table_name = a_Tables::find($request->deleted_field["table_id"])->table;
        $restore_field->field_type = $request->deleted_field["type"];
        $type = $request->deleted_field["type"];
        $restore_field->visibility = $request->deleted_field["visibility"];
        $restore_field->field_nullable = $request->deleted_field["nullable"];
        $nullable = isset($request->deleted_field["nullable"]) ? "NULL" : "NOT NULL";
        $restore_field->default_value = $request->deleted_field["default_value"];
        $default_value = $request->deleted_field["default_value"] != "" && ($request->deleted_field["type"] == "varchar(255)" or $request->deleted_field["type"] == "int(11)") ? "DEFAULT " . "'" .$request->deleted_field["default_value"] . "'" : "DEFAULT NULL";
        $restore_field->label_name = $request->deleted_field["label_name"];
        $restore_field->save();
        try {
            DB::statement("ALTER TABLE $table_name ADD $name $type $nullable $default_value;");
        }catch (Exception $e){
            echo $e;
        }
        return response("success :) , You restored the field again", 201);
    }

}