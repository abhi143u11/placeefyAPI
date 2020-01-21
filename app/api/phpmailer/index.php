<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include "class.phpmailer.php";
 require 'src/Exception.php';
 require 'src/PHPMailer.php';
 require 'src/SMTP.php';
// //Load Composer's autoloader
 require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$html = '<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style>
		.clear{clear:both;}
	</style>
</head>
<body>
	<div class="" style="width: 700px; margin: 0 auto;">
		<div class="logo_class_div">
			<img src="rasthetique.png" style="width: 240px;">
		</div>
		<div class="main_heading_div_class">
			<center><h2 style="font-size: 16px;">Here is copy of your invoice</h2></center>
		</div>
		<div class="left_heading_div_class">
			<p style="padding-left: 9px;font-size: 16px;">Here is copy of your invoice</p>
		</div>
		<div class="left_heading_div_class_second">
			<p style="padding-left: 9px;font-size: 16px;">This is final invoice of your booking with the artist on date-------& time-------</p>
		</div>
		<div class="" style="width: 100%;">
			<div class="" style="width: 48%; float: left;">
				<p style="padding-left: 10px;font-size: 16px;">Red Beat Enterprises</p>
				<p style="padding-left: 10px;font-size: 16px;">No.3374, 2nd cross, 7th Main RPC Layout</p>
				<p style="padding-left: 10px;font-size: 16px;">Vijaynagar 2nd Stage, Banglore - 560040</p>
				<p style="padding-left: 10px;font-size: 16px;"><b>GST No : 23AQMPN8666RIZE</b></p>
				<p style="padding-left: 10px;font-size: 16px;"><b>Invoice No : ---------</b></p>
			</div>
			<div class="" style="width: 48%;float: left;">
				<p style="font-size: 16px;text-align: center;">Billing Address : </p>
				<p style="font-size: 16px;text-align: right;">XYZ--------------------------------------------------</p>
				<p style="font-size: 16px;text-align: right;">---------------------------------------------------------</p>
				<p style="font-size: 16px;text-align: right;">---------------------------------------------------------</p>
				<p style="font-size: 16px;text-align: right;"><b>Invoice Date : ---------------------------------</b></p>
				<p style="font-size: 16px;text-align: right;"><b>Order No : ------------------------------</b></p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="table_div" style="width: 100%;">
			<table class="table" style="width: 100%;">
				<thead>
					<tr class="heading_tr">
						<th style="border-top: 2px solid #ccc!important;border-bottom: none!important;    padding: 24px!important;border-collapse: collapse;">Service</th>
						<th style="border-top: 2px solid #ccc!important;border-bottom: none!important;    padding: 24px!important;border-collapse: collapse;">Qty.</th>
						<th style="border-top: 2px solid #ccc!important;border-bottom: none!important;    padding: 24px!important;border-collapse: collapse;">Amout</th>
						<th style="border-top: 2px solid #ccc!important;border-bottom: none!important;    padding: 24px!important;border-collapse: collapse;">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr class="content_tr">
						<td style="border-bottom: 2px solid #ccc;border-top: none!important;padding: 24px!important;border-collapse: collapse;">Cristime valmy Facial Advance (Radiance)</td>
						<td style="border-bottom: 2px solid #ccc;border-top: none!important;padding: 24px!important;border-collapse: collapse;">1</td>
						<td style="border-bottom: 2px solid #ccc;border-top: none!important;padding: 24px!important;border-collapse: collapse;">1999.00</td>
						<td style="border-bottom: 2px solid #ccc;border-top: none!important;padding: 24px!important;border-collapse: collapse;">1999.00</td>
					</tr>
					<tr class="amount_tr">
						<td colspan="3" style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">Subtotal</td>
						<td style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">1,999.00</td>
					</tr>
					<tr class="amount_tr">	
						<td colspan="3" style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">Discount</td>
						<td style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">1,999.00</td>
					</tr>
					<tr class="amount_tr">	
						<td colspan="3" style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">Price & Discount</td>
						<td style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">1,999.00</td>
					</tr>
					<tr class="amount_tr">	
						<td colspan="3" style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">SGST 9%</td>
						<td style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">1,999.00</td>
					</tr>
					<tr class="amount_tr">	
						<td colspan="3" style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">CGST 9%</td>
						<td style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">1,999.00</td>
					</tr>
					<tr class="amount_tr">	
						<td colspan="3" style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">Total Tax</td>
						<td style="padding-left: 24px!important; padding-top: 15px;border: none!important;border-collapse: collapse;">1,999.00</td>
					</tr>
					<tr class="total_amout_payable">
						<td colspan="3" style="border-top: 2px solid #ccc!important;border-bottom: 2px solid #ccc;padding: 10px 24px!important;">Totalo Amount Payable</td>
						<td style="border-top: 2px solid #ccc!important;border-bottom: 2px solid #ccc;    padding: 10px 24px!important;">1,999.00</td>
					</tr>
					
				</tbody>
			</table>
			<p style="padding: 10px 24px;border-bottom: 2px solid #ccc;">Amount in words : One Thousand Nintey Hundread Ninety Nine Only</p>
		</div>
		<div class="footer_div_start">
			<center><p>This is computer generated invoice does not require signature</p></center>
			<center><p>Copyright-----------------Red Beat Enterprises | All rights reserved.</p></center>
		</div>
	</div>
</body>
</html>';


// $dompdf = new Dompdf();
// $dompdf->loadHtml($html);
// $dompdf->setPaper('A4','portrait');
// $dompdf->render();
//$invoice = "invoice_".date('m-d-Y').".pdf";

//$html = ob_get_contents();
//ob_get_clean();
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$output = $dompdf->output();
$filename = "invoice-".date('d-m-Y').".pdf";
file_put_contents($filename, $output);
//$dompdf->stream("invoice.pdf");

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
          //Server settings
          //echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n"; 
          $mail->SMTPDebug = 1;                                 // Enable verbose debug output smtpout.secureserver.net
          $mail->isSMTP();                                      // Set mailer to use SMTP
          $mail->Host = 'localhost';                                           // Specify main and backup SMTP servers
           $mail->SMTPAuth = false;                              // Enable SMTP authentication
           $mail->Username = 'care@rasthetique.com';                            // SMTP username
           $mail->Password = 'rasthetique@123';                                // SMTP password
          $mail->SMTPSecure = false;                           // Enable TLS encryption, `ssl` also accepted
           $mail->Port = 25;                                   // TCP port to connect to

          //$mail->Host = 'relay-hosting.secureserver.net';
//           $mail->Port = 25;
//           $mail->SMTPAuth = false;                               // Enable SMTP authentication
//           $mail->Username = 'care@rasthetique.com';                 // SMTP username
//          $mail->Password = 'rasthetique@123';                           // SMTP password               
//           $mail->SMTPSecure = false;     
          // $mail->SMTPOptions = array(
          //           'ssl' => array(
          //           'verify_peer' => false,
          //           'verify_peer_name' => false,
          //           'allow_self_signed' => true
          //           )
          //);
  
            // $smtp = Mail::factory('smtp', array(
            //     'host'  =>  "rasthetique.com",
            //     'port'  => "25",
            //     'auth'  => true,
            //     'username'  => "care@rasthetique.com",
            //     'password' => "rasthetique@123",
            //     'auth' => "PLAIN",
            //     'socket_options' => array('ssl' => array('verify_peer_name' => false))
            // ));
          // $mail->Host = 'relay-hosting.secureserver.net';
          // $mail->Port = 25;
          // $mail->SMTPAuth = false;
          // $mail->SMTPSecure = false;   
          // $mail->Username = 'care@rasthetique.com';                 // SMTP username
          // $mail->Password = 'rasthetique@123';                             // TCP port to connect to

                    //Recipients
                    $name= "pratik tambekar";
                    $emailid = 'pratik.tambekar91@gmail.com';
                    $mail->setFrom('care@rasthetique.com', 'care@rasthetique');
                    $mail->addAddress($emailid, $name);     // Add a recipient
                    $mail->addAddress($emailid);               // Name is optional
                    $mail->addReplyTo($emailid, 'Information');
                    $mail->addCC('info@rasthetique.com');
                    $mail->addBCC('info@rasthetique.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    //$mail->addStringAttachment($output, $filename);
    $mail->addAttachment($filename);

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Booking Summary';
    $mail->Body    = $html;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
   // echo 'Message has been sent';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}