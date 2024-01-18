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

<?php require_once('footer.php'); ?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
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