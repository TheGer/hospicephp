

<?php

/**
 * Description of DBConnect
 *
 * @author Anna Marie Rapa
 */
class DBConnection {

    private $dbh;

    public function __construct() {
        $this->dbh = new PDO('mysql:host=localhost;dbname=dbhospice', 'root', '123');
	   
	  
    }
    
    public function getTitleList() {
        $con = mysql_connect("localhost", "root", "123", "​​");

        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM titles") or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
            $unique_array[] = '<option value="' . $row['ID'] . '">' . $row['Title'] . '</option>';
        }

        return $unique_array;
    }

    public function getMembershipList() {
        $con = mysql_connect("localhost", "root", "123", "dbhospice");

        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM duration") or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {//SELECT `ID`, ``, ``, `` FROM `` WHERE 1
	
            $unique_array[] = '<option value="' . $row['ID'] . '">' . $row['Name'] . ' - &euro;'. $row['Price'].'</option>';
        }

        return $unique_array;
    }

    public function addNewMember($title, $name, $surname, $address, $street, $locality, $postcode, $idcard, $gender, $landline, $mobile, $email,$inContact, $timeDeleted, $recordDeletedBy, $dateOfBirth, $recordDeletedReason) {
        $dbConnection = mysqli_connect('localhost', 'root', '123', 'dbhospice');
        
        $query = "INSERT INTO `members`(`Title_FK`, `Name`, `Surname`, `Address`, `Street`, `Locality`, `Postcode`, `IDCard`, `Gender`, `Landline`, `Mobile`, `Email`, `InContact`, `TimeDeleted`, `RecordDeletedBy`, `DateOfBirth`,`RecordDeletedReason`) 
            VALUES ('$title','$name','$surname','$address','$street','$locality','$postcode','$idcard','$gender','$landline',
               '$mobile','$email ','$inContact','$timeDeleted', '$recordDeletedBy', '$dateOfBirth','$RecordDeletedReason')";
     
        if (mysqli_query($dbConnection, $query)) {
            return true;// "Successfully inserted " . mysqli_affected_rows($dbConnection) . " row";
        } else {
            return "Error occurred: " . mysqli_error($dbConnection);
        }
    }

    public function checkMemberExists($idcard, $name, $surname, $email) {
        $con = mysql_connect("localhost", "root", "123", "dbhospice");

        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM `members` WHERE (UPPER(`IDCard`) ='$idcard' and UPPER(`Name`) = '$name' and UPPER(`Surname`) = '$surname' and UPPER(`Email`) = '$email');") or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
            return true;
            break;
        }

        return false;
    }
    
    public function getMemberShip($membershipID) {
        $con = mysql_connect("localhost", "root", "123", "dbhospice");
        
        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM `duration` WHERE (ID ='$membershipID');") or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
            return $row['Price'];
            break;
        }

        return 0;
    }
    public function getMemberShipDurationByMembershipID($membershipID) {
        $con = mysql_connect("localhost", "root", "123", "dbhospice");
        
        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM `duration` WHERE (ID ='$membershipID');") or die(mysql_error());
    //    $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
            return  $row['Duration'];
            break;
        }
return "";
    }
    
    
    public function getMemberShipPriceByID($membershipID) {
        $con = mysql_connect("localhost", "root", "123", "dbhospice");
        
        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM `duration` WHERE (ID ='$membershipID');") or die(mysql_error());
    //    $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
           
           return  $row['Price'];
           break;
        }
        return "";

    }
	
	public function getMemberShipNameByID($membershipID) {
        $con = mysql_connect("localhost", "root", "123", "dbhospice");
        
        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM `duration` WHERE (ID ='$membershipID');") or die(mysql_error());
    //    $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
           
           return  $row['Name'];
           break;
        }
        return "";

    }
    
    public function getMemberShipDetailsByID($membershipID) {
        $con = mysql_connect("localhost", "root", "123", "dbhospice");
        
        $db = "dbhospice";
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM `duration` WHERE (ID ='$membershipID');") or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
            return $row['Name'];
            break;
        }

        return 0;
    }
    
	//amended to requirement 4- added additional fields and removed isRenewal
    public function addNewPayment($unitPrice, $quantity, $unitDuration, $memberId, $membershipId) {
        $dbConnection = mysqli_connect('localhost', 'root', '123', 'dbhospice');
        
        $query = "INSERT INTO `payments`(`UnitPrice`, `Quantity`, `UnitDuration`, `MemberID`, `MembershipID`)
            VALUES ('$unitPrice','$quantity','$unitDuration', '$memberId','$membershipId')";
        if (mysqli_query($dbConnection, $query)) {
            echo "Successfully inserted " . mysqli_affected_rows($dbConnection) . " row";
        } else {
            echo "Error occurred: " . mysqli_error($dbConnection);
        }
    }
	//amended to requirement 4- added additional fields and removed others
     public function addNewMemberShip($paidDate, $fromDate, $toDate, $paymentMethod, $numberOfYears, $totalPrice, $memberID, $isRenewal) {
        $dbConnection = mysqli_connect('localhost', 'root', '123', 'dbhospice');
        
        $query = "INSERT INTO `memberships`(`PaidDate`, `FromDate`, `ToDate`, `PaymentMethod`, `NumberOfYears`, `TotalPrice`, `MemberID`, `isRenewal`)"
                . "VALUES ('$paidDate', '$fromDate', '$toDate', '$paymentMethod', '$numberOfYears', '$totalPrice', '$memberID', '$isRenewal')";
        if (mysqli_query($dbConnection, $query)) {
            echo "Successfully inserted " . mysqli_affected_rows($dbConnection) . " row";
        } else {
            echo "Error occurred: " . mysqli_error($dbConnection);
        }
    }
}

?>
