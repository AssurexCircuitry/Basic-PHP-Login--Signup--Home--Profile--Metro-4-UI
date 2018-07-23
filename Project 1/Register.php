<?php
session_start();
require_once('main.php');
$user = new USER();




if($user->is_loggedin()!="")
{
	$user->redirect('home.php');
}

if(isset($_POST['complete']))
{
	$uname = strip_tags($_POST['username']);
	$upass = strip_tags($_POST['pass']);
	$cpass = strip_tags($_POST['cpass']);
	$unimg = strip_tags($_POST['uimg']);	
	
	if($uname=="")	{
		$error[] = "Provide a Username";	
	}
	else if($upass=="")	{
		$error[] = "Provide a Password";
	}
	else if(strlen($upass) < 6){
		$error[] = "Password must be at least 6 characters";	
	}
	else if (filter_var($unimg, FILTER_VALIDATE_URL) === FALSE) {
        $error[] = "Not a Valid Profile Picture URL";
    }
	else if ($upass != $cpass) {
		$error[] = "Passwords do not Match";
	}
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT user_name FROM users WHERE user_name=:uname");
			$stmt->execute(array(':uname'=>$uname));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['user_name']==$uname) {
				$error[] = "Username is Already in Use";
			}
			else
			{
				if($user->register($uname,$upass,$unimg)){	
					$user->redirect('Register?joined');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sign up</title>
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-all.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-colors.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-rtl.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-icons.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="form.css?version=3" type="text/css"  />
    <link rel="stylesheet" type="text/css" href="rainbow.css">
</head>
<body>

<div class="formbody-form">

<div class="container">
    	
<div class="form-body" style="margin-top:100px; background-image: url(&quot;images/space.gif&quot;);padding-bottom: 200px;max-width: 50%;max-height:100%;margin-bottom: 0px;border-radius: 0;"></div>     
       <form class="form-body" style="max-width: 50%; border-radius: 0;" method="post">
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div style="font-size: medium;" class="remark alert">
                      <i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?> !
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['joined']))
			{
				 ?>
                 <div style="font-size: medium;" class="remark success fg-green">
                      <i class="fa fa-check-square"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                 </div>
                 <?php
			}
			?>
            <div class="form-group">
            <input type="text" required="" class="form-control" name="username" placeholder="Username" value="<?php if(isset($error)){echo $uname;}?>" />
            </div>
            <div class="form-group">
            	<input type="password" required="" class="form-control" name="pass" placeholder="Password" />
            </div>
             <div class="form-group">
            	<input type="password" required="" class="form-control" name="cpass" placeholder="Confirm Password" />
            </div>
             <div class="form-group">
             	<label class="fg-dark">Profile Image Url</label>
            	<input type="text" required="" class="form-control" name="uimg" placeholder="http://example.com/profile.png" />
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="button dark" name="complete">
                	<i class="fa fa-sign-in"></i>&nbsp;SIGN UP
                </button>
            </div>
            <br />
            <label class="fg-dark">Already have an account ?<a href="index.php"> Sign In</a></label>
        </form>
       </div>
</div>

</div>

</body>
</html>