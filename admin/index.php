<?php
 
$username = trim((isset($_REQUEST["username"]) ? $_REQUEST["username"] : "")); 
$pass = (isset($_REQUEST["pass"]) ? $_REQUEST["pass"] : "");   
if( $username=="fang" && $pass=="65320"){
	setcookie('user_id',$username);
	$url = "http://icymint.me/icoding/admin/user.php"; 
	header("location: " .$url);
}else{
}
?>
<html>
  <head> 
    <meta charset="utf-8" />
    <title>iCoding Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Le styles -->
    <link href='css/bootstrap.min.css' rel='stylesheet' />
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="css/icoding.css" rel="stylesheet" />
  </head>
  <body>  
	<!-- header -->
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container"> 
          <a class="brand" href="#">iCoding</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
		      <li><a href="http://icymint.me/icoding/admin/user.php">Users</a></li>
			  <li><a href="http://icymint.me/icoding/admin/message.php">Message</a></li>
              <li><a href="http://www.baiwanlu.com">酷酷学校</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <br />
    <br />
    <br />  
	<div class="container"> 
      <form class="form-signin" method="post" >
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="input-block-level" placeholder="Email address" name="username">
        <input type="password" class="input-block-level" placeholder="Password" name="pass">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
      </form> 
    </div> <!-- /container -->
	
    <br />
    <br />
    <br />  
    <div id="footer">
      <div class="container">
        <p class="muted credit"><a href="http://martinbean.co.uk">酷酷学校</a>2012-2013</p>
      </div>
    </div>

	<!-- Le javascript
	================================================= -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="http://code.jquery.com/jquery.js"></script> 
	<script src="js/bootstrap.min.js"></script>
  </body>
</html>
