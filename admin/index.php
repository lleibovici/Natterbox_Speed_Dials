<?php
$dbfilename = '../speeddial.db';
if (file_exists($dbfilename)) {
    $db = new PDO('sqlite:../speeddial.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} else {
    $db = new PDO('sqlite:../speeddial.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $db->exec("create table phonedir(name char(64),phonenumber char(32))");
}
//$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

if ($_POST["editsubmit"] != "") {
    $snumber = $_POST['snumber'];
    $name = htmlspecialchars($_POST['name']);
    $phonenumber = str_replace(' ', '', $_POST['phonenumber']);
    if (substr($phonenumber, 0, 1) == '0') {
        $phonenumber = '+44' . substr($phonenumber, 1);
    }
    $oldname = htmlspecialchars($_POST['oldname']);
    if ($oldname == '') {
        $sqlu = "INSERT INTO speeddial VALUES(" . $snumber . ",'" . $name . "','" . $phonenumber . "')";
    } else {
        $sqlu = "UPDATE speeddial SET snumber=" . $snumber . ", name='" . $name . "', phonenumber='" . $phonenumber . "' WHERE name='" . $oldname . "'";
    }
    $db->exec($sqlu);
}


$SQL = "SELECT snumber, name, phonenumber FROM speeddial ORDER BY snumber";
$res = $db->query($SQL);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="ISO-8859-1">
    <title>Phone Directory</title>
    <link href="../style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../jquery-1.11.0.min.js"></script>
    <style type="text/css">
        #backgroundPopup {
            display: none;
            position: fixed;
            _position: absolute; /* hack for internet explorer 6*/
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            background: #000000;
            border: 1px solid #cecece;
            z-index: 1;
        }

        #editrec {
            display: none;
            position: fixed;
            _position: absolute; /* hack for internet explorer 6*/
            height: 150px;
            width: 500px;
            background: #FFFFFF;
            border: 2px solid #cecece;
            z-index: 2;
            padding: 12px;
            font-size: 13px;
        }

        #editrec h1 {
            text-align: left;
            color: #6FA5FD;
            font-size: 22px;
            font-weight: 700;
            border-bottom: 1px dotted #D3D3D3;
            padding-bottom: 2px;
            margin-bottom: 20px;
        }

        #editrecClose {
            font-size: 14px;
            line-height: 14px;
            right: 6px;
            top: 4px;
            position: absolute;
            color: #6fa5fd;
            font-weight: 700;
            display: block;
        }

        #custtable table {
            color: #000000;
            border: thin;
            border: #999999;
            font-size: small;
        }

        .d0 {
            background-color: #CCCCCC;
        }

        .d1 {
            background-color: #FFFFFF;
        }

    </style>
    <script type="text/javascript">
        var popupStatus = 0;
        function loadPopup() {
            //loads popup only if it is disabled
            if (popupStatus == 0) {
                $("#backgroundPopup").css({
                    "opacity": "0.7"
                });
                $("#backgroundPopup").fadeIn("slow");
                $("#editrec").fadeIn("slow");
                popupStatus = 1;
            }
        }
        function disablePopup() {
            //disables popup only if it is enabled
            if (popupStatus == 1) {
                $("#backgroundPopup").fadeOut("slow");
                $("#editrec").fadeOut("slow");
                popupStatus = 0;
            }
        }
        function centerPopup() {
            //request data for centering
            var windowWidth = document.documentElement.clientWidth;
            var windowHeight = document.documentElement.clientHeight;
            var popupHeight = $("#editrec").height();
            var popupWidth = $("#editrec").width();
            //centering
            $("#editrec").css({
                "position": "absolute",
                "top": windowHeight / 2 - popupHeight / 2,
                "left": windowWidth / 2 - popupWidth / 2
            });
            //only need force for IE6

            $("#backgroundPopup").css({
                "height": windowHeight
            });

        }
        $(document).ready(function () {
            $("#editrecClose").click(function () {
                disablePopup();
            });
            //Click out event!
            $("#backgroundPopup").click(function () {
                disablePopup();
            });
            //Press Escape event!
            $(document).keypress(function (e) {
                if (e.keyCode == 27 && popupStatus == 1) {
                    disablePopup();
                }
            });
        });
        $(document).ready(function () {
            $('#editrecClose').hover(function () {
                $(this).css('cursor', 'pointer');
            }, function () {
                $(this).css('cursor', 'auto');
            });
        });
        function editrec(snumber, custname, phonenumber) {
            $("#edittitle").text("Edit Record");
            $("#snumber").val(snumber);
            $("#name").val(custname);
            $("#oldname").val(custname);
            $("#phonenumber").val(phonenumber);
            centerPopup();
            loadPopup();
        }
        function addrecord() {

            $("#edittitle").text("Add Record");
            $("#snumber").val($("#nextnum").val());
            $("#name").val('');
            $("#oldname").val('');
            $("#phonenumber").val('');
            centerPopup();
            loadPopup();
        }
        function deleterec(ridx, recname) {
            if (confirm("Delete record for " + recname)) {
                $.ajax({
                    type: 'POST',
                    url: 'deletenumber.php',
                    data: {'recname': recname},
                    success: function (data) {
                        document.getElementById("dirtable").deleteRow(ridx+1);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
            else {
                return false;
            }
        }
    </script>
</head>
<body>
<div align="center" style="height: 30px; width: 600px; overflow: hidden;">
    <table width="90%" border="0">
        <tr>
            <th>Speed Dial Directory</th>
        </tr>
    </table>
</div>
<div id="custtable" align="center" id="customer table" style="height: 600px; overflow: auto; width: 600px;">
    <table id="dirtable" width="90%">
        <tr>
            <td>&nbsp;</td>
            <th>Speed Dial</th>
            <th>Name</th>
            <th>Number</th>
            <td>&nbsp;</td>
        </tr>
        <?php
        $nextnum = 8001;
        $rowidx = 0;
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $currentnum = $row['snumber'];
            if ($currentnum >= $nextnum) {
                $nextnum = $currentnum + 1;
            }
            ?>
            <tr align="left" class="d<?php echo $rowidx % 2 ?>">
                <td><a href="#"
                       onclick="editrec('<?php echo $row['snumber'] ?>','<?php echo $row['name'] ?>','<?php echo $row['phonenumber'] ?>')">Edit</a>
                </td>
                <td><?php echo $row['snumber'] ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['phonenumber'] ?></td>
                <td><a href="#" onclick="deleterec(<?php echo $rowidx ?>,'<?php echo $row['name'] ?>')">Delete</a></td>
            </tr>

            <?php
            $rowidx++;
        }
        ?>

    </table>
    <input type="hidden" name="nextnum" id="nextnum" value="<?php echo $nextnum; ?>">
    <?php
    $db = null;
    ?>
    <div id="editrec">
        <a id="editrecClose">x</a>

        <h1 id="edittitle" name="edittitle">Edit Customer</h1>

        <form method="post" name="editform">
            <input type="hidden" name="oldname" id="oldname">
            <table border="0">
                <tr>
                    <td align="right">Speed Dial Number</td>
                    <td><input type="text" name="snumber" id="snumber" maxlength="4"></td>
                </tr>
                <tr>
                    <td align="right">Name</td>
                    <td><input type="text" name="name" id="name" maxlength="64"></td>
                </tr>
                <tr>
                    <td align="right">Phone Number</td>
                    <td><input type="text" name="phonenumber" id="phonenumber" maxlength="32"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="right"><input type="submit" name="editsubmit" value="Update"></td>
                </tr>
            </table>
        </form>
    </div>
    <div id="backgroundPopup"></div>
    <div><input type="button" name="addrec" id="addrec" onclick="addrecord()" value="Add Record"></div>
    <div><input type="button" name="printpage" id="printpage" onclick="window.print();" value="Print Page"></div>

</body>
</html>
