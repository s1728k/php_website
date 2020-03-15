<?php

/**
 * 
 */
class Model 
{
	public static $servername;
	public static $dbname;
	public static $username;
	public static $password;
	public static $table = 'contacts';
	public static $fields = [];
	public static $visibles = [];

	function __construct()
	{
	}

	public static function roleArray($role = null){
		return static::$fields;
	}

	public static function create($role = null, $arr = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$arr = $arr??$_POST;
		foreach ($arr as $field => $value) {
		  if(in_array($field, static::roleArray($role)) && $field!='id'){
		    if (empty($arr[$field]) && !($arr[$field]===0)){
		    	$old[$field] = null;
		    }else{
		      $old[$field] = self::test_input($arr[$field]);
		    }
		  }
		}

		try {
	        $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
	        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	        $sql = "INSERT INTO ".static::$table . " (" . implode(', ', array_keys($old)) . ") VALUES (:" . implode(",:", array_keys($old)) . ")";
	        
	        $stmt = $conn->prepare($sql);
	        $stmt->execute($old);
	        $last_id = $conn->lastInsertId();
	    }catch(PDOException $e){
	        echo $sql . "<br>" . $e->getMessage();
	    }
	    $conn = null;
		return $last_id;
	}

	public static function update($id = null, $role = null, $arr = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$id = $id??$_POST["id"];
		$arr = $arr??$_POST;
		try {
	        $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
	        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        
	        $sql = "UPDATE ".static::$table." SET ";
					foreach ($arr as $field => $value) {
					  if(in_array($field, static::roleArray($role)) && $field!='id'){
					    if(empty($arr[$field]) && !($arr[$field]===0)){
					      $old[$field] = null;
					    }else{
					      $old[$field] = self::test_input($arr[$field]);
					    }
					    $sql = $sql."$field=:$field, ";
					  }
					}
					$sql = rtrim($sql, ", ");
					$sql = $sql." WHERE id=".$id;

	        $stmt = $conn->prepare($sql);
	        $stmt->execute($old);
	    }catch(PDOException $e){
	        echo $sql . "<br>" . $e->getMessage();
	    }

	    $conn = null;
	}

	public static function all($role = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$arr = static::roleArray($role);
		try {
		    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    
			$stmt = $conn->prepare("SELECT ".implode(', ',$arr)." FROM ".static::$table);
		    $stmt->execute();
		    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		}catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;
		return $rows;
	}

	public static function find($id = null, $role = null, $sarr = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$id = $id??$_POST["id"];
		$arr = static::roleArray($role);
		if(is_array($sarr)){
			$arr = array_intersect($arr,$sarr);
		}elseif($sarr){
			$arr = array_intersect($arr,[$sarr]);
		}

		try {
			$conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
	    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    
			$stmt = $conn->prepare("SELECT ".implode(', ', $arr)." FROM ".static::$table." where id=$id  LIMIT 1");
		    $stmt->execute();
		    $row = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;
		
		if($sarr && !is_array($sarr)){
			return $row[$sarr];
		}
		return $row;
	}

	public static function destroy($id = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$id = $id??$_POST["id"];
		try {
			$conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
	    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    
			$stmt = $conn->prepare("DELETE FROM ".static::$table." WHERE id=$id");
		    $stmt->execute();
		}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}

	public static function count(){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		try {
		    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM ".static::$table);
		    $stmt->execute();
		    $result = $stmt->fetch();
		    return $result['count'];
		}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}

	public static function sum($field){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		try {
		    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    $stmt = $conn->prepare("SELECT SUM(".$field.") as sum FROM ".static::$table);
		    $stmt->execute();
		    $result = $stmt->fetch();
		    return $result['sum'];
		}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}

	public static $i = 0;
	public static $exec = [];
	public static function form_sql($arr = null, $visibles = ['*']){
		$w_sql = ''; 
		foreach ($arr as $key => $value) {
			if(is_array($arr['_UNION_'])){
				foreach ($arr['_UNION_'] as $table) {
					$w_sql = $w_sql . 'SELECT '.implode(', ', $visibles).' FROM '.$table.' WHERE '.self::form_sql($arr[$table]).' UNION ALL ';
				}
				$w_sql = rtrim($w_sql, " UNION ALL ");
				return $w_sql;
			}
			if(in_array($key,['_OR_','_AND_','_NOT_','_UNION_','_BRACKET_'], empty($key) && $key !== '0')){
				continue;
			}
			$ao = $not = $bracket = '';
			if(in_array($key,$arr['_OR_'],true)){
				$ao = ' OR ';
			}elseif(in_array($key,$arr['_AND_'],true)){
				$ao = ' AND ';
			}else{
				$ao = ' AND ';
			}
			if(in_array($key,$arr['_NOT_'],true)){
				$not = ' NOT ';
			}
			if(in_array($key,$arr['_BRACKET_'],true)){
				$bracket = self::form_sql($arr[$key]);
				$w_sql = $w_sql . '(' . $bracket . ')'.$ao;
			}else{
				if(is_numeric($key)){
					if (empty($value[1]) && !($value[1]===0) || empty($value[2]) && !($value[2]===0)){
				    }else{
				    	if($value[1]=='between'){
				    		$w_sql = $w_sql .$not.'('. $value[0].' '.$value[1].' :val'.self::$i .' AND :val'.(self::$i+1).')'.$ao;
					    	self::$exec['val'.self::$i++] = $value[2][0];
					    	self::$exec['val'.self::$i++] = $value[2][1];
				    	}elseif($value[1]=='IS'){
				    		$w_sql = $w_sql . $value[0].' '.$value[1].' '.$value[2].$ao;
				    	}elseif($value[1]=='in'){
				    		$w_sql = $w_sql . $not .'('.$value[0].' '.$value[1].' (';
				    		foreach ($value[2] as $v) {
				    			$w_sql = $w_sql . ':val'.self::$i.',';
				    			self::$exec['val'.self::$i++] = $v;
				    		}
				    		$w_sql = rtrim($w_sql,',') . '))'. $ao;
				    	}else{
				    		$w_sql = $w_sql . $not . $value[0].' '.$value[1].' :val'.self::$i .$ao;
					    	self::$exec['val'.self::$i++] = $value[2];
				    	}
				    }
				}else{
					// echo $key.'|';
					if (empty($value) && !($value===0)){
				    }else{
				      $w_sql = $w_sql . $not . $key.'=:val'.self::$i .$ao;
				      self::$exec['val'.self::$i++] = $value;
				    }
				}
			}
		}
		$w_sql = rtrim($w_sql, $ao);
		return $w_sql;
	}

	public static function where($offset=null,$no_of_records_per_page=null,$role=null,$cmd=null,$arr=null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$arr = $arr??[];
		$limit_sql = ($offset===null||$no_of_records_per_page===null)?'':" LIMIT ".$offset.",".$no_of_records_per_page;
		
		$cmds = [];$sterm = true;
	    foreach (explode('|',$cmd) as $c) {
	    	$cc = explode(':',$c);
	    	if(count($cc)==2){
	    		$cmds[$cc[0]]=$cc[1];
	    	}else{
	    		$cmds[$c]=1;
	    	}
	    }
	    if($cmds['first']){
	    	$sterm = false;
	    }

		$visibles = static::roleArray($role);
		$searchables = $visibles;

		$w_sql = self::form_sql($arr,$visibles); 
		$exec = self::$exec;
		self::$i = 0;
		self::$exec = [];

		// print_r($exec);exit;

		$r = implode(', ', $visibles);
	    if($cmds['_UNION_']){
	    	$m_sql = '('.$w_sql.') AS T';
	    	$w_sql = null;
	    }elseif($cmds['union']){
	    	$tables = explode(',',$cmds['union']);
	    	$m_sql = "SELECT $r FROM ".static::$table;
	    	foreach ($tables as $table) {
	    		$m_sql = $m_sql . " UNION ALL " . "SELECT $r FROM ".$table;
	    	}
	    	$m_sql = '('.$m_sql.') AS T';
	    }else{
	    	$m_sql = '('.static::$table.')';
	    }

		if (isset($_GET['sterm'])) {
	        $s = $_GET['sterm'];
	        $s_sql = implode(" LIKE '%$s%' or ", $searchables)." LIKE '%$s%'";
	    }

	    if($s_sql && $w_sql && $sterm){
	    	$sql = ' WHERE ('.$s_sql . ') AND ('. $w_sql.')';
	    }elseif($s_sql && $sterm){
	    	$sql = ' WHERE '.$s_sql;
	    }elseif($w_sql){
	    	$sql = ' WHERE '.$w_sql;
	    }

	    if($cmds['count']){
	    	$r = 'count(*) as count';
	    }elseif($cmds['sum']){
	    	$r = 'SUM('.$cmds['sum'].') as sum';
	    }

    	$sql = "SELECT $r FROM ".$m_sql.$sql." ".(empty($cmds['sort'])?"ORDER BY updated_at DESC":$cmds['sort']).$limit_sql;
	    // echo $sql;exit;

		try {
		    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    $stmt = $conn->prepare($sql);
		    $stmt->execute($exec);
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;
		if($cmds['first']){
			return $result[0];
		}elseif($cmds['count']){
			return $result[0]['count'];
		}elseif($cmds['sum']){
			return $result[0]['sum'];
		}else{
			// print_r($result); exit;
			return $result;
		}
	}

	public static function upload_file($target_dir = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$target_dir = $target_dir??$files_folder;

	    $file_name = basename($_FILES[$_POST['file_name']]["name"]);
		$imageFileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
		$target_file = $target_dir . bin2hex(openssl_random_pseudo_bytes(32)) . '.' . $imageFileType;
		$uploadOk = 1;
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES[$_POST['file_name']]["tmp_name"]);
		    if($check !== false) {
		        echo "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;
		    } else {
		        echo "File is not an image.";
		        $uploadOk = 0;
		    }
		}
		// Check if file already exists
		if (file_exists($target_file)) {
		    echo "Sorry, file already exists.";
		    $uploadOk = 0;
		}
		// Check file size
		if ($_FILES[$_POST['file_name']]["size"] > 50000000) {
		    echo "Sorry, your file is too large.";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		if(!in_array(strtolower($imageFileType),["jpg","png","jpeg","gif","pdf"]) ) {
		    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES[$_POST['file_name']]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$target_file)) {
		        // echo "The file ". basename( $_FILES[$_POST['file_name']]["name"]). " has been uploaded.";
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
		return $target_file;
	}

	public static function upload($id = null, $target_dir = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$id = $id??$_POST['id'];
		$target_dir = $target_dir??$files_folder;
		try {
		    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    $target_file = self::upload_file($target_dir);

			if($id){
				$stmt = $conn->prepare("SELECT ".$_POST['file_name']." FROM ".static::$table." where id=$id  LIMIT 1");
			    $stmt->execute();
			    $avatar = $stmt->fetch();

			    if (!unlink($_SERVER['DOCUMENT_ROOT'].$avatar[$_POST['file_name']])) {
			      // echo ("Error deleting $file");
			    } else {
			      // echo ("Deleted $file");
			    }

			    $sql = "UPDATE ".static::$table." SET ".$_POST['file_name']."=:".$_POST['file_name']." WHERE id=$id";
			    $stmt = $conn->prepare($sql);
			    $stmt->execute([$_POST['file_name'] => $target_file]);
			    return 'success';
			}else{
				$sql = "INSERT INTO ".static::$table . " (" . $_POST['file_name'] . ") VALUES (:" . $_POST['file_name'] . ")";
			    $stmt = $conn->prepare($sql);
			    $stmt->execute([$_POST['file_name'] => $target_file]);
			    return $conn->lastInsertId();
			}

		}catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;

	}

	public static function upload_files($id = null, $target_dir = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$target_dir = $target_dir??$files_folder;

	    $file_name =[];
		$imageFileType =[];
		$target_file =[];
		for ($i=0; $i < $_POST['no_of_files']; $i++) { 
		    $file = $_FILES[$_POST['field_name'].'_'.$i];
		    $file_name[]=basename($file["name"]);
		    $imageFileType[]=strtolower(pathinfo($file_name[$i],PATHINFO_EXTENSION));
		    $target_file[]=$target_dir . bin2hex(openssl_random_pseudo_bytes(32)) . '.' . $imageFileType[$i];
		    $uploadOk = 1;
		    // Check if image file is a actual image or fake image
		    if(isset($_POST["submit"])) {
		        $check = getimagesize($file["tmp_name"]);
		        if($check !== false) {
		            $uploadErr =  "File is an image - " . $check["mime"] . ".";
		            $uploadOk = 1;
		        } else {
		            $uploadErr =  "File is not an image.";
		            $uploadOk = 0;
		        }
		    }
		    // Check if file already exists
		    if (file_exists($target_file[$i])) {
		        $uploadErr =  "Sorry, file already exists.";
		        $uploadOk = 0;
		    }
		    // Check file size
		    if ($file["size"] > 50000000) {
		        $uploadErr =  "Sorry, your file is too large.";
		        $uploadOk = 0;
		    }
		    // Allow certain file formats
		    if(!in_array(strtolower($imageFileType[$i]),["jpg","png","jpeg","gif","pdf"]) ) {
		        $uploadErr =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		        $uploadOk = 0;
		    }
		    // Check if $uploadOk is set to 0 by an error
		    if ($uploadOk == 0) {
		        // echo $uploadErr;exit;
		        $uploadErr =  "Sorry, your file was not uploaded.";
		    // if everything is ok, try to upload file
		    } else {
		        if (move_uploaded_file($file["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$target_file[$i])) {
		            // $uploadErr =  "The file ". basename( $file["name"]). " has been uploaded.";
		        } else {
		            $uploadErr =  "Sorry, there was an error uploading your file.";
		        }
		    }
		}
		return $target_file;
	}

	public static function uploads($id = null, $target_dir = null){
		include('env.php');
		self::$servername=$servername;self::$dbname=$dbname;self::$username=$username;self::$password=$password;
		$id = $id??$_POST['id'];
		$target_dir = $target_dir??$files_folder;
		try {
		    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    $target_file = self::upload_files($target_dir);

		    $stmt = $conn->prepare("SELECT ".$_POST['field_name']." FROM ".static::$table." where id=$id  LIMIT 1");
		    $stmt->execute();
		    $avatar = $stmt->fetch();

		    $file_arr=json_decode($avatar[$_POST['field_name']],true);
		    foreach ($file_arr as $link) {
		        if (!unlink($_SERVER['DOCUMENT_ROOT'].$link)) {
		          // echo ("Error deleting file");
		        } else {
		          // echo ("Deleted file");
		        }
		    }

		    $sql = "UPDATE ".static::$table." SET ".$_POST['field_name']."=:".$_POST['field_name']." WHERE id=$id";
		    $stmt = $conn->prepare($sql);
		    $stmt->execute([$_POST['field_name'] => json_encode($target_file)]);

		}catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		$conn = null;

	}

	public static function validate($arr = []){
		$validation_pass = true;

		foreach ($_POST as $field => $value) {
		  $old[$field] = self::test_input($_POST[$field]);
		}

		foreach ($arr as $field => $rules) {
		  if(!is_array($rules)){
		    $rules = explode('|',$rules);
		  }
		  foreach ($rules as $rule) {
		    if(is_callable($rule)){
		      continue;
		    }
		    if(strpos($rule, ":")>0){
		      $rule = explode(":",$rule);
		      $r = $rule[0];
		      $v = $rule[1];
		    }else{
		      $r = $rule;
		    }
		    if($r == 'required'){
		      if (empty($_POST[$field])) {
		        $error[$field] = $field. " is required";
		        $validation_pass = false;
		      }else{
		        $old[$field] = self::test_input($_POST[$field]);
		      }
		    }elseif($r == 'string'){
		      if (!preg_match('/^[A-Za-z0-9_-]+$/', $_POST[$field])) {
		        $error[$field] = $error[$field]??"Invalid string format";
		        $validation_pass = false;
		      }else{
		        $old[$field] = self::test_input($_POST[$field]);
		      }
		    }elseif($r == 'max'){
		      if (strlen($_POST[$field])>$v) {
		        $error[$field] = $error[$field]??"maximum $v charectors are allowed";
		        $validation_pass = false;
		      }else{
		        $old[$field] = self::test_input($_POST[$field]);
		      }
		    }elseif($r == 'numeric'){
		      if (!is_numeric($_POST[$field])) {
		        $error[$field] = $error[$field]??"Invalid numeric format";
		        $validation_pass = false;
		      }else{
		        $old[$field] = self::test_input($_POST[$field]);
		      }
		    }elseif($r == 'email'){
		      if (!filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)) {
		        $error[$field] = $error[$field]??"Invalid email format";
		        $validation_pass = false;
		      }else{
		        $old[$field] = self::test_input($_POST[$field]);
		      }
		    }elseif($r == 'password_confirmation'){
		      if($_POST[$r]!==$_POST[$field]){
		        $error[$field] = "password_confirmation did not match";
		        $validation_pass = false;
		      }
		    }elseif($r == 'exists'){
		    	try {
				    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		      $stmt = $conn->prepare('SELECT COUNT('.$field.') AS EmailCount FROM '.$v.' WHERE '.$field.' = :'.$field);
		      $stmt->execute(array($field => $_POST[$field]));
		      $result = $stmt->fetch(PDO::FETCH_ASSOC);

		      if ($result['EmailCount'] == '0') {
		        $error[$field] = $error[$field]??"email doesn't exists!";
		        $validation_pass = false;
		      }else{
		        $old[$field] = $result[$field];
		      }
		  		}catch(PDOException $e) {
				    echo "Error: " . $e->getMessage();
				}
				$conn = null;
		    }elseif($r == 'unique_exists'){
		    	try {
				    $conn = new PDO("mysql:host=".static::$servername.";dbname=".static::$dbname, static::$username, static::$password);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		      $stmt = $conn->prepare('SELECT '.$field.' FROM '.$v.' WHERE id='.$_POST['id']);
		      $stmt->execute();
		      $result = $stmt->fetch(PDO::FETCH_ASSOC);

		      if ($result[$field]) {
		        $old[$field] = $result[$field];
		      }else{
		        $stmt = $conn->prepare('SELECT COUNT('.$field.') AS EmailCount FROM '.$v.' WHERE '.$field.' = :'.$field);
		        $stmt->execute(array($field => $old[$field]));
		        $result = $stmt->fetch(PDO::FETCH_ASSOC);

		        if ($result['EmailCount'] !== '0') {
		          $error[$field] = $error[$field]??"User with this email already exists!";
		          $validation_pass = false;
		        }
		      }
		  		}catch(PDOException $e) {
				    echo "Error: " . $e->getMessage();
				}
				$conn = null;
		    }
		  }
		}

		if(!$validation_pass){
		  $_SESSION['error'] = $error;
		  $_SESSION['old'] = $old;
		  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		  	echo json_encode(['old'=>$old,'error'=>$error]);
		  }else{
		  	header("Location: ".$_SERVER['HTTP_REFERER']);
		  }
		  die();
		}
	}

	public static function old() {
		foreach ($_POST as $field => $value) {
		  $old[$field] = self::test_input($_POST[$field]);
		}
		return $old;
	}

	public static function test_input($data) {
	  $data = trim($data);
	  // $data = stripslashes($data);
	  // $data = htmlspecialchars($data);
	  return $data;
	}

}