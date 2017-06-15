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
                    {{ Form::open(["route" => ["store_option" , $table_id]])  }}
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
                                        <option value="varchar(255)" {!! $field_data->field_type == "varchar(255)" ? "selected" : "" !!}>Varchare</option>
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
                            </tr>
                            @endforeach
                        </table>
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
@stop