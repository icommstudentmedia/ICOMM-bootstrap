<?php
include 'byuicomm.net/wp-content/themes/icomm/includes/rsvp/phpmailer/class.phpmailer.php';

//Function that Checks if User is Already Registered --------------------------------------

function userAlreadyRegistered($privateId) { 
 
    global $wpdb;

              //Insert user into guests table
              $numberUsersFound = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM icom_rsvp_guests WHERE private_id = %s", $privateId));
              
              $alreadyRegistered = false;
              
              if($numberUsersFound != 0) {
                  $alreadyRegistered = true;
              }
              
     return $alreadyRegistered;
}



//Function that Resends the User's RSVP Update Link --------------------------------------

function resendRSVPFormLink($privateId) { 
 
    global $wpdb;
    
              //Get the User's Email Address
              $userEmail = $wpdb->get_var($wpdb->prepare("SELECT email FROM icom_rsvp_guests WHERE private_id = %s", $privateId));
                            
                    // Create a html version of the email
                        $htmlMessage = "<html>";
                        $htmlMessage .= "<head>";
                        $htmlMessage .= "<title>RSVP Link | Scroll / I~Comm Student Media Reunion</title>";
                        $htmlMessage .= "</head>";
                        $htmlMessage .= "<body>";
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "<p>&emsp;&emsp;A request has been made to resend your RSVP form link.</p>";
                        $htmlMessage .= "<p>&emsp;&emsp;You may view your current reservation information at anytime";                    
                        $htmlMessage .= "<br />&emsp;&emsp;by following this link* to your personal RSVP form:";                    
                        $htmlMessage .= "<br />&emsp;&emsp;&emsp;<a href='https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$privateId' title='View your Personal RSVP Form'>https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$privateId</a>";                    
                        $htmlMessage .= "</p>";

                        $htmlMessage .= "<br />";

                        $htmlMessage .= "<h4><b>Didn't request this link?</b></h4>";                    
                        $htmlMessage .= "<p>&emsp;&emsp; If you did not request for your link to be resent, please email us at the following address so we may take steps to correct the problem: icomm@byui.edu.</p>";                    
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "<br /><p>&emsp;&emsp;Thank you,";                    
                        $htmlMessage .= "<br />&emsp;&emsp;The I~Comm Student Media Staff";                       
                        $htmlMessage .= "<br />&emsp;&emsp; <a href='http://www.byuicomm.net' title='I~Comm Student Media Website'>http://www.byuicomm.net</a></p>";
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "<br /><p>&emsp;&emsp;<b>*</b><i>The personal RSVP form link in this email contains information intended solely";                    
                        $htmlMessage .= "<br />&emsp;&emsp; for its named recipient and is not to be shared with third parties unless otherwise";                    
                        $htmlMessage .= "<br />&emsp;&emsp; specified. Any unauthorized review, use, disclosure or distribution is prohibited.</i></p>";                    
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "<br />";
                        $htmlMessage .= "</body>";
                        $htmlMessage .= "</html>";



                    // Create a text version of the email
                        $txtMessage .= "\n";
                        $txtMessage .= "\tA request has been made to resend your RSVP form link.";
                        $txtMessage .= "\n";
                        $txtMessage .= "\n\tYou may view your current reservation information at anytime";                    
                        $txtMessage .= "\n\tby following this link* to your personal RSVP form:";                    
                        $txtMessage .= "\n\t https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$privateId";                    
                        $txtMessage .= "\n";

                        $txtMessage .= "\n\tDidn't request this link?";                    
                        $txtMessage .= "\n";
                        $txtMessage .= "\n\tIf you did not request for your link to be resent, please email us at the following address so we may take steps to correct the problem: icomm@byui.edu.";                    
                        $txtMessage .= "\n";
                        $txtMessage .= "\n";
                        $txtMessage .= "\n";
                        $txtMessage .= "\n\tThank you,";                    
                        $txtMessage .= "\n\tThe I~Comm Student Media Staff";                       
                        $txtMessage .= "\n\t http://www.byuicomm.net";
                        $txtMessage .= "\n";
                        $txtMessage .= "\n";
                        $txtMessage .= "\n";
                        $txtMessage .= "\n\t* The personal RSVP form link in this email contains information intended solely";                    
                        $txtMessage .= "\n\t  for its named recipient and is not to be shared with third parties unless otherwise";                    
                        $txtMessage .= "\n\t  specified. Any unauthorized review, use, disclosure or distribution is prohibited.";                    
                        $txtMessage .= "\n";
                        $txtMessage .= "\n";
                        $txtMessage .= "\n";


                        // Hookup and send the html and text versions of the email    
                        $mail = new PHPMailer();
                        $mail->IsMail();

                        $mail->AddReplyTo("Scroll.Icomm.Reunion@gmail.com", "I~Comm Student Media");
                        $mail->AddAddress($userEmail);
                        $mail->SetFrom("Scroll.Icomm.Reunion@gmail.com", "I~Comm Student Media");
                        $mail->Subject = "RSVP Updated | Scroll / I~Comm Student Media Reunion";

                        $mail->AltBody = $txtMessage;
                        $mail->MsgHTML($htmlMessage);
                        $mail->Send();


                        $successMessage = "Thank you. You should be receiving an email shortly.";
              
              
              $regResults = array($privateId,$successMessage,);
              
              
    return $regResults;    
    }



//Function that Retrieves the User's Registration Info --------------------------------------

function getReservation($privateId) { 
 
    global $wpdb;

              //Pull user registration info from the guests and events tables
              $userRegistrationInfo = $wpdb->get_results($wpdb->prepare("SELECT guest_name, staff_years, spouse_name, children_attending, email, phone, street_address, city, state, zipcode, twitter_name, number_adult_banquet_guests, number_child_banquet_guests, event_name FROM ((SELECT event_name, number_adult_banquet_guests, number_child_banquet_guests, icom_rsvp_guests_id, ire.icom_rsvp_events_id FROM icom_rsvp_events ire RIGHT JOIN icom_rsvp_events_guests sr ON sr.icom_rsvp_events_id WHERE ire.icom_rsvp_events_id = sr.icom_rsvp_events_id ORDER BY ire.icom_rsvp_events_id ASC) e INNER JOIN icom_rsvp_guests g USING(icom_rsvp_guests_id)) WHERE g.private_id = %s", $privateId), ARRAY_N);

             $organizedInfo  = array();

             $organizedInfo[0]= $userRegistrationInfo[0][0];
             $organizedInfo[1]= $userRegistrationInfo[0][1];
             $organizedInfo[2]= $userRegistrationInfo[0][2];
             $organizedInfo[3]= $userRegistrationInfo[0][3];
             $organizedInfo[4]= $userRegistrationInfo[0][4];
             $organizedInfo[5]= $userRegistrationInfo[0][5];
             $organizedInfo[6]= $userRegistrationInfo[0][6];
             $organizedInfo[7]= $userRegistrationInfo[0][7];
             $organizedInfo[8]= $userRegistrationInfo[0][8];
             $organizedInfo[9]= $userRegistrationInfo[0][9];
             $organizedInfo[10]= $userRegistrationInfo[0][10];

             for($i=0; $i < count($userRegistrationInfo); $i++) {
                  $currentRow = $userRegistrationInfo[$i];
                  
                  if ($currentRow[13] == 'Scroll / I~Comm Reunion Banquet Dinner') {
                    $organizedInfo[11]= $currentRow[11];
                    $organizedInfo[12]= $currentRow[12];
                  }
              }
              
             for($i=0; $i < count($userRegistrationInfo); $i++) {
                  $currentRow = $userRegistrationInfo[$i];
                  
                  $organizedInfo[]= $currentRow[13];
              }
              
    return $organizedInfo;
}



//Function that Registers a User for the Event --------------------------------------

function regUser($uname, $staffYears, $email_addrss, $phoneNum, $twName, $stAddress, $city, $state, $zipcode, $unameSpouse, $numChildren, $reception, $tours, $dinner, $dinnerAdults, $dinnerChildren, $otherAlumniEvents) {

    global $wpdb;
    
           $successMessage;
              
         //Create a Private ID from Hashed Values using sha1 (makes a 40 character hash)
              $privateId = sha1($staffYears.$phoneNum.$uname.$stAddress.$email_addrss);
              
              $userIsAlreadyRegistered = userAlreadyRegistered($privateId);
              
              if($userIsAlreadyRegistered == false) {

                    //Insert user into guests table
                    $successfulQueryA = $wpdb->query($wpdb->prepare("INSERT INTO icom_rsvp_guests (private_id, guest_name, staff_years, email, phone, twitter_name, street_address, city, state, zipcode, spouse_name, children_attending) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %d)", $privateId, $uname, $staffYears, $email_addrss, $phoneNum, $twName, $stAddress, $city, $state, $zipcode, $unameSpouse, $numChildren));

                    //Identifying the events the user will be attending
                    $eventsAttending = array();

                    if($reception === "yes") {
                        $eventsAttending[] = 'Scroll / I~Comm Reunion Reception';
                    }
                    if($tours === "yes") {
                        $eventsAttending[] = 'Walking Campus Tours';
                    }
                    if($dinner === "yes") {
                        $eventsAttending[] = 'Scroll / I~Comm Reunion Banquet Dinner';
                    }
                    if($otherAlumniEvents === "yes") {
                        $eventsAttending[] = 'Other Alumni Day Events';
                    }



                    $allEventsRegistered = true;

                    $spouseAttending = "";
                    if(!empty($unameSpouse)){
                        $spouseAttending = 1; 
                    }


                    //Inserting each event the user will be attending in the events_guests table
                    if ($successfulQueryA){
                        for($i=0; $i< count($eventsAttending); $i++) {
                            $currentevent = $eventsAttending[$i];
                            $adultDinnerNumber = "";
                            $childrenDinnerNumber = "";

                            if($currentevent == 'Scroll / I~Comm Reunion Banquet Dinner'){
                                $adultDinnerNumber = $dinnerAdults;
                                $childrenDinnerNumber = $dinnerChildren;
                            }

                            $successfulQuery = $wpdb->query($wpdb->prepare("INSERT INTO icom_rsvp_events_guests (icom_rsvp_events_id, icom_rsvp_guests_id, spouse_coming, number_children_coming, number_adult_banquet_guests, number_child_banquet_guests, date_of_rsvp) VALUES ((SELECT icom_rsvp_events_id FROM icom_rsvp_events WHERE event_name = %s), (SELECT icom_rsvp_guests_id FROM icom_rsvp_guests WHERE private_id = %s), %d, %d, %d, %d, NOW())",$currentevent, $privateId, $spouseAttending, $numChildren, $adultDinnerNumber, $childrenDinnerNumber));

                            if(!$successfulQuery){
                                $allEventsRegistered = false;
                            }
                        }
                    }

                    if($successfulQueryA && $allEventsRegistered) {

                        // Create a html version of the email
                            $htmlMessage = "<html>";
                            $htmlMessage .= "<head>";
                            $htmlMessage .= "<title>RSVP Confirmation | Scroll / I~Comm Reunion</title>";
                            $htmlMessage .= "</head>";
                            $htmlMessage .= "<body>";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<p>$uname,</p>";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<p>&emsp;&emsp;We have successfully received your RSVP information for the ";
                            if(($reception === "yes")||($tours === "yes")||($dinner === "yes")||($otherAlumniEvents === "yes")) {
                                $htmlMessage .= "following ";
                            }
                            $htmlMessage .= "Scroll / I~Comm Reunion";
                            if(($reception === "yes")||($tours === "yes")||($dinner === "yes")||($otherAlumniEvents === "yes")) {
                                $htmlMessage .= " events:</p>";
                            } else {
                                $htmlMessage .= ".</p>";
                            }

                            $htmlMessage .= "<br />";

                            if($reception === "yes") {
                                $htmlMessage .= "<p><b>&emsp;&emsp;&emsp;&emsp;Reunion Open House and Reception</b>";                        
                                $htmlMessage .= "<br />&emsp;&emsp;&emsp;&emsp;&emsp;Friday, June 22nd (6-9 p.m.) | Spori Art Gallery</p>";
                                $htmlMessage .= "<br />";
                            }
                            if($tours === "yes") {
                                $htmlMessage .= "<p><b>&emsp;&emsp;&emsp;&emsp;Walking Campus Tours</b>";                        
                                $htmlMessage .= "<br />&emsp;&emsp;&emsp;&emsp;&emsp;Saturday, June 23rd (10 a.m.) | Meet at Spori Building South Doors</p>";
                                $htmlMessage .= "<br />";
                            }
                            if($dinner === "yes") {
                                $htmlMessage .= "<p><b>&emsp;&emsp;&emsp;&emsp;Reunion Banquet Dinner</b>";                        
                                $htmlMessage .= "<br />&emsp;&emsp;&emsp;&emsp;&emsp;Saturday, June 23rd (5-7 p.m.) | Manwaring Center Ballroom";

                                if((preg_match("/[1-9]/", $dinnerAdults))||(preg_match("/[1-9]/", $dinnerChildren))){
                                    $htmlMessage .= "<br />&emsp;&emsp;&emsp;&emsp;&emsp; Seats Reserved: ";

                                    if(preg_match("/[1-9]/", $dinnerAdults)){
                                        $htmlMessage .= "$dinnerAdults Adults";
                                    }
                                    if(preg_match("/[1-9]/", $dinnerChildren)){
                                        $htmlMessage .= ", $dinnerChildren Child";
                                        if($dinnerChildren > 1){
                                            $htmlMessage .= "ren";
                                        }
                                    }
                                }
                                $htmlMessage .= "</p>";
                                $htmlMessage .= "<br />";
                            }
                            if($otherAlumniEvents === "yes") {
                                $htmlMessage .= "<p><b>&emsp;&emsp;&emsp;&emsp;BYU-Idaho Alumni Days</b>";                        
                                $htmlMessage .= "<br />&emsp;&emsp;&emsp;&emsp;&emsp;Friday, June 22nd - Saturday, June 23rd | Throughout Campus";                        
                                $htmlMessage .= "<br />&emsp;&emsp;&emsp;&emsp;&emsp; Schedule: <a href='http://www.byuiconnect.com/s/1085/07-idaho/idaho-interior.aspx?sid=1085&gid=4&pgid=3778&cid=8606&ecid=8606&crid=0&calpgid=61&calcid=1163' title='View the Alumni Days Events Homepage'>Alumni Days Events Homepage</a>";
                                $htmlMessage .= "</p>";
                                $htmlMessage .= "<br />";
                            }

                            if($dinner === "yes") {
                                $htmlMessage .= "<h4><b>To Purchase Tickets for Your Reunion Banquet Dinner Reservation:</b></h4>";                    
                                $htmlMessage .= "<p>&emsp;&emsp;You have indicated that you will be attending the Scroll / I~Comm Reunion Banquet Dinner on Saturday, June 23rd (5-7 p.m.).";                    
                                $htmlMessage .= "<br />&emsp;&emsp;All tickets for the banquet must be purchased in advance through the Ticket Office <b>before 5 p.m. on Wednesday, June 20.</b>";                    
                                $htmlMessage .= "<br />&emsp;&emsp;If you have not purchased your tickets yet, please follow this link to the BYU-Idaho Ticket Office:";                    
                                $htmlMessage .= "<br />&emsp;&emsp;&emsp;<a href='http://byui.universitytickets.com/user_pages/event.aspx?id=1113&cid=131&p=1' title='Visit the BYU-Idaho Ticket Office'>http://byui.universitytickets.com/user_pages/event.aspx?id=1113&cid=131&p=1</a>";                    
                                $htmlMessage .= "</p>";
                                $htmlMessage .= "<br />";
                                $htmlMessage .= "<p>&emsp;&emsp;For further information, you may contact the Ticket Office directly at (208) 496-3170 or 1-800-717-4257.</p>";                    
                                $htmlMessage .= "<br />";
                            }
                            $htmlMessage .= "<h4><b>To Update Your Reservation:</b></h4>";                    
                            $htmlMessage .= "<p>&emsp;&emsp;You may make changes to your submitted reservation <b>until Monday, June 18th at 5 p.m. (MST)</b>";                    
                            $htmlMessage .= "<br />&emsp;&emsp;by following this link* to your personal RSVP form:";                    
                            $htmlMessage .= "<br />&emsp;&emsp;&emsp;<a href='https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$privateId' title='View your Personal RSVP Form'>https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$privateId</a>";                    
                            $htmlMessage .= "</p>";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<p>&emsp;&emsp; If you have any comments or question about the Scroll / I~Comm Reunion please e-mail: Scroll.Icomm.Reunion@gmail.com</p>";                    
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br /><p>&emsp;&emsp;We appreciate you taking time to submit your reservation, and look forward to seeing you at the Reunion.";                    
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />&emsp;&emsp;The I~Comm Student Media Staff";                       
                            $htmlMessage .= "<br />&emsp;&emsp; <a href='http://www.byuicomm.net' title='I~Comm Student Media Website'>http://www.byuicomm.net</a>";
                            $htmlMessage .= "<br />&emsp;&emsp; <a href='https://twitter.com/#!/ScrollReunion' title='Scroll Reunion 2012 on Twitter'>@ScrollReunion</a></p>";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br /><p>&emsp;&emsp;<b>*</b><i>The personal RSVP form link in this email contains information intended solely";                    
                            $htmlMessage .= "<br />&emsp;&emsp; for its named recipient and is not to be shared with third parties unless otherwise";                    
                            $htmlMessage .= "<br />&emsp;&emsp; specified. Any unauthorized review, use, disclosure or distribution is prohibited.</i></p>";                    
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "</body>";
                            $htmlMessage .= "</html>";



                        // Create a text version of the email
                            $txtMessage .= "$uname,";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\tWe have successfully received your RSVP information for the ";
                            if(($reception === "yes")||($tours === "yes")||($dinner === "yes")||($otherAlumniEvents === "yes")) {
                                $txtMessage .= "following ";
                            }
                            $txtMessage .= "Scroll / I~Comm Reunion";
                            if(($reception === "yes")||($tours === "yes")||($dinner === "yes")||($otherAlumniEvents === "yes")) {
                                $txtMessage .= " events:";
                            } else {
                                $txtMessage .= ".";
                            }
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";

                            if($reception === "yes") {
                                $txtMessage .= "\t\tReunion Open House and Reception";                        
                                $txtMessage .= "\n\t\t&emsp;Friday, June 22nd (6-9 p.m.) | Spori Art Gallery";
                                $txtMessage .= "\n";
                                $txtMessage .= "\n";
                            }
                            if($tours === "yes") {
                                $txtMessage .= "\t\tWalking Campus Tours";                        
                                $txtMessage .= "\n\t\t Saturday, June 23rd (10 a.m.) | Meet at Spori Building South Doors";
                                $txtMessage .= "\n";
                                $txtMessage .= "\n";
                            }
                            if($dinner === "yes") {
                                $txtMessage .= "\t\tReunion Banquet Dinner";                        
                                $txtMessage .= "\n\t\t Saturday, June 23rd (5-7 p.m.) | Manwaring Center Ballroom";

                                if((preg_match("/[1-9]/", $dinnerAdults))||(preg_match("/[1-9]/", $dinnerChildren))){
                                    $txtMessage .= "\n\t\t Seats Reserved: ";

                                    if(preg_match("/[1-9]/", $dinnerAdults)){
                                        $txtMessage .= "$dinnerAdults Adults";
                                    }
                                    if(preg_match("/[1-9]/", $dinnerChildren)){
                                        $txtMessage .= ", $dinnerChildren Child";
                                        if($dinnerChildren > 1){
                                            $txtMessage .= "ren";
                                        }
                                    }
                                }
                                $txtMessage .= "\n";
                                $txtMessage .= "\n";
                            }
                            if($otherAlumniEvents === "yes") {
                                $txtMessage .= "\t\tBYU-Idaho Alumni Days";                        
                                $txtMessage .= "\n\t\t Friday, June 22nd - Saturday, June 23rd | Throughout Campus";                        
                                $txtMessage .= "\n\t\t Schedule: http://www.byuiconnect.com/s/1085/07-idaho/idaho-interior.aspx?sid=1085&gid=4&pgid=3778&cid=8606&ecid=8606&crid=0&calpgid=61&calcid=1163";
                                $txtMessage .= "\n";
                                $txtMessage .= "\n";
                            }

                            if($dinner === "yes") {
                                $txtMessage .= "\n\tTo Purchase Tickets for Your Reunion Banquet Dinner Reservation:";                    
                                $txtMessage .= "\n\tYou have indicated that you will be attending the Scroll / I~Comm Reunion Banquet Dinner on Saturday, June 23rd (5-7 p.m.).";                    
                                $txtMessage .= "\n\tAll tickets for the banquet must be purchased in advance through the Ticket Office before 5 p.m. on Wednesday, June 20.";                    
                                $txtMessage .= "\n\tIf you have not purchased your tickets yet, please follow this link to the BYU-Idaho Ticket Office:";                    
                                $txtMessage .= "\n\t http://byui.universitytickets.com/user_pages/event.aspx?id=1113&cid=131&p=1";                    
                                $txtMessage .= "\n";
                                $txtMessage .= "\n\tFor further information or assistance, you may contact the Ticket Office directly at (208) 496-3170 or 1-800-717-4257.";                    
                                $txtMessage .= "\n";
                            }
                            $txtMessage .= "\n\tTo Update Your Reservation:";                    
                            $txtMessage .= "\n\tYou may make changes to your submitted reservation until Monday, June 18th at 5 p.m. (MST)";                    
                            $txtMessage .= "\n\tby following this link* to your personal RSVP form:";                    
                            $txtMessage .= "\n\t https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$privateId";                    
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\tIf you have any comments or question about the Scroll / I~Comm Reunion please e-mail: Scroll.Icomm.Reunion@gmail.com";                    
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\tWe appreciate you taking time to submit your reservation, and look forward to seeing you at the Reunion.";                    
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\tThe I~Comm Student Media Staff";                       
                            $txtMessage .= "\n\t http://www.byuicomm.net";
                            $txtMessage .= "\n\t @ScrollReunion";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\t* The personal RSVP form link in this email contains information intended solely";                    
                            $txtMessage .= "\n\t  for its named recipient and is not to be shared with third parties unless otherwise";                    
                            $txtMessage .= "\n\t  specified. Any unauthorized review, use, disclosure or distribution is prohibited.";                    
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";


                            // Hookup and send the html and text versions of the email    
                            $mail = new PHPMailer();
                            $mail->IsMail();

                            $mail->AddReplyTo("Scroll.Icomm.Reunion@gmail.com", "I~Comm Student Media");
                            $mail->AddAddress($email_addrss);
                            $mail->SetFrom("Scroll.Icomm.Reunion@gmail.com", "I~Comm Student Media");
                            $mail->Subject = "RSVP Confirmation | Scroll / I~Comm Reunion";

                            $mail->AltBody = $txtMessage;
                            $mail->MsgHTML($htmlMessage);
                            $mail->Send();


                            $successMessage = "Thank you. You should be receiving a confirmation email shortly.";

                    }
              } else {
                  $successMessage = "Sorry, it seems that you have already registered. ";    
                  $successMessage .= "If you are trying to update your reservation, ";    
                  $successMessage .= "please use the link provided in your confirmation email. ";    
                  $successMessage .= "If there has been an error, please email Phillip Roberts at rob07015@byui.edu to correct the problem. ";    
                  $successMessage .= "Thank you.";    
              }
              
              $regResults = array($privateId,$successMessage,);
              
              
    return $regResults;

}

//Function that Updaets a User's Registration for the Event --------------------------------------

function updateUser($guestRSVPId, $uname, $staffYears, $email_addrss, $phoneNum, $twName, $stAddress, $city, $state, $zipcode, $unameSpouse, $numChildren, $reception, $tours, $dinner, $dinnerAdults, $dinnerChildren, $otherAlumniEvents) {

    global $wpdb;
    
           $successMessage;
              
              
              $userIsAlreadyRegistered = userAlreadyRegistered($guestRSVPId);
              
              if($userIsAlreadyRegistered == true) {

                    //Update user's info in the guests table
                    $successfulQueryA = $wpdb->query($wpdb->prepare("UPDATE icom_rsvp_guests SET guest_name = %s, staff_years = %s, email = %s, phone = %s, twitter_name = %s, street_address = %s, city = %s, state = %s, zipcode = %d, spouse_name = %s, children_attending = %d WHERE private_id = %s", $uname, $staffYears, $email_addrss, $phoneNum, $twName, $stAddress, $city, $state, $zipcode, $unameSpouse, $numChildren, $guestRSVPId));

                    //Identifying the events the user will be attending
                    $eventsAttending = array();

                    if($reception === "yes") {
                        $eventsAttending[] = 'Scroll / I~Comm Reunion Reception';
                    }
                    if($tours === "yes") {
                        $eventsAttending[] = 'Walking Campus Tours';
                    }
                    if($dinner === "yes") {
                        $eventsAttending[] = 'Scroll / I~Comm Reunion Banquet Dinner';
                    }
                    if($otherAlumniEvents === "yes") {
                        $eventsAttending[] = 'Other Alumni Day Events';
                    }



                    $allChangesRegistered = true;

                    $spouseAttending = "";
                    if(!empty($unameSpouse)){
                        $spouseAttending = 1; 
                    }


                    //Updating each event the user will be attending in the events_guests table
                    if ($successfulQueryA != false){
                        
                        //Retrieving the Old Registered Events
                        $oldRegisteredEvents = $wpdb->get_results($wpdb->prepare("SELECT event_name FROM icom_rsvp_events ire RIGHT JOIN icom_rsvp_events_guests sr ON sr.icom_rsvp_events_id WHERE ire.icom_rsvp_events_id = sr.icom_rsvp_events_id AND sr.icom_rsvp_guests_id = (SELECT icom_rsvp_guests_id FROM `icom_rsvp_guests` WHERE private_id = %s) ORDER BY ire.icom_rsvp_events_id ASC", $guestRSVPId), ARRAY_N);
                        
                        $eventsToUpdate = array();
                        $eventsToInsert = array();
                        $eventsToCancel = array();
                        
                        $adultDinnerNumber = "";
                        $childrenDinnerNumber = "";
                        
                        //Comparing the New and the Old Registered Events
                        for($i=0; $i< count($eventsAttending); $i++) {
                            
                            $currentevent = $eventsAttending[$i];
                            $currenteventFound = false;
                            $adultDinnerNumber = "";
                            $childrenDinnerNumber = "";
                            
                            //If the Current Event is already Registered it Needs to be Updated
                            for($ii=0; $ii< count($oldRegisteredEvents); $ii++) {
                                if($currentOldEvent == $oldRegisteredEvents[$ii]) {
                                    $eventsToUpdate[] = $currentOldEvent;
                                    $currenteventFound = true;
                                }
                            }
                            //If the Current Event is not already Registered it Needs to be Inserted                            
                            if(!$currenteventFound){
                                $eventsToInsert[] = $currentevent;
                            }
                            
                            //Getting the Number of Adults and Children if the Current Event is the Dinner                            
                            if($currentevent == 'Scroll / I~Comm Reunion Banquet Dinner'){
                                $adultDinnerNumber = $dinnerAdults;
                                $childrenDinnerNumber = $dinnerChildren;
                            }
                        }
                        
                        //Checking for Registered Events that were Canceled
                        for($i=0; $i< count($oldRegisteredEvents); $i++) {
                            
                            $currentevent = $oldRegisteredEvents[$i];
                            $currenteventFound = false;
                            
                            for($ii=0; $ii< count($eventsAttending); $ii++) {
                                if($currentevent == $eventsAttending[$ii]) {
                                    $currenteventFound = true;
                                }
                            }
                            
                            //If the Registered Event was not found in the New Events it needs to be Canceled
                            if(!$currenteventFound){
                                $eventsToCancel[] = $currentevent;
                            }                            
                        }
                        
                        //Make the Update changes
                        for($i=0; $i< count($eventsToUpdate); $i++) {
                            $currentevent = $eventsToUpdate[$i];
                            
                            $successfulQuery = $wpdb->query($wpdb->prepare("UPDATE icom_rsvp_events_guests SET spouse_coming = %d, number_children_coming = %d, number_adult_banquet_guests = %d, number_child_banquet_guests = %d) WHERE event_name = %s AND icom_rsvp_guests_id = (SELECT icom_rsvp_guests_id FROM icom_rsvp_guests WHERE private_id = %s)", $spouseAttending, $numChildren, $adultDinnerNumber, $childrenDinnerNumber, $currentevent, $guestRSVPId));

                            if($successfulQuery == false){
                                $allChangesRegistered = false;
                            }                            
                        }                        
                        //Make the Inserts
                        for($i=0; $i< count($eventsToInsert); $i++) {
                            $currentevent = $eventsToInsert[$i];
                            
                            $successfulQuery = $wpdb->query($wpdb->prepare("INSERT INTO icom_rsvp_events_guests (icom_rsvp_events_id, icom_rsvp_guests_id, spouse_coming, number_children_coming, number_adult_banquet_guests, number_child_banquet_guests, date_of_rsvp) VALUES ((SELECT icom_rsvp_events_id FROM icom_rsvp_events WHERE event_name = %s), (SELECT icom_rsvp_guests_id FROM icom_rsvp_guests WHERE private_id = %s), %d, %d, %d, %d, NOW())",$currentevent, $guestRSVPId, $spouseAttending, $numChildren, $adultDinnerNumber, $childrenDinnerNumber));

                            if($successfulQuery == false){
                                $allChangesRegistered = false;
                            }                            
                        }
                        //Make the Cancelation changes
                        for($i=0; $i< count($eventsToCancel); $i++) {
                            $currentevent = $eventsToCancel[$i];
                            
                            $successfulQuery = $wpdb->query($wpdb->prepare("UPDATE icom_rsvp_events_guests SET rsvp_status = %s WHERE event_name = %s AND icom_rsvp_guests_id = (SELECT icom_rsvp_guests_id FROM icom_rsvp_guests WHERE private_id = %s)", 'Canceled', $currentevent, $guestRSVPId));

                            if($successfulQuery == false){
                                $allChangesRegistered = false;
                            }                            
                        }

                    }

                    if(($successfulQueryA != false) && ($allChangesRegistered == true)) {

                        // Create a html version of the email
                            $htmlMessage = "<html>";
                            $htmlMessage .= "<head>";
                            $htmlMessage .= "<title>RSVP Updated | Scroll / I~Comm Student Media Reunion</title>";
                            $htmlMessage .= "</head>";
                            $htmlMessage .= "<body>";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<p>$uname,</p>";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<p>&emsp;&emsp;Your RSVP information has been successfully updated.</p>";
                            $htmlMessage .= "<p>&emsp;&emsp;You may view your current reservation information at anytime";                    
                            $htmlMessage .= "<br />&emsp;&emsp;by following this link* to your personal RSVP form:";                    
                            $htmlMessage .= "<br />&emsp;&emsp;&emsp;<a href='https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$guestRSVPId' title='View your Personal RSVP Form'>https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$guestRSVPId</a>";                    
                            $htmlMessage .= "</p>";

                            $htmlMessage .= "<br />";

                            $htmlMessage .= "<h4><b>Didn't request this update?</b></h4>";                    
                            $htmlMessage .= "<p>&emsp;&emsp; If you did not request this change in your reservation, please email us at the following address so we may take steps to correct the problem: icomm@byui.edu.</p>";                    
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br /><p>&emsp;&emsp;Thank you,";                    
                            $htmlMessage .= "<br />&emsp;&emsp;The I~Comm Student Media Staff";                       
                            $htmlMessage .= "<br />&emsp;&emsp; <a href='http://www.byuicomm.net' title='I~Comm Student Media Website'>http://www.byuicomm.net</a>";
                            $htmlMessage .= "<br />&emsp;&emsp; <a href='https://twitter.com/#!/ScrollReunion' title='Scroll Reunion 2012 on Twitter'>@ScrollReunion</a></p>";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br /><p>&emsp;&emsp;<b>*</b><i>The personal RSVP form link in this email contains information intended solely";                    
                            $htmlMessage .= "<br />&emsp;&emsp; for its named recipient and is not to be shared with third parties unless otherwise";                    
                            $htmlMessage .= "<br />&emsp;&emsp; specified. Any unauthorized review, use, disclosure or distribution is prohibited.</i></p>";                    
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "<br />";
                            $htmlMessage .= "</body>";
                            $htmlMessage .= "</html>";



                        // Create a text version of the email
                            $txtMessage .= "$uname,";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\tYour RSVP information has been successfully updated.";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\tYou may view your current reservation information at anytime";                    
                            $txtMessage .= "\n\tby following this link* to your personal RSVP form:";                    
                            $txtMessage .= "\n\t https://www.byuicomm.net/scroll-reunion-rsvp/?gr=$guestRSVPId";                    
                            $txtMessage .= "\n";

                            $txtMessage .= "\n\tDidn't request this update?";                    
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\tIf you did not request this change in your reservation, please email us at the following address so we may take steps to correct the problem: icomm@byui.edu.";                    
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\tThank you,";                    
                            $txtMessage .= "\n\tThe I~Comm Student Media Staff";                       
                            $txtMessage .= "\n\t http://www.byuicomm.net";
                            $txtMessage .= "\n\t @ScrollReunion";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n\t* The personal RSVP form link in this email contains information intended solely";                    
                            $txtMessage .= "\n\t  for its named recipient and is not to be shared with third parties unless otherwise";                    
                            $txtMessage .= "\n\t  specified. Any unauthorized review, use, disclosure or distribution is prohibited.";                    
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";
                            $txtMessage .= "\n";


                            // Hookup and send the html and text versions of the email    
                            $mail = new PHPMailer();
                            $mail->IsMail();

                            $mail->AddReplyTo("Scroll.Icomm.Reunion@gmail.com", "I~Comm Student Media");
                            $mail->AddAddress($email_addrss);
                            $mail->SetFrom("Scroll.Icomm.Reunion@gmail.com", "I~Comm Student Media");
                            $mail->Subject = "RSVP Updated | Scroll / I~Comm Student Media Reunion";

                            $mail->AltBody = $txtMessage;
                            $mail->MsgHTML($htmlMessage);
                            $mail->Send();


                            $successMessage = "Thank you. You should be receiving a confirmation email shortly.";

                    } else {
                            $txtMessage = "There has been an error in the RSVP Update for ";
                            $txtMessage .= "\n the following:";                    
                            $txtMessage .= "\n\t gr=".$guestRSVPId;
                            $txtMessage .= "\n";                    
                            $txtMessage .= "\n One or more of the Update Statements failed.";                    
                            $txtMessage .= "\n";                    
                            $txtMessage .= "\n Time of Occurance: ";
                            $timeZoneFix =  time () - 21600;
                            $txtMessage .= date("D, F jS G:i a",$timeZoneFix);
                            $txtMessage .= "\n";                       
                            $txtMessage .= "\n Attempted info going in: ";                    
                            $txtMessage .= "[1] ".$guestRSVPId." [2] ".$uname." [3] ".$staffYears." [4] ".$email_addrss." [5] ".$phoneNum." [6] ".$twName." [7] ".$stAddress." [8] ".$city." [9] ".$state." [10] ".$zipcode." [11] ".$unameSpouse." [12] ".$numChildren." [13] ".reception." [14] ".$tours." [15] ".$dinner." [16] ".$dinnerAdults." [17] ".$dinnerChildren." [18] ".$otherAlumniEvents;
                            $txtMessage .= "\n";                       
                            $txtMessage .= "\n";                       
                            $txtMessage .= "\n\tPlease fix it ASAP,";                    
                            $txtMessage .= "\n\tThe Reunion RSVP System";                       

                            $to = "rob07015@byui.edu";
                            $subject = "ERROR on RSVP UPDATE!!";
                            $message = $txtMessage;
                            $headers = "From: Scroll.Icomm.Reunion@gmail.com";
                            $headers .= "\n";

                            mail($to, $subject, $message, $headers);

                                                
                            $successMessage = "Sorry, there was a problem in processing the update to your reservation. ";    
                            $successMessage .= "Our site developer has been notifed and will contact you as soon as the problem is resolved. ";    
                            $successMessage .= "Thank you for your patience.";
//                  $successMessage .= "\n1st: ".$successfulQueryA."2nd: ".$allChangesRegistered;  
//                  $successMessage .= "ToUpdate: ".print_r($eventsToUpdate)."\n ToInsert: ".print_r($eventsToInsert)."\n ToCancel: ".print_r($eventsToCancel);
//                  $successMessage .= "ToUpdate#: ".count($eventsToUpdate)."\n ToInsert#: ".count($eventsToInsert)."\n ToCancel#: ".count($eventsToCancel);
//                  $successMessage .= "eventsAttending: ".print_r($eventsAttending)."\n oldRegisteredEvents: ".print_r($oldRegisteredEvents)."\n currentevent: ".$currentevent;
//                  $successMessage .= "eventsAttending#: ".count($eventsAttending)."\n oldRegisteredEvents#: ".count($oldRegisteredEvents);
                    }
              } else {
                        $txtMessage = "There has been an error in the RSVP Registration for ";
                        $txtMessage .= "\n the following:";                    
                        $txtMessage .= "\n\t gr=".$guestRSVPId;
                        $txtMessage .= "\n";                    
                        $txtMessage .= "\n User has id, but was not found in the database.";                    
                        $txtMessage .= "\n";                    
                        $txtMessage .= "\n Time of Occurance: ";                    
                        $timeZoneFix =  time () - 21600;
                        $txtMessage .= date("D, F jS G:i a",$timeZoneFix);
                        $txtMessage .= "\n";                       
                        $txtMessage .= "\n Attempted info going in: ";                    
                        $txtMessage .= "[1] ".$guestRSVPId." [2] ".$uname." [3] ".$staffYears." [4] ".$email_addrss." [5] ".$phoneNum." [6] ".$twName." [7] ".$stAddress." [8] ".$city." [9] ".$state." [10] ".$zipcode." [11] ".$unameSpouse." [12] ".$numChildren." [13] ".reception." [14] ".$tours." [15] ".$dinner." [16] ".$dinnerAdults." [17] ".$dinnerChildren." [18] ".$otherAlumniEvents;
                        $txtMessage .= "\n";                       
                        $txtMessage .= "\n";                       
                        $txtMessage .= "\n\tPlease fix it ASAP,";                    
                        $txtMessage .= "\n\tThe Reunion RSVP System";                       

                        $to = "rob07015@byui.edu";
                        $subject = "ERROR on RSVP REGISTRATION!!";
                        $message = $txtMessage;
                        $headers = "From: Scroll.Icomm.Reunion@gmail.com";
                        $headers .= "\n";

                        mail($to, $subject, $message, $headers);


                        $successMessage = "Sorry, there was a problem in processing the update to your reservation. ";    
                        $successMessage .= "Our site developer has been notifed and will contact you as soon as the problem is resolved. ";    
                        $successMessage .= "Thank you for your patience.";
              }
              
              $regResults = array($guestRSVPId,$successMessage,);
              
              
    return $regResults;

}

//End of RSVP Functions --------------------------------------

// Cleaning Post Values
foreach($_POST as $k=>$v)
{
	if(ini_get('magic_quotes_gpc'))
	$_POST[$k]=stripslashes($_POST[$k]);
	
	$_POST[$k]=htmlspecialchars(strip_tags($_POST[$k]));
}


include $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/icomm/includes/contact/phpmailer/class.phpmailer.php';


// Declaring Global Variables
$guestRSVPId;
$RSVPInfo;
$updateFlag;
$submitFlag;
$spam_bot;
$successMessage;
$errors;


// Cleaning Get Value
if(!empty($_GET['gr'])) {
    
	if(ini_get('magic_quotes_gpc'))
	$_GET['gr']=stripslashes($_GET['gr']);
	
	$_GET['gr']=htmlspecialchars(strip_tags($_GET['gr']));
        
        $guestRSVPId = $_GET['gr'];
}

if (empty($guestRSVPId)){
    
    // Pull needed values from $_POST
    $guestName = $_POST['guestName'];
    $staffYears = $_POST['staffYears'];
    $spouseName = $_POST['spouseName'];
    $children = $_POST['children'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $twitterName = $_POST['twitterName'];
    $reception = $_POST['reception'];
    $tours = $_POST['tours'];
    $dinner = $_POST['dinner'];
    $dinnerAdults = $_POST['dinner-adults'];
    $dinnerChildren = $_POST['dinner-children'];
    $otherAlumniEvents = $_POST['otherAlumniEvents'];
    $required = $_POST['required'];
    $submit = $_POST['rsvp-submit-button'];


    // Sanitize Submit $_POST Value
    $submit = preg_replace("/[^Submit Reservation]/", "", $submit);


    //////Register a new user
    if($submit === 'Submit Reservation') {

        // Hand sensitive values over to limited scope variables
            $uname = $guestName;
            $unameSpouse = $spouseName;
            $numChildren = $children;
            $email_addrss = $email;
            $phoneNum = $phone;
            $stAddress = $streetAddress;
            $twName = $twitterName;
            $bot_check = $required;


        ////Validate Inputs
        $errors = array();

        if(empty($uname)){
                $errors['user_name'] = 'Please enter your name.';
        }
        if(empty($staffYears)){
                $errors['staff_years'] = 'Please enter the years you were on staff.';
        }
        if(empty($email_addrss)){
                $errors['email'] = 'Please enter a valid email.';
        }
        if(empty($phoneNum)){
                $errors['phone'] = 'Please enter a valid phone number.';
        }
        if(empty($stAddress)){
                $errors['street_address'] = 'Please enter your address.';
        }
        if(empty($city)){
                $errors['city'] = 'Please enter your city.';
        }
        if(empty($state)){
                $errors['state'] = 'Please enter your state.';
        }
        if(empty($zipcode)){
                $errors['zipcode'] = 'Please enter your zipcode.';
        }

        if (!empty($bot_check)) {
                $spam_bot = true;            
        }
        if($spam_bot) {
            header('Location: http://www.byuicomm.net');
            exit();
        }

        if(empty($errors)){

            $submitFlag = true;
        }

        if(empty($errors) && ($submit === 'Submit Reservation')){

            $regResults = regUser($uname, $staffYears, $email_addrss, $phoneNum, $twName, $stAddress, $city, $state, $zipcode, $unameSpouse, $numChildren, $reception, $tours, $dinner, $dinnerAdults, $dinnerChildren, $otherAlumniEvents);

            $rsvpId = $regResults[0];
            $successMessage = $regResults[1];


            // Clean Up Variables for Security 
            $uname = '';                    
            unset($uname);

            $unameSpouse = '';                    
            unset($unameSpouse);

            $numChildren = '';
            unset($numChildren);

            $email_addrss = '';
            unset($email_addrss);

            $phoneNum = '';
            unset($phoneNum);

            $stAddress = '';
            unset($stAddress);

            $phoneNum = '';
            unset($phoneNum);

            $twName = '';
            unset($twName);

            $bot_check = '';
            unset($bot_check);

            $regResults = '';
            unset($regResults);

            $rsvpId = '';
            unset($rsvpId);

        }
    }    
} else {
    
    $submit = $_POST['rsvp-submit-button'];

    // Sanitize Submit $_POST Value
    $submit = preg_replace("/[^Update Reservation]/", "", $submit);

    //////Update user registration
    if($submit === 'Update Reservation') {
        
        // Pull needed values from $_POST
        $guestName = $_POST['guestName'];
        $staffYears = $_POST['staffYears'];
        $spouseName = $_POST['spouseName'];
        $children = $_POST['children'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $streetAddress = $_POST['streetAddress'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zipcode = $_POST['zipcode'];
        $twitterName = $_POST['twitterName'];
        $reception = $_POST['reception'];
        $tours = $_POST['tours'];
        $dinner = $_POST['dinner'];
        $dinnerAdults = $_POST['dinner-adults'];
        $dinnerChildren = $_POST['dinner-children'];
        $otherAlumniEvents = $_POST['otherAlumniEvents'];
        $required = $_POST['required'];
        $submit = $_POST['rsvp-submit-button'];


        // Sanitize Submit $_POST Value
        $submit = preg_replace("/[^Update Reservation]/", "", $submit);


        //////Update user registration
        if($submit === 'Update Reservation') {

            // Hand sensitive values over to limited scope variables
                $uname = $guestName;
                $unameSpouse = $spouseName;
                $numChildren = $children;
                $email_addrss = $email;
                $phoneNum = $phone;
                $stAddress = $streetAddress;
                $twName = $twitterName;
                $bot_check = $required;


            ////Validate Inputs
            $errors = array();

            if(empty($uname)){
                    $errors['user_name'] = 'Please enter your name.';
            }
            if(empty($staffYears)){
                    $errors['staff_years'] = 'Please enter the years you were on staff.';
            }
            if(empty($email_addrss)){
                    $errors['email'] = 'Please enter a valid email.';
            }
            if(empty($phoneNum)){
                    $errors['phone'] = 'Please enter a valid phone number.';
            }
            if(empty($stAddress)){
                    $errors['street_address'] = 'Please enter your address.';
            }
            if(empty($city)){
                    $errors['city'] = 'Please enter your city.';
            }
            if(empty($state)){
                    $errors['state'] = 'Please enter your state.';
            }
            if(empty($zipcode)){
                    $errors['zipcode'] = 'Please enter your zipcode.';
            }

            if (!empty($bot_check)) {
                    $spam_bot = true;            
            }
            if($spam_bot) {
                header('Location: http://www.byuicomm.net');
                exit();
            }

            if(empty($errors)){

                $submitFlag = true;
            }

            if(empty($errors) && ($submit === 'Update Reservation')){

                $updateResults = updateUser($guestRSVPId, $uname, $staffYears, $email_addrss, $phoneNum, $twName, $stAddress, $city, $state, $zipcode, $unameSpouse, $numChildren, $reception, $tours, $dinner, $dinnerAdults, $dinnerChildren, $otherAlumniEvents);

                $rsvpId = $updateResults[0];
                $successMessage = $updateResults[1];


                // Clean Up Variables for Security 
                $uname = '';                    
                unset($uname);

                $unameSpouse = '';                    
                unset($unameSpouse);

                $numChildren = '';
                unset($numChildren);

                $email_addrss = '';
                unset($email_addrss);

                $phoneNum = '';
                unset($phoneNum);

                $stAddress = '';
                unset($stAddress);

                $phoneNum = '';
                unset($phoneNum);

                $twName = '';
                unset($twName);

                $bot_check = '';
                unset($bot_check);

                $updateResults = '';
                unset($updateResults);

                $rsvpId = '';
                unset($rsvpId);

            }
        }    
    } else {
        
        $updateFlag = true;
        
        $RSVPInfo = getReservation($guestRSVPId);

        // Pull needed values from $RSVPInfo
        $guestName = $RSVPInfo[0];
        $staffYears = $RSVPInfo[1];
        $spouseName = $RSVPInfo[2];
        $children = $RSVPInfo[3];
        $email = $RSVPInfo[4];
        $phone = $RSVPInfo[5];
        $streetAddress = $RSVPInfo[6];
        $city = $RSVPInfo[7];
        $state = $RSVPInfo[8];
        $zipcode = $RSVPInfo[9];
        $twitterName = $RSVPInfo[10];
        $dinnerAdults = $RSVPInfo[11];
        $dinnerChildren = $RSVPInfo[12];        

        for($i=13; $i < count($RSVPInfo); $i++) {

            $currentEvent = $RSVPInfo[$i];

            if($currentEvent == 'Scroll / I~Comm Reunion Reception') {
                $reception = 'yes';   
            }
            if($currentEvent == 'Walking Campus Tours') {
                $tours = 'yes';   
            }
            if($currentEvent == 'Scroll / I~Comm Reunion Banquet Dinner') {
                $dinner = 'yes';   
            }
            if($currentEvent == 'Other Alumni Day Events') {
                $otherAlumniEvents = 'yes';   
            }        
        }
        

        // Clean Up Variables for Security 
        $RSVPInfo = '';                    
        unset($RSVPInfo); 
        
        $guestRSVPId = '';                    
        unset($guestRSVPId); 
        
    }
    
}
?>
