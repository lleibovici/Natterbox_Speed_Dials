<?php
/**
 * Created by IntelliJ IDEA.
 * User: leo
 * Date: 02/09/2015
 * Time: 15:06
 */
date_default_timezone_set('Europe/London');
$dbfilename='./speeddial.db';
if (file_exists($dbfilename) ) {
    $db = new PDO("sqlite:$dbfilename");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
else {
    $db = new PDO("sqlite:$dbfilename");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $db->exec("create table speeddial(snumber INTEGER , name VARCHAR(64), phonenumber VARCHAR (32))");
}
