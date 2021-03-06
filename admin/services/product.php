<?php
session_start();
include("../../lib/common.php");
include("../../lib/logger.php");
$base_dir = "../../";
include("../../controller/ProductManager.php");


$type="";
$id="";

$id = GetParameter("id");
$type = GetParameter("type");
$result = "";

switch($type){
	case "list":
		$counter = GetParameter("couter");// count last fetch data
		$max_fetch = GetParameter("fetch");
		$search_text = GetParameter("search_text");
		$result = get_list_fetch($lang,$search_text,$counter,$max_fetch);
	break;
	case "option":
		$CATEGORIES = 1;
		$result = getOptions($lang);
		
	break;
	case "list_products":
		$result = get_list_product($id);
		
	break;
	case "add":
		$result = Insert($_POST);
		log_debug("Product  > Insert " . print_r($result,true));
	break;
	case "add_color":
		$color_id = GetParameter("color_id");
		$result = Insert_color($id,$color_id);
		log_debug("Product Color  > Insert " . print_r($result,true));
	break;
	case "add_photo":
		//$photo_id = GetParameter("photo_id");
		$result = Insert_photo($_POST);
		log_debug("Product Photo  > Insert " . print_r($result,true));
	break;
	case "add_symbol":
		//$photo_id = GetParameter("photo_id");
		$result = Insert_symbol($_POST);
		log_debug("Product Symbol  > Insert " . print_r($result,true));
	break;
	case "edit":
		$result = Update($_POST);
		log_debug("Product  > Update " . print_r($result,true));
	break;
	case "del":
		$result = Delete($id);
	break;
	case "del_color":
		$color_id = GetParameter("color_id");
		$result = Delete_color($color_id);
	break;
	case "del_photo":
		$photo_id = GetParameter("photo_id");
		$result = Delete_photo($photo_id);
	break;
	case "del_symbol":
		$symbol_id = GetParameter("symbol_id");
		$result = Delete_symbol($symbol_id);
	break;
	case "product_color":
		$result = call_product_color($id);//pending code get product color;
	break;
	case "product_photo":
		$result = call_product_photo($id);//pending code get product color;
	break;
	case "product_symbol":
		$result = call_product_symbol($id);//pending code get product color;
	break;
	case "item":
		$result = call_product($id);
	break;
	case "newid":
		$result = getNewID();
	break;
}


echo json_encode(array("result"=> $result ,"code"=>"0"));

/************* function list **************/
function get_list_fetch($lang,$search_text,$start_fetch,$max_fetch){
	$product = new ProductManager();
	$data = $product->get_fetch_product($lang,$search_text,$start_fetch,$max_fetch);
	$result = "";
	
	if($data==null) return $result;
	
	
	while($row = $data->fetch_object()){

			// $item =  array("id"=>$row->id
			// ,"category"=>$row->category
			// ,"title"=>$row->title
			// ,"thumb"=>$row->thumb
			// ,"active"=>$row->active);
			
		$result[] = $row;
	}
	return $result;
}

function getNewID(){
	
	$product = new ProductManager();
	$data = $product->getNewId($lang,$id);
	if($data){
		$row = $data->fetch_object();
		$newid = $row->id;
	}

	return array("id"=>$newid);
}

function getOptions($lang){
	
	$product = new ProductManager();
	$data = $product->getMenu($lang);

	if($data){

		while($row = $data->fetch_object()){

			$menu =  array("id"=>$row->id
						,"parent"=>$row->parent
						,"title"=>$row->title
		  				,"link"=>$row->link);

			$result[] = $menu;
		}

	}
	return $result;
}

function get_list_product($cate_id){
	
	$product = new ProductManager();
	$data = $product->get_product_category($cate_id);

	if($data){

		while($row = $data->fetch_object()){

			$result[] = $row;
			
		}

	}
	return $result;
}

function call_product($id){
	
	$product = new ProductManager();
	$data = $product->get_product_info($id);
	
	if($data){
		
			$item = $data->fetch_array();
			$data_attribute = $product->get_attribute_by_product($id);
			
			while($row = $data_attribute->fetch_object()){
				$attr_name = $row->attribute;
				$item[$attr_name] = array("th"=>$row->th,"en"=>$row->en);
			}
			
	}
	
	$result = $item;
	return $result;
}

function call_product_color($proid){
	
	$product = new ProductManager();
	$data = $product->get_product_color($proid);
	if($data){
		
			while($row = $data->fetch_object()){
				$result[] = $row;
			}
	}
	
	return $result;
}

function call_product_photo($proid){
	$product = new ProductManager();
	$data = $product->get_product_photo($proid);
	if($data){
			while($row = $data->fetch_object()){
				$result[] = $row;
			}
	}
	
	return $result;
}

function call_product_symbol($proid){
	
	$product = new ProductManager();
	$data = $product->get_product_symbol($proid);
	if($data){
		
			while($row = $data->fetch_object()){
				$result[] = $row;
			}
	}
	
	return $result;
}

function Insert_color($id,$color_id){
	$product = new ProductManager();
	$product->insert_product_color($id,$color_id);
	return "INSERT SUCCESS.";
}

function Insert_photo($items){
	
	$dir = "../../images/products/".$items["proid"];
	if (!file_exists($dir)){
		$oldmask = umask(0); //# set your umask temporarily to zero so it has no effect. 
		mkdir($dir, 0777); //0777,true
		umask($oldmask);
	}
	
	if($_FILES["file_photo"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_photo']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$items["proid"]."/thumb_". date('Ymd_His').$ext;//20010310_224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_photo']['tmp_name'];
		$items["thumb"] = $filename;
		$items["image"] = $filename;
		upload_image($source,$distination);
		
	}
	$items["active"] = "1";
	
	$product = new ProductManager();
	$product->insert_product_photo($items);
	
	return "INSERT SUCCESS.";
}

function Insert_symbol($items){
	$items["proid"] = $items["proid_symbol"];
	
	$dir = "../../images/products/".$items["proid"];
	if (!file_exists($dir)){
		$oldmask = umask(0); //# set your umask temporarily to zero so it has no effect. 
		mkdir($dir, 0777); //0777,true
		umask($oldmask);
	}
	
	if($_FILES["file_symbol"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_symbol']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$items["proid"]."/symbol_". date('Ymd_His').$ext ;
		$distination =  "../../".$filename;
		$source = $_FILES['file_symbol']['tmp_name'];
		$items["path"] = $filename;
		upload_image($source,$distination);
		
	}
	$items["active"] = "1";
	
	$product = new ProductManager();
	$product->insert_product_symbol($items);
	
	return "INSERT SUCCESS.";
}

function Insert($items){
	
	
	
	$product = new ProductManager();
	$proid = $product->insert_product($items);
	
	//#create folder by id 
	$dir = "../../images/products/".$proid;
	if (!file_exists($dir) && $newid!="0"){
		$oldmask = umask(0); //# set your umask temporarily to zero so it has no effect. 
		mkdir($dir, 0777); //0777,true
		umask($oldmask);
	}
	
	//#import image after create 
	$items["id"] = $proid;
	
	//#upload content file
	if($_FILES["file_thumbnail"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_thumbnail']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/thumb_". date('Ymd_His') ."_".$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_thumbnail']['tmp_name'];
		$items["thumb"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["file_symbol"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_symbol']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/symbol_". date('Ymd_His') . $ext ;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_symbol']['tmp_name'];
		$items["symbol_file"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["file_plan"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_plan']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/plan_". date('Ymd_His') .$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_plan']['tmp_name'];
		$items["plan"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["file_dwg"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_dwg']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/dwf_". date('Ymd_His') .$ext ;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_dwg']['tmp_name'];
		$items["dwg_file"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["pdf_file_th"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['pdf_file_th']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/pdf_th_". date('Ymd_His') .$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['pdf_file_th']['tmp_name'];
		$items["pdf_file_th"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["pdf_file_en"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['pdf_file_en']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/pdf_en_". date('Ymd_His').$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['pdf_file_en']['tmp_name'];
		$items["pdf_file_en"] = $filename;
		upload_image($source,$distination);
	}
	
	//#update after upload image
	$product->update_product($items);
	
	
	return "INSERT SUCCESS.";
}

function Update($items){
	
	$proid = $items["id"];
	$items["thumb"] = "";
	$items["symbol_file"]="";
	$items["plan"] = "";
	$items["dwg_file"]="";
	//$items["pdf_file"]="";
	
	$dir = "../../images/products/".$proid;
	if (!file_exists($dir)){
		$oldmask = umask(0); //# set your umask temporarily to zero so it has no effect. 
		mkdir($dir, 0777); //0777,true
		umask($oldmask);
	}
	
	if($_FILES["file_thumbnail"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_thumbnail']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/thumb_". date('Ymd_His') .$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_thumbnail']['tmp_name'];
		$items["thumb"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["file_symbol"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_symbol']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/symbol_". date('Ymd_His') .$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_symbol']['tmp_name'];
		$items["symbol_file"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["file_plan"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_plan']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/plan_". date('Ymd_His').$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_plan']['tmp_name'];
		$items["plan"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["file_dwg"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['file_dwg']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/dwf_". date('Ymd_His') .$ext;//20010310224010
		$distination =  "../../".$filename;
		$source = $_FILES['file_dwg']['tmp_name'];
		$items["dwg_file"] = $filename;
		upload_image($source,$distination);
	}
	if($_FILES["pdf_file_th"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['pdf_file_th']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/pdf_th_". date('Ymd_His') .$ext;
		$distination =  "../../".$filename;
		$source = $_FILES['pdf_file_th']['tmp_name'];
		$items["pdf_file_th"] = $filename;
		upload_image($source,$distination);
	}
	
	if($_FILES["pdf_file_en"]["name"]!="")
	{
		$ext = ".". pathinfo($_FILES['pdf_file_en']['name'], PATHINFO_EXTENSION);
		$filename = "images/products/".$proid."/pdf_en_". date('Ymd_His') .$ext;
		$distination =  "../../".$filename;
		$source = $_FILES['pdf_file_en']['tmp_name'];
		$items["pdf_file_en"] = $filename;
		upload_image($source,$distination);
	}
	
	
	$product = new ProductManager();
	$result = $product->update_product($items);
	
	return "UPDATE SUCCESS.";
	
}

function Delete_color($id){
	$product = new ProductManager();
	$product->delete_product_color($id);
	return "DELETE SUCCESS.";
}

function Delete_photo($id){
	
	$product = new ProductManager();
	$product->delete_product_photo($id);
	return "DELETE SUCCESS.";
}

function Delete_symbol($id){
	$product = new ProductManager();
	$product->delete_product_symbol($id);
	return "DELETE SUCCESS.";
}

function Delete($id){
	
	$product = new ProductManager();
	$product->delete_product($id);
	return "DELETE SUCCESS.";
}

?>