<!DOCTYPE html>
<?php

function getPayment($membershipType,$membershipCost,$cctype,$ccnumber,$cvv2,$expdate,$firstname,$lastname,$street,$city)
{

$mainurl = 'http://localhost/phptest/PgNewMember';
	// Set sandbox (test mode) to true/false.
$sandbox = TRUE;

// Set PayPal API version and credentials.
$api_version = '98';
//$api_endpoint = $sandbox ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
$api_endpoint = $sandbox ? 'https://api.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
$api_username = $sandbox ? 'gerrysaid-facilitator_api1.gmail.com' : 'LIVE_USERNAME_GOES_HERE';
$api_password = $sandbox ? '1399023095' : 'LIVE_PASSWORD_GOES_HERE';
$api_signature = $sandbox ? 'AdsiGC3ZinSElg9JUD9va4Zt5LXRA86KqWl1VDYIG7Oh0.c5NWtIgiYl' : 'LIVE_SIGNATURE_GOES_HERE';
	
	
	$expdate = str_replace("/","",$expdate);
	
	
$params = array
					(
					'METHOD' => 'DoDirectPayment',
					'USER' => $api_username,
					'PWD' => $api_password,
					'SIGNATURE' => $api_signature,
					'VERSION' => $api_version,
					'PAYMENTACTION' => 'Sale',
					'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
					'CREDITCARDTYPE' => $cctype,
					'ACCT' => $ccnumber,
					'EXPDATE' => $expdate,
					'CVV2' => $cvv2,
					'FIRSTNAME' => $firstname,
					'LASTNAME' => $lastname,
					'STREET' => $street,
					'CITY' => $city,
					'COUNTRYCODE' => 'MT',
					'CURRENCYCODE' => 'EUR',
					'DESC' => 'Membership Renewal',
					'cancelURL'=>$mainurl,
					'returnURL'=>$mainurl,
					'AMT'=>$membershipCost
					);
	
	// Loop through $request_params array to generate the NVP string.
$nvp_string = '';
foreach($params as $var=>$val)
{
	$nvp_string .= '&'.$var.'='.urlencode($val);
}

//echo $nvp_string;

// Send NVP string to PayPal and store response
$curl = curl_init();
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_URL, $api_endpoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);


	//this is what is returned from paypal	
$result = curl_exec($curl);

	//close the curl connection


parse_str($result,$response_array);

//check if a specific variable here is true.  If that is true, this function returns true, if not returns false
print_r($response_array);

if ($response_array["ACK"] == "Success")
{
	return true;
}
else
{
	return false;
}

curl_close($curl);

}



        $title = "";
        $name = "";
        $surname = "";
        $dateOfBirth = "";
        $address = "";
        $street = "";
        $locality = "";
        $postcode = "";
        $idcard = "";
        $gender = "";
        $landline = "";
        $mobile = "";
        $email = "";
        $incontact = "";
        $month = NULL; // $_POST[''];
        $receiptno = NULL; //$_POST[''];
        $duration = "";


//-----------------------------the below code will be used when payment is complete---------------------------------------

$error_css = ""; //Will be used to set the textboxes to red
$displayError = ""; //Will  be used to display the error message
$priceResult = "";
require_once 'DBConnection.php';

session_start();

$db = new DBConnection();
$membershipPrice;
$membershipDuration;
$lblmembershipPrice = false;
$lblmembershipDuration = false;
$duration = 0;




// Don't post the form until the submit button is pressed. //added gender as required | added gender to the array
$requiredFields = array('name', 'surname', 'idcard', 'address', 'street', 'gender', 'locality', 'email');    // Add the 'name' for all required fields to this array
$errors = false;
if (isset($_POST['submit'])) {
    // Clean all inputs
    array_walk($_POST, 'check_input');
	
    // Loop over requiredFields and output error if any are empty
    foreach ($requiredFields as $r) {

        if (strpos($r,',')) {
            $errors = true;
            $error_css = 'background-color:red';
            $displayError = $displayError . '<br />• ' . $r . ' cannot contain commas or spaces.';
        }

        if (strlen($r) == 0) {
            $errors = true;
            $error_css = 'background-color:red';
            $displayError = "" . $displayError . "<br />• " . $r . " cannot be empty.";
        }
        if ($r == 'name') {
            //Checks that name does not contain any integers
            if (strcspn($r, '0123456789') != strlen($r)) {
                $errors = true;
                $error_css = 'background-color:red';
                $displayError = $displayError . '<br />• ' . $r . ' cannot contain numbers.';
            }
        }
        if ($r == 'surname') {
            //Checks that surname does not contain any integers
            if (strcspn($r, '0123456789') != strlen($r)) {
                $errors = true;
                $error_css = 'background-color:red';
                $displayError = $displayError . '<br />• ' . $r . ' cannot contain numbers.';
            }
        }
		
        if ($r == 'idcard') {
            //Checks that idcard last letter must be a letter
            if (!(strcspn(substr($r, -1), '0123456789') != strlen(substr($r, -1)))) {
               $errors = true;
               $error_css = 'background-color:red';
               $displayError = $displayError . "<br />• " . $r . ' should be in a correct format e.g. (1234A).';
			}
        }

        
    }

    if (strlen($_POST['mobile']) != 0) {
        //Checks that mobile does not contain any letters
        if (!(strcspn($_POST['mobile'], '0123456789') != strlen($_POST['mobile']))) {
            $errors = true;
            $error_css = 'background-color:red';
			//removed variable r
            $displayError = $displayError . '<br />• ' . ' mobile cannot contain letters.';
        }
    }
    if (strlen($_POST['landline']) != 0) {
        //Checks that mobile does not contain any letters
        if (!(strcspn($_POST['landline'], '0123456789') != strlen($_POST['landline']))) {
            $errors = true;
            $error_css = 'background-color:red';
			//removed variable r
            $displayError = $displayError . '<br />• ' . ' landline cannot contain letters.';
        }
    }
    if (strlen($_POST['email']) != 0) {
        if (preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/', $_POST['email'])) {
            $errors = true;
            $error_css = 'background-color:red';
			$displayError = $displayError . '<br />• ' . $r . ' should be valid (example@email.com).';
        }
    }
	
	//future date validation
	if(isset($_POST['dob']))
	{
		if($dateOfBirth = date_create(($_POST['dob'])))
		{
			$now = date_create();
			if($dateOfBirth >= $now)
			{
				$errors = true;
            	$error_css = 'background-color:red';
				$displayError = $displayError . '<br />• ' . ' Date of Birth cannot be empty or in the future';
			}
		}
		$date_value = htmlentities($_POST['dob']); 
	}


	
	
	
	
$title = strip_tags($_POST['title']);
        $name = strip_tags($_POST['name']);
        $surname = strip_tags($_POST['surname']);
        $dateOfBirth = $date_value;
        $address = strip_tags($_POST['address']);
        $street = strip_tags($_POST['street']);
        $gender = strip_tags($_POST['gender']);
        $locality = strip_tags($_POST['locality']);
        $postcode = strip_tags($_POST['postcode']);
        $idcard = strip_tags($_POST['idcard']);
        $landline = strip_tags($_POST['landline']);
        $mobile = strip_tags($_POST['mobile']);
        $email = strip_tags($_POST['email']);
        $incontact = strip_tags($_POST['inContact']);
        //requirement 5 - new fields in db
		$timeDeleted = NULL;
		$recordDeletedBy = NULL;
		$recordDeletedReason = NULL;
		
		$month = NULL; // $_POST[''];
        $receiptno = NULL; //$_POST[''];
        $duration = strip_tags($_POST['duration']);
        $expdate = strip_tags($_POST['expdate']);
		$cctype = $_POST['cctype'];
		$ccnumber = $_POST['ccnumber'];
		$cvv2 = $_POST['cvv2'];
		
		
		
    // Error/success check
    if ($errors == true) {
        //VALIDATION FAILED
    } else {
        $error_css = "";
        $displayError = "";
        // no errors   name, surname, idcard, address, street, gender, locality, email
        
        $gender = $_POST['gender'];

        $exists = $db->checkMemberExists(strtoupper($idcard), strtoupper($name), strtoupper($surname), strtoupper($email));

        if ($exists) {
            $message = "Member Already Exists";
            echo "<script type='text/javascript'>alert('$message');</script>";
        } else {
			//query paypal
			//check card success
			//return true
		
			//if true, addedsuccess

            $membershipName = $db->getMembershipNameByID($duration);
            $membershipPrice = $db->getMembershipPriceByID($duration);

			
			$addedSuccess = getPayment($membershipName,$membershipPrice,$cctype,$ccnumber,$cvv2,$expdate,$name,$surname,$street,$locality);
			
			
 
 
			if ($addedSuccess) {
			
				//db code to add member
                
				//insert into payments
			
				//insert into memberships

			$memberID = $db->addNewMember($title, $name, $surname, $address, $street, $locality, $postcode, $idcard, $gender, $landline, $mobile, $email, $incontact, null, null,$dateOfBirth,null);
			
            //add payment
            
            $paidDate = date("Y-m-d");
            $paymentMethod = "CC";
            
            
			$db->addNewMemberShip($paidDate, $membershipName, $paymentMethod,  $membershipPrice, $memberID,$duration);

            //add membership
            $db->addNewPayment($unitPrice, $quantity, $memberId, $duration)

            } else {
                $message = "Sorry, Member was not added successfully.";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }

    
        }
    }
}

// check_input function
function check_input(&$data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES);
    return $data;
}



?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <table>
            <tr>
            <div id="divInfo">
                <form action="PgNewMember.php" method="post">
                    <h1>New Membership</h1>
                    <table>
                        <tr>
                            <td align="right">
                                <label id="lblIdCardNo">ID Card No:</label> 
                            </td>
                            <td>
                                <input type="text" name="idcard" id="idcard" value="<?php echo $idcard; ?>"></input>*
                            </td>
                        </tr>

                        <tr>
                            <td align="right">
                                <label id="lblTitle">Title:</label>  
                            </td>
                            <td>
                                <select id="title" name="title" width="200px" >
                                    <?php
                                    $titleList = $db->getTitleList();
                                    foreach ($titleList as $q) {
                                        echo $q;
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblName">Name:</label> 
                            </td>
                            <td>
                                <input type="text" name="name" id="name" value="<?php echo $name; ?>"></input>*
                            </td>
                            <td align="right">
                                <label id="lblSurname">Surname:</label>  
                            </td>
                            <td>
                                <input type="text" name="surname" id="surname" value="<?php echo $surname; ?>"></input>*
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblDOB">Date of Birth:</label>   
                            </td>
                            <td>
                                <input type="text" name="dob" id="dob" value="<?php $date_value; ?>"></input>*
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblHouseNo">House Name or Number:</label>   
                            </td>
                            <td>
                                <input type="text" name="address" id="address" value="<?php echo $address; ?>"></input>*
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblStreet">Street:</label>
                            </td>
                            <td>
                                <input type="text" name="street" id="street" value="<?php echo $street; ?>"></input>*
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                            	<!-- locality functionality -->
                                <label id="lblLocality">Locality:</label> 
                            </td>
                            <td>
                                <select name="locality">
                                    <option value="ALBERT TOWN - MARSA">ALBERT TOWN - MARSA</option>
                                    <option value="ATTARD">ATTARD</option>
                                    <option value="BAHAR IC-CAGHAQ - NAXXAR">BAHAR IC-CAGHAQ - NAXXAR</option>
                                    <option value="BAHRIJA - RABAT">BAHRIJA - RABAT</option>
                                    <option value="BALZAN">BALZAN</option>
                                    <option value="BIDNIJA - MOSTA">BIDNIJA - MOSTA</option>
                                    <option value="BIDNIJA - ST. PAULS BAY">BIDNIJA - ST. PAULS BAY</option>
                                    <option value="BIRGUMA - NAXXAR">BIRGUMA - NAXXAR</option>
                                    <option value="BIRKIRKARA">BIRKIRKARA</option>
                                    <option value="BIRZEBBUGA">BIRZEBBUGA</option>
                                    <option value="BORMLA (Cospicua)">BORMLA (Cospicua)</option>
                                    <option value="BUBAQRA - ZURRIEQ">BUBAQRA - ZURRIEQ</option>
                                    <option value="BUGIBBA - ST. PAULS BAY">BUGIBBA - ST. PAULS BAY</option>
                                    <option value="BULUBEL - ZEJTUN">BULUBEL - ZEJTUN</option>
                                    <option value="BURMARRAD - ST. PAULS BAY">BURMARRAD - ST. PAULS BAY</option>
                                    <option value="BUSKETT - DINGLI">BUSKETT - DINGLI</option>
                                    <option value="CIRKEWWA - MELLIEHA">CIRKEWWA - MELLIEHA</option>
                                    <option value="DELIMARA - MARSAXLOKK">DELIMARA - MARSAXLOKK</option>
                                    <option value="DINGLI">DINGLI</option>
                                    <option value="FGURA">FGURA</option>
                                    <option value="FLEUR DE LYS - BIRKIRKARA">FLEUR DE LYS - BIRKIRKARA</option>
                                    <option value="FLORIANA">FLORIANA</option>
                                    <option value="GHADIRA - MELLIEHA">GHADIRA - MELLIEHA</option>
                                    <option value="GHAJN TUFFIEHA - MELLIEHA">GHAJN TUFFIEHA - MELLIEHA</option>
                                    <option value="GHAJN TUFFIEHA - MGARR">GHAJN TUFFIEHA - MGARR</option>
                                    <option value="GHARGHUR">GHARGHUR</option>
                                    <option value="GHAXAQ>">GHAXAQ</option>
                                    <option value="GUDJA">GUDJA</option>
                                    <option value="GWARDAMANGA - PIETA">GWARDAMANGA - PIETA</option>
                                    <option value="GZIRA">GZIRA</option>
                                    <option value="HAL FAR - BIRZEBBUGA">HAL FAR - BIRZEBBUGA</option>
                                    <option value="HAL FARRUG - LUQA">HAL FARRUG - LUQA</option>
                                    <option value="HAMRUN">HAMRUN</option>
                                    <option value="IBRAG - SWIEQI">IBRAG - SWIEQI</option>
                                    <option value="IKLIN">IKLIN</option>
                                    <option value="IMRIEHEL - BIRKIRKARA">IMRIEHEL - BIRKIRKARA</option>
                                    <option value="KALAFRANA- BIRZEBBUGA">KALAFRANA- BIRZEBBUGA</option>
                                    <option value="KALKARA">KALKARA</option>
                                    <option value="KAPPARA - SAN GWANN">KAPPARA - SAN GWANN</option>
                                    <option value="KIRKOP">KIRKOP</option>
                                    <option value="LIJA">LIJA</option>
                                    <option value="LUQA">LUQA</option>
                                    <option value="MADLIENA - SWIEQI">MADLIENA - SWIEQI</option>
                                    <option value="MAGHTAB - NAXXAR">MAGHTAB - NAXXAR</option>
                                    <option value="MANIKATA - MELLIEHA">MANIKATA - MELLIEHA</option>
                                    <option value="MANOEL ISLAND - GZIRA">MANOEL ISLAND - GZIRA</option>
                                    <option value="MARFA - MELLIEHA">MARFA - MELLIEHA</option>
                                    <option value="MARSA">MARSA</option>
                                    <option value="MARSASKALA">MARSASKALA</option>
                                    <option value="MARSAXLOKK">MARSAXLOKK</option>
                                    <option value="MDINA">MDINA</option>
                                    <option value="MELLIEHA">MELLIEHA</option>
                                    <option value="MGARR">MGARR</option>
                                    <option value="MOSTA">MOSTA</option>
                                    <option value="MQABBA">MQABBA</option>
                                    <option value="MSIDA">MSIDA</option>
                                    <option value="MTAHLEB - RABAT">MTAHLEB - RABAT</option>
                                    <option value="MTARFA">MTARFA</option>
                                    <option value="NAXXAR">NAXXAR</option>
                                    <option value="PACEVILLE - ST. JULIANS">PACEVILLE - ST. JULIANS</option>
                                    <option value="PAOLA">PAOLA</option>
                                    <option value="PEMBROKE">PEMBROKE</option>
                                    <option value="PIETA">PIETA</option>
                                    <option value="PWALES - ST. PAULS BAY">PWALES - ST. PAULS BAY</option>
                                    <option value="QAJJENZA - BIRZEBBUGA">QAJJENZA - BIRZEBBUGA</option>
                                    <option value="QAWRA - ST. PAULS BAY">QAWRA - ST. PAULS BAY</option>
                                    <option value="QORMI">QORMI</option>
                                    <option value="QRENDI">QRENDI</option>
                                    <option value="RABAT">RABAT</option>
                                    <option value="SAFI">SAFI</option>
                                    <option value="SALINA - NAXXAR">SALINA - NAXXAR</option>
                                    <option value="SAN GWANN">SAN GWANN</option>
                                    <option value="SAN MARTIN - ST. PAULS BAY">SAN MARTIN - ST. PAULS BAY</option>
                                    <option value="SAN PAWL TAT-TARGA - NAXXAR">SAN PAWL TAT-TARGA - NAXXAR</option>
                                    <option value="SANTA LUCIJA">SANTA LUCIJA</option>
                                    <option value="SANTA MARIA ESTATE - MELLIEHA">SANTA MARIA ESTATE - MELLIEHA</option>
                                    <option value="SANTA VENERA">SANTA VENERA</option>
                                    <option value="SENGLEA (L-ISLA)">SENGLEA (L-ISLA)</option>
                                    <option value="SIGGIEWI">SIGGIEWI</option>
                                    <option value="SLIEMA">SLIEMA</option>
                                    <option value="ST. JULIANS">ST. JULIANS</option>
                                    <option value="ST. PAULS BAY">ST. PAULS BAY</option>
                                    <option value="ST. PETERS - ZABBAR">ST. PETERS - ZABBAR</option>
                                    <option value="SWATAR - MSIDA">SWATAR - MSIDA</option>
                                    <option value="SWIEQI">SWIEQI</option>
                                    <option value="TA PARIS - BIRKIRKARA">TA PARIS - BIRKIRKARA</option>
                                    <option value="TA QALI - ATTARD">TA QALI - ATTARD</option>
                                    <option value="TA XBIEX">TA XBIEX</option>
                                    <option value="TAL-VIRTU - RABAT">TAL-VIRTU - RABAT</option>
                                    <option value="TARXIEN">TARXIEN</option>
                                    <option value="VALLETTA">VALLETTA</option>
                                    <option value="VITTORIOSA (BIRGU)">VITTORIOSA (BIRGU)</option>
                                    <option value="WARDIJA - ST. PAULS BAY">WARDIJA - ST. PAULS BAY</option>
                                    <option value="XEMXIJA - ST. PAULS BAY">XEMXIJA - ST. PAULS BAY</option>
                                    <option value="XGHAJRA">XGHAJRA</option>
                                    <option value="ZABBAR">ZABBAR</option>
                                    <option value="ZEBBIEGH - MGARR">ZEBBIEGH - MGARR</option>
                                    <option value="ZEBBUG">ZEBBUG</option>
                                    <option value="ZEJTUN">ZEJTUN</option>
                                    <option value="ZURRIEQ">ZURRIEQ</option>
                                   
                                </select>
                                
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblPostCode">Postcode:</label> 
                            </td>
                            <td>
                                <input type="text" name="postcode" id="postcode" value="<?php echo $postcode; ?>"></input>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                Gender: 
                            </td>
                            <td>
                            	<!-- gender functionality -->
                                <input type="hidden" name="gender">
                                <input type="radio" name="gender" value="1" <?php $_POST['gender'] = isset($_POST['gender']) ? $_POST['gender'] : 1?>/> Male</input>
	<input type="radio" name="gender" value="2" <?php $_POST['gender'] = isset($_POST['gender']) ? $_POST['gender'] : 2?>/> Female</input>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblLandline">Telephone No:</label> 
                            </td>
                            <td>
                                <input type="text" name="landline" id="landline" value="<?php echo $landline; ?>"></input>
                            </td>
                            <td align="right">
                                <label id="lblMobileNo">Mobile No:</label>
                            </td>
                            <td>
                                <input type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>"></input>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblEmail">Email:</label>
                            </td>
                            <td>
                                <input type="text" name="email" id="email" value="<?php echo $email; ?>"></input>*
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                            	<!-- inContact functionality -->
                                <label id="lblContact">In Contact List:</label>
                            </td>
                            <td>
                            	<input type="hidden" name="inContact" value="0">
                                <input type="checkbox" name="inContact" id="inContact" value="1" <?php $_POST['inContact'] = isset($_POST['inContact']) ? $_POST['inContact'] : 1?>/></input>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label id="lblDuration">Membership Duration:</label> 
                                <label id="lblDurationPrice"></label>  
                            </td>
                            <td>
                                <select id="duration" name="duration">
                                    <?php
                                    $membershipList = $db->getMembershipList();
                                    foreach ($membershipList as $q) {
                                        echo $q;
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
						<tr>
                            <td align="right">
                                <label id="lblEmail">Credit Card Type:</label>
                            </td>
                            <td>
                                <select id="cctype" name="cctype">
									<option value="Visa">VISA</option>
									<option value="MasterCard">MasterCard</option>
								</select>
                            </td>
                        </tr>
						
						
						<tr>
                            <td align="right">
                                <label id="lblEmail">Credit Card Number:</label>
                            </td>
                            <td>
                                <input type="text" name="ccnumber" id="ccnumber" value=""></input>
                            </td>
                        </tr>
						<tr>
                            <td align="right">
                                <label id="lblEmail">CVV2 Number:</label>
                            </td>
                            <td>
                                <input type="text" name="cvv2" id="cvv2" value=""></input>
                            </td>
                        </tr>
						<tr>
                            <td align="right">
                                <label id="lblEmail">Exp Date (mm/YYYY):</label>
                            </td>
                            <td>
                                <input type="text" name="expdate" id="expdate" value=""></input>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type="submit" name="submit" value="Submit" /></td>

                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" style="vertical-align:text-top; text-align: left;">
                                <p id="lblErrorMessage" style="color:red; vertical-align:text-top;"><?php echo $displayError; ?></p> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        <tr>
    </tr>
</table>


</body>
</html>
