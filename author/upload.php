<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../login/include/db2.php');
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();
$mail->Encoding = "base64";
$mail->SMTPAuth = true;
$mail->Host = "smtp.zeptomail.com";
$mail->Port = 587;
$mail->Username = "emailapikey";
$mail->Password = 'wSsVR60k/h/zCal/zTf/Lu07nlpVVV+jFxx1iQPyunP6Sq3F9sdpwxfKUQb0GPdJEWBrHGZHrb8smxYBhzQO2tokn1kJDiiF9mqRe1U4J3x17qnvhDzDWG9elxCLKoMAxgpsmWhlEs4n+g==';
$mail->SMTPSecure = 'TLS';
$mail->isSMTP();
$mail->IsHTML(true);
$mail->CharSet = "UTF-8";
$mail->From = "noreply@asuu.org.ng";
$mail->addAddress($_SESSION['username']);
$mail->Subject = "Manuscript Submission";
$mail->SMTPDebug = 2;
$fname = $_SESSION['username'];
$mail->Debugoutput = function ($str, $level) {
   echo "debug level $level; message: $str";
   echo "<br>"; };
$message = '<html><head> <title>ASUU - Journal</title> </head><body>';
$message .= '<h1>Hello, ' . $fname . '!</h1>';
$message .= '<p> Thank you for Getting in Touch Academic Staff Union of Universities E-journal, Your Manuscript has been submited successfully.' . '</p>';
'<br>';
$message .= '<p>Thank you, <br> Ejournal Academic Staff Union of Universities</p>';
$message .= "</body></html>";
$mail->MsgHTML($message);

$db = mysqli_connect("localhost", "root", "", "asuu_database");
// Check connection
if (mysqli_connect_errno()) {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_FILES['Upload'])) {
   $type = $_POST['ty'];
   $title = $_POST['title'];
   $displine = $_POST['displine'];

   $r_tittles = $_POST['r_tittles'];
   $r_names = $_POST['r_names'];
   $r_emails = $_POST['r_emails'];
   $r_phones = $_POST['r_phones'];
   $r_address = $_POST['r_address'];
   $r_reason = $_POST['r_reason'];

   $combinedData = array();

   // Combine titles and names into an array of objects
   for ($i = 0; $i < count($r_tittles); $i++) {
      $combinedData[] = array(
         'r_tittle' => $r_tittles[$i],
         'r_name' => $r_names[$i],
         'r_email' => $r_emails[$i],
         'r_phone' => $r_phones[$i],
         'r_address' => $r_address[$i],
         'r_reason' => $r_reason[$i]
      );
   }



   //------------------------------------//
   $errors = array();
   $file_name = $_FILES['Upload']['name'];
   $file_size = $_FILES['Upload']['size'];
   $file_tmp = $_FILES['Upload']['tmp_name'];
   $file_type = $_FILES['Upload']['type'];
   $file_name_parts = explode('.', $_FILES['Upload']['name']);
   $file_ext = strtolower(end($file_name_parts));


   $extensions = array("doc", "docx");

   if (in_array($file_ext, $extensions) === false) {
      $errors[] = "extension not allowed, please choose a Docx or Doc file.";
   }

   if ($file_size > 909715200) {
      $errors[] = 'File size must be excately 2 MB';
   }

   if (empty($errors) == true) {
      move_uploaded_file($file_tmp, "manuscripts_docs/" . $file_name);
      $f_url = 'manuscripts_docs/' . $file_name;
      $status = 'Submited';
      $date = date("Y/m/d");


      $sql = 'INSERT INTO manuscripts_docs (fname,email,article_tittle,category,status,date_s,f_url,displine)
     VALUES ("' . $_SESSION['fname'] . '","' . $_SESSION['username'] . '","' . $title . '","' . $type . '","' . $status . '","' . $date . '","' . $f_url . '","' . $displine . '")';

      if ($db->query($sql) === TRUE) {
         $lastID = $db->insert_id;

         if ($lastID) {
            foreach ($combinedData as $key => $value) {
               $r_tittle = $value['r_tittle'];
               $r_name = $value['r_name'];
               $r_email = $value['r_email'];
               $r_phone = $value['r_phone'];
               $r_address = $value['r_address'];
               $r_reason = $value['r_reason'];

               $sql = 'INSERT INTO suggested_reviwer (manu_id,r_tittle,r_name,r_email,r_phone,r_address,r_reason)
               VALUES ("' . $lastID . '","' . $r_tittle . '","' . $r_name . '","' . $r_email . '","' . $r_phone . '","' . $r_address . '","' . $r_reason . '")';
               $db->query($sql);
               // send email for success 
               $mail->send();
               header("Location:/author/submit_manuscript.php");
               $_SESSION["message"] = "Manuscript Submitted Successfully !!";
            }

         }

      } else {
         header("Location:/author/submit_manuscript.php");
         $_SESSION["message2"] = 'Format not supported, please choose MS word ';
         echo "Error: " . $sql . "<br>" . $db->error;
      }
   }
}
?>