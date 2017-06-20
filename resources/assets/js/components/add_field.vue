<script>
	export default {
		props: ["order" , "page" , "other_tables"],
		data () {
			return {

			}
		},
		methods : {
			remove_field(fi) {
				$(fi).remove();
			},
            check_all(id){
                $(".check_this-"+id).prop('checked', $(".check_all-"+id).prop("checked"));
                document.getElementById("send_c_value-"+id).value = "show,add,edit,";
                if (!$(".check_all-"+id).prop("checked")){
                    document.getElementById("send_c_value-"+id).value = "";
                }
            },
            send_v_checks(order , val){
                var chosed_allready = document.getElementById("send_c_value-"+order).value;
                if ($(".check_"+ val +"-"+order).prop("checked") && chosed_allready.search(val) == -1) {
                    document.getElementById("send_c_value-"+order).value += val + ","
                    if (document.getElementById("send_c_value-"+order).value.search("add") > -1 && document.getElementById("send_c_value-"+order).value.search("edit") > -1 && document.getElementById("send_c_value-"+order).value.search("show") > -1){
                        $(".check_all-"+order).prop("checked" , true)
                    }
                }
                if (!$(".check_"+ val +"-"+order).prop("checked")){
                    var new_value = document.getElementById("send_c_value-"+order).value.replace(val + "," , '');
                    document.getElementById("send_c_value-"+order).value = new_value
                    //if (document.getElementById("send_c_value-"+order).value == ""){
					$(".check_all-"+order).prop("checked" , false)
                    //}
                }
            }
		}
	}
</script>


<template>
	<tr v-bind:class="'field-'+ order ">
		<input type="hidden" v-bind:name="'ids['+ order +']'" v-bind:value="order" class="ids">
		<td>
		  <input v-bind:class="'form-control f-name f_name-'+ order " placeholder='Field Name' v-bind:name="'field_name['+ order +']'" type='text'>
		</td>
		<td>
		  <select class='form-control' v-bind:name="'field_type['+ order +']'">
		  	<option>chose</option>
		  	<option value='float'>Float</option>
		  	<option value='dateTime'>DateTime</option>
		  	<option value='integer'>Integer</option>
		  	<option value='longText'>LongText</option>
		  	<option value='mediumText'>MediumText</option>
		  	<option value='string'>Varchare</option>
		  	<option value='text'>Text</option>
		  </select>
		</td>
		<td>
		  <div class="checkbox checkbox-slider--b checkbox-slider-md">	
			<label>
				<input v-bind:name="'nullable['+ order +']'" type="checkbox"><span></span>
			</label>
		  </div>
		</td>
		<td v-if="page == 'edit'">
			<input type="hidden" v-bind:name="'visibility['+ order +']'" v-bind:id="'send_c_value-'+order" value="">
			<div>
				<label>
					<input type="checkbox"  @change="check_all(order)" v-bind:class="'check_all-'+order" ><span> All</span>
				</label>
			</div>
			<div>
				<label>
					<input type="checkbox" @change="send_v_checks(order , 'show')" v-bind:class="'check_this-'+order + ' check_show-'+order" value="show"><span> show page</span>
				</label>
			</div>
			<div>
				<label>
					<input type="checkbox" @change="send_v_checks(order , 'add')" v-bind:class="'check_this-'+order + ' check_add-'+order" value="add"><span> add page</span>
				</label>
			</div>
			<div>
				<label>
					<input type="checkbox" @change="send_v_checks(order , 'edit')" v-bind:class="'check_this-'+ order + ' check_edit-'+order" value="edit"><span> edit page</span>
				</label>
			</div>
		</td>
		<td>
			<input class='form-control d_value-"+ i +"' placeholder='Defualt value' v-bind:name="'default_value['+ order +']'" type='text'>
		</td>
		<td v-if="page == 'edit'">
			<input class="form-control" placeholder="Label name" name="'label_name['+ order +']'" type="text">
		</td>
		<td>
			<div class='btn btn-danger remove_field' @click="remove_field('.field-'+order)" num= "+ i +"><i class="fa fa-times" aria-hidden="true"></i></div>
		</td>
	</tr>
</template>