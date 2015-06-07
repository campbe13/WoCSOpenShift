<?php

// call byid.php?id=xxxxxxx

$dbname="peopledata";
$table="people";
$sql="SELECT  id, name, imageUrl, sex, birthyear FROM " . $table ;
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
        $sql="SELECT  id, name, imageUrl, sex, birthyear FROM " . $table ;
        if (array_key_exists("id", $_REQUEST)) {
                $res = $mysqli->query($sql." WHERE id =".$_REQUEST['id']);
        } else {
                $res = $mysqli->query($sql);
        }
        while ($row = $res->fetch_assoc()) {
                $output[]=$row;
        }
        if (isset($output)) {
                print(json_encode($output));
        } else {
                print("[{\"error\":\"no match\"}]");
        }
        $res->free();
}

