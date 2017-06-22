<script>
	export default {
		props: ["relation_field" , "order" , "other_tables"],
		mounted () {
			this.fetch_fields(this.relation_field);
			this.fetch_tables();
		},
		data () {
			return {
				koko: "koko"
			}
		},
		methods : {
			fetch_fields(fields) {
				var fetch_field = $("#fetch_field-"+this.order);
				for (var is = 0; is < fields.length; is++) {
					fetch_field.append('<option value="'+ fields[is] +'" > '+ fields[is] +'</option>');
				}
				//console.log(fields);

			},
			remove_relation(re) {
			    $(re).remove();
			},
			fetch_tables() {
				var fetch_table = $("#fetch_table-"+this.order);
				var o = 0;
				while(o<this.other_tables.data.length){
					fetch_table.append('<option value="'+ Object.values(this.other_tables.data[o])[1] +'" > '+ Object.values(this.other_tables.data[o])[1] +'</option>');
							//console.log(window.tests[f].table)
					o += 1
				}
			}
		}
	}
</script>

<template>
	<div v-bind:class="'row relation-' + order">
		<div class="col-md-3">
			<label for="relation_name">Relation Name</label>
			<input type="text" class="form-control" v-bind:name="'relation_name['+ order +']'" >
		</div>
		<div class='col-xs-3 col-md-4'>
			<label>chose field</label>
			<select class='form-control' v-bind:id="'fetch_field-' + order" v-bind:name="'relation_fields['+ order +']'">
				<option>chose</option>
			</select>
		</div>
		<div class='col-xs-3 col-md-4'>
			<label>chose table</label>
			<select class='form-control' v-bind:id="'fetch_table-' + order"  v-bind:name="'relation_tabels['+ order +']'">
				<option>chose</option>
			</select>
		</div>
		<div class='col-xs-1 col-md-1'>
		<label>Remove</label>
			<div class="btn btn-danger pull-left remove_field" @click="remove_relation('.relation-'+order)" ><i class="fa fa-times" aria-hidden="true"></i></div>
		</div>
	</div>

</template>