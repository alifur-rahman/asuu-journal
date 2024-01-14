<?php 
require_once('header.php');
require_once('config.php');
// session_start();
// if (!isset($_SESSION['username'])){ 
//   header("Location: /login");
// }
$volume = $_REQUEST['volume'];
$vy = $_REQUEST['vy'];
$type = $_REQUEST['type'];
if($type == 1){
	$def_type = "Journal of Humanities";
}
else if($type == 2){
	$def_type = "Journal Social Sciences";
}
else if($type == 3){
	$def_type = "Journal of Science";
}
else if($type == 4){
	$def_type = "Magazines";
}


?>
<style>

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
</style>



		<main class="main_content pt-5">
			<div class="container">




				<div class="row justify-content-center">
					<div class="col-lg-10">
						<div class="row">

						<?php  
							$limit = 4;
							if(isset($_REQUEST['page'])){
								$page = $_REQUEST['page'];
							}
							else{
								$page = 1;
							}
							$offset = ($page-1)*$limit;


							$stm= $pdo->prepare("SELECT * FROM uploaded_articles WHERE vy=? AND volume=? AND type=? ORDER BY a_id DESC LIMIT $offset, $limit");
							$stm->execute(array($vy,$volume,$def_type));
							$postCount =$stm->rowCount();
							if($postCount == 0){
								echo "<h3 style='font-size:30px;color:#f00;'>"."There are No Any post!";
							}
							else{
								$id= "1";
								$result = $stm->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row):
								$a = $id++;
						?>

							<div class="col-12">
								<div class="list_wrapper">
									<div class="list_title">
										<h4><?php echo $row['tittle'];?></h4>
									</div>

									<div class="listed_tags">
										<ul class="list-group list-group-horizontal flex-wrap">
											<li class="list-group-item">
											    <?php echo $row['authors'];?>
											</li>
										</ul>
									</div>

									<!-- yearly info -->
									<div class="other_info my-1">
										<ul class="list-group list-group-horizontal flex-wrap">
										<li class="list-group-item">Date: <?php echo $row['nm']."-".$row['vy']; ?></li>
											<li class="list-group-item">Type:<?php echo $row['type'];?></li>
											<li class="list-group-item border-0">Publisher:Academic Staff Union of Universities (ASUU)</li>
										<li class="list-group-item border-0">
										    
										    
										    
										    Status: <strong></strong> Submited</li>
										
										</ul>
									</div>


									<div class="pdf_section">
										<div class="abstract_wrap" data-toggle="modal" data-target="#myModal<?php echo $a ;?>" type="button">
											<i class="fas fa-caret-right"></i> <span>View article</span> |
											
										</div>

										<div class="pdf_wrap">
											<a href="../editor/uploaded_articles/<?php echo $row['url']; ?>" download="<?php echo $row['url']; ?>" ><img src="img/pdf_icon.png">(Download)</a>
										
										</div>
									</div>
								</div>
							</div>
									
							<div class="col-md-12">
									<!-- The Modal -->
								<div class="modal fade" id="myModal<?php echo $a ;?>">
									<div class="modal-dialog modal-xl">
										<div class="modal-content">

											<button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>

											<!-- Modal body -->
											<div class="modal-body">
												<iframe src="../editor/uploaded_articles/<?php echo $row['url']; ?>" style="width:100%; height:600px;" frameborder="0"></iframe>					</div>


										</div>
									</div>
								</div>
							</div>
							
						<?php endforeach ; 
						
						}?>
						</div>



	<!-- ////// php pagination ///////-->

	<?php 
	$stm= $pdo->prepare("SELECT * FROM uploaded_articles WHERE vy=? AND volume=? AND type=? ORDER BY a_id DESC");
	$stm->execute(array($vy,$volume,$def_type));
	$Ubooks =$stm->rowCount();
	// $result = $stm->fetchAll(PDO::FETCH_ASSOC);
	


	if($Ubooks > 0)
		$total_record = $Ubooks;
		$total_page = ceil($total_record/ $limit);

		echo '<ul class="pagination justify-content-center">';	
		if ($page > 1) {
		echo ' <li class="page-item"><a class="page-link" href="journal-view.php?page='.($page - 1).'&volume='.$volume.'&vy='.$vy.'&type='.$type.'">Prev</a></li>';
		}			
		for ($i=1; $i<=$total_page; $i++) { 
			if($i == $page){
			$active = "active";
			}
			else{
				$active = "";
			}
			echo '<li class="page-item"><a class="page-link'
			." ".$active.'" href="journal-view.php?page='.$i.'&volume='.$volume.'&vy='.$vy.'&type='.$type.'">'.$i.'</a></li>';
		}
		if($total_page > $page){
		echo '<li class="page-item"><a class="page-link" href="journal-view.php?page='.($page + 1).'&volume='.$volume.'&vy='.$vy.'&type='.$type.'">Next</a></li>';
		}
		echo '</ul>';
	?>
					</div>
					

					

				</div>
			</div>
		</main>
		

	</div>
<?php require_once('footer.php'); ?>