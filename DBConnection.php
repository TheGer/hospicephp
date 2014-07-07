

<?php

/**
 * Description of DBConnect
 *
 * @author Anna Marie Rapa
 */
class DBConnection {




    private $dbhost = "localhost";
    private $dbusername = "root";
    private $dbpassword = "";
    private $dbname = "dbhospice";

    public function __construct() {
       
	   
	  
    }
	
	public function checkMemberExistsID($idcard) {
          $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
        mysql_select_db($db, $con);
		
		$querystr = "SELECT * FROM `members` WHERE (UPPER(`IDCard`) ='$idcard')";
		
		echo $querystr;
		
        $query = mysql_query($querystr) or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
            return $row['memberid'];
            
        }

        return false;
    }

    
    public function getTitleList() {
        $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM titles") or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {
            $unique_array[] = '<option value="' . $row['ID'] . '">' . $row['Title'] . '</option>';
        }

        return $unique_array;
    }

    public function getMembershipList() {
      $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT * FROM duration") or die(mysql_error());
        $unique_array = array();

        while ($row = mysql_fetch_assoc($query)) {//SELECT `ID`, ``, ``, `` FROM `` WHERE 1
	
            $unique_array[] = '<option value="' . $row['ID'] . '">' . $row['Name'] . ' - &euro;'. $row['Price'].'</option>';
        }

        return $unique_array;
    }
	
	public function getMembershipValue($id)
	{
		$con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
        mysql_select_db($db, $con);
        $query = mysql_query("SELECT value FROM duration WHERE ID = $id") or die(mysql_error());
		
		  while ($row = mysql_fetch_assoc($query)) {
			return $row['value'];
			break;
		  }
	
	}
	

    public function addNewMember($title, $name, $surname, $address, $street, $locality, $postcode, $idcard, $gender, $landline, $mobile, $email,$inContact, $timeDeleted, $recordDeletedBy, $dateOfBirth, $recordDeletedReason) {
        $dbConnection = mysqli_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $update = false;
        $memberid = $this->checkMemberExistsID($idcard);
        if ($memberid<>0)
        {
            $query = "UPDATE `members` SET `Title_FK` = $title, `Name` = $name, `Surname` = $surname, `Address` = $address, `Street` = $street, `Locality` = $locality, `Postcode` = $postcode, `Gender` = $gender, `Landline` = $landline, `Mobile` = $mobile, 
            `Email` = $email, `InContact` = $inContact, `TimeDeleted` = $timeDeleted, `RecordDeletedBy` =$recordDeletedBy, `DateOfBirth`= $dateOfBirth,`RecordDeletedReason` = $recordDeletedReason WHERE `IDCard` = $idcard";
            //test the query
            echo $query;
                //insert a new membership

            $update = true;

        }
        else
        {
            $query = "INSERT INTO `members`(`Title_FK`, `Name`, `Surname`, `Address`, `Street`, `Locality`, `Postcode`, `IDCard`, `Gender`, `Landline`, `Mobile`, `Email`, `InContact`, `TimeDeleted`, `RecordDeletedBy`, `DateOfBirth`,`RecordDeletedReason`) 
            VALUES ('$title','$name','$surname','$address','$street','$locality','$postcode','$idcard','$gender','$landline',
               '$mobile','$email ','$inContact','$timeDeleted', '$recordDeletedBy', '$dateOfBirth','$RecordDeletedReason')";
            //insert a new membership based on a new member
            

        }

         
		 
        if (mysqli_query($dbConnection, $query)) {
            if ($update)
            {
				//add payment
				$updateArr = array(0=>$memberid,1=>'update');
				return $updateArr;
            }
            else
            {
				$memberid = mysql_insert_id();
				$updateArr = array(0=>$memberid,1=>'new');
				return $updateArr;
            }
        } else {
            return "Error occurred: " . mysqli_error($dbConnection);
        }
    }

    public function checkMemberExists($idcard, $name, $surname, $email) {
          $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
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
       $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
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
     $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
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
     $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
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
     $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
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
     $con = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);

        $db = $this->dbname;
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
    public function addNewPayment($unitPrice, $quantity,$unitDuration, $memberId, $membershipId) {
        $dbConnection = mysqli_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);
        
        $query = "INSERT INTO `payments`(`UnitPrice`, `Quantity`,'UnitDuration', `MemberID`, `MembershipID`)
            VALUES ('$unitPrice','$quantity','$unitDuration','$memberId','$membershipId')";
        if (mysqli_query($dbConnection, $query)) {
            echo "Successfully inserted " . mysqli_affected_rows($dbConnection) . " row";
        } else {
            echo "Error occurred: " . mysqli_error($dbConnection);
        }
    }


	
    //amended to requirement 4- added additional fields and removed others
     public function addNewMemberShip($paidDate, $paymentMethod, $totalPrice, $memberID, $duration,$renewal) {
	 
	 
		if ($renewal == 'update')
		{
			$renewal = 1;
		}
		else
		{
			$renewal = 0;
		}
	 
      $dbConnection = mysqli_connect($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);
        
		$membershipLengthValue = $this->getMembershipValue($duration);
		
		
		if ($membershipLengthValue != 99)
		{
		$toDate = $paidDate + strtotime($paidDate + $membershipLengthValue + " years");
		}
		else
		{
			$membershipLengthValue = '';
			$toDate = '';
		}	
		
		
		//echo $toDate;
		
        $query = "INSERT INTO `memberships`(`PaidDate`,`FromDate`, `ToDate`,`PaymentMethod`, `TotalPrice`, `MemberID`, 'NumberOfYears','IsRenewal')"
                . "VALUES ('$paidDate','$paidDate','$toDate','$paymentMethod', '$totalPrice', '$memberID', '$membershipLengthValue','$renewal')";
				
		echo $query;		
				
        if (mysqli_query($dbConnection, $query)) {
            echo "Successfully inserted " . mysqli_affected_rows($dbConnection) . " row";
			return mysql_insert_id();
        } else {
            echo "Error occurred: " . mysqli_error($dbConnection);
        }
    }

}

?>
