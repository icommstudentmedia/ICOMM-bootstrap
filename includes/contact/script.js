jQuery(document).ready(function(){

//        hide previous errors

	var use_ajax=true;

	jQuery("#button, #button2").click(function(){
		jQuery(".formError").css('visibility', 'hidden');
	});
	
	jQuery("#contact-form").submit(function(e){
			e.preventDefault();
			
			if(use_ajax)
			{
				jQuery('#loading').css('visibility','visible');
				
				//validate
				var valid = true;
				
				var name = jQuery('#name').val();
				if(name.length == 0){
					jQuery('.formError.name').css('visibility', 'visible').text('A name is required');
					valid = false;
				}
				
				var email = jQuery('#email').val();
				if(email.length == 0){
					jQuery('.formError.email').css('visibility', 'visible').text('An email address is required');
					valid = false;
				}else if(!validateEmail(email)){
					jQuery('.formError.email').css('visibility', 'visible').text('Please enter a valid email address');
					valid = false;
				}
				
				var phone = jQuery('#phone').val();
				if(phone.length == 0){
					jQuery('.formError.phone').css('visibility','visible').text('A phone number is required');
					valid = false;
				}else if(!validatePhone(phone)){
					jQuery('.formError.phone').css('visibility','visible').text('Please enter a valid phone number');
					valid = false;
				}
				
				
				var subject = jQuery('#subject').val();
				if(subject != 'Question' && subject != 'Business proposal' && subject != 'Advertisement' && subject != 'Complaint'){
					jQuery('.formError.subject').css('visibility', 'visible').text('Please select a subject');
					valid = false;
				}
				
				var message = jQuery('#message').val();	
				if(subject.length == 0){
					jQuery('.formError.message').css('visibility', 'visible').text('Please include a message');
					valid = false;
				}
				
                if(valid){                
					jQuery.post('/wp-content/themes/icomm/includes/contact/submit.php',jQuery(this).serialize()+'&ajax=1',
				
						function(data){
							if(parseInt(data)==-1)
								alert("Submission Failed");
							
							else if(parseInt(data)==1)
							{
								jQuery("#contact-form").hide('slow').after('<h1>Thank you!</h1>');
							}
						
							jQuery('#loading').css('visibility','hidden');
						}
				
					);
				}else{
					jQuery('#loading').css('visibility','hidden');
				}
			}
			
	})
	
});
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 
function validatePhone(phone){
	var re = /^([\(]{1}[0-9]{3}[\)]{1}[\.| |\-]{0,1}|^[0-9]{3}[\.|\-| ]?)?[0-9]{3}(\.|\-| )?[0-9]{4}$/;
	return re.test(phone);
}