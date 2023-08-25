<?php 
	

class Db_Manager{

  private $host   = "localhost";
  private $user   = "root";
  private $pass   = "";
  private $dbname = "module4project";
  private $db;

  	public function __construct(){

		  //try to connect to the database using the provided credentials
		  //if the connection works, it will keep the persistence, else it will throw an error

		  try {
		   $this->db = new PDO( "mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
		   $this->db->exec("set names utf8");
		  }catch (Exception $e){
		    die("Database Connection Error: " . $e->getMessage());
		  }	
	}

	/**
	 * [Add new character in database]
	 * @param [type] $character [description]
	 */
	function Add_Character($character){
		 $query = $this->db->prepare( "INSERT INTO `characters` (`name`, `avatar`) VALUES (:name, :avatar);");
	   $query->execute(array("name"=>$character->getName(),"avatar"=>$character->getAvatar()));	

	   //as u create new user .. should also upload the photo to the file
	   $target_dir = "upload/";
	   $target_file = $target_dir . basename($_FILES["avatar"]["name"]);

	    //move the image file to the upload folder
	    move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);	
	}

	/**
	 * [Update_Character]
	 * @param [type] $character [description]
	 */
	function Update_Character($character){
				$query = $this->db->prepare( "UPDATE `characters` SET `hit_count` = :hit_count,
																															`hp` = :hp
																															WHERE `id` = :id;");
				$query->execute(array("id"=> $character->getId(),
															"hit_count"=> $character->getHitCount(),
															"hp"=> $character->getHp()));
	}

	/**
	 * [Verify_name ]
	 * @param [type] $name [name of charac]
	 */
	function Verify_name($name){
		$query = $this->db->query( "SELECT * FROM characters WHERE name = '$name'");
		$character = $query->fetch( PDO::FETCH_ASSOC );

		if ($character != "") {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * [Get_Character ]
	 * @param [type] $id [id of the charac]
	 */
	function Get_Character($id){
		$query = $this->db->query( "SELECT * FROM characters WHERE id = $id");
		$singleCharacter = $query->fetch( PDO::FETCH_ASSOC );

		if (!empty($singleCharacter)) {
			$character = new character($singleCharacter);
			return $character;
		}
	}

	/**
	 * [Get_All_Characters from database]
	 */
	function Get_All_Characters(){
		$query = $this->db->query( "SELECT * FROM characters");
		$characters = $query->fetchAll( PDO::FETCH_ASSOC);
		$arrCharacters = array();

		// for loop to save the all the characters queried into the array of characters ...
		for ($i=0; $i < count($characters) ; $i++) { 
			$charac = new character($characters[$i]);
			array_push($arrCharacters,$charac);
		}
		return $arrCharacters; //return array of character ...
	}


	function Update_last_combo_time($id,$time){
		$query = $this->db->prepare( "UPDATE `characters` SET `last_combo_hit` = :last_combo_hit WHERE `id` = :id;");
		$query->execute(array("id"=> $id,"last_combo_hit"=> $time));
	}


	function Get_last_combo_time_elapse($id){
			$query = $this->db->query( "SELECT Last_Combo_Hit FROM characters WHERE id = $id");
			$last_combo_time = $query->fetch( PDO::FETCH_ASSOC );
			return $last_combo_time;
	}



}	


?>