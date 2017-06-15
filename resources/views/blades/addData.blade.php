@extends("layouts.app")

@section("content")

<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Quick Example</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{ Form::open(["route" => "add_field"]) }}
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
              <label for="exampleInputEmail1">Table name</label>
              {{ Form::text("table_name" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Link name</label>
              {{ Form::text("link_name" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Slug name</label>
              {{ Form::text("slug" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Model name</label>
              {{ Form::text("module_name" , "" , ["class" => "form-control" , "placeholder" => "Table name"]) }}
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Icon</label>
              <input class="icp demo form-control" name="icon" type="text">
            </div>
            <hr>
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


@stop
