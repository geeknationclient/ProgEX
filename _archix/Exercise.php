<?php
/*
 * Exercise class to handle excercise uploads
 */



class Exercise {

	public $path;
	public $timeUploaded;
	public $assignee;
	public $timeCompleted;
	public $completed;
	public $assigned;
	public $filename;
	public $filetmp_name;
	public $filetype;
	public $filesize;

	function getName() {
		include "dbconn.inc.php";
		$query = "SELECT name FROM excercise WHERE userCode=?";
		$stmt = $conn -> prepare($query);
		$stmt -> execute(array($this -> userCode));

		$data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

		echo json_encode($data);
	}

	function uploadExercise() {
		include "dbconn.inc.php";
		$path = "exercises/" . $this -> filename;

		if (move_uploaded_file($this -> filetmp_name, $path)) {

			$query = "INSERT INTO exercises(exCode,path,timeUploaded,assignee,timeCompleted,format,size,completed,assigned) VALUES(?,?,?,?,?,?,?,?,?)";

			$stmt = $conn -> prepare($query);
			$data = array($this -> exCode, $path, $this -> timeUploaded, $this -> assignee, $this -> timeCompleted, $this -> filetype,$this-> filesize, $this -> completed, $this -> assigned);
			$stmt -> execute($data);

			if ($stmt -> rowCount() == 1) {

				return "uploaded";

			} else {

				return "error uploading";
			}

		} 
		else {
			return "error uploading";
		}

	}

}
?>