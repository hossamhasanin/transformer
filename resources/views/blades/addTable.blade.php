@extends('layouts.app')

@section('content')
<script type="text/javascript">
  function remove_it(e) {
    var field_num = $(e).attr("num");
    $(".field-"+field_num).remove();
  }
  function show_label(s) {
    var vid = $(s).attr("vid");
    if ($(s).is(":checked")){
        var f_label_name = "<div class='col-xs-3 col-md-3 label-"+ vid +"'><input class='form-control' placeholder='Label Name' name='label_name[]' type='text'></div>"
        $("#show_label-"+vid).append(f_label_name);
    } else {
        $(".label-"+vid).remove();
    }
  }/*
  function remove_relation(re) {
    var relation_num = $(re).attr("rnum");
    $(".relation-"+relation_num).remove();
  }*/
</script>
<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Add new table</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{ Form::open(["route" => "add_table"]) }}
          <div class="box-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-group">
              <label>Table name</label>
              {{ Form::text("table_name" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label>Link name</label>
              {{ Form::text("link_name" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label>Slug name</label>
              {{ Form::text("slug" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label>Model name</label>
              {{ Form::text("module_name" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label>Icon</label>
              <input class="icp demor form-control" name="icon" type="text">
            </div>
            <div class="form-group">
              <label>Editable</label>
              <select name="editable">
                  <option value="1">True</option>
                  <option value="0">False</option>
              </select>
            </div>
            <hr>
              <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Fields</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered" id="fields">
                <tr>
                  <th>Field name</th>
                  <th>Field type</th>
                  <th style="width: 40px">Nullable</th>
                  <th>Default value</th>
                  <th>Remove</th>
                </tr>
                <tr>
                  <td><input class="form-control f-name f_name-0" placeholder="Field Name" name="field_name[]" type="text"></td>
                  <td>
                    <select class="form-control" name="field_type[]">
                        <option>chose</option>
                        <option value="float">Float</option>
                        <option value="dateTime">DateTime</option>
                        <option value="integer">Integer</option>
                        <option value="longText">LongText</option>
                        <option value="mediumText">MediumText</option>
                        <option value="string">Varchare</option>
                        <option value="text">Text</option>
                    </select>
                  </td>
                  <td>
                    <div class="checkbox checkbox-slider--b checkbox-slider-md">  
                      <label>
                        <input name="nullable[]" type="checkbox"><span></span>
                      </label>
                    </div>
                  </td>
                  <td>
                    <input class="form-control d_value-0" placeholder="Defualt value" name="default_value[]" type="text">
                  </td>
                  <td>
                    <h4 class="text-danger" style="font-family: 'Mada', sans-serif;">يا عزيزي لا يجب ازالة هدا الحقل</h4>  
                  </td>
                </tr>
                <tr :is="all_field.component" v-for="all_field in all_fields" v-bind="all_field.props">
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          </div>
      </div>
            <div class="row">
              <div class="col-xs-4">
                <div class="btn btn-success" @click="add_field()" id="add_field">Add Field</div>
              </div>
            </div>
            <hr>
            <div>
              <h3>Relationships</h3>
              <div class="alert alert-info" id="b_mess"><h4>Add new Relationship <strong class="text-danger">Here !</strong></h4></div>
              <div class="relations" style="margin-bottom: 5px;" :is="all_relation.component" v-for="all_relation in all_relations" v-bind="all_relation.props">
              </div>
            </div>
            <div class="row">
              <div class="col-xs-4">
                <div class="btn btn-success" @click="add_relation()" id="add_relation"><i class="fa fa-plus-square" aria-hidden="true"></i></div>
              </div>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          {{ Form::close() }}
      </div>
    </div>
  </div>

</section>

<script type="text/javascript">
 $(document).ready(function(){
   var i = 0;
/*
    $("#add_field").click(function () {
        i += 1
        var field = "<tr class='field-"+ i +"'><td><input class='form-control f_name-"+ i +"' placeholder='Field Name' name='field_name["+ i +"]' type='text'></td><td><select class='form-control' name='field_type["+ i +"]'><option>chose</option><option value='float'>Float</option><option value='dateTime'>DateTime</option><option value='integer'>Integer</option><option value='longText'>LongText</option><option value='mediumText'>MediumText</option><option value='string'>Varchare</option><option value='text'>Text</option></select></td><td><input type='checkbox' name='nullable["+ i +"]' data-toggle='toggle' ></td><td><input class='form-control d_value-"+ i +"' placeholder='Defualt value' name='defualt_value["+ i +"]' type='text'></td><td><div class='btn btn-danger remove_field' onclick='remove_it(this)' num= "+ i +">X</div></td></tr>"
        $("#fields").append(field);
    });
*/
    //var k = 0;


    $('.demor').iconpicker();
    //window.tests = {!! json_encode($all_tables) !!}
});
</script>


<!-- Warning do not use it causes errors with laravel 5.4 -->
@stop
