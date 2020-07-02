<?php 
    $url = 'https://api.hubapi.com/crm-objects/v1/objects/tickets/' . $_GET['ticket_details'] . '?hapikey=' . $optd->api . '&properties=subject&properties=content&properties=hs_pipeline_stage';
    $ticket = get_from_api($url);
    $response = json_decode($ticket,ARR);
    
    //echo '<pre>';
   // print_r($response);
    //echo '</pre>';
    
   
    
        ?>
 <div class="col-md-8 section-form mt-5 mb-5"  >
    <!--<table class="table table-hover">-->
       <table  class="table table-bordered">
  <thead>
    <tr>
      <th style='width:10%;font-size=14px;'scope="col">Ticket id</th>
      <th scope="col">Details</th>
    </tr>
  </thead>
  <tbody>
<?php if($response):  ?>
    <tr>
      
      <th scope="row"><?php echo $response['objectId']; ?></th> 
      <td><?php echo $response['properties']['content']['value']; ?></td>
    
      
      </tr>

<?php  else: ?>

<?php endif; ?>
  </tbody>
</table>
</div>