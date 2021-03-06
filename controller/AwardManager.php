<?php
require_once($base_dir."/lib/database.php");

class AwardManager{
	
	protected $mysql;
	function __construct(){

		try{

			$this->mysql = new database();
			$this->mysql->connect();
			//echo "initial database.";
		}
		catch(Exception $e)
		{
			die("initial award manager error : ". $e->getMessage());
		}
	}

	function __destruct(){ //page end
		$this->mysql->disconnect();
	}

	function getItem($id,$lang){
		
		try{

			$sql = " select id,title_".$lang." as title,detail_".$lang." as detail ,thumbnail ,update_date ";
			$sql .= "from award where active=1 and id='".$id."' order by update_date desc ";
			$result = $this->mysql->execute($sql);
			
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get  Award : ".$e->getMessage();
		}
		
	}
	
	function get_award_info($id){
		
		try{

			$sql = " select * ";
			$sql .= "from award where id='".$id."' ";
			$result = $this->mysql->execute($sql);
			
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get  Award Info : ".$e->getMessage();
		}
		
	}
	
	function get_sequence($typeid){
		
		try{

			$sql = " select count(0) max_seq ";
			$sql .= "from award where type='".$typeid."' ";
			log_warning("AwardManager > get sequence  > " .$sql);
			$result = $this->mysql->execute($sql);
			
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get  Sequence Award & Standard : ".$e->getMessage();
		}
		
	}

	function getListItem($lang,$type_reward){
			try{
			
			$sql = "select id,thumbnail,update_date,type,seq ";
			$sql .= "from award where active=1 and type=".$type_reward." order by seq ";
			
			log_warning("AwardManager > getListItem  > " .$sql);
			
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get List Award & Standard : ".$e->getMessage();
		}
	}
	
	function get_fetch_list($start_fetch,$max_fetch){
			try{

			$sql = " select * ";
			$sql .= " from award a ";
			$sql .= " order by id desc " ;
			$sql .= " LIMIT $start_fetch,$max_fetch ;";
			log_debug("Award > get_fetch_list > ".$sql);
			$result = $this->mysql->execute($sql);
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Get  List Award  : ".$e->getMessage();
		}
	}
	
	function insert_item($items){
		
		try{
			
			$title_th  =$items["title_th"];
			$title_en  =$items["title_en"];
			$detail_th =$items["detail_th"];
			$detail_en =$items["detail_en"];
			$thumbnail  =$items["thumbnail"];
			$category  =$items["category"];
			$seq = $items["seq"];
			$active='0';
			
			if(isset($items["active"]))	$active='1';
			
			$create_by=$_SESSION["profile"]->id;
			$create_date='now()';
			
			$sql = "insert into award (title_th ,title_en  ,detail_th ,detail_en ,thumbnail ,type ,active ,seq,create_by ,create_date ) ";
			$sql .= "values('$title_th'  ,'$title_en'  ,'$detail_th' ,'$detail_en','$thumbnail' ,$category  ,$active,$seq ,$create_by  ,$create_date ); ";
			$this->mysql->execute($sql);
			
			log_debug("AwardManager > insert  > " .$sql);
			
			$result = $this->mysql->newid();
			
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Insert Award Project : ".$e->getMessage();
		}
		
	}
	
	function update_item($items){
		try{
			$id = $items["id"];
			$title_th  =$items["title_th"];
			$title_en  =$items["title_en"];
			$detail_th =$items["detail_th"];
			$detail_en =$items["detail_en"];
			$category  =$items["category"];
			$seq = $items["seq"];
			$thumbnail  = ($items["thumbnail"] !="" ? ",thumbnail='".$items["thumbnail"]. "' " :  "" );
		
			$active='0';
			
			if(isset($items["active"]))	$active='1';
			
			$update_by=$_SESSION["profile"]->id;
			$update_date='now()';
			
			$sql = "update award set  ";
			$sql .= "title_th='$title_th' ,title_en='$title_en' ,detail_th='$detail_th' ,detail_en='$detail_en' ";
			$sql .=  $thumbnail ;
			$sql .= ",type=$category ,active=$active , seq=$seq ,update_by=$update_by ,update_date=$update_date  ";
			$sql .= "where id=$id ;";
			$this->mysql->execute($sql);
			
			log_debug("Award > update > " .$sql);
			$result = $this->mysql->newid();
			
			return  $result;
		}
		catch(Exception $e){
			echo "Cannot Update Award Organization : ".$e->getMessage();
		}
	}
	
	function delete_item($id){
		
		try{
			$sql = "delete from award where id=$id ; ";
			log_debug("Award > delete_project > " .$sql);
			$result = $this->mysql->execute($sql);
			return $result;
		}
		catch(Exception $e){
			echo "Cannot Delete Award Organization : ".$e->getMessage();
		}
	}
	
}

?>
