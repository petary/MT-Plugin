    <?php 
    
    $website_data = ['ids' => get_company_tickets($_SESSION['company'])];
    $all_url_tickets = TICKETS . $optd->api . '&properties=subject&properties=content&properties=hs_pipeline_stage' ;
    $all_url_pipelines = PIPELINES . $optd->api;
    
    $tickets = post_to_api(json_encode($website_data),$all_url_tickets);
    $pipelines = get_from_api($all_url_pipelines);
    $response = json_decode($tickets['response'],ARR);
    $pipeResponse = json_decode($pipelines,ARR);

    $pipes = [];
    foreach($pipeResponse['results'][0]['stages'] as $pipe){
        $pipes[$pipe['stageId']] = $pipe['label'];
      
    }
    
    // echo '<pre>';
    // print_r($pipeResponce['results'][0]['stages']);
    // echo '<pre>';
    

 
    

    ?>
<div class="col-md-9 section-form mt-5 mb-5" >
            <?php if(isset($_SESSION['danger'])){ ?>
        <div class="alert alert-danger mt-3 mb-3">
            <?php echo $_SESSION['danger'] ?>
        </div>
        <?php unset($_SESSION['danger']); }elseif(isset($_SESSION['success'])){ ?>
        <div class="alert alert-success mt-3 mb-3">
            <?php echo $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); } ?>
    <table class="table table-bordered">
  <thead>
    <tr>
      <th style="width:15%" scope="col">Ticket id</th>
      <th style="width:30%;text-align: center;"scope="col">Issue</th>
      <!--<th scope="col">Details</th>-->
      <th style="width:25%;text-align: center;"scope="col">Status</th>
      <th style="width:10%;text-align: center;"scope="col"></th>
      <th style="width:10%;text-align: center;"></th>
    </tr>
  </thead>
  <tbody>
<?php if($response): foreach($response as $item): ?>
    <tr>
       <th scope="row"><a  <?php echo isset($_GET['tickets&ticket_details']) ? 'active' : ''  ?>" href="<?php echo get_permalink( $optd->ppage ); ?>?tickets&ticket_details=<?php echo $item['objectId']; ?>"><?php echo $item['objectId']; ?> </a></th>
     <!-- <th scope="row"><?php echo $item['objectId']; ?></th> -->
      <td style="text-align: center;"><?php echo $item['properties']['subject']['value']; ?></td>
     <!--<td><?php echo $item['properties']['content']['value']; ?></td>-->
     <!--<td> <a  <?php echo isset($_GET['ticket_details']) ? 'active' : ''  ?>" href="<?php echo get_permalink( $optd->ppage ); ?>?ticket_details">click here</a></td> -->
      <?php if($item['properties']['hs_pipeline_stage']['value'] == 4): ?>
      <td style="text-align: center;"><?php echo $pipes[$item['properties']['hs_pipeline_stage']['value']] ?></td>
      <td style="width:10%;text-align: center;color:green">Closed</td>

      <td style="width:10%;text-align: center;color:blue">
          
          <form action="" method="post">
            <input type="hidden" name="re_open" value="<?php echo $item['objectId']; ?>">
        <input type="submit" class="btn btn-primary" value="Re-Open" name="Re-Open">

    </form>
    
    </td>

     <!-- <td style="width:10%;text-align: center;color:blue">Re-Open</td>-->
      <?php elseif($item['properties']['hs_pipeline_stage']['value'] == 767566): ?>
      <td><?php echo $pipes[$item['properties']['hs_pipeline_stage']['value']] ?></td>
      <td style="width:10%;text-align: center;color:orange">Open</td>
      <?php else: ?>
      <td><?php echo $pipes[$item['properties']['hs_pipeline_stage']['value']] ?></td>
      <td style="width:10%;text-align: center;color:red">Open</td>
      
      <?php endif; ?>
      
    </tr>

<?php endforeach; else: ?>

<?php endif; ?>
  </tbody>
</table>
</div>