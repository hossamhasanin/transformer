<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Validation\Factory;
use Mockery\CountValidator\Exception;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
//use JavaScript;

use App\a_Tables;
use Artisan;
use App\fields;
use App\relationships;

class TableController extends Controller
{

    // name instance to store the Request->field_name array in it to use it in schema function
    public $names;
    // type instance to contain the array of field_types to use it in schema function
    public $types;
    // nullables to define if that field accept the null value or not
    public $nullables;
    //default values of the fields
    public $default_values;

    public function AddNew(Request $request)
    {

      $this->validate($request, [
        'table_name' => 'required|unique:a_tables,table|max:255',
        'link_name' => 'required|unique:a_tables',
        'slug' => 'required|unique:a_tables',
        'module_name' => 'required|unique:a_tables',
        'icon' => 'required',
        'field_name.*' => 'required',
        'field_type.*' => 'required',
        //'relation_tabels.*' => "required",
        //"relation_fields.*" => "required",
        //"relation_name.*" => "required"
    ]);

        foreach (array_count_values($request->field_name) as $names){
            if ($names > 1){
                return redirect("dashboard/table/add")->withErrors("There is fields repeated");
                die();
            }
        }

        if ($request->relation_name){
            foreach (array_count_values($request->relation_name) as $names){
                if ($names > 1){
                    return redirect("dashboard/table/add")->withErrors("There is fields repeated");
                    die();
                }
            }
        }

      // save the data of new table in a_table (avillable tables) to use it later
      $a_tables = new a_Tables;
      // table name
      $a_tables->table = $request->table_name;
      // this is the name of link in dashlinks in a slide
      $a_tables->link_name = $request->link_name;
      // store the slug that should be url of this table
      $a_tables->slug = $request->slug;
      // store model name in database
      $a_tables->module_name = $request->module_name;     
      // store array data in field by implode "," in it to avoid errors
      $a_tables->field_types = implode("," ,$request->field_type);
      // store the icon that should appear in the slide list in dashboard (I use the icons of font awoesome)
      $a_tables->icon = $request->icon;
      $a_tables->editable = $request->editable > 1 or $request->editable < 0 or ! is_int($request->editable) ? 1 : 0;
      // Save the new data
      $a_tables->save();

      foreach ($request->field_name as $id => $name) {
          // save the data of the field in the fields tabl
          $fields = new fields;
          // save the field_name in field name column
          $fields->field_name = $name;
          // store the field_type
          $f_type = $request->field_type[$id] == "integer" ? "int(11)" : $request->field_type[$id];
          $fields->field_type = $request->field_type[$id];
          // save the table_name (indicate to the table of this column) in table_name column
          $fields->table_id = $a_tables->id;
          // store if it nullable or nor
          $fields->field_nullable = isset($request->nullable[$id]) ? 1 : 0;
          // store the default value of the field
          $fields->default_value = isset($request->default_values[$id]) ? $request->default_values[$id] : "";
          /*foreach ($request->relation_fields as $i => $field) {
              if ($field == $name) {
                  $fields->relation_field = $request->relation_tabels[$i];
              }
          }*/
          // save the default label_name
          $fields->label_name = $name;
          if ($request->relation_fields){
                foreach($request->relation_fields as $r => $f){
                    if ($name == $f){
                        $fields->relationship_field = 1;
                    }
                } 
          }
          $fields->save();
      }


      $this->nullables = $request->nullable;
      $this->names = $request->field_name;
      $this->types = $request->field_type;
      $this->default_values = $request->default_values;

      // Create the new model in App folder from artisan function instead of artisan command
      Artisan::call('make:model', [
            "name" => $request->module_name
        ]);
      $search_for = '/class\s*'. $request->module_name .'\s*extends\s*Model\s*\n*\{\n*\s*\/\/\n*\}/';
      $module_file = file_get_contents(app_path() . "/$request->module_name.php");
      $add_table_name = 'class '. $request->module_name ." extends Model \n{\n"."\t protected " . '$table = '. "'" . $request->table_name ."'" . ";\n\n\t //relationship places \n\n" .'}';
      $model_file = preg_replace($search_for, $add_table_name , $module_file);
      file_put_contents(app_path() . "/$request->module_name.php", $model_file);
        // Create the new table in data base
        Schema::create($request->table_name , function (Blueprint $table) {
          $table->increments('id');
          // iritate the names array to generate the fields in the table
          foreach ($this->names as $name_id => $name ) {
            //define the type of this field easily
                $type = $this->types[$name_id];
                if (isset($this->nullables[$name_id])){
                  if ($this->default_values[$name_id]){
                    $table->$type($name)->nullable()->default($this->default_values[$name_id]);
                  } else {
                    $table->$type($name)->nullable();
                  }
                } else {
                  if ($this->default_values[$name_id]){
                    $table->$type($name)->default($this->default_values[$name_id]);
                  } else {
                    $table->$type($name);
                  }
                }
          }
          $table->timestamps();
        });

        // modify the two models of relationships parent_model and child model
        if ($request->relation_tabels){
            $this->modify_model($request->relation_tabels , $request->relation_fields , $request->table_name , $request->module_name , $request->relation_name);
            foreach($request->relation_tabels as $id => $table){
                $relationship = new relationships();
                $table_id = a_Tables::where("table" , $table)->first()->id;
                $field_id = fields::where("field_name" , $request->relation_fields[$id])->first()->id;
                $relationship->relation_name = $request->relation_name[$id];
                $relationship->parent_id = $table_id;
                $relationship->child_id = $a_tables->id;
                $relationship->field_id = $field_id;
                $relationship->save();
            }
        }

        $request->session()->flash('table_success', 'Table was added successfully!');
        return redirect()->route("show_all");

    }


    protected function modify_model($relation_tabels , $relation_fields , $child_table, $child_model , $relation_name)
    {
      // irritate the relation tables that should contain the parent table
        foreach ($relation_tabels as $relation_id => $relation_tabel) {
          // irritate the relation fields to exctract the field that suppose to be the foriegn key
            $relation_parent_name = $relation_name[$relation_id] . "_child";
            $relation_child_name = $relation_name[$relation_id] . "_parent";
             // get the parent model of the relationship
                $parent_model = a_Tables::where("table" , $relation_tabel)->first()->module_name;
             // get the child model content
                $child_model_file = file_get_contents(app_path() . "/$child_model.php");
             // define the model that should be the parent of the relation ship
                $app_child_model = "'App\\$parent_model'";
             // add the function of the relationship to the child model
                $child_replacment = "public function ". $relation_child_name ."(){\n" . "\t\t return".' $this->belongsTo('. $app_child_model .' , "'. $relation_fields[$relation_id] .'");'."\n}\n\n//relationship places";
                // remove the final line of the model that is "}\n"
                $child_model_file = str_replace("//relationship places" , $child_replacment , $child_model_file);
                // modify the content of this child model and the new changes
                file_put_contents(app_path() . "/$child_model.php" , $child_model_file);
                // get the content of the parent model
                $parent_model_file = file_get_contents(app_path() . "/$parent_model.php");
                // define the parent model to put it in the function
                $app_parent_model = "'App\\$child_model'";
                // add the relationship function to parent model
                $parent_replacement = 'public function '. $relation_parent_name ."(){\n\t" . 'return $this->hasMany('. $app_parent_model .' , "'. $relation_fields[$relation_id] .'");'."\n}\n\n//relationship places";
                // replace comment relationship places with relationship function
                $parent_model_file = str_replace("//relationship places" , $parent_replacement , $parent_model_file);
                // put this changes in the parent model
                file_put_contents(app_path() . "/$parent_model.php" , $parent_model_file);
        }
    }

    public function AddOption($table){
        $fields_data = a_Tables::where("slug" , $table )->first()->fields()->where("relationship_field" , 0)->get();

        //$fields = a_Tables::where("slug" , $table )->first()->fields()->where("relationship_field" , 0)->get();

        $tables = a_Tables::get();

        $table_id = a_Tables::where("slug" , $table)->first()->id;

        return view("blades.addOption" , ["fields_data" => $fields_data , "table_id" => $table_id , "tables" => $tables]);

    }

    public function StoreOption($table_id , Request $request){

        $table_name = a_Tables::find($table_id)->table;

        $this->validate($request , [
            'field_name.*' => "required",
            'ids.*' => "required|numeric",
            'field_type.*' => "required",
            'visibility.*' => "required",
            'label_name.*' => "required",
        ]);

        foreach (array_count_values($request->field_name) as $names){
            if ($names > 1){
                return redirect("dashboard/table/option/tester")->withErrors("There is fields repeated");
                die();
            }
        }

        $get_old_vals = fields::where("table_id" , $table_id)->get();

        foreach ($get_old_vals as $old_val){
            $new_name = $request->field_name[$old_val->id];
            $new_type = $request->field_type[$old_val->id];
            $new_nullable = isset($request->nullable[$old_val->id]) ? "NULL" : "NOT NULL";
            $new_default_value = isset($request->default_value[$old_val->id]) && ($new_type == "varchar(255)" or $new_type == "int(11)") ? "DEFAULT " . "'" .$request->default_value[$old_val->id] . "'" : "DEFAULT NULL";
            if ($new_name != $old_val->field_name or $new_type != $old_val->field_type or $new_nullable != $old_val->nullable or $new_default_value != $old_val->default_value){
                // ALTER TABLE `tester` CHANGE `lljjllj` `lljjllj` LONGTEXT NULL DEFAULT NULL;
                try{
                    DB::statement("ALTER TABLE $table_name CHANGE $old_val->field_name $new_name $new_type $new_nullable $new_default_value;");
                }catch (Exception $e){
                    echo "OHHHHH ! Sorry but i think there big problem in database";
                }
                    //DB::statement("ALTER TABLE tester CHANGE dsfsdf hihihi text NULL DEFAULT weuhiwf");
            }
        }


        //$table_fields = fields::where("table_id" , $table_id)->get();
        foreach ($request->ids as $field_id) {
            $requested_field = fields::find($field_id);
            $requested_field->field_name = $request->field_name[$field_id];
            $requested_field->table_id = $table_id;
            $requested_field->field_type = $request->field_type[$field_id];
            $requested_field->visibility = $request->visibility[$field_id];
            $requested_field->field_nullable = isset($request->nullable[$field_id]) ? $request->nullable[$field_id] : 0;
            if ($request->field_type[$field_id] == "varchar(255)" or $request->field_type[$field_id] == "int(11)" or $request->field_type[$field_id] == "float"){
                $requested_field->default_value = $request->default_value[$field_id];
            }
            $requested_field->label_name = $request->label_name[$field_id];
            $requested_field->save();
        }

        $request->session()->flash('table_success', 'you added this options successfully!');


        return redirect()->route("show_all");


    }

    public function ShowAll(){
        $all_tables = a_Tables::get();

        return view("blades.AllTables" , ["all_tables" => $all_tables]);
    }

    public function EditTable($table){
        $table_data = a_Tables::where("slug" , $table)->first()->fields()->get();

        $table_id = a_Tables::where("slug" , $table)->first()->id;

        $table_name = a_Tables::where("slug" , $table)->first()->table;

        $all_tables = a_Tables::where("slug" , "!=" , $table)->pluck("table");

        $last_relationship = isset(relationships::orderBy('id', 'desc')->first()->id) ? relationships::orderBy('id', 'desc')->first()->id : 0;

        $last_field = isset(fields::orderBy('id', 'desc')->first()->id) ? fields::orderBy('id', 'desc')->first()->id : 0;

        $relations = a_Tables::where("slug" , $table)->first()->child_relation()->get();

        $all_fields = fields::where("table_id" , $table_id)->pluck("field_name");

        $table_info = a_Tables::where("slug" , $table)->first();

        return view("blades.EditTable" , ["table_data" => $table_data , "table_id" => $table_id , "all_tables" => $all_tables , "last_relationship" => $last_relationship , "relations" => $relations , "all_fields" => $all_fields , "table_nane" => $table_name , "last_field" => $last_field , "table_info" => $table_info]);
    }

    public function UpdateTable($table_id , Request $request){
        $table_name = a_Tables::find($table_id)->table;
        $slug = a_Tables::find($table_id)->slug;
        $table_model = a_Tables::find($table_id)->module_name;

        $this->validate($request , [
            "table_name" => "required|max:255",
            "link_name" => "required",
            "slug" => "required",
            "module_name" => "required",
            'field_name.*' => "required",
            'ids.*' => "required|numeric",
            'field_type.*' => "required",
            'visibility.*' => "required",
            'label_name.*' => "required",
        ]);
        $tables = a_Tables::where("id" ,"!=" , $table_id)->pluck("table");
        $slugs = a_Tables::where("id" ,"!=" , $table_id)->pluck("slug");
        $modules = a_Tables::where("id" ,"!=" , $table_id)->pluck("module_name");
        $links = a_Tables::where("id" ,"!=" , $table_id)->pluck("link_name");
        if (in_array($request->table_name , $tables->all())){
            return redirect("dashboard/table/edit/" . $slug)->withErrors("OHH , you cann't name this table this name find other unique name");
            die();
        }
        if (in_array($request->slug , $slugs->all())){
            return redirect("dashboard/table/edit/" . $slug)->withErrors("OHH , you cann't add this slug its not unique");
            die();
        }
        if (in_array($request->link_name , $links->all())){
            return redirect("dashboard/table/edit/" . $slug)->withErrors("OHH , you cann't add this link name it sounds that this is old name change it");
            die();
        }
        if (in_array($request->module_name , $modules->all())){
            return redirect("dashboard/table/edit/" . $slug)->withErrors("OHH , you cann't name this table this name for some issues");
            die();
        }
        foreach (array_count_values($request->field_name) as $names){
            if ($names > 1){
                return redirect("dashboard/table/edit/" . $slug)->withErrors("There is fields repeated");
                die();
            }
        }

        if ($request->relation_name){
            foreach (array_count_values($request->relation_name) as $names){
                if ($names > 1){
                    return redirect("dashboard/table/edit/". $slug)->withErrors("There is relation_names repeated");
                    die();
                }
            }
            foreach (array_count_values($request->field_in_relationship) as $names){
                if ($names > 1){
                    return redirect("dashboard/table/edit/".$slug)->withErrors("There is field_in_relationships repeated");
                    die();
                }
            }
        }

        // Get old field values
        $get_old_vals = fields::where("table_id" , $table_id)->get();

        // itrate throw it
        foreach ($get_old_vals as $old_val){
            $new_name = $request->field_name[$old_val->id];
            $new_type = $request->field_type[$old_val->id];
            $new_nullable = isset($request->nullable[$old_val->id]) ? "NULL" : "NOT NULL";
            $new_default_value = isset($request->default_value[$old_val->id]) && ($new_type == "varchar(255)" or $new_type == "int(11)") ? "DEFAULT " . "'" .$request->default_value[$old_val->id] . "'" : "";
            if ($new_name != $old_val->field_name or $new_type != $old_val->field_type or $new_nullable != $old_val->nullable or $new_default_value != $old_val->default_value){
                // ALTER TABLE `tester` CHANGE `lljjllj` `lljjllj` LONGTEXT NULL DEFAULT NULL;
                try{
                    DB::statement("ALTER TABLE $table_name CHANGE $old_val->field_name $new_name $new_type $new_nullable $new_default_value;");
                }catch (Exception $e){
                    echo "OHHHHH ! Sorry but i think there big problem in database";
                }
                //DB::statement("ALTER TABLE tester CHANGE dsfsdf hihihi text NULL DEFAULT weuhiwf");
            }
        }

        foreach ($request->ids as $field_id) {
            $requested_field = fields::find($field_id);
            $requested_field->field_name = $request->field_name[$field_id];
            $requested_field->table_id = $table_id;
            $requested_field->field_type = $request->field_type[$field_id] == "varchar(255)" ? "string" : $request->field_type[$field_id];
            $requested_field->visibility = $request->visibility[$field_id];
            //$requested_field->relation_table = isset($request->relationship[$field_id]) ? $request->relationship[$field_id] : NULL;
            $requested_field->field_nullable = isset($request->nullable[$field_id]) ? $request->nullable[$field_id] : 0;
            if ($request->field_type[$field_id] == "varchar(255)" or $request->field_type[$field_id] == "int(11)" or $request->field_type[$field_id] == "float"){
                $requested_field->default_value = $request->default_value[$field_id];
            }
            $requested_field->label_name = $request->label_name[$field_id];
            $requested_field->save();
        }

        // modify relationships if there is new

        // Array contains the new relationships which actually is the new parents
        $new_parents = [];
        // Array contains the fields in the new relationship
        $new_fields = [];
        // Array contains field_names for the new relationships
        $new_names = [];
        //$current_relations = relationships::get();
        
        if ($request->relationship){
            foreach($request->field_in_relationship as $id => $name){
                $field_id = array_search($name , $request->field_name);
                $field_type = $request->field_type[$field_id];
                if ($field_type != "int(11)"){
                    return redirect("dashboard/table/edit/" . $slug)->withErrors("hey bro , sorry but you cann't add relation with field not integer");            
                    die();
                }
            }
            foreach ($request->relationship as $relation_id => $parent_table){
                $parent_model = a_Tables::where("table" , $parent_table)->first()->module_name;
                $child_model = a_Tables::find($table_id)->module_name;
                $relationship = relationships::find($relation_id);
                $parent_id = a_Tables::where("table" , $parent_table)->first()->id;
                $field_id = fields::where("field_name" , $request->field_in_relationship[$relation_id])->first()->id;
                $relation_parent_name = $request->relation_name[$relation_id] . "_child";
                $relation_child_name = $request->relation_name[$relation_id] . "_parent";
                $relation_old_parent_name = isset($relationship->relation_name) ? $relationship->relation_name . "_child" : null;
                $relation_old_child_name = isset($relationship->relation_name) ? $relationship->relation_name . "_parent" : null;
                if ($relationship == null){
                    $new_relation = new relationships();
                    $new_relation->relation_name = $request->relation_name[$relation_id];
                    $new_relation->parent_id = $parent_id;
                    $new_relation->child_id = $table_id;
                    $new_relation->field_id = $field_id;
                    $new_relation->save();

                    // In old version this got replaced with lines under it to add new relationship function in the module
                    // $new_parents[$relation_id] = $parent_table;
                    // $new_fields[$relation_id] = $request->field_in_relationship[$relation_id];
                    // $new_names[$relation_id] = $request->relation_name[$relation_id];


                    $child_model_file = file_get_contents(app_path() . "/$child_model.php");
                    // define the model that should be the parent of the relation ship
                    $app_child_model = "'App\\$parent_model'";
                    // Make the function of the child part of the relationship
                    $relation_child_function = 'public function '. $relation_child_name ."(){\n" . "\t\t return".' $this->belongsTo('. $app_child_model .' , "'. $request->field_in_relationship[$relation_id] .'");'."\n}\n\n//relationship places";                        
                    // Replace the old function with the new
                    $child_model_file = str_replace("//relationship places", $relation_child_function , $child_model_file);
                    // Put the changes in the file
                    file_put_contents(app_path() . "/$child_model.php", $child_model_file);
                    // parent modifing
                    $parent_model_file = file_get_contents(app_path() . "/$parent_model.php");
                    // define the parent model to put it in the function
                    $app_parent_model = "'App\\$child_model'";
                    // add the relationship function to parent model
                    $relation_parent_function = 'public function '. $relation_parent_name ."(){\n" . "\t\t return".' $this->hasMany('. $app_parent_model .' , "'. $request->field_in_relationship[$relation_id] .'");'."\n}\n\n//relationship places";
                    // Replace the old function with the new
                    $parent_model_file = str_replace("//relationship places", $relation_parent_function, $parent_model_file);
                    // Put the changes in the file
                    file_put_contents(app_path() . "/$parent_model.php", $parent_model_file);

                } elseif ($relationship->relation_name != $request->relation_name[$relation_id] || $relationship->parent_id != $parent_id || $relationship->field_id != $field_id) {
                    $relationship->relation_name = $request->relation_name[$relation_id];
                    $relationship->parent_id = $parent_id;
                    $relationship->child_id = $table_id;
                    $relationship->field_id = $field_id;
                    $relationship->save();
                    //$child_table = a_Tables::find($table_id)->table;

                    $child_model_edit = '/public\s*function\s*' . $relation_old_child_name . '\s*\(\)\s*\n*\{\s*\n*return\s+\$this->belongsTo\(.*\);\s*\n*\}/';
                    // get the child model content
                    try{
                        // Get the content of the child model
                        $child_model_file = file_get_contents(app_path() . "/$child_model.php");
                    // catch any error if it happened
                    }catch(Exception $e){
                        echo "Error while opening the file , The error is : " . $e;
                    }
                    try{
                        // define the model that should be the parent of the relation ship
                        $app_child_model = "'App\\$parent_model'";
                        // Make the function of the child part of the relationship
                        $relation_child_function = 'public function '. $relation_child_name ."(){\n" . "\t\t return".' $this->belongsTo('. $app_child_model .' , "'. $request->field_in_relationship[$relation_id] .'");'."\n}\n\n//relationship places";                        
                        // Replace the old function with the new
                        $child_model_file = preg_replace($child_model_edit, $relation_child_function , $child_model_file);
                        // Put the changes in the file
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
                        // define the parent model to put it in the function
                        $app_parent_model = "'App\\$child_model'";
                        // add the relationship function to parent model
                        $relation_parent_function = 'public function '. $relation_parent_name ."(){\n" . "\t\t return".' $this->hasMany('. $app_parent_model .' , "'. $request->field_in_relationship[$relation_id] .'");'."\n}\n\n//relationship places";
                        // Replace the old function with the new
                        $parent_model_file = preg_replace($parent_model_edit, $relation_parent_function, $parent_model_file);
                        // Put the changes in the file
                        file_put_contents(app_path() . "/$parent_model.php", $parent_model_file);
                    }catch(Exception $e){
                        echo "Error while modifing the file , The error is : " . $e;
                    }

                }

            }
        }

        // got replaced with line from 441
        // if (count($new_parents) > 0) {
        //     $this->modify_model($new_parents, $new_fields, $table_name, $table_model, $new_names);
        // }
        
        
        $a_tables = a_Tables::find($table_id);
        if ($a_tables->table != $request->table_name){
            try{
                DB::statement("RENAME TABLE $a_tables->table TO $request->table_name ;");
                // table name
                $a_tables->table = $request->table_name;
            } catch(Execption $e){
                echo("Error while changing the table name");
            }
        }
        if ($a_tables->link_name != $request->link_name){
            // this is the name of link in dashlinks in a slide
            $a_tables->link_name = $request->link_name;
        }
        if ($a_tables->slug != $request->slug){
            // store the slug that should be url of this table
            $a_tables->slug = $request->slug;
        }
        if ($a_tables->module_name != $request->module_name){
            if (file_exists(app_path($a_tables->module_name.".php"))){
                if (is_writable($a_tables->module_name.".php")){
                        rename(app_path($a_tables->module_name.".php") , app_path($request->module_name.".php"));     
                        // store model name in database
                        $a_tables->module_name = $request->module_name;
                } else {
                    chmod(app_path($a_tables->module_name.".php") , 777);
                    rename(app_path($a_tables->module_name.".php") , app_path($request->module_name.".php"));     
                        // store model name in database
                    $a_tables->module_name = $request->module_name;
                }       
            }
        }
        $fields_type = implode("," ,$request->field_type);
        if ($a_tables->field_types != $fields_type){
            // store array data in field by implode "," in it to avoid errors
            $a_tables->field_types = $fields_type;
        }
        if ($a_tables->icon != $request->icon){
            // store the icon that should appear in the slide list in dashboard (I use the icons of font awoesome)
            $a_tables->icon = $request->icon;
        }
        if ($a_tables->editable != $request->editable){
            $a_tables->editable = $request->editable > 1 or $request->editable < 0 or ! is_int($request->editable) ? 1 : 0;
        }
        // Save the data
        $a_tables->save();

        //$table_fields = fields::where("table_id" , $table_id)->get();


        $request->session()->flash('table_success', 'قد تم تعديل الجدول بنجاح :)');


        return redirect()->route("show_all");
    }

    public function DeleteTable($table_id)
    {
        $table = a_Tables::find($table_id);
        if (file_exists(app_path($table->module_name.".php")) && isset($table)){
            $table->fields()->delete();
            try{
                DB::statement("DROP TABLE $table->table;");
            }catch(Exception $e){
                echo("OHHHH , there is error in database");
            }
            if (is_writable(app_path($table->module_name.".php"))){
                unlink(app_path($table->module_name.".php"));
            } else{
                chmod(app_path($table->module_name.".php") , 777);
                unlink(app_path($table->module_name.".php"));
            }
            $table_parent = relationships::where("parent_id" , $table_id)->get();
            $table_child = relationships::where("child_id" , $table_id)->get();
            if (isset($table_parent) && $table_parent->isNotEmpty()){
                // delete the relationships functions from the child modules
                foreach($table_parent as $relation){
                    $module = a_Tables::find($relation->child_id)->first()->module_name;
                    $search_for = '/public\s*function\s*' . $relation->relation_name . "_parent" . '\s*\(\)\s*\n*\{\s*\n*return\s+\$this->belongsTo\(.*\);\s*\n*\}/';                    
                    $module_file = file_get_contents(app_path() . "/$module.php");
                    $model_file = preg_replace($search_for, "" , $module_file);
                    file_put_contents(app_path() . "/$module.php", $model_file);
                    // delete the relationship from the table in the database
                    $relation->delete();
                }                
            } elseif (isset($table_child) && $table_child->isNotEmpty()){
                // delete the relationships functions from the child modules                
                foreach($table_child as $relation){
                    $module = a_Tables::find($relation->parent_id)->first()->module_name;
                    $search_for = '/public\s*function\s*' . $relation->relation_name . "_child" . '\s*\(\)\s*\n*\{\s*\n*return\s+\$this->hasMany\(.*\);\s*\n*\}/';                    
                    $module_file = file_get_contents(app_path() . "/$module.php");
                    $model_file = preg_replace($search_for, "" , $module_file);
                    file_put_contents(app_path() . "/$module.php", $model_file);
                    // delete the relationship from the table in the database
                    $relation->delete();
                }
            }
            $table->delete();
            session()->flash('table_success', 'You deleted the table successfully :)');
            return redirect()->route("show_all");
        }
    }

}
