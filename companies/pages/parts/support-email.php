<?php 
if($image_id){
    $fileData = '<div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">File url : </p>
        <p style="padding: 0.5rem;">' . $image_url . '</p>
    </div>';
}else{
    $fileData = '';
}
return '<div class="container" style="display: table;table-layout: fixed;width: 100%;min-width: 620px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
 <table class="top-panel center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;border-spacing: 0;margin: 0 auto;width: 602px;">
     <tbody>
     <tr>
         <td class="title" style="padding: 8px 0;vertical-align: top;text-align: left;width: 300px;color: #616161;font-family: Roboto, Helvetica, sans-serif;font-weight: 400;font-size: 12px;line-height: 14px;">' . get_bloginfo("name") . '</td>
         <td class="subject" style="padding: 8px 0;vertical-align: top;text-align: right;width: 300px;color: #616161;font-family: Roboto, Helvetica, sans-serif;font-weight: 400;font-size: 12px;line-height: 14px;"><a class="strong" href="#" target="_blank" style="text-decoration: none;color: #616161;font-weight: 700;">' . get_bloginfo("url") . '</a></td>
     </tr>
     <tr>
         <td class="border" colspan="2" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #e0e0e0;width: 1px;">&nbsp;</td>
     </tr>
     </tbody>
 </table>

 <div class="spacer" style="font-size: 1px;line-height: 16px;width: 100%;">&nbsp;</div>

 <table class="main center" width="602" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;border-spacing: 0;-webkit-box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 1px 2px 0 rgba(0, 0, 0, 0.24);-moz-box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 1px 2px 0 rgba(0, 0, 0, 0.24);box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 1px 2px 0 rgba(0, 0, 0, 0.24);margin: 0 auto;width: 602px;">
     <tbody>
     <tr>
         <td class="column" style="padding: 0;vertical-align: top;text-align: left;background-color: #ffffff;font-size: 14px;">
             <div class="column-top" style="font-size: 24px;line-height: 24px;">&nbsp;</div>
             <table class="content" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;border-spacing: 0;width: 100%;">
                 <tbody>
                 <tr>
                     <td class="padded" style="padding: 0 24px;vertical-align: top;">
                       <h1 style="margin-top: 0;margin-bottom: 16px;color: #212121;font-family: Roboto, Helvetica, sans-serif;font-weight: 400;font-size: 20px;line-height: 28px;">
                       New ticket created by ' . $company[0]->username . '   from ' . $profile->company_name . ' Ticket No : ' . $tid .  '
                       </h1>
<div style="margin:0 auto;display:block;padding:5px;">

    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Company : ' . $profile->company_name . '</p>
    </div>

    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Email : ' . $data['email'] . '</p>
    </div>

    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Phone : ' . $data['mobilephone'] . '</p>
    </div>


    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Issue Type :  ' . $data['issue_type'] . '</p>
    </div>
   
    
    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Issue : ' . $data['subject'] . '</p>
    </div>

    
    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Impact : ' . $data['impact'] . '</p>
    </div>
	
	 <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Priority : ' . $data['hs_ticket_priority'] . '</p>
    </div>

    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Device Serial Number(s) :  ' . $postData[6]['value'] . '</p>
    </div>

    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Device Type & Model Number :  ' . $postData[7]['value'] . '</p>
    </div>
   
    
    <div style="padding: 0.5rem;display:flex;">
        <p style="padding: 0.5rem;">Details : ' . $data['content'] . '</p>
    </div>

    
    ' . $fileData . '

</div>
                     </td>
                 </tr>
                 </tbody>
             </table>
             <div class="column-bottom" style="font-size: 8px;line-height: 8px;">&nbsp;</div>
         </td>
     </tr>
     </tbody>
 </table>
</div>';