<?php
/**
 * Created by IntelliJ IDEA.
 * User: leo
 * Date: 02/09/2015
 * Time: 15:13
 */
require_once("db.php");
$db->exec("DELETE FROM speeddial");
$sql="INSERT INTO speeddial VALUES(8001,'Dummy Entry','+44123456789')";
$db->exec($sql);

