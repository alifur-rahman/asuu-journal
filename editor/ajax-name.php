<?php require_once('config.php');

$areaSpe = $_GET['areaSpe'];

$alif = $pdo->prepare("SELECT fname,onames,user_id FROM ejournal_users WHERE displine=? AND user_role=? ");
$alif->execute(array($areaSpe, 'Reviewer'));
$result = $alif->fetchAll(PDO::FETCH_ASSOC);

// $array = array_column($result, 'fname', 'mName' , 'lname');




echo json_encode($result);

?>