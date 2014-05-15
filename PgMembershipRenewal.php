<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();
/* if (!isset($_SESSION['es']['loggedin']) || 
  $_SESSION['es']['loggedin'] != 1) {
  header('Location: index.php');
  }
 */
require_once 'DBConnection.php';
$db = new DBConnection();

//$db->addNewMembership(1, "anna", "rapa", "55", "white str", "Xewkija", "XWK2430", "22555", 0, 2555666, 333699, "email@email.com", 1546464, NULL, NULL, 2588, 1);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $idcard = $_POST['idcard'];
    $email = $_POST['email'];

    $exists = $db->checkMemberExists(strtoupper($idcard), strtoupper($name), strtoupper($surname), strtoupper($email));

    if ($exists) {
        $message = "Member Exists";
        echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
        $message = "Member Does NOT EXIST!";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="PgMembershipRenewal.php" method="post">
            <h1>Membership Renewal</h1>
            <table>
                <tr>
                    <td align="right">
                        <label id="lblIdCardNo">ID Card No:</label> 
                    </td>
                    <td>
                        <input type="text" name="idcard" id="idcard"></input>
                    </td>
                </tr>
                </td>
                </tr>
                <tr>
                    <td align="right">
                        <label id="lblName">Name:</label> 
                    </td>
                    <td>
                        <input type="text" name="name" id="name"></input>
                    </td>
                    <td align="right">
                        <label id="lblSurname">Surname:</label>  
                    </td>
                    <td>
                        <input type="text" name="surname" id="surname"></input>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label id="lblEmail">Email:</label>
                    </td>
                    <td>
                        <input type="text" name="email" id="email"></input>
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" name="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>
