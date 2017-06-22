<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(["prefix" => "dashboard" , "middleware" => "auth"] , function (){

      Route::get('/', 'HomeController@index');

      Route::group(["prefix" => "table"] , function (){

            Route::get("/add" , function () {
              $tables = \App\a_Tables::get();
              return view("blades.addTable" , ["all_tables" => $tables]);
            })->name("get_add_table");

            Route::post("/add" , "TableController@AddNew")->name("add_table");

            Route::get("/option/{table}" , "TableController@AddOption")->name("add_option");

            Route::post("/option/{table_id}" , "TableController@StoreOption")->name("store_option")->where("table_id" , "\d+");

            Route::get("/edit/{table}" , "TableController@EditTable")->name("edit_table");

            Route::put("/update/{table_id}" , "TableController@UpdateTable")->name("update_table")->where("table_id" , "\d+");
            
            Route::delete("delete/{table_id}" , "TableController@DeleteTable")->name("delete_table");
      
      });

      // Categories routs
      Route::group(["prefix" => "cat" , "middleware" => "perm_access"] , function ()
      {
          Route::get("/" , "CategoryController@AllCats")->name("all_cats");
          Route::get("/add" , "CategoryController@AddNew_form")->name("get_add_cat");
          Route::post("/add" , "CategoryController@AddNew")->name("add_cat");
          Route::get("/edit/{cat_id}" , "CategoryController@EditCat")->name("edit_cat")->where("cat_id" , "\d+");
          Route::put("/update/{cat_id}" , "CategoryController@UpdateCat")->name("update_cat");
          Route::delete("/delete/{cat_id}" , "CategoryController@DeleteCat")->name("delete_cat");
      });

      // Show all tables that inside the database and save inside the (a_table)
      Route::get("/tables/" , "TableController@ShowAll")->name("show_all");

      // Show the columns that is inside that table {table}
      Route::get("/{table}" , "PagesController@ShowData");

     // Route::get("/add/{table}" , "PagesController@newData");

});

Route::get("/test_js" , function(){
  return view("blades.test_js");
});

Route::get('/test', function()
{

  // Artisan::call('make:model', [
  //       "name" => "koko"
  //   ]);

  // $files = file(app_path() . "/a_Tables.php");
  //
  // if (in_array("}\n" , $files)){
  //   echo "Exist";
  // }
/*
  $m = file_get_contents(app_path() . "/a_Tables.php");

  $m = str_replace("}\n" , "" , $m);

  $h = $m . "koko
}
  ";

  file_put_contents(app_path() . "/test.php" , $h);
*/


/*
$tr = ["1" => "test" , "2" => "test" , "3" => "sldkfs" , "4" => "lsdkfds"];

foreach (array_count_values($tr) as $v){
    if ($v < 2){
        echo "allow";
    }
}*/

    //$app_child_model = "'App\\$parent_model'";
    /*$table = "tester";
    $child_model_edit = '/public\s*function\s*'. $table .'\s*\(\)\s*\n*\{\s*\n*return\s+\$this->belongsTo\(.*\);\s*\n*\}/';

    $parent_model_edit = '/public\s*function\s*users\s*\(\)\s*\n*\{\s*\n*return\s+\$this->hasMany\(.*\);\s*\n*\}/';
    // get the child model content
    $child_model_file = file_get_contents(app_path() . "/User.php");


    $child_model_file = preg_replace($child_model_edit , "" , $child_model_file);

    file_put_contents(app_path() . "/User.php" , $child_model_file);*/

     //dd(\App\a_Tables::pluck("table"));

    /**$s = \App\a_Tables::where("slug" , "tester")->first()->child_relation()->get();
   // $d = \App\relationships::where("child_id" , "2")->get()->all()->fields->get();
    dd($s);*/
    /*$relation_child_function = '
public function tester(){
        return $this->belongsTo("App\tester" , "koko");
}';*/

    //$extract_relation_function = '/public\s*function\s*(\w+)\s*\(\)\s*\n*\{\s*\n*return\s+\$this->belongsTo\("App\\\\([a-zA-Z]+)"\s*,\s*"([a-zA-Z]+)"\);\s*\n*\}/';


    /*preg_match($extract_relation_function,
        $relation_child_function, $matches);*/
    //$host = $matches[1];
    //dd($matches);
// get last two segments of host name
    /*preg_match('/[^.]+\.[^.]+$/', $host, $matches);
    echo "domain name is: {$matches[0]}\n";*/

    //chmod(storage_path("framework/sessions") , 0777);
    //   $search_for = '/class\s*'. 'test2' .'\s*extends\s*Model\s*\n*\{\n*\s*\/\/\n*\}/';
    //   $module_file = file_get_contents(app_path() . "/test2.php");
    //   $add_table_name = 'class '. 'test2 ' ."extends Model \n{\n"."\t protected " . '$table = '. "'test2';\n\n" .'}';
    //   $child_model_file = preg_replace($search_for, $add_table_name , $module_file);
    //   file_put_contents(app_path() . "/test2.php", $child_model_file);
    //   $f = file_get_contents(app_path() . "/test2.php");
    //   dd($child_model_file);
    $r = \App\relationships::find(10);
    if ($r == null){
        echo True;
    }
    dd($r);
});
