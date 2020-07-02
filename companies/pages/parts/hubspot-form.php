<?php
 $company = get_company_by('id',$_SESSION['company_user']);
 $profile = get_company_parent($_SESSION['company']);
 $company_devices = get_company_devices($_SESSION['company']);
?>



<div class="col-md-8 section-form mt-5 mb-5">
    <div class="overlay">
    <div class="lds-ripple"><div></div><div></div></div>
    </div>

    <form action="" method="post">

        <input type="submit" class="btn btn-primary" value="logout" name="company_logout">

    </form>

    <form method="post" action="" enctype="multipart/form-data">

        <?php if(isset($_SESSION['danger'])){ ?>
        <div class="alert alert-danger mt-3 mb-3">
            <?php echo $_SESSION['danger'] ?>
        </div>
        <?php unset($_SESSION['danger']); }elseif(isset($_SESSION['success'])){ ?>
        <div class="alert alert-success mt-3 mb-3">
            <?php echo $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); } ?>
        <div class="intro">

            <h1>please fill out the required fields</h1>
        </div>
        <p><label class="" for="">Company : <?php echo $profile->company_name; ?></label></p>

          <p><label class="" for="">Name : <?php echo $company[0]->username; ?></label></p>

        <p><label class="" for="">Email : <?php echo $company[0]->email; ?></label></p>

        <p><label class="" for="">Phone : <?php echo $company[0]->phone; ?></label></p>
        
       <!-- <p style="font-weight: bold;"><label class="" for=""><?php 
                                                       $link_address='https://www.synamedia.com/video-professional-services/';
                                                       if ($profile->platinum_sla=="yes"){
                                                        echo "if it is an Urgent issue go directly to <a href='".$link_address."'>Synamedia Portal</a>";
                                                       }?></label></p>-->

        <input class="form-control" type="hidden" value="<?php echo $company[0]->company_name; ?>"
            placeholder="Company Name" name="company" tabindex="1" />

        <input required class="form-control" type="hidden" value="<?php echo $company[0]->email; ?>" placeholder="E-mail"
            name="email" tabindex="2" />

        <input required class="form-control" type="hidden" value="<?php echo $company[0]->phone; ?>" placeholder="Mobile" name="mobile" tabindex="3" />
         
        <div class="input-group mb-3">
         <div class="input-group-prepend">
    <label class="input-group-text" for="issue">Issue type :</label>
         </div>

        <select class="form-control" name="issue_type" id="issue" required>
        <option value="Hardware issue">Hardware issue</option>
        <option value="Software issue">Software issue</option>
        </select>
        </div>
         <input required class="form-control" type="text" value="" placeholder="Issue" name="issue" tabindex="5" />
         
         <div class="input-group mb-3 d-flex">
         <div class="input-group-prepend">
    <label class="input-group-text" for="impact">Impact :</label>
         </div>
        <select class="form-control" name="impact" id="impact" required>
        <option value="Service effecting">Service affecting</option>
        <option value="Operation effecting">Operation affecting</option>
        <option value="Need to resolve">Need to resolve</option>
        <option value="To be resolved - Low level">To be resolved - Low level</option>
        </select>
        <p class="help-warp"><span class="help" data-toggle="tooltip" data-placement="top" title="Level of issue or How the issue effects on your work">?</span></p>
        </div>
        
       <div class="input-group mb-3 d-flex">
         <div class="input-group-prepend">
    <label class="input-group-text" for="priority">Priority:</label>
         </div>
        <select class="form-control" name="priority" id="priority" required>
        <option value="LOW">LOW</option>
        <option value="MEDIUM">MEDIUM</option>
			<option value="HIGH">HIGH</option>
        </select>
        <p class="help-warp"><span class="help" data-toggle="tooltip" data-placement="top" title="Low for To be resolved ,Medium For operation affecting,High for service affecting ">?</span></p>
        </div>  
        
        
    <div id="dsm-wrapper" class="dsm-wrapper" data-mfield-options='{"section": ".dsm-input-wrapper","btnAdd":"#addButton","btnRemove":".removeButton","max": 3}'>
            <a id="addButton" style="color:#fff;cursor: pointer;" class="btn btn-primary btn-small mb-2">Add another item</a>
                    <div class="row dsm-input-wrapper d-flex">
                           <div class="col-md-10">
                                <input required class="form-control dsm" type="text" value="" placeholder="Device Serial Number" name="dsm[]"
            tabindex="6" id="dsm" />
                        <span id="message"></span>
        <input required class="form-control dtmn" type="text" value="" placeholder="Device Type & Model Number" name="dtmn[]"
            tabindex="7" id="dtmn" />
         <p hidden style="font-weight: bold; " id="pgsla">if it is an Urgent issue go directly to <a href='https://www.synamedia.com/video-professional-services/'>Synamedia Portal</a></p>
                           </div>
          
            <div class="col-md-2">
                
                <p class="help-warp" style="margin: 12px 0px;"><span id="removeButton" class="help removeButton" title="Remove">-</span></p>
            </div>
             
        </div>
        
       
    </div>
        
            

         
          <textarea required class="form-control" name="details" placeholder="Details" ></textarea>

    <label for="myfile">Attach file:</label>
   <input type="file" name="fileh"  />
    <!--<input type="file" name="fileh" class="form-control mb-2"  />-->
       <br>
        <input id="submit" class="btn btn-primary"  type="submit" name="hubspot" value="Submit" tabindex="10" />
        
        <p>if you are not sure with the Device serial number please  <span> <a href="https://www.mera-tech.com/non-customer-support"> Click here</a></span>.</p>

    </form>
    <script>
    var company = <?php echo $_SESSION['company']; ?> ;
    </script>
</div>