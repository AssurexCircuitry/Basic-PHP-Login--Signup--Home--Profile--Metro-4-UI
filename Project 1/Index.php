<?php
session_start();
require_once("main.php");
$login = new USER();



if($login->is_loggedin()!="")
{
	$login->redirect('home.php');
}

if(isset($_POST['login']))
{
	$uname = strip_tags($_POST['user']);
	$upass = strip_tags($_POST['pass']);
		
	if($login->doLogin($uname,$upass))
	{
            $stmt = $login->runQuery("SELECT * FROM users WHERE user_name=:user");
            $stmt->execute(array(":user"=>$uname));
            
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
            $userid = $userRow['user_id'];

        $stmt = $login->runQuery("UPDATE users SET Status='Online' WHERE user_id=:user_id");
        $stmt->execute(array(":user_id"=>$userid));
		$login->redirect('home');
	}
	else
	{
		$error = "Incorrect Username / Password !";
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
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

<div class="formbody">

	<div class="container">
    
        <div class="form-body" style="margin-top:100px; background-image: url(&quot;images/space2.gif&quot;);padding-bottom: 200px;max-width: 50%;max-height:100%;margin-bottom: 0px;border-radius: 0;"></div>     
       <form class="form-body" style="max-width: 50%; border-radius: 0;" method="post">
    
        <div id="error">
        <?php
			if(isset($error))
			{
				?>
                <div class="remark alert">
                   <i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?> 
                </div>
                <?php
			}
		?>
        </div>
        
        <div class="form-group">
        <input type="text" class="form-control" name="user" placeholder="Username" required />
        <span id="check-e"></span>
        </div>
        
        <div class="form-group">
        <input type="password" class="form-control" name="pass" placeholder="Password" />
        </div>
       
     	<hr />
        
        <div class="form-group">
            <button data-role="ripple" type="submit" name="login" class="button dark">
                	<i class="fa fa-sign-in"></i> &nbsp;SIGN IN
            </button>
        </div>  
      	<br />
            <label class="fg-dark">Don't have account yet ? <a href="Register">Sign Up</a></label>
      </form>

    </div>
    
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.metroui.org.ua/v4/js/metro.min.js"></script> 
</body>
</html>