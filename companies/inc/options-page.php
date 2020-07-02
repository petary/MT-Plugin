<?php

function my_custom_menu_page(){







    global $wpdb;



    $table_name = $wpdb->prefix.'comany_plugin_option';



    $pages = new WP_Query(['post_type' => 'page','posts_per_page' => -1]);





    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $options = $wpdb->get_results("SELECT * FROM $table_name WHERE id=1");

    $errors=[];



        $data = [



            'api' => $_POST['api'],



            'ppage' => $_POST['ppage']



        ];



        if(!empty($data['api'])){



            $opts = array(

                'http'=>array(

                  'method'=>"GET",

                  'header'=>"Content-Type:application/json"

                )

              );

              

              $context = stream_context_create($opts);



            $url = API_GET_STATUS . $data['api'];



            $get_api_status = file_get_contents($url,false,$context);



            if(!$get_api_status){

                $errors[] = 'Wrong Api key please make sure the key is valid';

                $data['api'] = '';

            }



        }

        



    if(!$errors){

        $fData = json_encode($data);

        if(empty($options)){

            $wpdb->insert($table_name,['options' => $fData],['%s']);

            $errors[]= 'Settings saved';

        }else{

            $wpdb->update($table_name,['options' => $fData],['id' => 1],['%s']);

            $errors[]= 'Settings saved';

        }

    }

        

    }



    $options = $wpdb->get_results("SELECT * FROM $table_name WHERE id=1");



    $optionsData = json_decode($options[0]->options);

    ?>



<form action="" method="post">



    <h2 style="text-align:center;padding:20px 0;">Companies Plugin Settings</h2>



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



<div class="full-container">
<table class="form-table">



<tbody>



    <tr>



        <td><label for="api">API Key</label></td>



        <td>



            <input style="width: 300px;" type="text" id="api" name="api" placeholder="API key"

                value="<?php echo $optionsData->api ? $optionsData->api : ''; ?>">

            <?php echo $optionsData->api ? '<span style="color: #fff;background: green;padding: 2px 8px;">Active</span>' : ''; ?>



        </td>



    </tr>







    <tr>



        <td><label for="ppage">Plugin Page</label></td>



        <td>



            <select name="ppage" id="ppage">



                <?php if($pages->have_posts(  )): ?>



                <option value="">Selecte Page</option>



                <?php

                            while($pages->have_posts()): $pages->the_post(); global $post;

                            $selected = $post->ID == $optionsData->ppage ? 'selected' : ''

                            ?>



                <option <?php echo $selected; ?> value="<?php echo $post->ID ?>"><?php the_title(); ?></option>



                <?php endwhile; wp_reset_postdata(  ); else: ?>



                <option disabled>No Pages exist</option>



                <?php endif; ?>



            </select>



        </td>



    </tr>







</tbody>



</table>



<p class="submit"><input type="submit" value="Save Changes" class="button-primary" name="Submit"></p>
</div>







</form>



<?php



}