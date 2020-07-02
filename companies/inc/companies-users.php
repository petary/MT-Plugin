<?php

function companies_users(){
    global $wpdb;

    $table_name = $wpdb->prefix.'company_info';
    $company_table = $wpdb->prefix.'company_info_parent';
    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        if(isset($_POST['delete_company_user'])){
            $delete = $wpdb->delete($table_name,['id' => $_POST['row']]);

            if($delete){
                $errors[] = 'User has been deleted';
            }else{
                $errors[] = 'No data effected .. ERROR';
            }
        }

        if(isset($_POST['add_new_company_user'])){
            $insert = $wpdb->insert($table_name,[
                'username'  => $_POST['username'],
                'password'  => $_POST['password'],
                'email'     => $_POST['email'],
                'phone'  => $_POST['phone'],
                'company_id'  => $_POST['company'],
                'contact_id'  => $_POST['contact_id'] ? $_POST['contact_id'] : 0,
                ]);

                if($insert){
                    $errors[] = 'User has been created';
                }else{
                    $errors[] = 'No data created .. ERROR';
                }
        }

        if(isset($_POST['edit_company_user'])){
            $update = $wpdb->update($table_name,[
                'username'  => $_POST['username'],
                'password'  => $_POST['password'],
                'email'     => $_POST['email'],
                'phone'  => $_POST['phone'],
                'company_id'  => $_POST['company'],
                'contact_id'  => $_POST['contact_id'],
            ],['id' => $_POST['id'] ]);

                if($update){
                    $errors[] = 'Company has been updated';
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
            <h1>Edit User</h1>
            <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                <input class="prmgtk_input" type="text" name="username" required placeholder="Username" value="<?php echo $row->username; ?>" required>
                <input class="prmgtk_input" type="text" name="password" value="<?php echo $row->password; ?>" placeholder="Password" required>
                <input class="prmgtk_input" type="email" name="email" value="<?php echo $row->email; ?>" placeholder="email" required>
                <input class="prmgtk_input" type="number" name="phone" value="<?php echo $row->phone; ?>" placeholder="Phone">
                <select name="company" id="company" class="prmgtk_input" required>
                    <?php if($companies): ?>
                        <option value="">Select company</option>
                        <?php foreach($companies as $comp): ?>
                            <option <?php echo ($row->company_id == $comp->id) ? 'selected' : ''; ?> value="<?php echo $comp->id ?>"><?php echo $comp->company_name; ?></option>
                        <?php endforeach; else: ?>
                        <option value="">No companies exist</option>
                    <?php endif; ?>
                    
                </select>
                <input class="prmgtk_input" value="<?php echo $row->contact_id; ?>" type="text" name="contact_id" placeholder="Hubspot ID">
                <button type="submit" name="edit_company_user" class="btn btn-submit">Edit user</button>
                <a href="<?php menu_page_url( 'companies_users' ); ?>">Back</a>
            </form>
</div>
<?php
        }
 }elseif(isset($_GET['new'])){
     ?>

<div class="half-container">
            <form action="" method="post">
            <h1>Add new User</h1>
                <input class="prmgtk_input" type="text" name="username" required placeholder="Username" required>
                <input class="prmgtk_input" type="text" name="password" placeholder="Password" required>
                <input class="prmgtk_input" type="email" name="email" placeholder="email" required>
                <input class="prmgtk_input" type="number" name="phone" placeholder="Phone">
                <select name="company" id="company" class="prmgtk_input" required>
                    <?php if($companies): ?>
                        <option value="">Select company</option>
                        <?php foreach($companies as $comp): ?>
                            <option value="<?php echo $comp->id ?>"><?php echo $comp->company_name; ?></option>
                        <?php endforeach; else: ?>
                        <option value="">No companies exist</option>
                    <?php endif; ?>
                    
                </select>
                <input class="prmgtk_input" type="text" name="contact_id" placeholder="Hubspot ID">
                <button type="submit" name="add_new_company_user" class="btn btn-submit">Add new</button>
                <a href="<?php menu_page_url( 'companies_users' ); ?>">Back</a>
            </form>
</div>
     <?php
 }else{
?>
<div>

    <div class="full-container">
    <div class="add-new">
        <a class="btn btn-add-new" href="<?php menu_page_url( 'companies_users' ); ?>&new">Add new user</a>

    </div>
    <script>
        var tables_to_export = [0,1,2,3,4,5,6];
    </script>
        <table id="table_id" class="display w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Hubspot ID</th>
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
                    <td><?php echo $one->username; ?></td>
                    <td><?php echo $one->password; ?></td>
                    <td><?php echo $one->email; ?></td>
                    <td><?php echo $one->phone; ?></td>
                    <td><?php echo $company ? $company->company_name : '--'; ?></td>
                    <td><?php echo $one->contact_id; ?></td>
                    <td style="display:flex;">
                
                    <a href="<?php menu_page_url( 'companies_users' ); ?><?php echo '&edit=' . $one->id; ?>" class="btn btn-edit myBtn">Edit</a>
                
                <form action="" method = "post">
                    <input type="hidden" name="row" value="<?php echo $one->id ?>">
                    <button type="submit" onclick="if(!confirm('Are you sure ?')){ event.preventDefault() }" name="delete_company_user" class="btn btn-delete">Delete</button>
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