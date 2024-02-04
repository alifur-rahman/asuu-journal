<?php require_once('config.php');
if (isset($_FILES['Upload'])) {
    $updateMane = $_FILES['Upload'];
    $u_id = $_POST['u_id'];
    $m_id = $_POST['m_id'];
    $file_name = $_FILES['Upload']['name'];
    $file_size = $_FILES['Upload']['size'];
    $file_tmp = $_FILES['Upload']['tmp_name'];
    $file_type = $_FILES['Upload']['type'];
    $file_exted = explode('.', $_FILES['Upload']['name']);
    $file_ext = strtolower(end($file_exted));
    if ($file_name == "") {
        header("Location:/asuu-journal/reviewer/assigned_script.php?error=Please Attech your Manuscript  ddd!!");
    }

    $extensions = array("doc", "docx");

    if (in_array($file_ext, $extensions) === false) {
        $errors = "extension not allowed, please choose a Docx or Doc file swd.";
        header("Location:/asuu-journal/reviewer/assigned_script.php?error=" . $errors);
    } else if ($file_size > 909715200) {
        $errors = "File size must be excately 2 MB";
        header("Location:/asuu-journal/reviewer/assigned_script.php?error=" . $errors);
    } else {
        move_uploaded_file($file_tmp, "manuscripts_docs/" . $file_name);
        $f_url = 'manuscripts_docs/' . $file_name;
        echo $f_url;

        $stm = $pdo->prepare("INSERT INTO manu_histoory (m_id, u_id,asset) VALUES (?, ?, ?)");
        $stm->execute(array($m_id, $u_id, $f_url));
        header("Location:/asuu-journal/reviewer/assigned_script.php?success=Submitted Successfully !!");

    }


} else {
    header("Location:/asuu-journal/reviewer/assigned_script.php?error=Please Attech your Manuscript !!");

}
