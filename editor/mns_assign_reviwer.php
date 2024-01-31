<?php require_once('config.php');

$m_id = $_POST['m_id'];
$reviwerid = $_POST['reviwer_id'];

$stm = $pdo->prepare("SELECT id FROM assigned_script WHERE m_id=?");
$stm->execute(array($m_id));
$count = $stm->rowCount();

if ($count == 0) {
    $alif = $pdo->prepare("INSERT INTO assigned_script (m_id,u_id) VALUES (?,?)");
    $alif->execute(array($m_id, $reviwerid));

} else {
    $alif = $pdo->prepare("UPDATE assigned_script SET u_id=? WHERE m_id=?");
    $alif->execute(array($reviwerid, $m_id));
}

$stUpdate = $pdo->prepare("UPDATE manuscripts_docs SET status=? WHERE m_id=?");
$stUpdate->execute(array('under review', $m_id));

header('location:mns_tracking.php');

