@extends("layouts.app")

@section("content")

<section class="content">
	<div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="cat_name" cat_name="{{ $cat->cat_name }}">Edit category : {{ $cat->cat_name }}</h3>
                    </div>
                    <div class="box-body">
                    	{{ Form::open(["route" => ["update_cat" ,$cat->id] , "method" => "PUT"])  }}
                    		<div class="form-group">
                    			<label>Name</label>
              					{{ Form::text("cat_name" , "" , ["class" => "form-control" , "placeholder" => "Category name" , "v-model" => "edit_cat_name"]) }}
                    		</div>
                    		<div class="form-group">
                    			<label>Slug</label>
              					<input type="text" name="cat_slug" class="form-control" v-bind:value="edit_cat_name | slugify" placeholder="Category slug">
                    		</div>
                    		@if (isset($parents))
	                    		<div class="form-group">
	                    			<label>parent</label>
	              					<select name="parent">
	              						<option value="0">None</option>
	              						@foreach($parents as $parent)
	              							<option value="{{ $parent->id }}" @if ($cat->parent == $parent->id) "selected" @endif>{{ $parent->cat_name }}</option>
	              						@endforeach
	              					</select>
	                    		</div>
	                    	@endif
                    		<div class="form-group">
                    			<label>Description</label><br>
              					<textarea name="description" cols="70" rows="5">{{ $cat->description }}</textarea>
                    		</div>
                    		<div class="form-group">
                    			<label>Icon</label>
              					<input class="icp demor form-control" name="icon" type="text" value="{{ $cat->icon }}">
                    		</div>
                    		<div class="box-footer">
					            <button type="submit" class="btn btn-primary">Submit</button>
					        </div>
                    	{{ Form::close() }}
                    </div>
            	</div>
            </div>
    </div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		$('.demor').iconpicker();
	});
</script>

@stop