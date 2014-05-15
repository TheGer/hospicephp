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
        $incontact = NULL; // $_POST[''];
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




// Don't post the form until the submit button is pressed.
$requiredFields = array('name', 'surname', 'idcard', 'address', 'street', 'locality', 'email');    // Add the 'name' for all required fields to this array
$errors = false;
if (isset($_POST['submit'])) {
    // Clean all inputs
    array_walk($_POST, 'check_input');

    // Loop over requiredFields and output error if any are empty
    foreach ($requiredFields as $r) {
        if (strlen($_POST[$r]) == 0) {
            $errors = true;
            $error_css = 'background-color:red';
            $displayError = "" . $displayError . "<br />• " . $r . " cannot be empty.";
        }
        if ($r == 'name') {
            //Checks that name does not contain any integers
            if (strcspn($_POST[$r], '0123456789') != strlen($_POST[$r])) {
                $errors = true;
                $error_css = 'background-color:red';
                $displayError = $displayError . '<br />• ' . $r . ' cannot contain numbers.';
            }
        }
        if ($r == 'surname') {
            //Checks that surname does not contain any integers
            if (strcspn($_POST[$r], '0123456789') != strlen($_POST[$r])) {
                $errors = true;
                $error_css = 'background-color:red';
                $displayError = $displayError . '<br />• ' . $r . ' cannot contain numbers.';
            }
        }
        if ($r == 'idcard') {
            //Checks that idcard last letter must be a letter
            if (!(strcspn(substr($_POST[$r], -1), '0123456789') != strlen(substr($_POST[$r], -1)))) {
               // $errors = true;
                //$error_css = 'background-color:red';
                //$displayError = $displayError . "<br />• " . $r . ' should be in a correct format e.g. (1234A).';
            }
        }
    }


    if (strlen($_POST['mobile']) != 0) {
        //Checks that mobile does not contain any letters
        if (!(strcspn($_POST['mobile'], '0123456789') != strlen($_POST['mobile']))) {
            $errors = true;
            $error_css = 'background-color:red';
            $displayError = $displayError . '<br />• ' . $r . ' cannot contain letters.';
        }
    }
    if (strlen($_POST['landline']) != 0) {
        //Checks that mobile does not contain any letters
        if (!(strcspn($_POST['landline'], '0123456789') != strlen($_POST['landline']))) {
            $errors = true;
            $error_css = 'background-color:red';
            $displayError = $displayError . '<br />•' . $r . ' cannot contain letters.';
        }
    }
    if (strlen($_POST['email']) != 0) {
        if (preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/', $_POST['email'])) {
            $errors = true;
            $error_css = 'background-color:red';
            $displayError = $displayError . '<br />•' . $r . ' should be valid (example@email.com).';
        }
    }

$title = strip_tags($_POST['title']);
        $name = strip_tags($_POST['name']);
        $surname = strip_tags($_POST['surname']);
        $dateOfBirth = strip_tags($_POST['dob']);
        $address = strip_tags($_POST['address']);
        $street = strip_tags($_POST['street']);
        $gender = strip_tags($_POST['gender']);
        $locality = strip_tags($_POST['locality']);
        $postcode = strip_tags($_POST['postcode']);
        $idcard = strip_tags($_POST['idcard']);
        $landline = strip_tags($_POST['landline']);
        $mobile = strip_tags($_POST['mobile']);
        $email = strip_tags($_POST['email']);
        $incontact = NULL; // $_POST[''];
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
        // no errors   name, surname, idcard, address, street, locality, email
        
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
			
			$addedSuccess = getPayment($db->getMembershipNameByID($duration),$db->getMembershipPriceByID($duration),$cctype,$ccnumber,$cvv2,$expdate,$name,$surname,$street,$locality);
			
			
 
 
			if ($addedSuccess) {
			
				//db code to add member
                
				//insert into payments
			
				//insert into memberships
			
			$addedSuccess = $db->addNewMember($title, $name, $surname, $address, $street, $locality, $postcode, $idcard, $gender, $landline, $mobile, $email, $incontact, false);
			
				
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
                                <input type="text" name="dob" id="dob" value="<?php echo $dateOfBirth; ?>"></input>
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
                                <label id="lblLocality">Locality:</label> 
                            </td>
                            <td>
                                <input type="text" name="locality" id="locality" value="<?php echo $locality; ?>"></input>*
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
                                <input type="radio" name="gender" id="gender" value="male">Male<br>
                                <input type="radio" name="gender" id="gender" value="female">Female
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
                                <input type="text" name="email" id="email" value="<?php echo $email; ?>"></input>
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
        </tr>
        <tr>
    </tr>
</table>


<!--
<form method="post" id="frmMembershipPayment" action="https://www.paypal.com/cgi-bin/webscr" target="paypal">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="business" value="hospice@email.com">
    <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($membershipType); ?>" />
    <input type="hidden" name="item_number" value="">
    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($membershipCost); ?>" > 
    <input type="hidden" name="currency_code" value="EUR">
    <input type="hidden" name="shipping" value="">
    <input type="hidden" name="shipping2" value="">
    <input type="hidden" name="handling_cart" value="">
    <input type="hidden" name="bn"  value="ButtonFactory.PayPal.001">
    <input type="image" name="add" src="http://www.powersellersunite.com/buttonfactory/x-click-but23.gif">
</form>


-->
</body>
</html>
