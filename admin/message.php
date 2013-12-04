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
				  <li><a href="http://icymint.me/icoding/admin/user.php">Users</a></li>
				  <li class="active"><a href="http://icymint.me/icoding/admin/message.php">Message</a></li>
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
            <form name="myForm">
                <table  border="1" cellspacing="0" cellpadding="0"> 
                        <th>Name</th> 
                        <th>Request</th> 
                        <th>Reply</th>   
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
								$sql = "select UNIX_TIMESTAMP(ptime) as ptime, name, reply, msg from users, wxmsg where users.user=wxmsg.fromuser order by wxmsg.id desc limit 100"; 
								if($result = mysql_query($sql,$conn)){  
									while($rs=mysql_fetch_object($result)){ 
										$name=$rs->name;
										$reply=$rs->reply;
										$request=$rs->msg; 
										$ptime = $rs->ptime;
										$ptime=date('Y-m-d H:i', $ptime);   
										$postObj = simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA);
										if($postObj->MsgType == 'text')
											$request = trim($postObj->Content);
										else
											$request = $postObj->MsgType;
										$reply = str_replace("\n", "<br/>", $reply);
								?>
						<tr align="center"> 
							<td><?php echo $name ?></td>
							<td><?php echo $request ?></td> 
							<td><?php echo $reply ?></td> 
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
