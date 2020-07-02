<?php

function companies_devices(){
    global $wpdb;

    $table_name = $wpdb->prefix.'company_devices';
    $company_table = $wpdb->prefix.'company_info_parent';
    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['delete_company_device'])){
            $delete = $wpdb->delete($table_name,['id' => $_POST['row']]);

            if($delete){
                $errors[] = 'Device has been deleted';
            }else{
                $errors[] = 'No data effected .. ERROR';
            }
        }

        if(isset($_POST['add_new_company_device'])){
            $insert = $wpdb->insert($table_name,[
                    'company_id' => $_POST['company'],
                    'device_serial_number' => $_POST['dsn'],
                    'device_type' => $_POST['device_type'],
                    'warranty_start' => $_POST['start_date'] ,
                    'warranty_end'  => $_POST['end_date'],
                    'manufacture'  => $_POST['manufacture'],
                    'identification'  => $_POST['identification'],
                    'product_name'  => $_POST['product_name'],
                    'period'  => $_POST['period'],
                    'sla_type' => $_POST['sla'],
                ]);

                if($insert){
                    $errors[] = 'Device has been created';
                }else{
                    $errors[] = 'No data created .. ERROR';
                }
        }

        if(isset($_POST['edit_company_device'])){
            $update = $wpdb->update($table_name,[
                'company_id' => $_POST['company'],
                'device_serial_number' => $_POST['dsn'],
                'device_type' => $_POST['device_type'],
                'warranty_start' => $_POST['start_date'] ,
                'warranty_end'  => $_POST['end_date'],
                'manufacture'  => $_POST['manufacture'],
                'identification'  => $_POST['identification'],
                'product_name'  => $_POST['product_name'],
                'period'  => $_POST['period'],
                'sla_type' => $_POST['sla'],
            ],['id' => $_POST['id']]);

                if($update){
                    $errors[] = 'Device has been updated';
                }else{
                    $errors[] = 'No data updated .. ERROR';
                }
        }
    }

    $data = $wpdb->get_results("SELECT * FROM $table_name");
    $companies = $wpdb->get_results("SELECT * FROM $company_table");

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
            <h1>Edit device</h1>
            <select name="company" id="company" class="prmgtk_input" required>
                    <?php if($companies): ?>
                        <option value="">Select company</option>
                        <?php foreach($companies as $comp): ?>
                            <option <?php echo ($comp->id == $row->company_id) ? 'selected' : ''; ?> value="<?php echo $comp->id ?>"><?php echo $comp->company_name; ?></option>
                        <?php endforeach; else: ?>
                        <option value="">No companies exist</option>
                    <?php endif; ?>
                    
                </select>
                <script>
                var start_date = <?php echo $row->warranty_start; ?>,
                    end_date = <?php echo $row->warranty_end; ?>;
                </script>
                <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                <input class="prmgtk_input" type="text" value="<?php echo $row->manufacture; ?>" name="manufacture" placeholder="Manufacture" required>
                <input class="prmgtk_input" value="<?php echo $row->device_serial_number; ?>" type="text" name="dsn" placeholder="Device serial number" required>
                <input class="prmgtk_input" value="<?php echo $row->device_type; ?>" type="text" name="device_type" placeholder="Device type" required>
                <input class="prmgtk_input" type="text" value="<?php echo $row->identification; ?>" name="identification" placeholder="Identification">
                <input class="prmgtk_input" type="text" value="<?php echo $row->product_name; ?>" name="product_name" placeholder="Product name" >
                <label for="sla">SLA Type:</label>
                 <select name="sla" id="sla">
                   <?php if($data): ?>
                 <option <?php if ($data->sla_type == "Platinum" ) {echo 'selected';}  ?>value="Platinum">Platinum</option>
                 <option <?php if ($data->sla_type  == "Gold" ) {echo 'selected' ;} ?>value="Gold">Gold</option>
                 <option <?php if ($data->sla_type  == "Normal" ) {echo 'selected';}  ?>value="Normal">Normal</option>
                 <?php  else: ?>
                        <option value="">No SLA Chosen</option>
                    <?php endif; ?>
                 </select>
                 <br>
                <input class="prmgtk_input" value="<?php echo $row->warranty_start; ?>" type="text" name="start_date" id="warrantyStart" placeholder="Warranty start">
                <input class="prmgtk_input" type="number" value="<?php echo $row->period; ?>" name="period" id="period" placeholder="period">
                <input class="prmgtk_input" value="<?php echo $row->warranty_end; ?>" type="text" name="end_date" id="warrantyEnd" placeholder="Warranty end">
                <button type="submit" name="edit_company_device" class="btn btn-submit">Update</button>
                <a href="<?php menu_page_url( 'companies_devices' ); ?>">Back</a>
            </form>
</div>

<?php
        }
 }elseif(isset($_GET['new'])){
     ?>

<div class="half-container">
<form action="" method="post">
            <h1>Add new Device</h1>
            <select name="company" id="company" class="prmgtk_input" required>
                    <?php if($companies): ?>
                        <option value="">Select company</option>
                        <?php foreach($companies as $comp): ?>
                            <option value="<?php echo $comp->id ?>"><?php echo $comp->company_name; ?></option>
                        <?php endforeach; else: ?>
                        <option value="">No companies exist</option>
                    <?php endif; ?>
                    <script>
                var start_date,end_date;
                </script>
                </select>
                <input class="prmgtk_input" type="text" name="manufacture" placeholder="Manufacture" required>
                <input class="prmgtk_input" type="text" name="dsn" placeholder="Device serial number" required>
                <input class="prmgtk_input" type="text" name="device_type" placeholder="Device type" required>
                <input class="prmgtk_input" type="text" name="identification" placeholder="Identification">
                <input class="prmgtk_input" type="text" name="product_name" placeholder="Product name">
                <label for="sla">SLA Type:</label>
                 <select name="sla" id="sla">
                 <option  value="Platinum">Platinum</option>
                 <option value="Gold">Gold</option>
                 <option value="Normal">Normal</option>
                 </select>
                 <br>
                 <option <?php echo ($row->company_id == $comp->id) ? 'selected' : ''; ?> value="<?php echo $comp->id ?>"><?php echo $comp->company_name; ?></option>
                <input class="prmgtk_input" type="text" name="start_date" id="warrantyStart" placeholder="Warranty start">
                <input class="prmgtk_input" type="number" name="period" id="period" placeholder="period">
                <input class="prmgtk_input" type="text" name="end_date" id="warrantyEnd" placeholder="Warranty end">
                <button type="submit" name="add_new_company_device" class="btn btn-submit">Add new</button>
                <a href="<?php menu_page_url( 'companies_devices' ); ?>">Back</a>
        </form>
</div>
     <?php
 }else{
?>
<div>

    <div class="full-container">
    <div class="add-new">
        <a class="btn btn-add-new" href="<?php menu_page_url( 'companies_devices' ); ?>&new">Add new device</a>
    </div>
        <script>
        var tables_to_export = [0,1,2,3,4,5,6,7,8,9];
    </script>
        <table id="table_id" class="display w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Manufacture</th>
                    <th>Device serial number</th>
                    <th>Device type</th>
                    <th>Identification</th>
                    <th>Product name</th>
                    <th>SLA Type</th>
                    <th>Warranty start</th>
                    <th>period</th>
                    <th>Warranty end</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
             if($data): foreach($data as $one):
                $company = $wpdb->get_row("SELECT * FROM $company_table WHERE id = $one->company_id");
                 ?>
                <tr>
                    <td><?php echo $one->id; ?></td>
                    <td><?php echo $company ? $company->company_name : '--'; ?></td>
                    <td><?php echo $one->manufacture; ?></td>
                    <td><?php echo $one->device_serial_number; ?></td>
                    <td><?php echo $one->device_type; ?></td>
                    <td><?php echo $one->identification ? $one->identification : ' -- '; ?></td>
                    <td><?php echo $one->product_name ? $one->product_name : ' -- '; ?></td>
                    <td><?php echo $one->sla_type ? $one->sla_type : ' -- '; ?></td>
                    <td><?php echo $one->warranty_start; ?></td>
                    <td><?php echo $one->period; ?></td>
                    <td><?php echo $one->warranty_end; ?></td>
                    <td style="display:flex;">
                
                    <a href="<?php menu_page_url( 'companies_devices' ); ?><?php echo '&edit=' . $one->id; ?>" class="btn btn-edit myBtn">Edit</a>
                
                <form action="" method = "post">
                    <input type="hidden" name="row" value="<?php echo $one->id ?>">
                    <button type="submit" onclick="if(!confirm('Are you sure ?')){ event.preventDefault() }" name="delete_company_device" class="btn btn-delete">Delete</button>
                </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <?php
    }
}