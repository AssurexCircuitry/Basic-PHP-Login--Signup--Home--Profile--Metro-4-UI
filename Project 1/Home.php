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

                $stmt = $user->runQuery("SELECT * FROM users WHERE Status='Online'");
                $stmt->execute();

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
    <link rel="stylesheet" href="form.css?version=2" type="text/css"  />
    <link rel="stylesheet" type="text/css" href="rainbow.css">
<title>Welcome - <?php print($userRow['user_name']); ?></title>
</head>

<body>

<aside class=" sidebar pos-absolute z-2" data-role="sidebar" data-toggle="#sidebar-toggle-3" id="sb3" data-shift=".shifted-content">
<div class="fg-white sidebar-header" data-image="images/bg.gif?version=2" style="background-image: url(&quot;images/bg.gif&quot;);">
    <a href="/" class="fg-white sub-action"
       onclick="Metro.sidebar.close('#sb1'); return false;">
    </a>
    <div style="border-color: #8e8d8d; background: #373737; border-radius: 0%; " class="avatar">
        <img src="<?php print($userRow['user_img']); ?>">
    </div>
  <h1 class="title"><strong>
    <?php
if ($userRow['Status'] = 'Online') {
  echo ' <i class="fa fa-circle fg-green"></i> ';
}
else if ($userRow['Status'] = 'Offline') {
 echo ' <i class="fa fa-circle fg-red"></i> ';
}
 print($userRow['user_name']); ?></strong> | <a class="fg-white" href="profile.php">
   Profile</a></h1>
  </div>
</div>
    <ul class="sidebar-menu">
        <li><a href="home"><span class="mif-home icon"></span>Home</a></li>
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

<div class="cell-3 place-left" data-role="panel" data-title-caption="Online Users" data-title-icon="<span class='mif-users mif-lg'></span>">
 <?php
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
                {
               echo '<i class="fa fa-circle fg-green "></i><span style="text-transform: capitalize;font-size: medium; "> ', $row['user_name'] , '<br>';
                }
        ?>
</div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.metroui.org.ua/v4/js/metro.min.js"></script>
</body>
</html>