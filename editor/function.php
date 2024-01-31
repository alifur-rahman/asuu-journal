<?php require_once('config.php');

function user_details($condi)
{
     $alif = $pdo->prepare("SELECT name FROM ejournal_users WHERE user_id=?");
     $alif->execute(array($condi));
     $dbresult = $alif->fetchAll(PDO::FETCH_ASSOC);
     return $dbresult;
}


// function getAllSuggestedReviewersById($m_id) {
//      $alif = $pdo->prepare("SELECT * FROM suggested_reviewers WHERE manu_id=?");

// }

echo user_details('44');

?>