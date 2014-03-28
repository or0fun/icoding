<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>短地址生成</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://baiwanlu.com/phonetrack/js/jquery.min.js"></script>
	<!-- bootstrap js -->
    <script src="http://baiwanlu.com/phonetrack/bootstrap/js/bootstrap.js"></script>
    <!-- Le styles -->
    <link href="http://baiwanlu.com/phonetrack/bootstrap/css/bootstrap.css" rel="stylesheet">
	
  </head>

  <body>

    <div class="container">
		<div class="middle">
			<h1>短地址生成</h1>
		</div>
		<form class="form-signin" method="POST"  data-ajax="false" 
			onsubmit="return check();">
			<input type="text" class="form-control" placeholder="网址" name="address"  id="address" >
			<button class="btn btn-large btn-block btn-primary" type="submit">生成</button>
		</form>
		<div class="middle warning">
			<div class="alert alert-success alert-dismissable fade in out" id="warning-block" style="display:none">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong><span id="warningText"></span></strong> 
			</div>
		</div>

    </div>
	<?php
		require_once('ge.php');
	?>
	<script lang="javascript" >
		function checkeURL(URL){
			var str=URL;
			var Expression=/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
			var objExp=new RegExp(Expression);
			if(objExp.test(str)==true){
				return true;
			}else{
				return false;
			}
		} 
		function check(){
			$(".alert").hide();
			var r = checkeURL($("#address").val());
			if (r == false){
				$("#warningText").text("网址格式不对");
				$(".alert").show();
				return false;
			}
			return true;
		}
	</script>
  </body>
</html>