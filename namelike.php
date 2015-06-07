<?php

// call namelike.php?name=xxxxxxx

$dbname="peopledata";
$table="people";
$sql="SELECT  id, name, imageUrl, sex, birthyear FROM " . $table ;

// connect to people database
$mysqli = new mysqli(
                getenv('OPENSHIFT_MYSQL_DB_HOST'),
                getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
                getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
                $dbname,
                getenv('OPENSHIFT_MYSQL_DB_PORT'));
if ($mysqli->connect_errno) {
        print    ("[{\"error\":\"db connect error:"
                . $mysqli->connect_error."\"}]");
} else  {
        // check if input from form
        if (array_key_exists("name", $_REQUEST)) {
        	// use name if given
        	// check for surronding quotes & remove
        	$name = $_REQUEST['name'];
        	if (preg_match('/^(["\']).*\1$/m', $name)) {
        	   $name = substr($name, 1 , -1);
        	}
                $res = 
                    $mysqli->query($sql." WHERE name like '%".$name."%'");
        } else {
        	// if no name then get all
                $res = $mysqli->query($sql);
        }
        // read the db response
        while ($row = $res->fetch_assoc()) {
                $output[]=$row;
        }
        // if db response then encode to json for caller
        if (isset($output)) {
                print(json_encode($output));
        } else {
        	// if no db response send back error array 
                print("[{\"error\":\"no match\"}]");
        }
        //free the result
        $res->free();
}

