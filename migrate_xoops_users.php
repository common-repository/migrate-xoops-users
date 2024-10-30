<br /><br />
<div>
	<p>
		<strong>instructions:</strong> 
		<ul>
			<li>Set Host, Username and Password of xoops database same as wordpress database. </li>
			<li>Delete users of wordPress site excluding admin user.</li>
			<li>To remove duplication of users ID in users table, please make unique ID of admin user ID in user table and user meta table which are not exists in xoops users IDs.</li>
		</ul>
	</p>
</div>
<?php 
//Check form submition
if(isset($_POST['submit'])){
	
	$xoopsDbName 	= sanitize_text_field($_POST['xoops_xoopsDbName']);
	$DbNameexpld 	= explode(".",$xoopsDbName);
	$DbName			= $DbNameexpld[0];
	$xoops_host 	= sanitize_text_field($_POST['xoops_host']);
	$xoops_port 	= sanitize_text_field($_POST['xoops_port']);
	$xoops_username = sanitize_text_field($_POST['xoops_username']);
	$xoops_password = sanitize_text_field($_POST['xoops_password']);
	
	if(($xoopsDbName != '' && $xoopsDbName != 'xoopsDBname.prefix') || $xoops_host != '' || $xoops_port != '' || $xoops_username != '' || $xoops_password != ''){
			// Check db
			// 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object			
			//WP DB Prefix
			$lwpdb 		= new wpdb( $xoops_username, $xoops_password, $DbName, $xoops_host );
			//$lwpdb->show_errors();
	      	//-----------------------------------------------------------XXXXXX---------------------------------------------------------     
	      	//Insert records into users meta       
	      	$jUser = $lwpdb->get_results( $lwpdb->prepare( "SELECT 
					      u.uid user_id, 
	      				  u.uname user_login,
	      				  u.pass password
					 FROM 
					        ".$xoopsDbName."_users u 
					 ORDER BY 
					      u.uid LIMIT 0 , 100","",""));
	      	global $wpdb;
	      	$wpdb->show_errors();
	      	$wpPrefix	=	$wpdb->prefix;
			if($jUser){
				$i=1;
		   		foreach($jUser as $jUserVal){ 
		           //$this_id 		= 	$jUserVal->user_id."<br />"; 
		           $user_login 		= 	$jUserVal->user_login;
		           $password 		= 	$jUserVal->password;
				   
				   //Insert into users
		           if($user_login){
		           	$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."users (  user_login, user_pass, user_nicename, user_email,  user_registered, user_status, display_name ) 
		           								VALUES (  '$user_login', '$password', '', '', '', '', '' )","",""));
		           	$this_id=$wpdb->insert_id;
		           }
		           
			       if($this_id){
			           //Insert into users meta			
						//Insert rich editing
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'rich_editing', 'true' )","",""));
						//Insert comment shortcuts status
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'comment_shortcuts', 'false' )","",""));
						//Insert admin color
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'admin_color', 'fresh' )","",""));
						//Insert Nickname
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'use_ssl', 0 )","",""));
						//Insert show admin bar front status
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'show_admin_bar_front', 'true' )","",""));
			       }	
				    $i++;
		     	}   
		     	echo '<span style="color:green;">Users insert has been inserted successfully. !!! ENJOY !!!</span>';
			 }  
		}else{ 
			echo '<span style="color:red;">Error establishing a database connection. </span>';
		}
}else{
		$xoopsDbName='xoopsDBname.prefix';
}
?>
<form method="post">
<table>
<tr><th>Insert xoops database name with prefix<span style="color:red;"> (ex - xoopsDBname.prefix) *</span></th><td><input type="text" name="xoops_xoopsDbName" id="xoops_xoopsDbName" onfocus="this.value=='xoopsDBname.prefix'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='xoopsDBname.prefix':this.value=this.value;" value="<?php if(isset($xoopsDbName)) { echo $xoopsDbName; } ?>" maxlength="50"></td></tr>
<tr><th>Hostname <span style="color:red;">*</span></th><td><input type="text" name="xoops_host" id="xoops_host" onfocus="this.value=='Hostname'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Hostname':this.value=this.value;" value="<?php if(isset($xoops_host)) { echo $xoops_host; } ?>" maxlength="100"></td></tr>
<tr><th>Port <span style="color:red;">*</span></th><td><input type="text" name="xoops_port" id="xoops_port" onfocus="this.value=='Port'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Port':this.value=this.value;" value="<?php if(isset($xoops_port)) { echo $xoops_port; } ?>" maxlength="100"></td></tr>
<tr><th>Username <span style="color:red;">*</span></th><td><input type="text" name="xoops_username" id="xoops_username" onfocus="this.value=='Username'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Username':this.value=this.value;" value="<?php if(isset($xoops_username)) { echo $xoops_username; } ?>" maxlength="100"></td></tr>
<tr><th>Password <span style="color:red;">*</span></th><td><input type="password" name="xoops_password" id="xoops_password" onfocus="this.value=='Password'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Password':this.value=this.value;" value="<?php if(isset($xoops_password)) { echo $xoops_password; } ?>" maxlength="100"></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="submit"></td></tr>
</tr>
</table>
</form>