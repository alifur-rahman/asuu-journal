<?php
require_once('header.php');

?>
<style>
     .action-bbtn {
          display: inline-block;
          padding: 1px 17px;
          text-align: center;
          background: #bb0000b8;
          color: #fff;
          border-radius: 13px;
          transition: .4s;
     }

     .action-bbtn:hover {
          color: #000;
          background: #ec0303;
     }
</style>

<main class="main_content pt-5">
     <div class="container-fluid">
          <div class="row justify-content-center">
               <div class="col-lg-12">
                    <div class="al_dataTable">
                         <table id="jquery-datatable-ajax-php" class="display" style="width:100%">
                              <thead>
                                   <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Institution</th>
                                        <th>Area Specialization</th>
                                        <th>Displine </th>
                                        <th>Role</th>
                                        <th>Action</th>
                                   </tr>
                              </thead>
                         </table>
                    </div>

               </div>
          </div>
     </div>
</main>



<!-- Modal -->
<div class="modal fade" id="myModal">
     <div class="modal-dialog" role="document">
          <div class="modal-content">
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                              aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Change Role
                    </h4>
               </div>
               <div class="modal-body">
                    <form id="assignRoleForm" action="#" method="post">
                         <p><span id="authSeleted"></span> is Selected</p>
                         <p class="text-info" id="modalError"></p>

                         <div class="form-group">
                              <select class="form-select custom-select" aria-label="Default select example" id='rank'
                                   name="role" required>
                                   <option value="alif" selected>---- Select
                                        Role----</option>
                                   <option value="Reviewer">Reviewer</option>
                                   <option value="Editor">Editor</option>
                              </select>
                         </div>
                         <input id="alUserId" type="hidden" name="userId" value="">

                         <div class="modal-footer">
                              <img class="popLoading" style="display: none; width: 100%; max-width: 40px;"
                                   id="popLoading" src="img/source.gif" alt="loding">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="submit" id="popsubmit" name="modal_submit"
                                   class="btn btn-primary">Submit</button>
                         </div>
                    </form>
               </div>

          </div>
     </div>
</div>
<!-- end modal  -->


<?php require_once('footer.php'); ?>
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
     $(document).ready(function () {
          $('#jquery-datatable-ajax-php').DataTable({
               'processing': true,
               'serverSide': true,
               'serverMethod': 'post',
               'ajax': {
                    'url': 'develop/ajaxUserShow.php'
               },
               'columns': [

                    { data: 'fname' },
                    { data: 'email' },
                    { data: 'institution' },
                    { data: 'area_specialization' },
                    { data: 'displine' },
                    { data: 'user_role' },
                    {
                         data: 'action', orderable: false,
                         render: function (data, type, row, meta) {
                              return data; // This will treat data as HTML
                         }
                    }
               ]
          });

     });
</script>
<script>
     function editUserRole(event, id, name) {
          event.preventDefault();
          $('#alUserId').val(id);
          $('#authSeleted').html(name);
          $('#myModal').modal('show');
          $('#myModal').on('hidden.bs.modal', function () {
               $('#alUserId').val('');
               $('#modalError').html('');
          });
          return false;
     }

     $(document).on("click", ".al_edit_roleModal", function (e) {
          event.preventDefault();
          $('#alUserId').val($(this).data('user-id'));
          $('#authSeleted').html($(this).data('fullname'));
          $('#myModal').modal('show');
          $('#myModal').on('hidden.bs.modal', function () {
               $('#alUserId').val('');
               $('#modalError').html('');
               $('#authSeleted').html('');
          })
     })


     $(document).on('submit', '#assignRoleForm', function (e) {
          e.preventDefault();
          $('#popLoading').show();
          $('#popsubmit').attr('disabled', true);
          $.ajax({
               url: "develop/ajaxAssignRole.php",
               type: "POST",
               data: $('#assignRoleForm').serialize(),
               success: function (data) {
                    const res = JSON.parse(data);
                    $('#modalError').html(res.message);
                    console.log(data);
                    $('#popLoading').hide();
                    $('#popsubmit').attr('disabled', false);
                    if (res.status == true) {
                         setTimeout(function () {
                              $('#myModal').modal('hide');
                              $('#jquery-datatable-ajax-php').DataTable().ajax.reload();
                         }, 2000);
                    }


               }
          });
     });



</script>