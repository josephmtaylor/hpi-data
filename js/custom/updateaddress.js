
jQuery(document).ready(function(){
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#form").validate({
		rules: {
			//first_name: "required",
			//last_name: "required",
			full_name: "required",
			specialty_id_fk: "required",
			npi: "required"
		},
		messages: {
			//first_name: "Please enter First Name",
			//last_name: "Please enter Last Name",
			full_name: "Please enter Full Name",
			specialty_id_fk: "Please select Specialty",
			npi: "Please enter NPI"
		},
		submitHandler: function() {
				
				$("#btnnewdoc").prop('disabled', true);
				var docid = $("#docid").val();
				var uid = $("#adrid").val();
				var action = 'newaddressdoctor';
				if(document.getElementById("advertiser").checked) 
					var adv = 'Y'; 
				else
					var adv = 'N';
					
				var dataString = 'action=' + action + '&adrid=' + uid +  '&docid=' + docid + '&full_name=' +  $("#full_name").val() + '&first_name=' +  $("#first_name").val() + '&last_name=' +  $("#last_name").val() + '&npi=' +  $("#npi").val() + '&phone=' +  $("#cphone").val() + '&fax=' +  $("#cfax").val() + '&specialty_id_fk=' +  $("#specialty_id_fk").val() + '&category_id_fk=' +  $("#category_id_fk").val() + '&notes=' +  $("#doctor_notes").val() + '&status=' +  $("#doctor_status").val()  + '&new=' +  $("#new").val() + '&advertiser=' + adv;
				proceedForm(dataString,1);
			},
	});
	
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#formcnr").validate({
		rules: {
			name: "required",
			category_id_fk: "required"
			
		},
		messages: {
			name: "Please enter Center Name",
			category_id_fk: "Please select Category"
			
		},
		submitHandler: function() {
				
				$("#btnnewcnr").prop('disabled', true);
				var cnid = $("#cnid").val();
				var uid = $("#adrid").val();
				var action = 'newaddresscenter';
				if(document.getElementById("advertiser").checked) 
					var adv = 'Y'; 
				else
					var adv = 'N';
					
				var dataString = 'action=' + action + '&adrid=' + uid +  '&cnid=' + cnid + '&name=' +  $("#name").val() + '&npi=' +  $("#npi").val() + '&phone=' +  $("#cphone").val() + '&fax=' +  $("#cfax").val() + '&specialty_id_fk=' +  $("#specialty_id_fk").val() + '&category_id_fk=' +  $("#category_id_fk").val() + '&description=' +  $("#center_description").val() + '&notes=' +  $("#center_notes").val() + '&status=' +  $("#center_status").val()+'&new='+$("#new").val()+'&advertiser=' + adv;
				proceedForm(dataString,8);
			},
	});
	
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#formud").validate({
		rules: {
			address_1: "required",
			city_id: "required",
			state_id: "required",
			zip_id: "required",
			phone: "required"
		},
		messages: {
			address_1: "Please enter Address line 1",
			city_id: "Please select City",
			state_id: "Please select State",
			zip_id: "Please select Zip",
			phone: "Please enter Phone"
			
		},
		submitHandler: function() {
				
				//$("#btnupdatedoc").prop('disabled', true);
				var uid = $("#addrid").val();
				var action = 'updateaddress';
				var dataString = 'action=' + action + '&address_id=' + uid + '&address_1=' +  $("#address_1").val() + '&address_2=' +  $("#address_2").val() + '&city_id=' +  $("#city_id").val() + '&state_id=' +  $("#state_id").val() + '&zip_id=' +  $("#zip_id").val() + '&county_id=' +  $("#county_id").val() +'&phone=' +  $("#phone").val() + '&fax=' +  $("#fax").val() + '&notes=' +  $("#notes").val() + '&status=' +  $("#status").val();
				proceedForm(dataString,5);
			},
	});

});	

function proceedForm(dataString,action) {
	
	var strurl = "ajaxclient.php";
	// alert(dataString);
	jQuery.ajax({
			type: "POST",
			url: strurl,
			data:  dataString, 
		//	cache: false,
			success: function(result) {
					if(action==1) {
						createAddressDoctor(result,1);
					} else if(action==2) {
						setSpecialtyCategory(result,1);
					} else if(action==3) {
						deleteDoctorAddress(result,1);
					} else if(action==4) {
						getAddressInfo(result,1);
					} else if(action==5) {
						updateAddressInfo(result,1);
					} else if(action==6) {
						setDoctorInUpdateBox(result,1);
					} else if(action==7) {
						setCenterInUpdateBox(result,1);
					} else if(action==8) {
						createAddressCenter(result,1);
					}  
			},
			error: AjaxFailed
		}); 

		return false;
}



// --- delete doctor address
function deleteDoctorAddress(result,fm) {
	
	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];
	
	if (errors == 1) { 
		 if (fm==1) {
			alert('Something went wrong. please try again!');
		 }
		 
		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			//var daid = str.result['da_id'];
			return false;
		}
	}
	
	return false;
	
	
}


function createAddressDoctor(result,fm) {
	//alert(result);
	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];
	
	if (errors == 1) { 
		 if (fm==1) {
			alert('Something went wrong. please try again!');
		 }
		 
		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var ne = str.result['new'];
			var daid = str.result['da_id'];
			var did = str.result['doctor_id'];
			var doctor_name = str.result['doctor_name'];
			var specialty = str.result['specialty'];
			var npi = str.result['npi'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];
			var notes = str.result['notes'];
			var current_status = str.result['current_status'];
			
			alert(message);
			
			document.getElementById("searchdr").value = '';
			document.getElementById("full_name").value = '';
			document.getElementById("first_name").value = '';
			document.getElementById("last_name").value = '';
			document.getElementById("specialty_id_fk").selectedIndex = '';
			document.getElementById("category_id_fk").selectedIndex = '';
			document.getElementById("doctor_status").selectedIndex = 'A';
			document.getElementById("doctor_notes").value = '';
			document.getElementById("npi").value = '';
			document.getElementById("cphone").value = '';
			document.getElementById("cfax").value = '';
			
			if (ne == 1) {
				
				var ol = document.getElementById('loaddr').innerHTML;
				var stradr = '<div class="one_half" class="daddr_'+daid+'"><h3>'+ doctor_name +'</h3><br />';
				var stradr = stradr +'<strong>Specialty # </strong> '+specialty+'<br />'+'<strong>NPI #</strong> '+npi+ '<br />' + '<strong>Phone #</strong> '+phone+ '<br />' + '<strong>Fax #</strong> '+fax+ '<br />' +'<strong>Current Status #</strong> ' + current_status + '<br /><strong>Notes #</strong> ' + notes +  '<br /><br /></div>';
			
				document.getElementById('loaddr').innerHTML = ol+stradr;	
			}
			else if (ne == 0) {
				var stradr = '<h3>'+ doctor_name +'</h3><br />';
				var stradr = stradr +'<strong>Specialty # </strong> '+specialty+'<br />'+'<strong>NPI #</strong> '+npi+ '<br />' + '<strong>Phone #</strong> '+phone+ '<br />' + '<strong>Fax #</strong> '+fax+ '<br />' +'<strong>Current Status #</strong> ' + current_status + '<br /><strong>Notes #</strong> ' + notes + '<br /><br />';
				document.getElementById('doc_'+did).innerHTML = stradr;
			}
		}
	}
	
	$("#btnnewdoc").removeAttr("disabled");
	
	$('html, body #example tr').each(function (i, row) {
		var $row = $(row),
		$text = $row.find("span:eq(1)").html();
		 if (did == $text) {
		//	$row.find('td:eq(4)').html('<span id="aid" style="display:none;">'+aid+'</span>'+last_updated + '<br />' + 'By: <b>'+updated_by+'</b>');
			$row.find('td:eq(5)').html(current_status);
		 }
	});
	
	return false;
	
	
}


function createAddressCenter(result,fm) {
	//alert(result);
	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];
	
	if (errors == 1) { 
		 if (fm==1) {
			alert('Something went wrong. please try again!');
		 }
		 
		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var ne = str.result['new'];
			var daid = str.result['da_id'];
			var did = str.result['center_id'];
			var center_name = str.result['center_name'];
			//var specialty = str.result['specialty'];
			var category = str.result['category'];
			var npi = str.result['npi'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];
			var description = str.result['description'];
			var notes = str.result['notes'];
			var current_status = str.result['current_status'];
			
			alert(message);
			
			document.getElementById("searchcn").value = '';
			document.getElementById("name").value = '';
			document.getElementById("specialty_id_fk").selectedIndex = '';
			document.getElementById("category_id_fk").selectedIndex = '';
			document.getElementById("center_status").selectedIndex = 'A';
			document.getElementById("center_description").value = '';
			document.getElementById("center_notes").value = '';
			document.getElementById("npi").value = '';
			document.getElementById("cphone").value = '';
			document.getElementById("cfax").value = '';

			if (ne == 1) {

				var ol = document.getElementById('loaddr').innerHTML;
				var stradr = '<div class="one_half" class="daddr_'+daid+'"><h3>'+ center_name +'</h3><br />';
				var stradr = stradr +'<strong>Category # </strong> '+category+'<br />'+'<strong>NPI #</strong> '+npi+ '<br />' + '<strong>Phone #</strong> '+phone+ '<br />' + '<strong>Fax #</strong> '+fax+ '<br />' +'<strong>Current Status #</strong> ' + current_status + '<br /><strong>Description #</strong> ' + description + '<br /><strong>Notes #</strong> ' + notes +  '<br /><br /></div>';

				document.getElementById('loaddr').innerHTML = ol+stradr;	
			}
			else if (ne == 0) {
				var stradr = '<h3>'+ center_name +'</h3><br />';
				var stradr = stradr +'<strong>Category # </strong> '+category+'<br />'+'<strong>NPI #</strong> '+npi+ '<br />' + '<strong>Phone #</strong> '+phone+ '<br />' + '<strong>Fax #</strong> '+fax+ '<br />' +'<strong>Current Status #</strong> ' + current_status + '<br /><strong>Description #</strong> ' + description + '<br /><strong>Notes #</strong> ' + notes + '<br /><br />';
				document.getElementById('doc_'+did).innerHTML = stradr;
			}
		}
	}

	$("#btnnewcnr").removeAttr("disabled");

	$('html, body #example tr').each(function (i, row) {
		var $row = $(row),
		$text = $row.find("span:eq(1)").html();
		 if (did == $text) {
		//	$row.find('td:eq(4)').html('<span id="aid" style="display:none;">'+aid+'</span>'+last_updated + '<br />' + 'By: <b>'+updated_by+'</b>');
			$row.find('td:eq(5)').html(current_status);
		 }
	});

	return false;

}


function updateAddressInfo(result,fm) {

	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];

	if (errors == 1) { 
		 if (fm==1) {
		 	if (errors) {
				alert(message);
			}
			else
				alert('Something went wrong. please try again!');
		 }

		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var aid = str.result['address_id'];
			var address_1 = str.result['address_1'];
			var address_2 = str.result['address_2'];
			var city = str.result['city'];
			var state = str.result['state'];
			var zip = str.result['zip'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];
			var status = str.result['status'];
			var notes = str.result['notes'];
			var last_updated = str.result['last_updated'];
			var updated_by = str.result['updated_by'];

			alert('Address updated successfully.');

			document.getElementById("adrinfo1").innerHTML = phone + '<br />' +  fax + '<br />' + status +'<br />' + notes;
			document.getElementById("adrinfo2").innerHTML = address_1 + '<br />' +  address_2 + '<br />' + city + '<br />' + state + '<br />' + zip;

			//$("#btnupdatedoc").removeAttr("disabled");
			toggleadr('0');

			$('html, body #example tr').each(function (i, row) {
				var $row = $(row),
                $text = $row.find("span:first").html();
				 if (aid == $text) {
					$row.find('td:eq(4)').html('<span id="aid" style="display:none;">'+aid+'</span>'+last_updated + '<br />' + 'By: <b>'+updated_by+'</b>');
					$row.find('td:eq(1)').html(status);
				 }
			});

		}
	}

	return false;

}

function getAddressInfo(result,fm) {

	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];

	if (errors == 1) { 
		 if (fm==1) {
			alert('Something went wrong. please try again!');
		 }

		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var aid = str.result['address_id'];
			var address_1 = str.result['address_1'];
			var address_2 = str.result['address_2'];
			var city_id = str.result['city_id'];
			var state_id = str.result['state_id'];
			var zip_id = str.result['zip_id'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];

			document.getElementById("address_1").value = address_1;
			document.getElementById("address_2").value = address_2;
			document.getElementById("city_id").selectedIndex = city_id;
			document.getElementById("state_id").selectedIndex = state_id;
			document.getElementById("zip_id").selectedIndex = zip_id;
			document.getElementById("phone_number").value = phone;
			document.getElementById("fax").value = fax;
		}
	}

}


function setCenterInfo(v) {
	
		var pieces = v.split("[");
		var p = pieces[0];
		var dp = p.split(">>");
		var x = pieces[1];
		var pieces1 = x.split("]");
		var ids = pieces1[0];
		var id = ids.split(",");
		var did = id[0];
		var spid = id[1];
		var ctid = id[2];
		
		document.getElementById("cnid").value = did;
		
		var action = 'getcenterinfo';
		var dataString = 'action=' + action + '&'+ '&center_id=' +  did;
		proceedForm(dataString,7);
		return false;

}

function setCenterInUpdateBox(result,fm) {
	
	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];

	if (errors == 1) { 
		 if (fm==1) {
			alert('Something went wrong. please try again!');
		 }

		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var did = str.result['center_id'];
			var name = str.result['name'];
			var specialty_id_fk = str.result['specialty_id_fk'];
			var category_id_fk = str.result['category_id_fk'];
			var npi = str.result['npi'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];
			var description = str.result['description'];
			var notes = str.result['notes'];
			var status = str.result['status'];
			var advertiser = str.result['advertiser'];
			
			document.getElementById("name").value = name;
			document.getElementById("specialty_id_fk").value = specialty_id_fk;
			document.getElementById("category_id_fk").value = category_id_fk;
			document.getElementById("npi").value = npi;
			document.getElementById("cphone").value = phone;
			document.getElementById("cfax").value = fax;
			document.getElementById("center_description").value = description;
			document.getElementById("center_notes").value = notes;
			document.getElementById("center_status").value = status;
			if (advertiser == 'Y')
				document.getElementById("advertiser").checked = true;
		}
	}

}


function setDoctorInfo(v) {
	
		var pieces = v.split("[");
		var p = pieces[0];
		var dp = p.split(">>");

		var x = pieces[1];
		var pieces1 = x.split("]");
		var ids = pieces1[0];
		var id = ids.split(",");
		var did = id[0];
		var spid = id[1];
		var ctid = id[2];
		
		document.getElementById("docid").value = did;
		var action = 'getdoctorinfo';
		var dataString = 'action=' + action + '&'+ '&doctor_id=' +  did;
		proceedForm(dataString,6);
		return false;

}

function setDoctorInUpdateBox(result,fm) {

	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];
	
	if (errors == 1) { 
		 if (fm==1) {
			alert('Something went wrong. please try again!');
		 }

		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var did = str.result['doctor_id'];
			var first_name = str.result['first_name'];
			var last_name = str.result['last_name'];
			var specialty_id_fk = str.result['specialty_id_fk'];
			var category_id_fk = str.result['category_id_fk'];
			var npi = str.result['npi'];
			var phone = str.result['phone'];
			var fax = str.result['fax'];
			var notes = str.result['notes'];
			var status = str.result['status'];
			var fullname = str.result['fullname'];
			var advertiser = str.result['advertiser'];

			document.getElementById("full_name").value = fullname;
			document.getElementById("first_name").value = first_name;
			document.getElementById("last_name").value = last_name;
			document.getElementById("specialty_id_fk").value = specialty_id_fk;
			document.getElementById("category_id_fk").value = category_id_fk;
			document.getElementById("npi").value = npi;
			document.getElementById("cphone").value = phone;
			document.getElementById("cfax").value = fax;
			document.getElementById("doctor_notes").value = notes;
			document.getElementById("doctor_status").value = status;
			if (advertiser == 'Y')
				document.getElementById("advertiser").checked = true;
		
		}
	}

}

function setSpecialtyCategory(result, fm) { 

	var str = JSON.parse(result);
	var success = str.result['success'];
	var errors = str.result['failure'];
	var message = str.result['message'];

	if (errors == 1) { 
		 if (fm==1) {
			//alert('Something went wrong. please try again!');
		 }

		 return false;
	}
	else if (success == 1) { 
		if (fm==1) {
			var spid = str.result['spid'];
			var ctid = str.result['ctid'];

			document.getElementById("specialty_id_fk").selectedIndex = spid;
			document.getElementById("ctid").selectedIndex = ctid;
		}
	}
}

function AjaxFailed(result){
	alert('Failed ' + result.status + ' ' + result.statusText);
	//var msg = 'Error in processing..';
	//alert(msg);
	return false;
}

$(document).ready(function() {

	// --- delete doctor from address

	$('.deleteadr').click(function(){

			var url = $(this).attr('href');
		    var pieces = url.split("?");
			var pfx = pieces[1];
			var p1 = pfx.split("&");
			var p2 = p1[0].split("=");
			var docid = p2[1];

			if(confirm('Are you sure to remove doctor from location?'))
			{ 
				var action = 'deleteDoctorAddress';
				var dataString = 'action=' + action + '&'+ pfx; // '&address_id=' + addressid + '&doctor_id=' +  docid;
				var tr = $(this).closest('div');
				tr.css("background-color","#FF3700");

				tr.fadeOut(400, function(){
					tr.remove();
				});

				proceedForm(dataString,3);
				$('html, body #example tr').each(function (i, row) {
					var $row = $(row),
					$text = $row.find("span:last").html();
					if (docid == $text) {
						$row.remove();
					 }
				});

			} 			  
			return false;
	});

// --- edit doctor address

	$('.editadr').click(function(){

			var url = $(this).attr('href');
		    var pieces = url.split("?");
			var pfx = pieces[1];
			var pcs = pfx.split('&');
			var did = pcs[0].split('=');
			var docid = did[1];
			var nw = 0;
			document.getElementById("docid").value = docid;
			document.getElementById("new").value = nw;

			var action = 'getdoctorinfo';
			var dataString = 'action=' + action + '&'+ pfx;
		
			var tr = $(this).closest('div');

			var element = document.getElementById("divupd");
			element.scrollIntoView({block: "end", behavior: "smooth"});

			proceedForm(dataString,6);

			return false;
	});

// --- delete center from address

	$('.deletecnadr').click(function(){

			var url = $(this).attr('href');
		    var pieces = url.split("?");
			var pfx = pieces[1];
			var p1 = pfx.split("&");
			var p2 = p1[0].split("=");
			var cnid = p2[1];

			if(confirm('Are you sure to remove center from location?'))
			{ 

				var action = 'deleteCenterAddress';
				var dataString = 'action=' + action + '&'+ pfx; // '&address_id=' + addressid + '&doctor_id=' +  docid;
				var tr = $(this).closest('div');
				tr.css("background-color","#FF3700");

				tr.fadeOut(400, function(){
					tr.remove();
				});

				proceedForm(dataString,3);
				$('html, body #example tr').each(function (i, row) {
					var $row = $(row),
					$text = $row.find("span:last").html();
					if (cnid == $text) {
						$row.remove();
					 }
				});

			} 			  
			return false;
	});

// --- edit center address
	
	$('.editcnadr').click(function(){

			var url = $(this).attr('href');
		    var pieces = url.split("?");
			var pfx = pieces[1];
			var pcs = pfx.split('&');
			var did = pcs[0].split('=');
			var cnid = did[1];
			var nw = 0;
			document.getElementById("cnid").value = cnid;
			document.getElementById("new").value = nw;

			var action = 'getcenterinfo';
			var dataString = 'action=' + action + '&'+ pfx;

			var tr = $(this).closest('div');

			var element = document.getElementById("divupd");
			element.scrollIntoView({block: "end", behavior: "smooth"});

			proceedForm(dataString,7);

			return false;
	});

});


var j = jQuery.noConflict() ;

j(document).ready(function(){

	j("#searchdr").autocomplete("ajaxsearch/ajaxdoctorlist.php", {
		width: 350,
		matchContains: true,
	});

	j("#searchcn").autocomplete("ajaxsearch/ajaxcenterlist.php", {
		width: 350,
		matchContains: true,
	});

});


function toggleadr(flag) {

	if (flag == 1) {
		document.getElementById("showadr").style.display = 'none';
		document.getElementById("updateadr").style.display = 'block';
	} else if (flag == 0) {
		document.getElementById("showadr").style.display = 'block';
		document.getElementById("updateadr").style.display = 'none';
	}
}
