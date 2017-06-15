@extends('layouts.app')

@section('content')

<section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">All {{ ucfirst($p_name) }}</h3>

            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                @foreach($columns as $column)
                  @if (!in_array($column , ["password" , "remember_token" , "updated_at"]))
                    <th>{{ ucfirst($column) }}</th>
                  @endif
                @endforeach
              </tr>
              @foreach($all as $d)
              <tr>
                  @foreach($columns as $column)
                    @if (!in_array($column , ["password" , "remember_token" , "updated_at"]))
                      <td>{{ $d->$column == "" ? "------" : $d->$column }}</td>
                    @endif
                  @endforeach
              </tr>
              @endforeach
            </table>
          </div>
          <!-- /.box-body -->
          {{ $all->links() }}
        </div>
        <!-- /.box -->
      </div>
    </div>
</section>

@stop
