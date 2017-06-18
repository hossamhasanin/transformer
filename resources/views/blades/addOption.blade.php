@extends("layouts.app")

@section("content")

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add options</h3>
                    </div>
                    <!-- /.box-header -->
                    {{-- {{ Form::open(["route" => ["store_option" , $table_id]])  }} --}}
                    <form id="option_form" table_id = "{{ $table_id }}">
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
                        @if(session()->has('add_option'))
                            <div class="alert alert-success">
                                <h4>{{ session()->get('add_option') }}</h4>
                            </div>
                        @endif
                        <table class="table table-bordered" id="fields">
                            <tr>
                                <th>Field name</th>
                                <th>Field type</th>
                                <th style="width: 40px">Nullable</th>
                                <th>Visibility</th>
                                <th>Default value</th>
                                <th>Label name</th>
                            </tr>
                             @foreach($fields_data as $field_data)
                              <tr>
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
                                        <option value="string" {!! $field_data->field_type == "string" ? "selected" : "" !!}>Varchare</option>
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
                                    <input class="form-control d_value-0" placeholder="Default value" value="{{ $field_data->default_value  }}" name="default_value[{{ $field_data->id }}]" type="text">
                                </td>
                                <td>
                                    <input class="form-control d_value-0" placeholder="Label name" value="{{ $field_data->label_name  }}" name="label_name[{{ $field_data->id }}]" type="text">
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div @click="send_form_ajax('option_form','/transformer/public/api/v1/storeotion/')" class="btn btn-primary">Submit</div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Fields render :</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Field name</th>
                                <th>Render type</th>
                                <th>Options</th>
                            </tr>
                             @foreach($fields_data as $field_data)
                              <tr id="field_render_container-{{ $field_data->id }}">
                                  <input type="hidden" name="ids[{{ $field_data->id }}]" value="{{ $field_data->id }}" class="ids">
                                  <input type="hidden" id="multichoice_value-{{ $field_data->id }}" name="multichoice_value[{{ $field_data->id }}]" value="">
                                <td><input class="form-control f-name f_name-0" value="{{ $field_data->field_name  }}" placeholder="Field Name" name="field_name[{{ $field_data->id }}]" type="text"></td>
                                <td>
                                    <input type="radio" name="render_type[{{ $field_data->id }}]" value="textbox"> Textbox<br>
                                    <input type="radio" name="render_type[{{ $field_data->id }}]" value="textarea"> Textarea<br>
                                    <input type="radio" name="render_type[{{ $field_data->id }}]" value="checkbox"> Checkbox<br>
                                    <input type="radio" name="render_type[{{ $field_data->id }}]" value="radiobutton"> Radiobutton<br>
                                    <input type="radio" name="render_type[{{ $field_data->id }}]" @click="edit_multi_vals($event)" field_id="{{ $field_data->id }}" data-toggle="modal" data-target="#myModal-{{ $field_data->id }}" value="multichoice"> Multi choice field<br>                                                                                                                                                
                                </td>
                                <td></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
            @foreach($fields_data as $field_data)        
            <!-- Modal -->
            <div id="myModal-{{ $field_data->id }}"  class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content" style="border-radius: 10px;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modal Header {{ $field_data->id }}</h4>
                        </div>
                        <div class="modal-body" modal_id="{{ $field_data->id }}">
                            <div class="row multi_parts-{{ $field_data->id }}">
                                <div class="col-md-6">
                                    <div class="btn btn-success" field_id="{{ $field_data->id }}" @click="multi_custom($event)">Use Custom values</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="btn btn-primary">Database values</div>                                
                                </div>                            
                            </div>
                            <div id="render_table-{{ $field_data->id }}" style="display:none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-primary">
                                            <div class="box-body">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Value</th>
                                                        <th>Text</th>
                                                    </tr>
                                                    <tr :is="render_multi_value.component" v-for="render_multi_value in render_multi_values" v-bind="render_multi_value.props"></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <multi_choice :order="1" :target_field="{{ $field_data->id }}"></multi_choice>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success pull-left" @click="save_multi_custom($event)" field_id = "{{ $field_data->id }}">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </section>
@stop