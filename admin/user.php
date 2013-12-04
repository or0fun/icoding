<?php
	if(empty($_COOKIE['user_id']) || $_COOKIE['user_id'] != "fang"){   
		$url = "http://icymint.me/icoding/admin/";
		header("location: " .$url);
	}
?>
<html>
    <head>
        <title>iCoding</title>
		<meta charset="utf-8" /> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
				  <li><a href="#">Home</a></li>
				  <li class="active"><a href="http://icymint.me/icoding/admin/user.php">Users</a></li>
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
        <center>
			<a href="http://icymint.me/icoding/admin/user.php?s=1">Subscribe</a>
			<a href="http://icymint.me/icoding/admin/user.php?s=0">Unsubscribe</a>
            <form name="myForm">
                <table  border="1" cellspacing="0" cellpadding="0"> 
                        <th>Name</th> 
                        <th>Sex</th>
                        <th>City</th>
                        <th>Subscribe</th>
                        <th>Time</th> 
                        <?php
							$hostname_conn = "mysql1403.ixwebhosting.com:3306"; 
							$database_conn = "C360953_fangjun";   
							$username_conn = "C360953_fangjun";
							$password_conn = "Fangjun65320"; 
							date_default_timezone_set('PRC');
							$conn = @mysql_connect($hostname_conn,$username_conn,$password_conn);
							if ($conn){ 
								mysql_select_db($database_conn, $conn);
								mysql_query("set names 'utf8'");
								//database operation 
								$subscribe = $_GET['s'];
								$sql = "select UNIX_TIMESTAMP(ptime) as ptime, name, sex, city, subscribe from users order by id desc"; 
								if(isset($subscribe)){
									$sql = "select UNIX_TIMESTAMP(ptime) as ptime, name, sex, city, subscribe from users where subscribe='$subscribe' order by id desc"; 
								}
								if($result = mysql_query($sql,$conn)){  
									while($rs=mysql_fetch_object($result)){ 
										$name=$rs->name;
										$sex=$rs->sex;
										$city=$rs->city;
										$subscribe=$rs->subscribe;  
										$ptime = $rs->ptime;
										$ptime=date('Y-m-d H:i', $ptime);                     
								?>
						<tr align="center"> 
							<td><?php echo $name ?></td>
							<td><?php echo $sex ?></td>
							<td><?php echo $city ?></td>
							<td><?php echo $subscribe ?></td>
							<td><?php echo $ptime ?></td>
						</tr>
								<?php
									}
								}
								mysql_close();
							}
							?>
                </table>
            </form>
        </center>
    </body>
</html>
