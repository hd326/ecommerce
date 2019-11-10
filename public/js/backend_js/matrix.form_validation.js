
$(document).ready(function(){
	
	$("#current_pwd").keyup(function(){
		var current_pwd = $("#current_pwd").val();
		$.ajax({
			type: 'get',
			url: '/admin/check-pwd',
			data: {current_pwd: current_pwd},
			success: function(response) {
				if(response == "false") {
					$('#chkPwd').html("<font color='red'>Current Password is Incorrect</font>");
				} else if (response == "true") {
					$('#chkPwd').html("<font color='green'>Current Password is Correct</font>")
				}
			},
			error: function(){
				alert("Error");
			}
		});
	});

	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
	$('select').select2();
	$("#basic_validate").validate({

	});
	// Add Category Validation
    $("#add_category").validate({
		rules:{
			category_name:{
				required:true
			},
			description:{
				required:true,
			},
			url:{
				required:true,
			},
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	$("#edit_category").validate({
		rules:{
			category_name:{
				required:true
			},
			description:{
				required:true,
			},
			url:{
				required:true,
				date: true
			},
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// Add Product Validation

	$("#add_product").validate({
		rules:{
			category_id:{
				required:true
			},
			product_name:{
				required:true
			},
			product_code:{
				required:true,
			},
			product_color:{
				required:true,
			},
			price:{
				required:true,
				number:true
			},
			image:{
				required:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// Edit Product Validation

	$("#edit_product").validate({
		rules:{
			category_id:{
				required:true
			},
			product_name:{
				required:true
			},
			product_code:{
				required:true,
			},
			product_color:{
				required:true,
			},
			price:{
				required:true,
				number:true
			},

		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	
	$("#number_validate").validate({
		rules:{
			min:{
				required: true,
				min:10
			},
			max:{
				required:true,
				max:24
			},
			number:{
				required:true,
				number:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	
	$("#password_validate").validate({
		rules:{
			current_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			new_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			confirm_pwd:{
				required:true,
				minlength:6,
				maxlength:20,
				equalTo:"#new_pwd"
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	$("#delCat").click(function(){
		if(confirm('Are you sure you want to delete this Category?')){
			return true;
		}
		return false;
	});

	$("#delProduct").click(function(){
		if(confirm('Are you sure you want to delete this Product?')){
			return true;
		}
		return false;
	});

	$('#delAttribute').click(function(){
		if(confirm('Are you sure you want to delete this Attribute?')){
			return true;
		}
		return false;
	});

	$('#delImage').click(function(){
		if(confirm('Are you sure you want to delete this Image?')){
			return true;
		}
		return false;
	});

	//$(".deleteRecord").click(function(){
	//	var id = $(this).attr('rel');
	//	var deleteRoute = $(this).attr('rel1');
	//	Swal.fire({
	//		title: 'Are you sure you want to delete this product?',
	//		text: "You won't be able to revert this!",
	//		type: 'warning',
	//		showCancelButton: true,
	//		confirmButtonColor: '#3085d6',
	//		cancelButtonColor: '#d33',
	//		confirmButtonText: 'Yes, delete it!'
	//	  }).then(function(){
	//		  window.location.href= "/admin/"+deleteRoute+"/"+id;
	//	  });
	//});

	//$(".deleteCategory").click(function(){
	//	var id = $(this).attr('rel');
	//	Swal.fire({
	//		title: 'Are you sure?',
	//		text: "You won't be able to revert this!",
	//		type: 'warning',
	//		showCancelButton: true,
	//		confirmButtonText: 'Yes, delete it!',
	//		cancelButtonText: 'No, cancel!',
	//	},
	//	function(){
	//		window.location.href = "/admin/delete-category/"+id;
	//	});
	//});
	$(document).ready(function(){
		var maxField = 10; //Input fields increment limitation
		var addButton = $('.add_button'); //Add button selector
		var wrapper = $('.field_wrapper'); //Input field wrapper
		var fieldHTML = '<div class="field_wrapper" style="margin-left:180px;"><div><input type="text" name="sku[]" placeholder="SKU" style="width:120px; margin-right: 3px; margin-top: 5px;"/><input type="text" name="size[]" placeholder="Size" style="width:120px; margin-right: 3px; margin-top: 5px;"/><input type="text" name="price[]" placeholder="Price" style="width:120px; margin-right: 3px; margin-top: 5px;"/><input type="text" name="stock[]" placeholder="Stock" style="width:120px; margin-right: 3px; margin-top: 5px;"/><a href="javascript:void(0);" class="remove_button" title="Remove field">Remove</a></div></div>'; //New input field html 
		var x = 1; //Initial field counter is 1
		
		//Once add button is clicked
		$(addButton).click(function(){
			//Check maximum number of input fields
			if(x < maxField){ 
				x++; //Increment field counter
				$(wrapper).append(fieldHTML); //Add field html
			}
		});
		
		//Once remove button is clicked
		$(wrapper).on('click', '.remove_button', function(e){
			e.preventDefault();
			$(this).parent('div').remove(); //Remove field html
			x--; //Decrement field counter
		});
	});
});
