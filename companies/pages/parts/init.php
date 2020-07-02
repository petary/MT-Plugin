<?php

    $opt = get_companies_options();

    $optd = json_decode($opt[0]->options);
    
     $profile = get_company_parent($_SESSION['company']);

if($_SERVER['REQUEST_METHOD'] == 'POST'){



    if(is_company_logged_in()){
        $company = get_company_by('id',$_SESSION['company_user']);
        
        $company_devices = get_company_devices($_SESSION['company']);
    }


    if(isset($_POST['login'])){

        $email = $_POST['email'];

        $password = $_POST['password'];

        $errors = false;



        if(!$email){

            $_SESSION['danger'] = 'Email is required';

            $errors = true;

        }

        if(!$password){

            $_SESSION['danger'] = 'Password is required';

            $errors = true;

        }



        if(!$errors){

            $company=get_company_by('email',$email,$password);

            if(count($company) > 0){

                    $_SESSION['company'] = $company[0]->company_id;

                    $_SESSION['company_user'] = $company[0]->id;

                    $_SESSION['success'] = 'Sign-in successful';

                    wp_safe_redirect( get_permalink( $optd->ppage ) );

                    exit;

            }else{
                
                $_SESSION['danger'] = 'Wrong email or password';
            }

        }



    }



    if(isset($_POST['hubspot'])){

        // echo '<pre>';
        // print_r($company_devices);
        // echo '</pre>';
        // exit;

        $data = [

            'company' => $profile->company_name,

            'email' => $_POST['email'],

            'mobilephone' => $_POST['mobile'],

            'subject' => $_POST['issue'],
            
            'issue_type' => $_POST['issue_type'],

            'content' => $_POST['details'],

            'device_serial_number' => $_POST['dsm'],

            'device_type_model_number' => $_POST['dtmn'],
            
            'impact' => $_POST['impact'],
			
			'hs_ticket_priority' => $_POST['priority']

        ];
        $image_id = null;
        if($_FILES['fileh']['error'] !== 4){
            $image_id = insertImage($_FILES['fileh']);
            
            if(isset($image_id['error'])){
                $_SESSION['danger'] = $image_id['error'];
                wp_safe_redirect( get_permalink( $optd->ppage ) );
                 exit;
            }
            
            $image_url = wp_get_attachment_url($image_id);
        }


 
         

        //  $sup_message = require('customer-email.php');
        
         $notfound ='';
         
            if(is_array($data['device_serial_number'])){
                foreach($data['device_serial_number'] as $o){
                    $notfound = in_array($o,$company_devices);
                }
            }else{
                $notfound = in_array($data['device_serial_number'],$company_devices);
            }
        
        if(!$notfound){
            $_SESSION['danger'] = 'No such serial belongs to you please try again or contact us';
        }else{
        $postData = [];

        foreach($data as $key => $value){
            
            $finalValue = '';
            
            if(is_array($value)){
                foreach($value as $val){
                    $finalValue .= $val . ' , ';
                }
                $finalValue = rtrim($finalValue,', ');
            }else{
                $finalValue= $value;
            }

            $postData[] = [

                'name' => $key,

                'value' => $finalValue

            ];

        }

        $postData[] = [
            'name' => 'hs_pipeline_stage',
            'value' => 1
        ];
        
        $opts = array(

            'http' =>

            array(

                'method'  => 'POST',

                'header'  => "Content-Type: application/json" ,

                'content' => json_encode($postData)

            )

        );

        $arr = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $company[0]->email
                ),
                array(
                    'property' => 'firstname',
                    'value' => $company[0]->username
                ),
                array(
                    'property' => 'phone',
                    'value' => $company[0]->phone
                )
            )
        );




        $context  = stream_context_create($opts);



        $url = API_PT. $optd->api;



        $url3 = CONTACT .  $optd->api;

        /* Test */

            // $contact = post_to_api(json_encode($arr),$url3);
            // echo '<pre>';
            // print_r($contact);
            // echo '</pre>';
            // die();

        /* Test */
        
        $d = save_contact_id_if_not_exist(null,$_SESSION['company_user']);
        if(!$d->contact_id){
            $contact = post_to_api(json_encode($arr),$url3);
            if($contact['status_code'] === 409){
                 $contact_result = json_decode($contact['response'])->identityProfile->vid ? json_decode($contact['response'])->identityProfile->vid : null;
                 $save_contact_id=save_contact_id_if_not_exist($contact_result,$_SESSION['company_user']);
            }elseif($contact['status_code'] === 200){
                $contact_result = json_decode($contact['response'])->vid ? json_decode($contact['response'])->vid : null;
                $save_contact_id=save_contact_id_if_not_exist($contact_result,$_SESSION['company_user']);  
            }
            $d = save_contact_id_if_not_exist(null,$_SESSION['company_user']);
        }


        $result = file_get_contents($url, false, $context);

        

        if($result !== false AND !empty($result)){
            $result = json_decode($result);
            $tid = $result->properties->hs_ticket_id->value;
            $save = insert_tid($tid,$_SESSION['company'],$_SESSION['company_user'],$image_id);
            $title = 'Ticket has been created successfully';
            $sub_title = 'New ticket has been created';
            $message = require('customer-email.php'); //'Ticket has been created successfully and we will contact you ASAP - ticket NO# ( ' . $tid . ' )';
            $sup_message = require('support-email.php');
            add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');

            $send=wp_mail($data['email'], $title, $message); // send mail 
            
            $send2=wp_mail('a.support@mera-tech.com', $sub_title, $sup_message); // send mail  
        
            remove_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');

            if($send){

                $associat_data =[
                    'fromObjectId' => $d->contact_id, // contact_id
                    'toObjectId' => $result->objectId, // ticket objectId
                    'category' => 'HUBSPOT_DEFINED',
                    'definitionId' => 15
                ];
        
                $associat_opts = array(
        
                    'http' =>
        
                    array(
        
                        'method'  => 'PUT',
        
                        'header'  => "Content-Type: application/json" ,
        
                        'content' => json_encode($associat_data)
        
                    )
        
                );

              
                $context2 = stream_context_create($associat_opts);
                $url2 = ASSOCIAT . $optd->api;


                $associat = @file_get_contents($url2, false, $context2);
                $_SESSION['success'] = 'Ticket created successfully - Ticket Number is : ' . $tid;
                wp_safe_redirect( get_permalink( $optd->ppage ) );
                 exit;
            }else{
                $_SESSION['danger'] = 'ERROR ...';
                wp_safe_redirect( get_permalink( $optd->ppage ) );
                 exit;
            }
            
        }


    }

    }



    if(isset($_POST['company_logout'])){

        unset($_SESSION['company']);

        session_destroy();

        wp_safe_redirect( get_permalink( $optd->ppage ) );

        exit;

    }
    
    
    if(isset($_POST['re_open'])){
        
        $url = 'https://api.hubapi.com/crm-objects/v1/objects/tickets/' . $_POST["re_open"] .'?hapikey=' . $optd->api; 
        
        $data = [
                    [
                    'name' => "hs_pipeline_stage",
                    'value'=> "778636"
                    ]
                ];

        $data_string =json_encode($data);                                                                     
    
        $ch = curl_init($url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                       
        );                                                                                                                   

        $result = curl_exec($ch);
        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        if($status_code === 200){
                $_SESSION['success'] = 'The ticket was successfully reopened';
            wp_safe_redirect( get_permalink( $optd->ppage ) . '?tickets' );
            exit;
        }
    
    }





}