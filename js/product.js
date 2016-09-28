$(document).ready(function(){
	
});


function loadproducts()
{
	
	$.ajax({
		url:"services/product",
		data:{"_": new Date().getHours() ,"cate":"1","type":"list"},
		dataType:'json',
		type:"GET",
		success: function(data){
			
			//console.log(data.result);
			setviewlist(data);
			//console.warn()
		},
		error : function (xhr,status,err)
		{
			console.error(xhr.responseText);
			alert("load product list error : "+ xhr.responseText);
		}
	});
	
}

function setviewlist(data)
{
	var view = $('#viewproduct');
	$.each(data.result,function(i,val){
		var item = "";

		item += "<li class='col-md-3 col-sm-6 col-xs-12' >";
		item += "<div class='port-1 effect-2' >";
		item += "<div class='image-box' >";
		item += "<img src='"+val.thumb+"' class='img-fluid'  alt=''>";
		item += "<div class='thumbnail-desc'><label>"+val.name+"</label><span>"+val.code+"</span></div>";
		item += "</div>";
		item += "</div>";
		item += "<div class='text-desc'>";
		item += "<p><span class='bigger glyphicon glyphicon-zoom-in orange topbar'></span><p/>";
		item += "<a href='productdetail.html?id=1' class='btn'>View</a>";
		item += "</div>";
		item += "</li>";
		console.warn(item);
		view.append(item);

	});
	
}
