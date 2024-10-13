<?php
require_once('../TCPDF/tcpdf_import.php');
require_once('PHPMailer-5.2.13/class.phpmailer.php');
require_once('PHPMailer-5.2.13/class.smtp.php');
require_once('PHPMailer-5.2.13/PHPMailerAutoload.php');

$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$method = isset($_POST['method']) ? $_POST['method'] : '';

/*----------------qrcode start----------------------*/
include('phpqrcode/qrlib.php');

    // how to save PNG codes to server
    
    $tempDir =  "qrcode/";
    
    $codeContents = 'http://140.138.77.70/~s1113318/web_hw2/pdf/'.$name.'.pdf';
    
    // we need to generate filename somehow, 
    // with md5 or with database ID used to obtains $codeContents...
    $fileName = $name.'.png';
    
    $pngAbsoluteFilePath = $tempDir.$fileName;
   
    // generating
    if (!file_exists($pngAbsoluteFilePath)) 
    {
        QRcode::png($codeContents, $pngAbsoluteFilePath,'H',6);
    }
    
/*----------------qrcode end------------------------*/
/*---------------- Sent Mail Start -----------------*/
$mail= new PHPMailer;                                                                                 
$mail->IsSMTP();                                                                                        
$mail->SMTPAuth = true;                                                                                
$mail->SMTPSecure = 'STARTTLS';                                                                              
$mail->Host = 'smtp.office365.com';                                                                        
$mail->Port = 587;                                                                                     
$mail->Username = ('s1113318@mail.yzu.edu.tw');                                                             
$mail->Password = 'zxc901012';
$mail->CharSet = 'utf8';
$mail->DEBUG = 3;

$mail->SetFrom('s1113318@mail.yzu.edu.tw');
$mail->AddAddress(isset($_POST['email']) ? $_POST['email'] : '');
$mail->isHTML(true);
                                                                 
$mail->Subject = '主題';
$mail->Body= "方案: " . $method . "<br \>姓名: " . $name . "<br \>Email: " . $email . "<br \>手機號碼: " . $phone ."<br \>地址: " .$address . "";
$mail->AddAttachment('/home/s1113318/public_html/web_hw2/qrcode/'.$name.'.png');
$mail->send();
/*---------------- Sent Mail End -------------------*/
/*---------------- Print PDF Start -----------------*/
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetFont('cid0jp','', 18); 
$pdf->AddPage();

$html= <<<EOF
<h2>Ru's Piano 會員募資計畫 匯款單</h2>
<table border="1">
	<tr>
		<td>姓名</td>
		<td>$name</td>
		<td>電話</td>
		<td>$phone</td>
    <td>方案</td>
    <td style ="color:rgb(225,0,0);">$method</td>
  </tr>
	<tr>
		<td>Email</td>
		<td style="font-family : corier" colspan="5">$email</td>
    </tr>
    <tr>
		<td>商品寄送地址</td>
		<td style="font-family : corier" colspan="5">$address</td>
	</tr>
</table>
EOF;
/*---------------- Print PDF End -------------------*/

$pdf->writeHTML($html);
$pdf->lastPage();
$pdf->Output('/home/s1113318/public_html/web_hw2/pdf/'.$name.'.pdf', 'F');
$pdf->Output('order.pdf', 'I');
?>