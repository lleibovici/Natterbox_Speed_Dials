<?php
$custname = $_REQUEST['recname'];

$db = new PDO('sqlite:../speeddial.db');
//$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = "DELETE FROM speeddial WHERE name='" . $custname . "'";
$db->exec($sql);
$db = null;
