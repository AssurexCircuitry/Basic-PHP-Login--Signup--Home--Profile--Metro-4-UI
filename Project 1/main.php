<?php

require_once('conn.php');

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }

	    public function passgen() {
	 
	    $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	    $segment_chars = 5;
	    $num_segments = 4;
	    $key_string = '';
	 
	    for ($i = 0; $i < $num_segments; $i++) {
	 
	        $segment = '';
	 
	        for ($j = 0; $j < $segment_chars; $j++) {
	                $segment .= $tokens[rand(0, 45)];
	        }
	 
	        $key_string .= $segment;
	 
	 
	    }
	 
	   return $key_string;
	   }

    public function CreatePass($hashed, $plaintext)
    {
       $stmt = $this->db->prepare("INSERT INTO passwords(Plain,Hashed) 
                                            VALUES(:plain, :hashd)");

           $stmt->bindparam(":plain", $plaintext);
           $stmt->bindparam(":hashd", $hashed);       
           $stmt->execute(); 
   
           return $stmt; 
    }

	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function register($uname,$upass,$unimg)
	{
		try
		{
			$new_password = password_hash($upass, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO users(user_name,user_pass,user_img) VALUES(:user,:pass,:img)");
												  
			$stmt->bindparam(":user", $uname);
			$stmt->bindparam(":img", $unimg);
			$stmt->bindparam(":pass", $new_password);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function updatePass($uid,$upass)
	{
		try
		{
			$newpass = password_hash($upass, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("UPDATE users SET user_pass='$newpass' WHERE user_id=$uid");
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function updateImg($uid,$unimg)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE users SET user_img='$unimg' WHERE user_id=$uid");
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function doLogin($uname,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_pass FROM users WHERE user_name=:uname ");
			$stmt->execute(array(':uname'=>$uname));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($upass, $userRow['user_pass']))
				{
					$_SESSION['user_session'] = $userRow['user_id'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
?>