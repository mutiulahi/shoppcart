<?php 
function recover($mode,$email){
	$mode = sanitize($mode);
	$email = sanitize($email);
	$user_data = user_data(user_id_from_email($email),'first_name','username');
	if ($mode == 'username') {
		email($email,'Your username',"Hello ".$user_data['first_name'].",\n\nYour username is : ".$user_data['username']."\n\n-Shoppcart");
	}elseif ($mode == 'password') {
		
	}
}



/*********************************************product Functions******************/
function product_id_from_productname($productname){
	$productname = sanitize($productname);
	return mysql_result(mysql_query("SELECT `p_id` FROM `cat` WHERE `pname`= '$productname' "), 0 , 'p_id');

}
function product_data($p_id){
	$data = array();
	$p_id = (int)$p_id;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if ($func_get_args > 1) {
		unset($func_get_args[0]);

		$fields = '`'. implode('`, `', $func_get_args). '`';

		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `cat` WHERE `p_id` = $p_id" ));
		return $data;

	}



}
function product_exits($productname){
	$productname = sanitize($productname);
	return (mysql_result(mysql_query("SELECT COUNT(`p_id`) FROM `cat` WHERE `pname` = '$productname'"), 0) == 1 ) ? true : false;
}
/*********************************************user data update Functions******************/
function update_user($update_data){
	$update =array();
	array_walk($update_data, 'array_sanitize');
	foreach ($update_data as $field => $data) {
		$update[] = '`'.$field . '`=\''.$data.'\'';
	}
	mysql_query("UPDATE `users` SET " . implode(', ', $update) . " WHERE `user_id` =" . $_SESSION['user_id']);

}


/*********************************************Email Activation Functions******************/
function activate($email,$email_code){
	$email = mysql_real_escape_string($email);
	$email_code = mysql_real_escape_string($email_code);

	if (mysql_result( mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email' AND `email_code`= '$email_code' AND `active`= 0"),0)==1) {
		mysql_query("UPDATE `users` SET `active`=1 WHERE `email` = '$email'");
		return true;
	}else  {
		return false;
	}



}
/*********************************************Change Password******************/
function change_password($user_id,$password){
	$user_id = (int)$user_id;
	$password = md5($password);
	mysql_query("UPDATE `users` SET `password` = '$password' WHERE `user_id` = $user_id");
	
}



/*********************************************register Functions******************/
function register_user($register_data){
	include 'msg.php';
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = md5($register_data['password']); 
	$fields = '`'.implode('`, `',array_keys($register_data)).'`';
	$data = '\''.implode('\', \'',$register_data).'\'';
	mysql_query("INSERT INTO `users` ($fields) VALUES ($data) ");
	email($register_data['email'],'Activate Account...!!!',$message );
}


function user_count(){
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1 "), 0);
}


function user_data($user_id){
	$data = array();
	$user_id = (int)$user_id;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if ($func_get_args > 1) {
		unset($func_get_args[0]);

		$fields = '`'. implode('`, `', $func_get_args). '`';

		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `user_id` = $user_id" ));
		return $data;

	}



}

/*********************************************Log in Functions******************/
 
function logged_in(){
	return (isset($_SESSION['user_id']))? true : false;
}



function user_exits($username){
	$username = sanitize($username);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'"), 0) == 1 ) ? true : false;
}
function email_exits($email){
	$email = sanitize($email);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'"), 0) == 1 ) ? true : false;
}

function user_active($username){
	$username = sanitize($username);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active` = 1 "), 0) == 1 ) ? true : false;
}


function user_id_from_username($username){
	$username = sanitize($username);
	return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `username`= '$username' "), 0 , 'user_id');

}
function user_id_from_email($email){
	$email = sanitize($email);
	return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `email`= '$email' "), 0 , 'user_id');

} 
function login($username, $password) {
	$user_id = user_id_from_username($username);

	$username = sanitize($username);  
	$password = md5($password);

	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username`= '$username' AND `password` = '$password' "), 0) == 1) ? $user_id : false;

}





?>