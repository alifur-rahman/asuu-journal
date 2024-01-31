<?php
require_once('config.php');
require_once('header.php');
$user_email = $_SESSION['username'];
$sgts = $pdo->prepare("SELECT user_id FROM ejournal_users WHERE email=?");
$sgts->execute(array($user_email));
$UserArrar = $sgts->fetchAll(PDO::FETCH_ASSOC);
$user_id = $UserArrar[0]['user_id']
    ?>


<style>
    .status_sty {
        padding: 0 15px;
        border-radius: 50px;
        font-size: 15px;
        color: #fff;
        text-transform: capitalize;
    }

    .submitted {
        background: #ffa700;
    }

    .under_review {
        background: purple;
    }

    .minor_correction {
        background: blue;
    }

    .major_correction {
        background: brown;
    }

    .accepted {
        background: green;
    }

    .rejected {
        background: red;
    }



    .fixed-top {
        z-index: 0;
    }

    #main_header {
        z-index: 3;
    }

    .modal-backdrop {
        z-index: 5;
    }

    .main_content {
        z-index: inherit;
    }


    .popLoading {
        position: absolute;
        top: 35%;
        left: 35%;
        width: 100px;
        height: U;
        display: none;
    }
</style>

<main class="main_content">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">

                    <?php
                    $limit = 4;
                    if (isset($_REQUEST['page'])) {
                        $page = $_REQUEST['page'];
                    } else {
                        $page = 1;
                    }
                    $offset = ($page - 1) * $limit;


                    $stmd = $pdo->prepare("SELECT * FROM assigned_script WHERE u_id=? ORDER BY m_id DESC LIMIT $offset, $limit");
                    $stmd->execute(array($user_id));
                    $postCountd = $stmd->rowCount();
                    if ($postCountd == 0) {
                        echo "<h3 style='font-size:30px;color:#f00;'>" . "There are No Any post!";
                    } else {
                        $resultOfAssigned = $stmd->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($resultOfAssigned as $row):
                            $m_id = $row['m_id'];
                            $stm = $pdo->prepare("SELECT * FROM manuscripts_docs WHERE m_id=? ORDER BY m_id DESC LIMIT $offset, $limit");
                            $stm->execute(array($m_id));
                            $postCount = $stm->rowCount();
                            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row):
                                ?>
                                <div class="col-12">
                                    <div class="list_wrapper">
                                        <div class="list_title">
                                            <h4>
                                                <?php echo $row['article_tittle']; ?>
                                            </h4>
                                        </div>

                                        <div class="listed_tags">
                                            <ul class="list-group list-group-horizontal flex-wrap">
                                                <li class="list-group-item">
                                                    <?php echo $row['fname']; ?>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- yearly info -->
                                        <div class="other_info my-1">
                                            <ul class="list-group list-group-horizontal flex-wrap">
                                                <li class="list-group-item">Date:
                                                    <?php echo $row['date_s']; ?>
                                                </li>
                                                <li class="list-group-item">Type:
                                                    <?php echo $row['category']; ?>
                                                </li>
                                                <li class="list-group-item border-0">Publisher:Academic Staff Union of Universities
                                                    (ASUU)</li>
                                                <li class="list-group-item border-0">



                                                </li>

                                            </ul>
                                        </div>


                                        <div class="pdf_section">
                                            <div class="abstract_wrap" data-toggle="modal" data-target="#myModal" type="button">
                                                <i class="fas fa-caret-right"></i> <span>Manuscript History</span>
                                            </div>

                                            <div class="pdf_wrap">
                                                <a href="/asuu-journal/author/<?php echo $row['f_url']; ?>"
                                                    download="<?php echo $row['f_url']; ?>"> <i class="fas fa-download    "></i>
                                                    (Download)</a>
                                            </div> &nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="status">
                                                <strong>Status:&nbsp;</strong>
                                                <?php
                                                $status = $row['status'];
                                                if ($status == 'Submited') {
                                                    echo "<span class='status_sty submitted'>" . $status . "</span>";
                                                } else if ($status == 'under review') {
                                                    echo "<span class='status_sty under_review'>" . $status . "</span>";
                                                } else if ($status == 'minor corrections') {
                                                    echo "<span class='status_sty minor_correction'>" . $status . "</span>";
                                                } else if ($status == 'major corrections') {
                                                    echo "<span class='status_sty major_correction'>" . $status . "</span>";
                                                } else if ($status == 'accepted') {
                                                    echo "<span class='status_sty accepted'>" . $status . "</span>";
                                                } else if ($status == "rejected") {
                                                    echo "<span class='status_sty rejected'>" . $status . "</span>";
                                                }

                                                ?>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="listed_tags">
                                                <ul class="list-group list-group-horizontal flex-wrap">


                                                    <li class="list-group-item">

                                                        <select _ngcontent-nqw-c11="" class="custom-select"
                                                            id="urlSelect<?php echo $a; ?>" onchange="updateStatus(this)"><!---->

                                                            <?php $mid = $row['m_id']; ?>
                                                            <option _ngcontent-nqw-c11="" value="#" selected>Update Status </option>

                                                            <option _ngcontent-nqw-c11=""
                                                                value="update_status.php?upt=1&mid=<?php echo $mid; ?>">Submitted
                                                            </option>

                                                            <option _ngcontent-nqw-c11=""
                                                                value="update_status.php?upt=2&mid=<?php echo $mid; ?>">Under Review
                                                            </option>

                                                            <option _ngcontent-nqw-c11=""
                                                                value="update_status.php?upt=3&mid=<?php echo $mid; ?>">Minor
                                                                Corrections</option>

                                                            <option _ngcontent-nqw-c11=""
                                                                value="update_status.php?upt=4&mid=<?php echo $mid; ?>">Major
                                                                Corrections</option>

                                                            <option _ngcontent-nqw-c11=""
                                                                value="update_status.php?upt=5&mid=<?php echo $mid; ?>">Accepted
                                                            </option>

                                                            <option _ngcontent-nqw-c11=""
                                                                value="update_status.php?upt=6&mid=<?php echo $mid; ?>">Rejected
                                                            </option>


                                                        </select>


                                                </ul>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; endforeach;

                    } ?>
                </div>



                <!-- ////// php pagination ///////-->

                <?php

                $stmd = $pdo->prepare("SELECT * FROM assigned_script WHERE u_id=? ORDER BY m_id DESC LIMIT $offset, $limit");
                $stmd->execute(array($user_id));

                $postCountd = $stmd->rowCount();
                if ($postCountd !== 0) {
                    $resultOfAssigned = $stmd->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($resultOfAssigned as $row) {
                        $m_id = $row['m_id'];
                        $stm = $pdo->prepare("SELECT * FROM manuscripts_docs WHERE m_id=? ORDER BY m_id DESC LIMIT $offset, $limit");
                        $stm->execute(array($m_id));
                        $Ubooks = $stm->rowCount();
                        // $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                
                        // Process each assigned script or do other tasks here
                
                    }

                    // Pagination section
                    $total_record = $postCountd;
                    $total_page = ceil($total_record / $limit);

                    echo '<ul class="pagination justify-content-center">';
                    if ($page > 1) {
                        echo ' <li class="page-item"><a class="page-link" href="assigned_script.php?page=' . ($page - 1) . '">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($i == $page) {
                            $active = "active";
                        } else {
                            $active = "";
                        }
                        echo '<li class="page-item"><a class="page-link' . " " . $active . '" href="assigned_script.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo '<li class="page-item"><a class="page-link" href="assigned_script.php?page=' . ($page + 1) . '">Next</a></li>';
                    }
                    echo '</ul>';
                }
                ?>







            </div>




        </div>

</main>

<?php
require_once('footer.php');
?>

<script>
    function updateStatus(element) {
        window.location.href = element.value;
    }
</script>