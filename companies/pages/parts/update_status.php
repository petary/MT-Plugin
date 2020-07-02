   
   if(isset($_POST["Re-open"])){
        $postStatus[] = [
            'name' => 'hs_pipeline_stage',
            'value' => 778636
        ];
        $associat_opts2 = array(
        
                    'http' =>
        
                    array(
        
                        'method'  => 'PUT',
        
                        'header'  => "Content-Type: application/json" ,
        
                        'content' => json_encode($postStatus)
        
                    )
        
                );
         $context22 = stream_context_create($associat_opts2);
                
        define('API_UPDATE_STATUS','https://api.hubapi.com/crm-objects/v1/objects/tickets/$item["objectId"]?hapikey='); 
       $item['properties']['hs_pipeline_stage']['value']==778636;
        $update_status = API_UPDATE_STATUS . $optd->api ;
    post_to_api(json_encode($associat_opts2),$update_status);
    
  }  

     