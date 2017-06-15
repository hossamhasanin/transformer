@extends("layouts.app")

@section("content")

    <input type="hidden" id="last_relationship" value={{ $last_relationship }}>
    <input type="hidden" id="last_field" value={{ $last_field }}>
    <section class="content">
        {{ Form::open(["route" => ["update_table" , $table_id] , "method" => "put"])  }}
        <div class="row">
             @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
            @endif
            @if(session()->has('edit_table'))
                        <div class="alert alert-success">
                            <h4>{{ session()->get('edit_table') }}</h4>
                        </div>
            @endif
            @if(session()->has('add_option'))
                        <div class="alert alert-success">
                            <h4>{{ session()->get('add_option') }}</h4>
                        </div>
            @endif
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit : {{ $table_nane }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Table name</label>
                            {{ Form::text("table_name" , $table_info->table , ["class" => "form-control" , "placeholder" => "Table name"]) }}
                        </div>
                        <div class="form-group">
                            <label>Link name</label>
                            {{ Form::text("link_name" , $table_info->link_name , ["class" => "form-control" , "placeholder" => "Table name"]) }}
                        </div>
                        <div class="form-group">
                            <label>Slug name</label>
                            {{ Form::text("slug" , $table_info->slug , ["class" => "form-control" , "placeholder" => "Table name"]) }}
                        </div>
                        <div class="form-group">
                            <label>Model name</label>
                            {{ Form::text("module_name" , $table_info->module_name , ["class" => "form-control" , "placeholder" => "Table name"]) }}
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input class="icp demor form-control" name="icon" value="{{ $table_info->icon }}" type="text">
                        </div>
                        <div class="form-group">
                            <label>Editable</label>
                            <select name="editable">
                                <option value="1" @if ($table_info->editable == 1) selected @endif >True</option>
                                <option value="0" @if ($table_info->editable == 0) selected @endif >False</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box">
                    {{-- <div class="box-header with-border">
                        <h3 class="box-title">Edit : {{ $table_nane }}</h3>
                    </div> --}}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered" id="fields">
                            <tr>
                                <th>Field name</th>
                                <th>Field type</th>
                                <th style="width: 40px">Nullable</th>
                                <th>Visibility</th>
                                <th>Default value</th>
                                <th>Label name</th>
                                <th>Remove</th>
                            </tr>
                            @foreach($table_data as $field_data)
                                <tr id="edit_field-{{ $field_data->id }}">
                                    <input type="hidden" name="ids[{{ $field_data->id }}]" value="{{ $field_data->id }}" class="ids">
                                    <td><input class="form-control f-name f_name-0" value="{{ $field_data->field_name  }}" placeholder="Field Name" name="field_name[{{ $field_data->id }}]" type="text"></td>
                                    <td>
                                        <select class="form-control" name="field_type[{{ $field_data->id }}]">
                                            <option>chose</option>
                                            <option value="float" {!! $field_data->field_type == "float" ? "selected" : "" !!}>Float</option>
                                            <option value="dateTime" {!! $field_data->field_type == "dateTime" ? "selected" : "" !!}>DateTime</option>
                                            <option value="int(11)" {!! $field_data->field_type == "int(11)" ? "selected" : "" !!}>Integer</option>
                                            <option value="longText" {!! $field_data->field_type == "longText" ? "selected" : "" !!}>LongText</option>
                                            <option value="mediumText" {!! $field_data->field_type == "mediumText" ? "selected" : "" !!}>MediumText</option>
                                            <option value="varchar(255)" {!! $field_data->field_type == "string" ? "selected" : "" !!}>Varchare</option>
                                            <option value="text" {!! $field_data->field_type == "text" ? "selected" : "" !!}>Text</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="checkbox checkbox-slider--b checkbox-slider-md">
                                            <label>
                                                <input name="nullable[{{ $field_data->id }}]" {!! $field_data->field_nullable == 1 ? "checked" : "" !!} type="checkbox" value="1"><span></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="checks">
                                        <input type="hidden" name="visibility[{{ $field_data->id }}]" id="send_c_value-{{ $field_data->id }}" value="{!! $field_data->visibility == 'all' ? 'show,add,edit' : $field_data->visibility !!}">
                                        <div>
                                            <label>
                                                <input type="checkbox"  @change="chose_all({{ $field_data->id }})" id="check_all-{{ $field_data->id }}"
                                                {!! strpos($field_data->visibility , "show") !== false && strpos($field_data->visibility , "add") !== false && strpos($field_data->visibility , "edit") !== false ? "checked" : "" !!} ><span> All</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label>
                                                <input type="checkbox" @change="send_v_checks({{ $field_data->id }} , 'show')" class="v_check-{{ $field_data->id }} v_check_show-{{ $field_data->id }}" value="show"><span> show page</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label>
                                                <input type="checkbox" @change="send_v_checks({{ $field_data->id }} , 'add')" class="v_check-{{ $field_data->id }} v_check_add-{{ $field_data->id }}" value="add"><span> add page</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label>
                                                <input type="checkbox" @change="send_v_checks({{ $field_data->id }} , 'edit')" class="v_check-{{ $field_data->id }} v_check_edit-{{ $field_data->id }}" value="edit"><span> edit page</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input class="form-control d_value-0" placeholder="Defualt value" value="{{ $field_data->default_value  }}" name="default_value[{{ $field_data->id }}]" type="text">
                                    </td>
                                    <td>
                                        <input class="form-control d_value-0" placeholder="Label name" value="{{ $field_data->label_name  }}" name="label_name[{{ $field_data->id }}]" type="text">
                                    </td>
                                    <td>
                                        <div class="btn btn-danger" v-on:click="delete_field({{ $field_data->id }})">Delete</div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr :is="back_ag.component" v-for="back_ag in back_again" v-bind="back_ag.props"></tr>
                            <tr :is="field.component" v-for="field in add_new_field" v-bind="field.props"></tr>
                        </table>
                            <div :is="undo.component" v-for="undo in noti_undo" v-bind="undo.props"></div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <div class="btn btn-success pull-right" v-on:click="add_new_field_in_edit()">Add new field</div>
                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-4">
                <div class="btn btn-success" v-on:click="add_new_relation({{ $all_fields }})" >New Relationship <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </div>
            </div>
        </div>
        <div :is="add_new.component" v-for="add_new in add_new_relations" v-bind="add_new.props" :all_tables="{{ $all_tables }}"></div>
        @foreach($relations as $relation)
                <edit_relation ids="{{ $relation->id }}" table_id="{{ $table_id }}" :all_tables="{{ $all_tables }}" relation_table="{{ $relation->parent_table->table }}" field="{{ $relation->fields->field_name }}" :all_fields="{{ $all_fields }}" relation_name="{{ $relation->relation_name }}"></edit_relation>
        @endforeach
        {{ Form::close() }}
    <!-- Modal -->
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="border-radius: 10px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <h4 style="margin: 5px;">Add parent function</h4>
                            <textarea id="parent_code"><?php echo "<?php \n\n/*Add your parent function*/ \n\n?>" ?></textarea>
                            <hr>
                            <h4 style="margin: 5px;">Add child function</h4>
                            <textarea id="child_code"><?php echo "<?php \n\n/*Add your child function*/ \n\n?>" ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
@stop