<?php

function companies_tab(){
    global $wpdb;

    $table_name = $wpdb->prefix.'company_info_parent';

    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['delete_company'])){
            $delete = $wpdb->delete($table_name,['id' => $_POST['row']]);
   // $options = $wpdb->get_results("SELECT * FROM wp_comany_plugin_option WHERE id=1");

   // $optionsData = json_decode($options[0]->options);
            if($delete){
                $errors[] = 'Company has been deleted';
            }else{
                $errors[] = 'No data effected .. ERROR';
            }
        }

        if(isset($_POST['add_new_company'])){
            $insert = $wpdb->insert($table_name,[
                'company_name' => $_POST['company_name'],
                'platinum_sla' => $_POST['platinum'],
                'hubspot_id' => $_POST['hubspot_id'] ? $_POST['hubspot_id'] : 0
                ]);

                if($insert){
                    $errors[] = 'Company has been created';
                }else{
                    $errors[] = 'No data created .. ERROR';
                }
        }

        if(isset($_POST['edit_company'])){
            $update = $wpdb->update($table_name,[
                'company_name' => $_POST['company_name'],
                'platinum_sla' => $_POST['platinum'],
                'hubspot_id' => $_POST['hubspot_id'] ? $_POST['hubspot_id'] : 0
            ],['id' => $_POST['id'] ]);

                if($update){
                    $errors[] = 'Company has been updated';
                }else{
                    $errors[] = 'No data updated .. ERROR';
                }
        }
    }

    $data = $wpdb->get_results("SELECT * FROM $table_name");

    ?>

    <div class="warp" style="margin-top:50px;">
    <?php if(isset($errors)): ?>

<div id="message" class="updated notice is-dismissible">

    <?php foreach($errors as $error): ?>

    <p><?php echo $error; ?></p>



    <?php endforeach; ?>

    <button type="button" class="notice-dismiss">

        <span class="screen-reader-text">إخفاء هذه الملاحظة.</span>

    </button>

</div>



<?php endif; ?>
    
    </div>
    <?php
     if(isset($_GET['edit'])){
         $id = intval($_GET['edit']);
        $row = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$id");


        if($row){
        ?>

        <div class="half-container">
            <form action="" method="post">
                <span>ID : <?php echo $row->id; ?></span>
                <input type="hidden" name="id" value="<?php echo $row->id ?>">
                <input class="prmgtk_input" required type="text" name="company_name" placeholder="Company Name" value="<?php echo $row->company_name; ?>">
                <input class="prmgtk_input" type="text" name="hubspot_id" placeholder="Hubspot ID" value="<?php echo $row->hubspot_id; ?>">
                 <label for="platinum">Platinum:</label>
                 <select name="platinum" id="platinum">
                 <option value="yes">yes</option>
                 <option value="no">No</option>
                 </select>
                 <br>
                <button type="submit" name="edit_company" class="btn btn-submit">Edit Company</button>
                <a href="<?php menu_page_url( 'companies_tab' ); ?>">Back</a>
            </form>
    </div>
<?php
        }
 }elseif(isset($_GET['view'])){
    $id = intval($_GET['view']);
    $row = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$id");
    

    
   // $url = 'https://api.hubapi.com/companies/v2/companies/' . $row->hubspot_id . '?hapikey='. $optionsData->api;
    //$data_api = json_decode(get_from_api($url));
?>
    <div class="half-container">
        <h3>Under construction</h3>
        <?php //echo '<pre>';
        //print_r($data_api);
       // echo '</pre>'; ?>
    </div>
<?php
 }else{
?>
<div style="display:flex;">
    <div class="half-container" style="width:60%">
            <script>
        var tables_to_export = [0,1,2];
    </script>
        <table id="table_id" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Hubspot ID</th>
                    <th>Platinium SLA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($data): foreach($data as $one): ?>
                <tr>
                    <td><?php echo $one->id; ?></td>
                    <td><?php echo $one->company_name; ?></td>
                    <td><?php echo $one->hubspot_id; ?></td>
                    <td><?php echo $one->platinum_sla; ?></td>
                    <td style="display:flex;">
                    <a href="<?php menu_page_url( 'companies_tab' ); ?><?php echo '&view=' . $one->id; ?>" class="btn btn-edit myBtn">View</a>
                    <a href="<?php menu_page_url( 'companies_tab' ); ?><?php echo '&edit=' . $one->id; ?>" class="btn btn-edit myBtn">Edit</a>
                
                <form action="" method = "post">
                    <input type="hidden" name="row" value="<?php echo $one->id ?>">
                    <button type="submit" onclick="if(!confirm('Are you sure ?')){ event.preventDefault() }" name="delete_company" class="btn btn-delete">Delete</button>
                </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="half-container">
            <form action="" method="post">
            <h1>Add new company</h1>
                <input class="prmgtk_input" type="text" name="company_name" required placeholder="Company Name">
                <input class="prmgtk_input" type="text" name="hubspot_id" placeholder="Hubspot ID">
                 <label for="platinum">Platinum:</label>
                 <select name="platinum" id="platinium">
                 <option value="yes">yes</option>
                 <option value="no">No</option>
                 </select>
                 <br>
                <button type="submit" name="add_new_company" class="btn btn-submit">Add New</button>
            </form>
    </div>
</div>

    <?php
    }
}