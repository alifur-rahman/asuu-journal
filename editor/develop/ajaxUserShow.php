<?php
// Database Connection
require_once('../config.php');

// Reading value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchArray = array();

// Search
$searchQuery = " user_role=:reviewer OR user_role=:author";
if ($searchValue != '') {
    $searchQuery .= " AND (email LIKE :Searchvalue OR fname LIKE :Searchvalue OR onames LIKE :Searchvalue OR institution LIKE :Searchvalue OR rank LIKE :Searchvalue OR displine LIKE :Searchvalue OR user_role LIKE :Searchvalue ) ";
    $searchArray = array(
        'Searchvalue' => "%$searchValue%"
    );
}
$searchArray['reviewer'] = "Reviewer";
$searchArray['author'] = "Author";

// echo json_encode($searchQuery);
// exit;

// Total number of records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM ejournal_users WHERE user_role=? OR user_role=?");
$stmt->execute(array("Reviewer", "Author"));
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM ejournal_users WHERE  " . $searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

// Fetch records
$stmt = $pdo->prepare("SELECT * FROM ejournal_users WHERE  " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

// Bind values
foreach ($searchArray as $key => $search) {
    if (is_array($search)) {
        $stmt->bindValue(':' . $key, $search[0], PDO::PARAM_STR);
    } else {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }
}

$stmt->bindValue(':limit', (int) $row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int) $rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();



$ids = 1;
foreach ($empRecords as $row) {
    $actionsButtons = '<div class="d-flex justify-content-space-between gap-2 ">
                             <a href="delete-user.php?uid=' . $row['user_id'] . '" class="action-bbtn" onclick="alert(\'Are you Sure!\')"> <i class="fas fa-trash-alt"></i> </a>
                             <a href="#" class="action-bbtn" onclick="alert(\'Are you Sure!\')"> <i class="fas fa-pen"></i> </a>

                         </div>';

    $data[] = array(
        "fname" => $row['fname'] . " " . $row['onames'],
        "email" => $row['email'],
        "institution" => $row['institution'],
        "area_specialization" => $row['area_specialization'],
        "displine" => $row['displine'],
        "user_role" => $row['user_role'],
        "action" => $actionsButtons
    );
}

// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);