<?php

$email = $_POST['userEmail'];

class DB
{
	private $db;
	public $email;
	public $id;

	public function __construct()
	{
		$this->db = new mysqli('localhost', 'mysql', '', 'task_tz');
	}

	public function getId($emailUser)
	{
		if ($emailUser == '') {
			$msg = "Enter E-mail";
			echo json_encode($msg);
			$emailUser = ' ';
		} else {
			$result = $this->db->query("SELECT * FROM `user` WHERE (`email`) LIKE '$emailUser%'");
			$rows = $result->num_rows;

			if ($rows == 0) {
				$msg = 'Такого E-mail  не существует';
				echo json_encode($msg);
				exit;
			} else {
				for ($i = 0; $i < $rows; $i++) {
					$row = $result->fetch_assoc();
					$this->email[] = $row['email'];
					$this->id[] = $row['id'];
				}
			}
		}
	}

	public function getInfo()
	{
		$in = join(', ', $this->id);

		$result = $this->db->query("SELECT * FROM `user_info` WHERE `user_id` IN ($in)");

		if (!$result) die($this->db->error);

		$rows = $result->num_rows;

		for ($i = 0; $i < $rows; $i++) {
			$email = $this->email[$i];
			$row = $result->fetch_assoc();
			$row['email'] = $email;
			$arr[] = $row;
		}

		echo json_encode($arr);
	}

	public function closeConnect()
	{
		$this->db->close();
	}
}

$db = new DB;
$db->getId($email);
$db->getInfo();
$db->closeConnect();
