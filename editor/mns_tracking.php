<?php
require_once('header.php');
// session_start();
// if (!isset($_SESSION['username'])){ 
//   header("Location: /login");
// }
$user_email = $_SESSION['username'];
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
			<div class="col-lg-8 col-md-10">
				<div _ngcontent-nqw-c5="" class="search-bar-container fill-background not-homepage">
					<xpl-search-bar-migr _ngcontent-nqw-c5="" _nghost-nqw-c11="">
						<div _ngcontent-nqw-c11="" class="search-bar">

							<form _ngcontent-nqw-c11="" class="search-bar-wrapper ng-untouched ng-pristine ng-valid"
								novalidate="">

								<div _ngcontent-nqw-c11="" class="drop-down">
									<label _ngcontent-nqw-c11="">
										<select _ngcontent-nqw-c11="" class="custom-select"
											aria-label="content type dropdown"><!---->
											<option _ngcontent-nqw-c11="">All</option>
											<option _ngcontent-nqw-c11="">Journal of Humanities</option>
											<option _ngcontent-nqw-c11="">Journal Social Sciences</option>
											<option _ngcontent-nqw-c11="">Journal of Science</option>
											<option _ngcontent-nqw-c11="">Magazines</option>
										</select>
									</label>
								</div>

								<div _ngcontent-nqw-c11="" class="search-field all">
									<!---->
									<div _ngcontent-nqw-c11="" class="search-field-container">
										<!---->
										<div _ngcontent-nqw-c11="" class="global-search-bar">
											<xpl-typeahead-migr _ngcontent-nqw-c11="" minchars="3" name="search-term"
												placeholder=""
												ulclass="search-within-results ui-autocomplete ui-front ui-menu ui-widget ui-widget-content ui-corner-all"
												_nghost-nqw-c20="">
												<div _ngcontent-nqw-c20="" class="Typeahead">

													<input _ngcontent-nqw-c20="" aria-label="Enter search text"
														autocomplete="off"
														class="Typeahead-input ng-pristine ng-valid ng-touched form-control"
														type="text" placeholder="Search Here...">
													<!---->
												</div>
											</xpl-typeahead-migr>
										</div>
										<!---->
									</div>
								</div>

								<div _ngcontent-nqw-c11="" class="search-icon">
									<button _ngcontent-nqw-c11="" class="fa fa-search form-control" aria-label="Search"
										type="submit"></button>
								</div>
							</form>

						</div>
					</xpl-search-bar-migr>
				</div>
			</div>
		</div>



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

					if ($user_email == 'thesciencejournal@asuu.org.ng') {
						$stm = $pdo->prepare("SELECT * FROM manuscripts_docs WHERE category=? ORDER BY m_id DESC LIMIT $offset, $limit");
						$stm->execute(array('Journal of Science'));
					} else if ($user_email == 'thejournalofhumanities@asuu.org.ng') {
						$stm = $pdo->prepare("SELECT * FROM manuscripts_docs WHERE category=? ORDER BY m_id DESC LIMIT $offset, $limit");
						$stm->execute(array('Journal of Humanities'));
					} else if ($user_email == 'thejournalofsocielsciences@asuu.org.ng') {
						$stm = $pdo->prepare("SELECT * FROM manuscripts_docs WHERE category=? ORDER BY m_id DESC LIMIT $offset, $limit");
						$stm->execute(array('Journal Social Sciences'));
					} else {
						$stm = $pdo->prepare("SELECT * FROM manuscripts_docs ORDER BY m_id DESC LIMIT $offset, $limit");
						$stm->execute();
					}

					$postCount = $stm->rowCount();
					if ($postCount == 0) {
						echo "<h3 style='font-size:30px;color:#f00;'>" . "There are No Any post!";
					} else {
						$result = $stm->fetchAll(PDO::FETCH_ASSOC);
						$id = 1;
						foreach ($result as $row):
							$a = $id++;
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


										</ul>
									</div>


									<div class="pdf_section">

										<div class="status">
											<strong></strong>
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


										<div class="pdf_wrap">
											<a href="../author/<?php echo $row['f_url']; ?>"
												download="<?php echo $row['f_url']; ?>"> <i class="fas fa-download    "></i>
												(Download)</a>
										</div>

										&nbsp;&nbsp;&nbsp;&nbsp;

										<div class="listed_tags">
											<ul class="list-group list-group-horizontal flex-wrap">


												<li class="list-group-item">

													<select _ngcontent-nqw-c11="" class="custom-select"
														aria-label="content type dropdown" id="urlSelect<?php echo $a; ?>"
														onchange="window.location = jQuery('#urlSelect<?php echo $a; ?> option:selected').val();"><!---->

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

										<!-- definy reviwer  -->
										<div class="listed_tags">
											<ul class="list-group list-group-horizontal flex-wrap">


												<li class="list-group-item">


													&ensp;&ensp; <a onclick="return(false);" href="#"><i class="fas fa-book"
															data-toggle="modal" data-target="#suggestedReview<?php echo $a; ?>">
															Suggested Reviewer</i> </a>

													&ensp;&ensp; <a onclick="return(false);" href="#"><i class="fas fa-plus"
															data-toggle="modal" data-target="#myModal<?php echo $a; ?>"> Asign
															Reviewer</i> </a>









													<!-- Modal -->

													<div class="modal fade" id="suggestedReview<?php echo $a; ?>">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal"
																		aria-label="Close"><span
																			aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Suggested Reviewer
																	</h4>
																</div>
																<div class="modal-body">

																	<div class="al_suggested_reviwer_wrapper">
																		<?php
																		$stm = $pdo->prepare("SELECT * FROM suggested_reviwer WHERE manu_id=?");
																		$stm->execute(array($row['m_id']));
																		$sug_rev = $stm->fetchAll(PDO::FETCH_ASSOC);
																		if (!empty($sug_rev)):
																			$rID = 1;
																			foreach ($sug_rev as $sug)

																			: ?>



																				<div class="al_suggested_reviwer_list">
																					<p class="text-center">Reviewer
																						<?php echo $rID++; ?>
																					</p>

																					<ul class="text-secondary">
																						<li><b>Name : </b>
																							<?php echo $sug['r_tittle'] . ' ' . $sug['r_name']; ?>
																						</li>
																						<li><b>Email : </b>
																							<?php echo $sug['r_email']; ?>
																						</li>
																						<li><b>Phone : </b>
																							<?php echo $sug['r_phone']; ?>
																						</li>
																						<li><b>Address : </b>
																							<?php echo $sug['r_address']; ?>
																						</li>
																						<li><b>Reason : </b>
																							<?php echo $sug['r_reason']; ?>
																						</li>

																					</ul>





																				</div>

																			<?php endforeach; ?>
																		<?php else: ?>
																			<p class="text-info">Suggested Reviewer not found</p>
																		<?php endif; ?>


																	</div>

																</div>
																<img class="popLoading" id="popLoadingReviwer"
																	src="img/source.gif" alt="loding">
															</div>
														</div>
													</div>
													<!-- end modal  -->






													<!-- Modal -->

													<div class="modal fade" id="myModal<?php echo $a; ?>">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal"
																		aria-label="Close"><span
																			aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Assign
																		Reviewer
																	</h4>
																</div>
																<div class="modal-body">
																	<form action="mns_assign_reviwer.php" method="post">
																		<div class="form-group">
																			<select id="getAreaSp" onchange="findNames()"
																				class=" form-select custom-select"
																				aria-label="Default select example"
																				name="area_specialization" required>
																				<option selected>---- Accademic Disciplines ----
																				</option>
																				<option value="ns">Natural Sciences</option>
																				<option value="ss">Social Sciences</option>
																				<option value="hu">Humanities</option>
																				<option value="et">Engineering and Technology
																				</option>
																				<option value="ar">Arts</option>
																			</select>
																		</div>

																		<div class="form-group">
																			<select id="ShowAreaSp" onchange="findNames()"
																				class="form-select custom-select"
																				aria-label="Default select example"
																				name="academic_disciplines" required>
																				<option selected value="alif">---- Area
																					Specialization ----
																				</option>
																			</select>
																		</div>



																		<div class="form-group">
																			<select class="form-select custom-select"
																				onchange="(this.value);"
																				aria-label="Default select example" id="name"
																				name="reviwer_id" required>
																				<option value="alif" selected>----Names----
																				</option>

																			</select>
																		</div>
																		<input type="hidden" name="m_id"
																			value="<?php echo $row['m_id']; ?>">




																		<div class="modal-footer">
																			<button type="button" class="btn btn-default"
																				data-dismiss="modal">Close</button>
																			<button disabled type="submit" id="popsubmit"
																				name="modal_submit"
																				class="btn btn-primary">Submit</button>
																		</div>
																	</form>
																</div>
																<img class="popLoading" id="popLoading" src="img/source.gif"
																	alt="loding">
															</div>
														</div>
													</div>
													<!-- end modal  -->

											</ul>
										</div>

									</div>
								</div>
							</div>



						<?php endforeach;
					} ?>
				</div>



				<!-- ////// php pagination ///////-->

				<?php
				$stm = $pdo->prepare("SELECT * FROM manuscripts_docs WHERE email=? ORDER BY m_id DESC");
				$stm->execute(array($user_email));
				$Ubooks = $stm->rowCount();
				// $result = $stm->fetchAll(PDO::FETCH_ASSOC);
				// echo $user_email;
				

				if ($Ubooks > 0) {
					$total_record = $Ubooks;
					$total_page = ceil($total_record / $limit);

					echo '<ul class="pagination justify-content-center">';
					if ($page > 1) {
						echo ' <li class="page-item"><a class="page-link" href="mns_tracking.php?page=' . ($page - 1) . '">Prev</a></li>';
					}
					for ($i = 1; $i <= $total_page; $i++) {
						if ($i == $page) {
							$active = "active";
						} else {
							$active = "";
						}
						echo '<li class="page-item"><a class="page-link'
							. " " . $active . '" href="mns_tracking.php?page=' . $i . '">' . $i . '</a></li>';
					}
					if ($total_page > $page) {
						echo '<li class="page-item"><a class="page-link" href="mns_tracking.php?page=' . ($page + 1) . '">Next</a></li>';
					}
					echo '</ul>';
				}
				?>
			</div>




		</div>
	</div>
</main>
<!-- The Modal -->


</div>
<?php require_once('footer.php'); ?>
<script>
	// function submite_institute(value) {
	// 	var loadImg = document.getElementById('popLoading');
	// 	loadImg.style.display = 'block';
	// 	$.ajax({
	// 		type: 'GET',
	// 		url: 'ajax-institute.php',
	// 		data: {
	// 			institute: value,
	// 		},
	// 		success: function (response) {
	// 			var rank_array = JSON.parse(response);
	// 			var p_rank = "";
	// 			for (let index = 0; index < rank_array.length; index++) {
	// 				p_rank += "<option value='" + rank_array[index] + "'>" + rank_array[index] + "</option>";
	// 			}
	// 			document.getElementById('rank').innerHTML = "<option value='alif' selected>---- Select Rank----</option>" + p_rank;
	// 			loadImg.style.display = 'none';
	// 		}
	// 	});
	// 	submite_rank('alif');
	// }

	// function submite_rank(value) {
	// 	var loadImg = document.getElementById('popLoading');
	// 	loadImg.style.display = 'block';
	// 	var institute_data = document.getElementById('institute').value;
	// 	$.ajax({
	// 		type: 'GET',
	// 		url: 'ajax-rank.php',
	// 		data: {
	// 			rank: value,
	// 			institute_data: institute_data
	// 		},
	// 		success: function (response) {
	// 			var rank_array = JSON.parse(response);
	// 			var p_area = "";
	// 			for (let index = 0; index < rank_array.length; index++) {
	// 				p_area += "<option value='" + rank_array[index] + "'>" + rank_array[index] + "</option>";
	// 			}
	// 			document.getElementById('displine').innerHTML = "<option value='alif' selected>---- Displine/Area of Specilization----</option>" + p_area;
	// 			loadImg.style.display = 'none';
	// 		}
	// 	});
	// 	submite_displine('alif');
	// }

	// al new comment 

	const areadSpArray = {
		'ns': ['Physics', 'Chemistry', 'Biology', 'Astronomy'],
		'ss': ['Sociology', 'Psychology', 'Economics', 'Political Science'],
		'hu': ['Physics', 'Chemistry', 'Biology', 'Astronomy'],
		'et': ['Physics', 'Chemistry', 'Biology', 'Astronomy'],
		'ar': ['Physics', 'Chemistry', 'Biology', 'Astronomy']
	};
	const initialOption = `<option selected value="alif">---- AreaSpecialization ----</option>`;

	$('#getAreaSp').on('change', function (e) {
		var getAreaSp = e.target.value;
		$('#ShowAreaSp').empty();
		$('#ShowAreaSp').append(initialOption);
		if (areadSpArray[getAreaSp]) {
			areadSpArray[getAreaSp].forEach(function (subject) {
				$('#ShowAreaSp').append($('<option>', {
					value: subject,
					text: subject
				}));
			});
		}
	});

	function findNames() {
		var ShowAreaSp = document.getElementById('ShowAreaSp').value;
		if (ShowAreaSp !== 'alif') {
			var loadImg = document.getElementById('popLoading');
			loadImg.style.display = 'block';
			$.ajax({
				type: 'GET',
				url: 'ajax-name.php',
				data: {
					areaSpe: ShowAreaSp
				},
				success: function (response) {
					var rank_array = JSON.parse(response);
					var p_area = "";
					for (let index = 0; index < rank_array.length; index++) {
						var full_name = rank_array[index].fname + " " + rank_array[index].onames;
						p_area += "<option value='" + rank_array[index].user_id + "'>" + full_name + "</option>";
					}
					document.getElementById('name').innerHTML = "<option value='alif' selected>---- Names----</option>" + p_area;
					loadImg.style.display = 'none';

					var popsubmitButton = document.getElementById('popsubmit');
					if (rank_array.length !== 0) {
						popsubmitButton.disabled = false;
					} else {
						popsubmitButton.disabled = true;
					}


				}
			});
		}

	}








</script>