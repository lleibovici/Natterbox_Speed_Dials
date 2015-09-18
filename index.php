<?php
/**
 * Created by IntelliJ IDEA.
 * User: leo
 * Date: 02/09/2015
 * Time: 14:39
 */


if (isset($_GET['snumber']) && $_GET['snumber'] != '') {
    $scode=$_GET['snumber'];
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
    $sql = "SELECT * FROM speeddial WHERE snumber=$scode";
    $res = $db->query($sql);
    if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $numberToDial = $row['phonenumber'];
        $name = $row['name'];
        header("Content-Type:text/xml");
        returnRec($numberToDial, $name);

    } else {
        $thousands = substr($scode,0,1);
        $hundreds = substr($scode,1,1);
        $tens = substr($scode,2,1);
        $units = substr($scode,3,1);
        header("Content-Type:text/xml");
        echo('<?xml version="1.0" encoding="UTF-8"?>');
        echo('<records>');
        echo('<record>');
        echo("<Error>Speed Dial $thousands $hundreds $tens $units not found</Error>");
        echo('</record>');
        echo('</records>');
    }
}

function returnRec($numberToDial, $name)
{
    echo('<?xml version="1.0" encoding="UTF-8"?>');
    echo('<records>');
    echo('<record>');
    echo("<Number>$numberToDial</Number>");
    echo("<Name>$name</Name>");
    echo("<Error>OK</Error>");
    echo('</record>');
    echo('</records>');
}