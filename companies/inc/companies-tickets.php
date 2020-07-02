<?php

function companies_tickets(){
    global $wpdb;

    $table_name = $wpdb->prefix.'company_tickets';
    $table_company = $wpdb->prefix.'company_info_parent';
    $table_users = $wpdb->prefix.'company_info';

    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['delete_ticket'])){
            $delete = $wpdb->delete($table_name,['id' => $_POST['row']]);

            if($delete){
                $errors[] = 'Ticket has been deleted';
            }else{
                $errors[] = 'No data effected .. ERROR';
            }
        }

        if(isset($_POST['add_new_ticket'])){
            $user = $_POST['user'];
            $user_data = $wpdb->get_row("SELECT * FROM $table_users WHERE id = $user");
            $insert = $wpdb->insert($table_name,[
                'company_id' => $user_data->company_id,
                'user_id' => $_POST['user'],
                'ticket_id' => $_POST['hubspot_id'] ? $_POST['hubspot_id'] : 0
                ]);

                if($insert){
                    $errors[] = 'Ticket has been created';
                }else{
                    $errors[] = 'No data created .. ERROR';
                }
        }

        if(isset($_POST['edit_ticket'])){
            $user = $_POST['user'];
            $user_data = $wpdb->get_row("SELECT * FROM $table_users WHERE id = $user");
            $update = $wpdb->update($table_name,[
                'company_id' => $user_data->company_id,
                'user_id' => $_POST['user'],
                'ticket_id' => $_POST['hubspot_id'] ? $_POST['hubspot_id'] : 0
            ],['id' => $_POST['id']]);

                if($update){
                    $errors[] = 'Ticket has been updated';
                }else{
                    $errors[] = 'No data updated .. ERROR';
                }
        }
    }

    $data = $wpdb->get_results("SELECT * FROM $table_name");
    $users = $wpdb->get_results("SELECT * FROM $table_users");
    $companies = $wpdb->get_results("SELECT * FROM $table_company");

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
            <h1>Add new ticket</h1>
            <input type="hidden" name="id" value="<?php echo $row->id; ?>">
            <select name="user" id="user" class="prmgtk_input" required>
                    <?php if($users): ?>
                        <option value="">Select user</option>
                        <?php foreach($users as $comp): ?>
                            <option <?php echo ($row->user_id == $comp->id) ? 'selected' : ''; ?> value="<?php echo $comp->id ?>"><?php echo $comp->username; ?></option>
                        <?php endforeach; else: ?>
                        <option value="">No users exist</option>
                    <?php endif; ?>
                    
            </select>
                <input class="prmgtk_input" type="text" value="<?php echo $row->ticket_id ?>" name="hubspot_id" placeholder="Hubspot ID" required>
                <button type="submit" name="edit_ticket" class="btn btn-submit">Update</button>
                <a href="<?php menu_page_url( 'companies_tickets' ); ?>">Back</a>
            </form>
    </div>
<?php
        }
 }else{
?>
<div style="display:flex;">
    <div class="half-container" style="width:80%">
            <script>
        var tables_to_export = [0,1,2,3];
    </script>
        <table id="table_id" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Username</th>
                    <th>Ticket id</th>
                    <th>Attachment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
             if($data): foreach($data as $one):
                $company = $wpdb->get_row("SELECT * FROM $table_company WHERE id = $one->company_id");
                $user = $wpdb->get_row("SELECT * FROM $table_users WHERE id = $one->user_id");
                 ?>
                <tr>
                    <td><?php echo $one->id; ?></td>
                    <td><?php echo $company->company_name ?? '--'; ?></td>
                    <td><?php echo $user->username ?? '--'; ?></td>
                    <td><?php echo $one->ticket_id; ?></td>
                    <td><?php if($one->attachment){ ?> <a target="_blank" href="<?php echo wp_get_attachment_url($one->attachment); ?>">Attachment</a> <?php }else{ echo '--';} ?></td>
                    <td style="display:flex;">
                
                    <a href="<?php menu_page_url( 'companies_tickets' ); ?><?php echo '&edit=' . $one->id; ?>" class="btn btn-edit myBtn">Edit</a>
                
                <form action="" method = "post">
                    <input type="hidden" name="row" value="<?php echo $one->id ?>">
                    <button type="submit" onclick="if(!confirm('Are you sure ?')){ event.preventDefault() }" name="delete_ticket" class="btn btn-delete">Delete</button>
                </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="half-container">
            <form action="" method="post">
            <h1>Add new ticket</h1>
            <select name="user" id="user" class="prmgtk_input" required>
                    <?php if($users): ?>
                        <option value="">Select user</option>
                        <?php foreach($users as $comp): ?>
                            <option value="<?php echo $comp->id ?>"><?php echo $comp->username; ?></option>
                        <?php endforeach; else: ?>
                        <option value="">No users exist</option>
                    <?php endif; ?>
                    
            </select>
                <input class="prmgtk_input" type="text" name="hubspot_id" placeholder="Hubspot ID" required>
                <button type="submit" name="add_new_ticket" class="btn btn-submit">Add New</button>
            </form>
    </div>
</div>

    <?php
    }
}