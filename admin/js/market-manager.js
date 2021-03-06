
var market = {};


market.edit = function(args){
	
	var endpoint = "services/market.php";
	var method = "POST";
	utility.data(endpoint,method,args,function(data){
		
		
		var response = JSON.parse(data);
		console.debug(response);
		alert(response.result);
		control.pagetab('market-manager.html');
	});

}


market.reset = function(){
	$('#preview_th').attr('src',"");
	$('#preview_en').attr('src',"");
}

market.edit_page = function(){

	market.data["org.title"].th = $('#title_th').val();
	market.data["org.title"].en = $('#title_en').val();
	market.data["org.header"].th = $('#detail_th').summernote('code')
	market.data["org.header"].en = $('#detail_en').summernote('code')
	console.log(market.data);
	
	var endpoint = "services/market.php";
	var method = "POST";
	
	var args =  {'_':new Date().getMilliseconds()
	,'type':'edit_page'
	,'item': market.data};
	
	utility.service(endpoint,method,args,function(data){
		console.debug(data);
		alert(data.result);
	});
	
}

market.loaditem = function(id){
	
	$('#id').val(id);
	var endpoint = "services/market.php";
	var method = "GET";
	var args = {'_':new Date().getMilliseconds(),'type':'item','id':id};
	utility.service(endpoint,method,args,set_view_item);
	
}

market.load = function(){
	
	var endpoint = "services/market.php";
	var method = "GET";
	var args = {'_':new Date().getMilliseconds(),'type':'page'};
	utility.service(endpoint,method,args,set_view);
	
}

market.list = function(){
	var endpoint = "services/market.php";
	var method = "GET";
	var args = {'_':new Date().getMilliseconds(),'type':'list'};
	utility.service(endpoint,method,args,set_view_list);
}

function set_view(data){
	
	console.log(data);
	
	if(data.result==undefined) return;
	
	//var item = data.result;
	
	market.data = data.result;
	
	$('#preview_th').attr('src',data.result.chart_th);
	$('#preview_en').attr('src',data.result.chart_en);
	
}

function set_view_item(data){
	
	console.log(data);
	//if(data.result==undefined) return;
	
	
	$('#name_th').val(data.result["name_th"]);
	$('#position_th').val(data.result["position_th"]);
	$('#education_th').val(data.result["education_th"]);
	$('#work_th').val(data.result["work_th"]);
	$('#name_en').val(data.result["name_en"]);
	$('#position_en').val(data.result["position_en"]);
	$('#education_en').val(data.result["education_en"]);
	$('#work_en').val(data.result["work_en"]);
	
	$('#image').attr('src',data.result["image"]);
	
	if(data.result["active"]=="1")
		$('#active').prop('checked',true);
	
	
}

function set_view_list(data){
	//console.debug(data);
	var view = $('#data_list');
	var item = "";
	if(data.result==undefined || data.result=="") {
		console.log("market-manager > list :: data not found.")
		return;
	}
	
	$.each(data.result,function(i,val){
		var param = '?id='+val.id;
		var active = val.active == "1" ? "<span class='btn btn-success btn-sm'>Enable</span> ": "<span class='btn btn-danger btn-sm'>Disable</span>";
		
		item+="<tr id='row"+val.id+"'>";
		item+="<td><input type='checkbox' name='mark[]' data-id='"+val.id+"' /></td>";
		item+="<td>"+val.id+"</td>";
		item+="<td>"+val.name_th+"</td>";
		item+="<td>"+val.name_en+"</td>";
		item+="<td>"+ active +"</td>";
		item+="<td><span class='btn btn-warning btn-sm' onclick=control.pagetab('personal-edit.html','"+param+"') >แก้ไข</span></td>";
		item+="</tr>";
	});
	//console.debug(item);
	view.append(item);
}
