<?php
function post_to_api($post_json,$endpoint){
    $ch = @curl_init();
    @curl_setopt($ch, CURLOPT_POST, true);
    @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
    @curl_setopt($ch, CURLOPT_URL, $endpoint);
    @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = @curl_exec($ch);
    $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_errors = curl_error($ch);
    @curl_close($ch);

    return ['response' => $response,'status_code' => $status_code,'curl_errors' => $curl_errors];
}

function get_from_api($url){
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url
    ]);
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    return $resp;
}

function create_tables(){

    global $wpdb;

    $tables_parent = $wpdb->prefix.'company_info_parent';

    $tables_companies = $wpdb->prefix.'company_info';

    $tables_tickets = $wpdb->prefix.'company_tickets';

    $options = $wpdb->prefix.'comany_plugin_option';

    $tables_devices = $wpdb->prefix.'company_devices';

    $sql = "CREATE TABLE IF NOT EXISTS $tables_parent(

        id int NOT NULL AUTO_INCREMENT,

        company_name varchar (100) DEFAULT '',

        company_data varchar (100) DEFAULT '',

        hubspot_id varchar (100) DEFAULT 0,

        PRIMARY KEY (id)

    )";

    $sql1 = "CREATE TABLE IF NOT EXISTS $tables_companies(

        id int NOT NULL AUTO_INCREMENT,

        username varchar (100) DEFAULT '',

        password varchar (100) DEFAULT '',

        email varchar (100) DEFAULT '',

        phone varchar (100) DEFAULT '',

        company_id varchar (100) DEFAULT '',

        contact_id int DEFAULT 0,
        
        last_email timestamp NULL DEFAULT NULL

        PRIMARY KEY (id)

    )";



    $sql2 = "CREATE TABLE IF NOT EXISTS $tables_tickets(

        id int NOT NULL AUTO_INCREMENT,

        company_id int NOT NULL,

        user_id int NOT NULL,

        ticket_id varchar (100) DEFAULT '',

        PRIMARY KEY (id)

    )";



    $sql3 = "CREATE TABLE IF NOT EXISTS $options(

        id int NOT NULL AUTO_INCREMENT,

        options varchar (100) DEFAULT '',

        PRIMARY KEY (id)

    )";

    $sql4 = "CREATE TABLE IF NOT EXISTS $tables_devices(

        id int NOT NULL AUTO_INCREMENT,

        company_id int NOT NULL,

        device_serial_number varchar (100) DEFAULT '',

        device_type varchar (100) DEFAULT '',

        warranty_start varchar (100) DEFAULT '',

        warranty_end varchar (100) DEFAULT '',

        PRIMARY KEY (id)

    )";



    require_once(ABSPATH . "wp-admin/includes/upgrade.php");

    dbDelta( $sql );

    dbDelta( $sql1 );

    dbDelta( $sql2 );

    dbDelta( $sql3 );

    dbDelta( $sql4 );


}

function get_companies_options(){
    global $wpdb;
    $table_name = $wpdb->prefix.'comany_plugin_option';
    $options = $wpdb->get_results("SELECT * FROM $table_name WHERE id=1");

    return $options;
}

function get_company_devices($company,$all = null){
    global $wpdb;
    $tables_devices = $wpdb->prefix.'company_devices';
    $devices = $wpdb->get_results("SELECT * FROM $tables_devices WHERE company_id=$company");

    if($devices){
        if(!$all){
            $serials =[];
        foreach($devices as $device){
            $serials[] = $device->device_serial_number;
        }
        }else{
            return $devices;
        }
    }

    return $serials;
}

function get_company_by($type = 'id',$data,$password = null){
    global $wpdb;
    $table_name = $wpdb->prefix.'company_info';
    if($type == 'id'){
        $company = $wpdb->get_results("SELECT * FROM $table_name WHERE $type = $data");
    }else{
        $company = $wpdb->get_results("SELECT * FROM $table_name WHERE $type = '$data' and password = '$password'");
    }

    return $company;

}

function get_company_parent($company){
    global $wpdb;
    $table_name = $wpdb->prefix.'company_info_parent';
    $query = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$company");

    return $query;
}

function get_company_tickets($company){
    global $wpdb;
    $table_name = $wpdb->prefix.'company_tickets';
    $query = $wpdb->get_col("SELECT ticket_id FROM $table_name WHERE company_id=$company");

    return $query;
}

function insert_tid($tid,$company,$user,$image_id = null){
    global $wpdb;
    $table_name = $wpdb->prefix.'company_tickets';
    $data = [ 'company_id' => $company, 'ticket_id' => $tid,'user_id' => $user ];
    if($image_id){
        $data['attachment']= $image_id;
    }
    $query = $wpdb->insert( $table_name, $data );

    return $query;
}

function page_all_template($template){

    global $post,$wpdb;

    if(!$post){
      return $template;
    }
    $options = get_companies_options();
    
    if(!$options){
        return $template;
    }
    $option_obj=json_decode($options[0]->options);

    if( $option_obj->ppage and  $post->ID == $option_obj->ppage ) {



        if ( $theme_file = locate_template( array( 'page-companies.php' ) ) ) {
  
            $template = $theme_file;
  
        } else {
  
            $template = P_PATH . 'pages/page-companies.php';
  
        }
  
    }
  
  
  
    if($template == '') {
  
        throw new \Exception('No template found');
  
    }
  
  
  
    return $template;


}


require 'options-page.php';
require 'companies-options.php';
require 'companies-users.php';
require 'companies-devices.php';
require 'companies-tickets.php';


function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}

function wpb_sender_email( $original_email_address ) {
    return 'a.support@mera-tech.com';
}

function wpb_sender_name( $original_email_from ) {
    return 'Mera-Tech Support';
}



function my_action_callback() {
    global $wpdb; // this is how you get access to the database
    $company = $_POST['company'];
    $serial = $_POST['serial'];

    $company_devices = get_company_devices($company);
    $company = get_company_by('id',$_SESSION['company_user']);
    if(!in_array($serial,$company_devices)){
        wp_send_json( ['status' => 'false','message' => 'No such serial belongs to you please try again or contact us ']);
        die(); // this is required to return a proper result
    }
        


    $tables_devices = $wpdb->prefix.'company_devices';
    $device = $wpdb->get_row( "SELECT * FROM $tables_devices WHERE device_serial_number = '$serial'" );
    // wp_send_json($device);
    // die();
    if($device){

        $warranty_end = strtotime($device->warranty_end);
        $last_email = $company[0]->last_email;
        $today = strtotime(date('d-m-Y'));

        if($warranty_end < $today){
            
            if(!$last_email or $today > strtotime($last_email)){
                $title = 'SLA issue';
                $message = 'The device  with S/N : ' . $serial . ' has expired SLA, please contact us to renew';
                add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
                $send=wp_mail($company[0]->email, $title, $message); // send mail 
                remove_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
                
                $query = $wpdb->update($wpdb->prefix.'company_info',['last_email' => date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ) ],['id' => $company[0]->id]);
                
            }

            wp_send_json( ['status' => 'false','message' => 'Support period ended, please contact us.','save'=> [$send,$query]] );
            die(); // this is required to return a proper result
        }else{
            wp_send_json([
                'status' => 'true',
                'message' => 'Device type for this serial',
                'device_type' => $device->device_type,
                'manufacture' => $device->manufacture ,
                'product_name' => $device->product_name,
                'identification' => $device->identification,
                'sla_type' => $device->sla_type
                ]);
            die(); // this is required to return a proper result
        }

    }

    
}

function save_contact_id_if_not_exist($contact = NULL,$company){
    global $wpdb;
    $tables_companies = $wpdb->prefix.'company_info';
    if(!$contact){
        $query = $wpdb->get_row("SELECT * FROM $tables_companies WHERE id = $company");
    }else{
        $query = $wpdb->update($tables_companies,['contact_id' => $contact],['id' => $company]);
    }
    
    return $query;

}

/* Auth Code : Upload Image -- */

function uploadimage($image){

    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    
    $uploadedfile = $image;
    
    $upload_overrides = array( 'test_form' => false );
    
    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
    
    return $movefile;
}

/* Auth Code : Handel Uploaded Image */

function insertImage($image){

    $old_data=uploadimage($image);
    
    if(isset($old_data['error'])){
        return $old_data;
    }
    
    // $filename should be the path to a file in the upload directory.
    $filename = $old_data['file'];


    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $filename ), null );

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

    // Prepare an array of post data for the attachment.
    $attachment = array(
        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Insert the attachment.
    $attach_id = wp_insert_attachment( $attachment, $filename );

    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    return $attach_id;

}