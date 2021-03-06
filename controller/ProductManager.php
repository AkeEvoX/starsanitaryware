<?php
require_once($base_dir."/lib/database.php");

class ProductManager{

	protected $mysql;
	function __construct(){

		try{

			$this->mysql = new database();
			$this->mysql->connect();
			//echo "initial database.";
		}
		catch(Exception $e)
		{
			die("initial Product manager error : ". $e->getMessage());
		}
	}

	function __destruct(){ //page end
		$this->mysql->disconnect();
	}

	function getNewId(){
		try{

			$sql = " SELECT `auto_increment` as id FROM INFORMATION_SCHEMA.TABLES ";
			$sql .= " WHERE table_name = 'products' order by auto_increment desc limit 1 ";

			log_warning("product > getNewId > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product new id : ".$e->getMessage();
		}
	}

	function getProduct($lang,$id){

		try{

			$sql = "select p.id ,p.typeid ,p.title_".$lang." as title ,p.detail_".$lang." as detail,p.thumb,p.image,p.plan,d.code,d.name_".$lang." as name,p.pdf_file_".$lang." as pdf_file,p.dwg_file,p.symbol_file ,t.title_".$lang." as catename";
			$sql .= " from products p inner join product_detail d on p.id=d.proid ";
			$sql .= " inner join product_type t on p.typeid=t.id ";
			$sql .= " where p.id='".$id."' ;";
			
			log_warning("product > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Item : ".$e->getMessage();
		}

	}

	function get_product_info($id){
		
		try{

			$sql = "select * ";
			$sql .= " from products  ";
			$sql .= " where id='".$id."' ;";
			
			log_warning("product info > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Info Item : ".$e->getMessage();
		}

		
	}
	
	function getImages($id) {
		try{

			$sql = "select id,proid,thumb,image ";
			$sql .= " from product_images  where active=1 and proid='".$id."' ";
			log_warning("product image > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Images : ".$e->getMessage();
		}
	}
	
	function getProductList($lang,$cate) {
		try{

			$sql = " select p.id,p.title_".$lang." as title ,p.detail_".$lang." as detail,p.thumb,p.image,p.plan,d.code,d.name_".$lang." as name";
			$sql .= " from products p inner join product_detail d on p.id=d.proid ";
			$sql .= " where p.typeid='".$cate."' ";
			$sql .= " order by d.code  ";
			log_debug("product list > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product List : ".$e->getMessage();
		}
	}
	
	function getProductTypeByID($id) {
		try{
			//get type serial condition top 1 asc
			$sql = " select id,parent,title_th,title_en,detail_th,detail_en,link,thumb";
			$sql .= " from product_type ";
			$sql .= " where active=1 and id='".$id."' ;";
			log_warning("getProductTypeByID > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get ProductType By ID : ".$e->getMessage();
		}
	}
	
	function get_product_category($cateid){
		
		try{
			//get type serial condition top 1 asc
			$sql = " select p.*,pd.code,pd.name_th,pd.name_en ";
			$sql .= " from products p ";
			$sql .= " inner join product_detail pd on p.id = pd.proid ";
			$sql .= " where p.typeid=".$cateid." order by pd.code ;";
			log_debug("get_product_category > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product of Type : ".$e->getMessage();
		}
		
	}
	
	function get_product_color($proid){
		try{
			//get type serial condition top 1 asc
			$sql = " select p.*,c.title_th,c.title_en,c.thumb ";
			$sql .= " from product_color p";
			$sql .= " inner join color_master c on p.colorid = c.id";
			$sql .= " where p.proid=".$proid." ;";
			log_debug("get_product_color > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Color : ".$e->getMessage();
		}
	}
	
	function get_product_photo($proid){
		try{
			//get type serial condition top 1 asc
			$sql = " select * ";
			$sql .= " from product_images";
			$sql .= " where proid=".$proid." ;";
			log_debug("get_product_photo > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Photos : ".$e->getMessage();
		}
	}
	
	function get_product_symbol($proid){
		try{
			//get type serial condition top 1 asc
			$sql = " select * ";
			$sql .= " from product_symbol ";
			$sql .= " where proid=".$proid." ;";
			log_debug("get_product_symbol > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Symbol : ".$e->getMessage();
		}
	}

	function getProductType($lang,$id) {
		try{
			//get type serial condition top 1 asc
			$sql = " select id,title_".$lang." as title ,detail_".$lang." as detail,thumb, cover_".$lang." as cover ";
			$sql .= " from product_type ";
			$sql .= " where active=1 and id='".$id."' ;";
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get ProductType : ".$e->getMessage();
		}
	}

	function getSeriesDefault($lang) {
		try{
			//get type serial condition top 1 asc
			$sql = " select id,title_".$lang." as title ,detail_".$lang." as detail,thumb,cover_".$lang." as cover ";
			$sql .= " from product_type ";
			$sql .= " where active=1 order by id asc limit 1;";
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Series List : ".$e->getMessage();
		}
	}
	
	function getSeriestList($lang,$id){
		try{
			//get type serial condition top 1 asc
			$sql = " select s.id ,s.pro_id ,s.title_".$lang." as title ,p.typeid ,p.thumb ,p.plan ,pd.code ,pd.name_".$lang." as name ";
			$sql .= " from series s ";
			$sql .= " inner join products p on s.pro_id=p.id ";
			$sql .= " inner join product_detail pd on p.id = pd.proid ";
			$sql .= " where s.active=1 and s.series_id='".$id."' order by pd.code ; ";
			
			log_debug($sql);
						
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Series List : ".$e->getMessage();
		}
	}

	function getShowRoomList($lang,$id){
		try{
			//get type serial condition top 1 asc
			$sql = " select s.id ,s.pro_id ,s.title_".$lang." as title ,p.typeid ,p.thumb ,p.plan ,pd.code ,pd.name_".$lang." as name";
			$sql .= " from showroom s ";
			$sql .= " inner join products p on s.pro_id=p.id ";
			$sql .= " inner join product_detail pd on p.id = pd.proid ";
			$sql .= " where s.active=1 and s.room_id='".$id."' order by pd.code ; ";
			
			log_debug($sql);
						
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get ShowRoome List : ".$e->getMessage();
		}
	}
	//limit product view to 15 pieces
	function getProductReleated($lang,$cate,$proid) {
		try{


			$sql = "(select p.id,p.title_".$lang." as title ,p.detail_".$lang." as detail,p.thumb,p.image,p.plan,d.code,d.name_".$lang." as name ";
			$sql .= "from products p inner join product_detail d on p.id=d.proid ";
			$sql .= "where  p.id in (select ss.pro_id from series ss where ss.series_id = (select s.series_id from series s where s.pro_id=$proid limit 1)) ) ";
			$sql .= "union ";
			$sql .= "(select p.id,p.title_".$lang." as title ,p.detail_".$lang." as detail,p.thumb,p.image,p.plan,d.code,d.name_".$lang." as name ";
			$sql .= "from products p inner join product_detail d on p.id=d.proid ";
			$sql .= "where p.typeid='".$cate."' ";
			$sql .= "order by p.create_date desc limit 5); ";

/* backup
			$sql = "select p.id,p.title_".$lang." as title ,p.detail_".$lang." as detail,p.thumb,p.image,p.plan,d.code,d.name_".$lang." as name ";
			$sql .= " from products p inner join product_detail d on p.id=d.proid where p.typeid='".$cate."' ";
			$sql .= " or p.id in (select ss.pro_id from series ss where ss.series_id = (select s.series_id from series s where s.pro_id=$proid limit 1)) ";
			$sql .= " order by  p.create_date desc limit 15 ";*/
			$result = $this->mysql->execute($sql);
//
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product List : ".$e->getMessage();
		}
	}
	
	function getAttributes($lang,$id) {
		try{

			$sql = " select p.id,p.".$lang." as title,a.".$lang." as label from product_attribute p ";
			$sql .= " left join attribute_master a on a.name=p.attribute ";
			$sql .= " where p.proid='".$id."' order by a.seq ";
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Attribute : ". $e->getMessage();
		}
	}
	
	function get_attribute_by_product($proid){
		
		try{

			$sql = "select * ";
			$sql .= " from product_attribute  ";
			$sql .= " where proid='".$proid."' ;";
			
			log_debug("product attribute info > " . $sql);
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product attribute info : ".$e->getMessage();
		}

	}
	
	function getColor($id){
		try{

			$sql = " select c.thumb from product_color p ";
			$sql .=  " left join color_master c on p.colorid = c.id ";
			$sql .= " where p.proid='".$id."' and c.active=1 ";
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Product Color : ". $e->getMessage();
		}
	}
	
	function getColorMaster($id){
		try{

			$sql = " select * from color_master  ";
			$sql .= " where id='".$id."' ";
			$result = $this->mysql->execute($sql);

			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Color Master: ". $e->getMessage();
		}
	}

	function getMenu($lang)	{
		try{

			$sql = " select id,parent,title_".$lang." as title,link,seq ";
			$sql .= " from product_type ";
			$sql .= " where active=1 ";
			$sql .= " order by id,seq; ";

			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get  Menu Product : ".$e->getMessage();
		}
		
	}
	
	function insert_product($items){
		try{
			
			$typeid = $items["cate_id"];
			$title_th = $items["name_th"];
			$title_en = $items["name_en"];
			$thumb =  $items["thumb"];
			$plan = $items["plan"];
			$symbol_file =  $items["symbol_file"];
			$dwg_file = $items["dwg_file"];
			$pdf_file_th = $items["pdf_file_th"];
			$pdf_file_en = $items["pdf_file_en"];
			$active = "0";
			if(isset($items["active"])) $active='1';
			$create_by = "0";
			$create_date = "now()";
			
			$sql = "insert into products(typeid,title_th,title_en,thumb,plan,dwg_file,pdf_file_th,pdf_file_en,symbol_file,active,create_by,create_date) ";
			$sql .= "values($typeid,'$title_th','$title_en','$thumb','$plan','$dwg_file','$pdf_file_th','$pdf_file_en','$symbol_file',$active,$create_by,$create_date); ";
			
			log_debug("product manager > inesrt product > ".$sql);
			
			$this->mysql->execute($sql);
			
			
			$proid = $this->mysql->newid();
			
			$attributes[] = array("name"=>"prod.code","th"=>$items["code_th"],"en"=>$items["code_en"]);
			$attributes[] = array("name"=>"prod.name","th"=>$items["name_th"],"en"=>$items["name_en"]);
			$attributes[] = array("name"=>"prod.type","th"=>$items["type_th"],"en"=>$items["type_en"]);
			$attributes[] = array("name"=>"prod.size","th"=>$items["size_th"],"en"=>$items["size_en"]);
			$attributes[] = array("name"=>"prod.shape","th"=>$items["shape_th"],"en"=>$items["shape_en"]);
			$attributes[] = array("name"=>"prod.seat","th"=>$items["seat_th"],"en"=>$items["seat_en"]);
			$attributes[] = array("name"=>"prod.outlet","th"=>$items["outlet_th"],"en"=>$items["outlet_en"]);
			$attributes[] = array("name"=>"prod.rough","th"=>$items["rough_th"],"en"=>$items["rough_en"]);
			$attributes[] = array("name"=>"prod.systems","th"=>$items["systems_th"],"en"=>$items["systems_en"]);
			$attributes[] = array("name"=>"prod.comsumption","th"=>$items["comsumption_th"],"en"=>$items["comsumption_en"]);
			$attributes[] = array("name"=>"prod.faucet","th"=>$items["faucet_th"],"en"=>$items["faucet_en"]);
			$attributes[] = array("name"=>"prod.overflow","th"=>$items["overflow_th"],"en"=>$items["overflow_en"]);
			
			$this->insert_product_attribute($proid,$attributes);
		
			return $proid;
		}
		catch(Exception $e){
			echo "Cannot Insert Product : ".$e->getMessage();
		}
	}
	
	function update_product($items){
		try{
			$proid = $items["id"];
			$typeid = $items["cate_id"];
			$title_th = $items["name_th"];
			$title_en = $items["name_en"];
			$thumb =  $items["thumb"]=="" ? "" : ",thumb='".$items["thumb"]. "' ";
			$plan = $items["plan"] == "" ? "" : ",plan='".$items["plan"]. "' ";
			$symbol_file =  $items["symbol_file"] == "" ? "" : ",symbol_file='".$items["symbol_file"]. "' ";
			$dwg_file = $items["dwg_file"] == "" ? "" : ",dwg_file='".$items["dwg_file"]. "' ";
			$pdf_file_th = $items["pdf_file_th"] == "" ? "" : ",pdf_file_th='".$items["pdf_file_th"]. "' ";
			$pdf_file_en = $items["pdf_file_en"] == "" ? "" : ",pdf_file_en='".$items["pdf_file_en"]. "' ";
			
			$active = "0";
			if(isset($items["active"])) $active='1';
			$update_by = "0";
			$update_date = "now()";
			
			$sql = "update products set ";
			$sql .= " typeid=$typeid , title_th='$title_th' ,title_en='$title_en' ,active=$active ,update_by=$update_by , update_date='$update_date' ";
			$sql .= $thumb . " ".  $plan  . " ".  $symbol_file  . " ".  $dwg_file  . " ".  $pdf_file_th . " ".$pdf_file_en ;
			$sql .= " where id=$proid " ; 
			
			log_debug("product manager > update product > ".$sql);
			
			$this->mysql->execute($sql);
			
			$attributes[] = array("name"=>"prod.code","th"=>$items["code_th"],"en"=>$items["code_en"]);
			$attributes[] = array("name"=>"prod.name","th"=>$items["name_th"],"en"=>$items["name_en"]);
			$attributes[] = array("name"=>"prod.type","th"=>$items["type_th"],"en"=>$items["type_en"]);
			$attributes[] = array("name"=>"prod.size","th"=>$items["size_th"],"en"=>$items["size_en"]);
			$attributes[] = array("name"=>"prod.shape","th"=>$items["shape_th"],"en"=>$items["shape_en"]);
			$attributes[] = array("name"=>"prod.seat","th"=>$items["seat_th"],"en"=>$items["seat_en"]);
			$attributes[] = array("name"=>"prod.outlet","th"=>$items["outlet_th"],"en"=>$items["outlet_en"]);
			$attributes[] = array("name"=>"prod.rough","th"=>$items["rough_th"],"en"=>$items["rough_en"]);
			$attributes[] = array("name"=>"prod.systems","th"=>$items["systems_th"],"en"=>$items["systems_en"]);
			$attributes[] = array("name"=>"prod.comsumption","th"=>$items["comsumption_th"],"en"=>$items["comsumption_en"]);
			$attributes[] = array("name"=>"prod.faucet","th"=>$items["faucet_th"],"en"=>$items["faucet_en"]);
			$attributes[] = array("name"=>"prod.overflow","th"=>$items["overflow_th"],"en"=>$items["overflow_en"]);
			
			$this->update_product_attribute($proid,$attributes);
		
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Update Product : ".$e->getMessage();
		}
	}
	
	function insert_product_attribute($proid,$attrs){
		
			
		try{
			
			foreach($attrs as $item){
				
				$attribute = $item["name"];
				$th = $item["th"];
				$en = $item["en"];
				$sql = "insert into product_attribute(proid,attribute,th,en) ";
				$sql .= "values($proid,'$attribute','$th','$en'); ";
				
				log_debug("product manager > inesrt product attribute > ".$sql);
				$this->mysql->execute($sql);
			}
			
			log_info("product manager > inesrt product attribute [".$proid."] > success ");
			$result = "Insert Attribute Success.";
			
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Insert Product Attribute : ".$e->getMessage();
		}
		
	}
	
	function update_product_attribute($proid,$attrs){
		
			
		try{
			
			foreach($attrs as $item){
				
				$attribute = $item["name"];
				$th = $item["th"];
				$en = $item["en"];
				$sql = "update product_attribute set  ";
				$sql .= " th='$th',en='$en' ";
				$sql .= "where proid=$proid and attribute='$attribute'  ";
				
				log_debug("product manager > update product attribute > ".$sql);
				$this->mysql->execute($sql);
			}
			
			log_info("product manager > update product attribute [".$proid."] > success ");
			$result = "Insert Attribute Success.";
			
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Update Product Attribute : ".$e->getMessage();
		}
		
	}
	
	function insert_product_type($items){
		try{
			$parent = $items["parent"];
			$title_th = $items["title_th"];
			$title_en = $items["title_en"];
			$link = $items["link"];
			$cover_th = $items["cover_th"];
			$cover_en = $items["cover_en"];
			$active='0';
			if(isset($items["active"]))	$active='1';
			$create_by = "0";
			$create_date = "now()";
			
			$sql = "insert into product_type(parent,title_th,title_en,cover_th,cover_en,active,link,create_by,create_date) ";
			$sql .= "values($parent,'$title_th','$title_en','$cover_th','$cover_en',$active,'$link',$create_by,$create_date); ";
			
			log_debug("product manager > inesrt product type > ".$sql);
			
			$this->mysql->execute($sql);
			$result = $this->mysql->newid();
			
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Insert Product Type: ".$e->getMessage();
		}
	}

	function insert_product_color($id,$color_id){
		try{
			$active = "1";
			$create_by = "0";
			$create_date = "now()";
			
			$sql = "insert into product_color(proid,colorid,active,create_by,create_date) ";
			$sql .= "values($id,$color_id,$active,$create_by,$create_date); ";
			
			log_debug("insert product color : " .$sql); 
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Insert Product Color : ".$e->getMessage();
		}

	}
	
	function insert_product_photo($items){
		try{
			$proid = $items["proid"];
			$thumb = $items["thumb"];
			$image = $items["image"];
			$active = "1";
			$update_by = "0";
			$update_date = "now()";
			
			$sql = "insert into product_images(proid,thumb,image,active,update_by,update_date) ";
			$sql .= " values($proid,'$thumb','$image',$active,$update_by,$update_date); ";
			
			log_debug("product manager > inesrt product photo > ".$sql);
			
			$this->mysql->execute($sql);
			$result = $this->mysql->newid();
			
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Insert Product Photo: ".$e->getMessage();
		}
		
	}
	
	function insert_product_symbol($items){
		try{
			$proid = $items["proid"];
			$path = $items["path"];
			
			$create_by = $_SESSION["profile"]->id;
			$create_date = "now()";
			
			$sql = "insert into product_symbol(proid,path,create_by,create_date) ";
			$sql .= "values($proid,'$path',$create_by,$create_date); ";
			
			log_debug("insert_product_symbol > " .$sql); 
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Insert Product Symbol  : ".$e->getMessage();
		}
	}
	
	function insert_color($items){
		try{
			$title_th = $items["title_th"];
			$title_en = $items["title_en"];
			$thumb = $items["thumb"];
			
			$active='0';
			if(isset($items["active"]))	$active='1';
			
			$create_by = "0";
			$create_date = "now()";
			
			$sql = "insert into color_master(title_th,title_en,thumb,active,create_by,create_date) ";
			$sql .= "values('$title_th','$title_en','$thumb',$active,$create_by,$create_date); ";
			
			log_debug("insert_color > ".$sql); 
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Insert Product Color : ".$e->getMessage();
		}
	}
	
	function update_color($items){
		try{
			
			$id = $items["id"];
			$title_th = $items["title_th"];
			$title_en = $items["title_en"];
			$thumb = "";
			
			if($items["thumb"]){
				$thumb = ",thumb='".$items["thumb"]."' ";	
			}
		
			$active='0';
			
			if(isset($items["active"]))	$active='1';
			
			$update_by = "0";
			$update_date = "now()";
			
			$sql = "update color_master set  ";
			$sql .= "title_th='$title_th' ";
			$sql .= ",title_en='$title_en' ";
			$sql .= $thumb;
			$sql .= ",active=$active ";
			$sql .= ",update_by=$update_by ";
			$sql .= ",update_date=$update_date ";
			$sql .= "where id=$id ;";
			
			log_warning("update color master > " . $sql);
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Update Color Master : ".$e->getMessage();
		}
	}
	
	function update_product_type($items){
		try{
			$id = $items["id"];
			$parent = $items["parent"];
			$title_th = $items["title_th"];
			$title_en = $items["title_en"];
			//$link = $items["link"];
			//$cover = $items["cover"];
			//$active = "1";
			$update_by = "0";
			$update_date = "now()";
			
			$sql = "update product_type set  ";
			$sql .= "parent=$parent ";
			$sql .= ",title_th='$title_th' ";
			$sql .= ",title_en='$title_en' ";
			//$sql .= ",cover='$cover' ";
			
			//$sql .= ",link='$link' ";
			//$sql .= ",active=$active ";
			$sql .= ",update_by=$update_by";
			$sql .= ",update_date='$update_date' ";
			$sql .= "where id=$id ;";
			
			//echo $sql."<br/>";
			log_warning("update_product_type > " . $sql);
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Update Product Type: ".$e->getMessage();
		}
	}
	
	function delete_color($id){
		try{

			$sql = "delete from color_master where id=$id ;";
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Delete Color Master : ".$e->getMessage();
		}
	}

	function delete_product_color($id){
		try{

			$sql = "delete from product_color where id=$id ;";

			log_debug("delete product color : " . $sql);
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Delete Product Color : ".$e->getMessage();
		}
	}
	
	function delete_product_photo($id){
		try{

			$sql = "delete from product_images where id=$id ;";

			log_debug("delete product images : " . $sql);
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Delete Product Images : ".$e->getMessage();
		}
	}
	
	function delete_product_symbol($id){
		try{

			$sql = "delete from product_symbol where id=$id ;";

			log_debug("delete product symbol : " . $sql);
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Delete Product Symbol : ".$e->getMessage();
		}
	}
	
	function delete_product($id){
		try{

			$sql = "delete from products where id=$id ;";
			$this->mysql->execute($sql);
			
			$sql = "delete from product_attribute where proid=$id ;";
			$this->mysql->execute($sql);
			
			$sql = "delete from product_images where proid=$id ;";
			$this->mysql->execute($sql);
			
			$sql = "delete from product_color where proid=$id ;";
			$this->mysql->execute($sql);
			
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Delete Product : ".$e->getMessage();
		}
	}
	
	function delete_product_type($id){
		try{
		
			//$sql = "delete from product_type where id=$id ;";
			$sql = "update product_type set active='0' where id=$id ;";
			
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Delete Product Type: ".$e->getMessage();
		}
	}
	
	function get_fetch_category(){//$start_fetch,$max_fetch
		try{
			//$max_fetch = 10;

			$sql = " select * ";
			$sql .= " from product_type ";
			$sql .= " where active=1 ";
			$sql .= " order by id ";
			//$sql .= " LIMIT $start_fetch,$max_fetch ;";
			
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get  Category Product : ".$e->getMessage();
		}
	}

	function get_fetch_product($lang,$search_text,$start_fetch,$max_fetch){
		try{
			
			$sql = " select p.id,p.title_en as title,t.title_en category,p.thumb,p.create_date,p.active ,a.en as code ";
			$sql .= " from products p ";
			$sql .= " inner join product_type t on t.id = p.typeid ";
			$sql .= " inner join product_attribute a on a.proid = p.id ";
			$sql .= " where t.parent not in ('2','3') ";
			$sql .= " and a.attribute='prod.code' ";
			
			$sql .= " and (p.title_en like '%".$search_text."%' "; //product name
			$sql .= " or t.title_en like '%".$search_text."%' "; //category name
			$sql .= " or a.en like '%".$search_text."%' ) "; // product code
			
			$sql .= " order by p.id ";
			$sql .= " LIMIT $start_fetch,$max_fetch ;";
			
			log_debug("get_fetch_product > ".$sql);
			
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Fetch Product : ".$e->getMessage();
		}
	}
	
	function get_fetch_showroom($lang,$start_fetch,$max_fetch){
		try{

			$sql = " select p.id ,p.title_th ,p.title_en ,p.thumb ,p.cover,p.create_date  ";
			$sql .= " from product_type p ";
			$sql .= " where p.parent in ('3') ";
			$sql .= " order by p.id ";
			$sql .= " LIMIT $start_fetch,$max_fetch ;";

			log_debug($sql);
			
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Fetch ShowRoom  : ".$e->getMessage();
		}
	}
	
	function get_fetch_color($lang,$start_fetch,$max_fetch){
		try{

			$sql = " select * from color_master  ";
			$sql .= " order by id ";
			$sql .= " LIMIT $start_fetch,$max_fetch ;";

			log_debug("get fetch color > " + $sql);
			
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get Fetch Color  : ".$e->getMessage();
		}
	}

	function search_fetch_product($lang,$search_text,$search_fillter,$start_fetch,$max_fetch){
		
		try{
			//$max_fetch = 10;

			$sql = " select s.proid,s.code,s.name,s.rough,s.systems,s.seat,s.comsumption,s.overflow,s.size,s.shape,s.faucet,s.type,s.outlet,t.title_".$lang." as category ";
			//$sql .= " from search_product_".$lang." s  ";
			$sql .= "from ( select a.* from search_product_th a ";
			$sql .= " union ALL";
			$sql .= " select b.* from search_product_en b ) s";
			$sql .= " inner join products p on s.proid = p.id  ";
			$sql .= " inner join product_type t on p.typeid = t.id ";
			$sql .= " where 1=1 ";
			$sql .= " and s.name like '%".$search_text."%' ";
			$sql .= " or s.code like '%".$search_text."%' ";
			$sql .= " or s.type like '%".$search_text."%' ";
			$sql .= " or s.systems like '%".$search_text."%' ";
			$sql .= " or s.size like '%".$search_text."%' ";
			//$sql .= " or t.title_".$lang." like '%".$search_text."%' ";
			//$sql .= " or t.title_".$lang." like '%".$search_text."%' ";
			$sql .= " order by s.name ";
			$sql .= " LIMIT $start_fetch,$max_fetch ;";
			
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Search  Product  : ".$e->getMessage();
		}
	}
	
}

?>


