<?php 
	
// 打印变量  调试
function D() {echo '<pre>'; print_r( func_get_args() ); echo '</pre>'; echo "<hr />"; }
session_start();
if(empty($_SESSION['userInfo']))
{
	echo "<script type='text/javascript'> alert('请先去登录哦');  window.location.href='login.php' </script>";
}
else
{
	$userInfo = $_SESSION['userInfo'];
}

if(!empty($_POST))
{
	$connect  = mysqli_connect( 'localhost', 'root', '', 'demo', '3306' );
	$avatar = '';
	$name = trim($_POST['name']);
	if(!empty($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 )
	{
		$avatar = $_FILES['avatar'];
		$imagePath = './images/' .$avatar['name'];

		$move = move_uploaded_file( $avatar['tmp_name'], $imagePath );
		if($move === true)
		{
			$avatar = "http://localhost/demo/web/images/{$avatar['name']}";
		}
	}
	$sqlupdate ="  UPDATE `demo`.`user` SET `name`='{$name}', `avatar`='{$avatar}' WHERE `id`='{$userInfo['id']}'; ";

	// 执行修改或者添加
	$result = mysqli_query($connect, $sqlupdate);
	// 返回受影响的函数
	$rows   = mysqli_affected_rows($connect);

	$info = "受影响行数{$rows}";
	// D($sqlupdate);
	// D($sqlupdate, $_POST, $rows, $_FILES);
	// exit;

	// 保证操作成功了，就跳转
	if($rows > 0 )
	{

		$query = "SELECT * FROM demo.user where id = {$userInfo['id']} ";
		$result   = mysqli_query($connect, $query);
		$userInfo     = mysqli_fetch_assoc($result);
		$_SESSION['userInfo'] = $userInfo;

		$info .= '修改成功';
		echo "<script type='text/javascript'> alert('{$info}');  window.location.href='userInfo.php' </script>";
	}
	else
	{
		$info .= ' 修改失败';
		echo "<script type='text/javascript'> alert('{$info}'); </script>";
	}
}	



 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<style>
	table
	{
		    text-align: center;
    width: 980px;
    margin: 0 auto;
	}
	tr,td
	{
		line-height: 40px;
		border: 1px solid #dddddd;
	}

</style>
<form action="" method="post" enctype="multipart/form-data" >

	<table>
		<tr>
			<td>账户</td>
			<td>
				<?php echo $userInfo['username'] ?>
			</td>
		</tr>
		<tr>
			<td>姓名</td>
			<td>
				<input type="text" name="name" value="<?php echo $userInfo['name']; ?>">
			</td>
		</tr>
		<tr>
			<td>头像</td>
			<td>
				<div>
					<img src="<?php echo $userInfo['avatar']; ?>" alt="" width="120px">
				</div>
				<input type="file" name="avatar" >
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<button>提交修改</button>
			</td>
		</tr>
	</table>


	</form>
</body>
</html>