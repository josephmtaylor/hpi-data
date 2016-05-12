
var ajaxhttp		= new getHttpObject();

function getHttpObject() {
	var xmlhttp; try { xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");  } catch (e) { try { xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) { xmlhttp=false; } } 
	if(!xmlhttp && typeof XMLHttpRequest !=undefined) { try { xmlhttp=new XMLHttpRequest(); } catch (e) { xmlhttp=false; } }
	if(!xmlhttp) {  alert("Your browser doesn't have ajax Support!"); } else { return xmlhttp; }
}

function uncache(url) { var d=new Date(); return url+"&time="+d.getTime(); }




function displayCarModel(obj) {
	var requrl			= '';
	var manufacturer_id	= obj.value;

	var requrl='ajax/ajaxresponse.php?type=viewmodel&manufacturer_id='+manufacturer_id;
	
	ajaxhttp.open('GET',uncache(requrl),true);
	ajaxhttp.send(null);
	ajaxhttp.onreadystatechange=displayCarModelDiv;
	
}

function displayCarModelDiv(){
		//	alert(ajaxhttp.responseText);
		if (ajaxhttp.readyState == 4) {
			if(ajaxhttp.status==200) {
				if(ajaxhttp.responseText!='' && ajaxhttp.responseText!=0){
					document.getElementById("modelinfo").style.display="block";
					document.getElementById("modelinfo").innerHTML='';
					document.getElementById("modelinfo").innerHTML=ajaxhttp.responseText;
				}
				else{
					document.getElementById("modelinfo").style.display="none";
				}
			}
		}
}


