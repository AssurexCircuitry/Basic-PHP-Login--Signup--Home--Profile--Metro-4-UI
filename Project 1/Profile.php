<?php

  require_once("session.php");
  
  require_once("main.php");
  $user = new USER();

  $userid = $_SESSION['user_session'];
  
  $stmt = $user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
  $stmt->execute(array(":user_id"=>$userid));
  
  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

  if(isset($_GET['logout']))
  {
    $stmt = $user->runQuery("UPDATE users SET Status='Offline' WHERE user_id=:user_id");
    $stmt->execute(array(":user_id"=>$userid));
    session_destroy();
    $user->redirect('index.php');
  }

  if(isset($_POST['changeimgurl']))
  {
     $unimg = strip_tags($_POST['uimg']);  

     if (filter_var($unimg, FILTER_VALIDATE_URL) === FALSE) {
      $error[] = "Not a Valid Profile Picture URL";
     }
     else
     {
        try
        {
          $stmt = $user->runQuery("SELECT user_name FROM users WHERE user_name=:id");
          $stmt->execute(array(':id'=>$userid));
          $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
          $uid =  $userid;
            
          if($row['user_pass'] == password_hash($upass, PASSWORD_DEFAULT)) {
            $error[] = "Use a Different Password";
          }
          else
          {
            if($user->updateImg($uid, $unimg)){  
              $user->redirect('Profile?Success');
            }
          }
        }
        catch(PDOException $e)
        {
          echo $e->getMessage();
        }
     }

  }

      if(isset($_POST['changepass']))
      {
          $upass = strip_tags($_POST['pass']);
          $cpass = strip_tags($_POST['cpass']);
          
          if($upass=="") {
            $error[] = "Provide a Password";
          }
          else if(strlen($upass) < 6){
            $error[] = "Password must be at least 6 characters";  
          }
          else if ($upass != $cpass) {
            $error[] = "Passwords do not Match";
          }
          else
          {
            try
            {
              $stmt = $user->runQuery("SELECT user_name FROM users WHERE user_name=:id");
              $stmt->execute(array(':id'=>$userid));
              $row=$stmt->fetch(PDO::FETCH_ASSOC);

              $uid =  $userid;
                
              if($row['user_pass']==password_hash($upass, PASSWORD_DEFAULT)) {
                $error[] = "Use a Different Password";
              }
              else
              {
                if($user->updatePass($uid, $upass)){  
                  $user->redirect('Profile?Success');
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
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-all.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-colors.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-rtl.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-icons.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="form.css?version=3" type="text/css"  />
    <link rel="stylesheet" type="text/css" href="rainbow.css">
<title><?php print($userRow['user_name']); ?>'s Profile</title>
</head>

<body>

<aside class=" sidebar pos-absolute z-2" data-role="sidebar" data-toggle="#sidebar-toggle-3" id="sb3" data-shift=".shifted-content">
<div class="fg-white sidebar-header" data-image="images/space.gif?version=2" style="background-image: url(&quot;images/space.gif&quot;);">
    <a href="/" class="fg-white sub-action"
       onclick="Metro.sidebar.close('#sb1'); return false;">
    </a>
    <div style="border-color: #8e8d8d; background: #373737; border-radius: 0%; " class="avatar">
        <img src="<?php print($userRow['user_img']); ?>">
    </div>
  <h1 class="title"><strong><?php
if ($userRow['Status'] = 'Online') {
  echo '<i class="fa fa-circle fg-green"></i> ';
}
else if ($userRow['Status'] = 'Offline') {
 echo '<i class="fa fa-circle fg-red"></i> ';
} 
?><?php print($userRow['user_name']); ?></i></strong></h1>
  </div>
</div>
    <ul class="sidebar-menu">
        <li><a href="home.php"><span class="mif-home icon"></span>Home</a></li>
        <li class="divider"></li>
        <li><form method="post"><a href="?logout"><span class="fa fa-sign-out icon"></span>Logout</a></form></li>
    </ul>
</aside>
<div class="shifted-content h-100 p-ab">
    <div class="app-bar pos-absolute bg-dark z-1" data-role="appbar">
        <button class="app-bar-item c-pointer" id="sidebar-toggle-3">
            <span class="mif-menu fg-white"></span>
        </button>
    </div>

    <div class="h-100 p-4">
            <?php
      if(isset($error))
      {
        foreach($error as $error)
        {
           ?>
                     <div style="font-size: medium;" class="remark alert">
                      <i class="fa fa-exclamation-triangle fg-red"></i> &nbsp; <?php echo $error; ?> !
                     </div>
                     <?php
        }
      }
      else if(isset($_GET['Success']))
      {
         ?>
                 <div style="font-size: medium;" class="remark success">
                      <i class="fa fa-check-square fg-green"></i> &nbsp; Successfully Updated Details</div>
                 <?php
      }
      ?>
<form method="post" class="cell offset-2 form-body">

    <div class="form-group">
        <label class="fg-dark">Update Password</label>
        <input type="password" required="" name="pass" placeholder="Password"/>
        <small class="text-muted">Update your Current Password</small>
    </div>
    <div class="form-group">
        <label class="fg-dark">Confirm Password</label>
        <input type="password" required="" name="cpass" placeholder="Confirm Password"/>
    </div>
    <div class="form-group">
        <button type="submit"  class="button bg-green fg-white " name="changepass"class="button success">
          <i class="fa fa-key"></i> Change Password</button>
    </div>
  </form>
<form method="post" class="form-body">
<div class="form-group">
        <label class="fg-dark">Profile Image Url</label>
        <input type="text" required="" name="uimg" value="<?php print($userRow['user_img']); ?>"/>
    </div>
    <div class="form-group">
        <button type="submit"  class="button bg-green fg-white " name="changeimgurl"class="button success"><i class="fa fa-cloud-upload"></i> Update Profile Image</button>
    </div>
    </form>


              </div>
            </div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.metroui.org.ua/v4/js/metro.min.js"></script>
</body>
</html>