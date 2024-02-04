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

    .al_script_history_details {
        background: #e4e4e4;
        padding: 10px;
        border-radius: 6px;
        border-bottom: 1px solid;
        margin-bottom: 10px;
    }
</style>

<main class="main_content">
    <div class="container">

        <div class="row justify-content-center">

            <div class="journals_filter text-center" style="padding-top: 40px;">
                <?php
                if (isset($_REQUEST["error"])) {
                    echo '<div class="alert alert-danger" role="alert">
                                ' . $_REQUEST["error"] . '
                                </div>';
                }

                ?>

            </div>

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
                                            <div class="abstract_wrap" data-toggle="modal"
                                                data-target="#myModal-<?php echo $row['m_id']; ?>" type="button">
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
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="update_manuScript" data-toggle="modal"
                                                data-target="#UpdateModal-<?php echo $row['m_id']; ?>" type="button">
                                                <i class="fas fa-edit"></i> <span>Update</span>
                                            </div>



                                        </div>

                                        <!-- Modal -->

                                        <div class="modal fade" id="myModal-<?php echo $row['m_id']; ?>">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Manuscript History
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="al_suggested_reviwer_wrapper">
                                                            <?php
                                                            $stm = $pdo->prepare("SELECT * FROM manu_histoory WHERE m_id=?");
                                                            $stm->execute(array($row['m_id']));
                                                            $sug_rev = $stm->fetchAll(PDO::FETCH_ASSOC);
                                                            if (!empty($sug_rev)):
                                                                $rID = 1;
                                                                foreach ($sug_rev as $sug)
                                                                : ?>
                                                                    <div class="al_script_history_details">
                                                                        <div class="d-flex justify-content-between"
                                                                            style="margin-bottom: 10px;">
                                                                            <div class="pdf_wrap" style="margin:0;">

                                                                                <a href="/asuu-journal/author/<?php echo $sug['asset']; ?>"
                                                                                    download="<?php echo $sug['asset']; ?>"
                                                                                    style="font-size:16px"> <i
                                                                                        class="fas fa-download    "></i>
                                                                                    (Download)</a>
                                                                            </div>
                                                                            <div class="al_date_show" style="font-size:13px">
                                                                                <?php
                                                                                if (isset($sug['create_at'])) {
                                                                                    $timestamp = strtotime($sug['create_at']);
                                                                                    if ($timestamp !== false) {
                                                                                        echo date("d-M-Y", $timestamp);
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </div>

                                                                        </div>
                                                                        <div class="al_user_show" style="font-size:14px">
                                                                            By :
                                                                            <?php
                                                                            $stmuser = $pdo->prepare("SELECT * FROM ejournal_users WHERE user_id=?");
                                                                            $stmuser->execute(array($sug['u_id']));
                                                                            $user = $stmuser->fetchAll(PDO::FETCH_ASSOC);
                                                                            echo $user[0]['fname'] . " " . $user[0]['onames'];
                                                                            ?>
                                                                        </div>
                                                                    </div>

                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <p class="text-info">There have no History at this moment </p>
                                                            <?php endif; ?>


                                                        </div>

                                                    </div>
                                                    <img class="popLoading" id="popLoadingReviwer" src="img/source.gif"
                                                        alt="loding">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end modal  -->

                                        <!-- UpdateModal -->

                                        <div class="modal fade" id="UpdateModal-<?php echo $row['m_id']; ?>">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Update Manuscript
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="update_manuscript.php" method="post"
                                                            enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="box">
                                                                    <input type="file" name="Upload"
                                                                        id="file-<?php echo $row['m_id']; ?>"
                                                                        class="inputfile inputfile-2 d-none"
                                                                        data-multiple-caption="{count} files selected" multiple />
                                                                    <label for="file-<?php echo $row['m_id']; ?>"
                                                                        class="form-control"><svg xmlns="#" width="20" height="17"
                                                                            viewBox="0 0 20 17">
                                                                            <path
                                                                                d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z" />
                                                                        </svg> <span>Choose a Word Doc file&hellip;</span></label>

                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="u_id" value="<?php echo $user_id; ?>">
                                                            <input type="hidden" name="m_id" value="<?php echo $row['m_id']; ?>">

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" id="popsubmit" name="modal_submit"
                                                                    class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <img class="popLoading" id="popLoading" src="img/source.gif" alt="loding">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end modal  -->



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