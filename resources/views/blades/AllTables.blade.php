@extends("layouts.app")

@section("content")

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
            @if (session()->has("table_success"))
                    <div class="alert alert-success"><h3>{{ session()->get("table_success") }}</h3></div>
            @endif
            @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
            @endif
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">All tables</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body @if (empty($all_tables)) table-responsive no-padding @endif">
                    @if ($all_tables->isNotEmpty())
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Table</th>
                                <th>Status</th>
                                <th>Slug</th>
                                <th>Link name</th>
                                <th>Icon</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Option</th>
                            </tr>
                             @foreach($all_tables as $table)
                               <tr>
                                    <td>{{ $table->id }}</td>
                                    <td>{{ $table->table }}</td>
                                    <td>{{ $table->status }}</td>
                                    <td>{{ $table->slug }}</td>
                                    <td>{{ $table->link_name }}</td>
                                    <td><i class="fa {{ $table->icon }}"></i></td>
                                    <td>
                                        <a href="{{ route('edit_table' , $table->slug) }}" class="btn btn-warning">Edit</a>
                                    </td>
                                    <td>
                                        {{ Form::open(["route" => ["delete_table" ,$table->id] , "method" => "delete"])  }}
										    <button type="submit" class="btn btn-danger"  @click="delete_confirm()" ><i class="fa fa-times"></i> Delete</button>
									    {{ Form::close() }}
                                    </td>
                                    <td>
                                        @if ($table->table != "users")
                                            <a href="{{ route('add_option' , $table->slug) }}" class="btn btn-primary">Add Option</a> 
                                        @else
                                            <p>------</p>
                                        @endif
                                    </td>
                               </tr>
                            @endforeach
                        </table>
                    @else
                        <div class="alert alert-info"><h3>There is no tables add one : <a href="{{ route('add_table') }}">here</a></h3></div>
                    @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>

@endsection