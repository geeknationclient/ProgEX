<?php
/*
 * User class
*/

include "_Global.php";
require_once "Exercise.php";

class User{
	
	public $userCode;
	public $email;
	public $password;
	public $phonenumber;
	public $usertype;
	public $location;
	public $firstName;
	public $secondName;
	
	
	public function createUser(){
		
		include "dbconn.inc.php";
		require_once "_Global.php";
		$query="INSERT INTO users(userCode,firstName,otherNames,email,acss,location,phonenumber,userType,createdStamp) VALUES(?,?,?,?,?,?,?,?,?)";
		//add username password to login
		//upload file type
		$values=array($this->userCode,$this->firstName,$this->secondName,$this->email,$this->password,$this->location,$this->phonenumber,$this->usertype,time());
		$stmt=$conn->prepare($query);
		$stmt->execute($values);
		
		
		if($stmt->rowCount()==1){
			return "details inserted";
		}else{
			return "error inserting details";
		}
	}
	
	
	function login(){
		$query="SELECT username,password FROM login WHERE username=? AND password=?";
		$values=array($this->email,md5($this->password));
		$stmt=$conn->prepare($query);
		$stmt->execute($values);
		$global=new _Global();
		if($stmt->rowCount()==1){
			session_start();
			$_SESSION['user_allow']=$this->email;
			$_SESSION['session_key']=$global->genUniqId();
			echo "login success";
		}else{
			echo "login fail";
		}
	}
	
	function logOut(){
		if(isset($_SESSION['user_allow']) and isset($_SESSION['session_key'])){
			session_destroy($_SESSION['user_allow']);
			session_destroy($_SESSION['session_key']);
			header("location: index.php");
		}
	}
	
	
	function generateUserCode($length = 10){
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function getBillingEmail($userId){
		include "dbconn.inc.php";
		$query="SELECT email from users WHERE userCode=?";
		$stmt=$conn->prepare($query);
		$stmt->execute(array($userId));
		$data=$stmt->fetchAll(PDO::FETCH_ASSOC);
		$email=$data[0]["email"];
		return $email;
		 /*
		  * 
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
					echo $row['email'];
				}*/
		
		
		
	}
	
}

?>
