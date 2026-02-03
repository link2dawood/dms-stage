 jQuery(document).ready(function($) {
    $(document).on('click', ".submit_contact_form", function(e) {
        e.preventDefault();

        var $parent = $(this).parent();

        // collect input field values
        var userName    = $parent.find("input[name='name']").val();
        var userEmail   = $parent.find("input[name='email']").val();
        var userMessage = $parent.find("textarea[name='message']").val();
        var isRecaptcha = $parent.find('#contact_form_recaptcha').length;

        console.log('parent', $parent, isRecaptcha);

        // simple validation at client's end
        // we simply change border color to red if empty field using .css()
        var proceed = true;

        if($parent.find(".gdpr_label").length){
          if(!$parent.find("input[name='gdpr']").is(":checked")){
            $parent.find(".gdpr_label").css("border", "1px solid red");
            proceed = false;
          } else {
            $parent.find(".gdpr_label").removeAttr("style");
          }
        }

        if(userName==""){
            $parent.find("input[name='name']").css("border", "1px solid red");
            proceed = false;
        } else {
    			$parent.find("input[name='name']").removeAttr("style");
    		}

        if(userEmail==""){
            $parent.find("input[name='email']").css("border", "1px solid red");
            proceed = false;
        } else {
    			$parent.find("input[name='email']").removeAttr("style");
    		}

        if(userMessage=="") {
            $parent.find("textarea[name='message']").css("border", "1px solid red");
            proceed = false;
        } else {
    			$parent.find("textarea[name='message']").removeAttr("style");
    		}

        if(typeof listing_ajax.contact_gdpr_form !== 'undefined' && $parent.find('input[name="gdpr"]').length){
          var $gdprCheckbox = $parent.find('input[name="gdpr"]');

          if(!$gdprCheckbox.is(":checked")){
            proceed = false;
            $gdprCheckbox.closest('.gdpr-control').css('border', '1px solid #F00');
          } else {
            $gdprCheckbox.closest('.gdpr-control').attr('style', '');
          }
        }

        // everything looks good! proceed...
        if(proceed) {
            // data to be sent to server
            var post_data = {'userName':userName, 'userEmail':userEmail, 'userMessage':userMessage, 'action':'send_contact_form'};

            if(isRecaptcha){
              var $recaptchaResponseField = $("#g-recaptcha-response");

              console.log('challenge', $recaptchaResponseField);

              post_data.challenge = $recaptchaResponseField.val();
            }

            // ajax post data to server
            $.post(ajax_variables.ajaxurl, post_data, function(data){

                // load success massage in #result div element, with slide effect.
                if(data.success == "yes"){
                    $parent.find(".contact_result").hide().html('<div class="success">'+data.message+'</div>').slideDown();

                    // reset values in all input fields
                    $parent.find('input[type="text"]').val('');
				            $parent.find('input[type="email"]').val('');
                    $parent.find('textarea').val('');
                } else {
                    $parent.find(".contact_result").hide().html('<div class="error">'+data.message+'</div>').slideDown();
                }

            }).fail(function(err) {
                $parent.find(".contact_result").hide().html('<div class="error">'+err.statusText+'</div>').slideDown();
            });
        }

        setTimeout(function(){
            $parent.find("input, textarea").css('border-color','');
            $parent.find(".contact_result").slideUp();
        }, 3000);
    });

});
