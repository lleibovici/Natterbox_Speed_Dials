<?php
/**
 * Created by IntelliJ IDEA.
 * User: leo
 * Date: 15/09/2015
 * Time: 16:51
 */
$name = 'NOT FOUND';

if (isset($_GET['callerid']) && trim($_GET['callerid']) != '') {
    $callerid = str_replace(' ', '', $_GET['callerid']);
    if (substr($callerid, 0, 1) == '0') {
        $callerid = '+44' . substr($callerid, 1);
    }
    date_default_timezone_set('Europe/London');
    $dbfilename = './speeddial.db';
    if (file_exists($dbfilename)) {
        $db = new PDO("sqlite:$dbfilename");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } else {
        $db = new PDO("sqlite:$dbfilename");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $db->exec("create table speeddial(snumber INTEGER , phonenumber VARCHAR (32))");
    }
    $sql = "SELECT * FROM speeddial WHERE phonenumber='$callerid'";
    $res = $db->query($sql);
    if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $name = $row['name'];
    }
}
else {
    $name='NO callerid sent';
}
header("Content-Type:text/xml");
echo('<?xml version="1.0" encoding="UTF-8"?>');
echo('<records>');
echo('<record>');
echo("<Name>$name</Name>");
echo("<Error>OK</Error>");
echo('</record>');
echo('</records>');
exit;
