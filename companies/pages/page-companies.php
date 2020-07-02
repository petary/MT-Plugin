<?php
require 'parts/init.php';

 get_header();
?>
<div class="container">

    <div class="row">
        <?php
if(!is_company_logged_in()){
  require 'parts/login.php';
}else{
  require 'parts/sidebar.php';
  if(!isset($_GET['tickets'])){
      require 'parts/hubspot-form.php';
  }elseif(isset($_GET['tickets'])){
    if(isset($_GET['ticket_details']) and $_GET['ticket_details'] != ''){
      require 'parts/details.php';
     }else{
        require 'parts/all-ticket.php';
     }


   } 
}
if(isset($_POST['Re-open'])){
      require 'parts/update_status.php';}
?>
    </div>

</div>

<?php


get_footer();