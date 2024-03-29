<?php

namespace ParlerAdmin;

class TicketForm
{
    public function enableForm() {
        if (isset($_POST['parler-ticket-submit-button'])) {
            add_action('init', array($this, "processFormSubmission"));
            add_shortcode('parler-support-form', array($this, "returnSubmittedFormHTML"));
        } else {
            add_shortcode('parler-support-form', array($this, "returnFormHTML"));
        }

    }

    public function returnFormHTML()
    {
        $jQueryIsReady = __("jQuery 1.12.4-wp is ready!", 'parler');
        $siteUrl = get_site_url();
        $output =
            <<<output

<script>

    jQuery( document ).ready(function() {
            //alert('$jQueryIsReady');
        	jQuery(".col-2").click(function(){          
			var box = jQuery(this);
			box.addClass("hovered");
			box.siblings().removeClass("hovered");
			var theForm = jQuery("#form-" + box.attr('id'));
			theForm.slideDown("slow").removeClass("hidden");
			theForm.siblings().slideUp("slow",function() { jQuery(this).addClass("hidden");
			 			jQuery('#parler-form')[0].reset();
			 });

		});

		/* Upload Event */
		const uploadButton = jQuery('#browse-btn');
		const realInput = jQuery('#screenshot');

		uploadButton.click(function(e){
			e.preventDefault();
			realInput.click();
		});
        
    });      

</script>
<script src="https://kit.fontawesome.com/48aa48d378.js"></script>
<link rel="stylesheet" href="$siteUrl/wp-content/plugins/parler-wordpress-php/css/reset.css">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700|Roboto:500&display=swap" rel="stylesheet">

<link rel="stylesheet" href="$siteUrl/wp-content/plugins/parler-wordpress-php/css/style.css">
<form method = "post" id = "parler-form"> 

<div class="container-fluid">
		<!-- Ticket Type Boxes -->
		<div class="row" style="margin: 18px 0;">
			<span class="row-title">Please select the question type</span>
			<div class="box-container">
				<div class="col-2" id="delete">
					<div><i class="fa fa-user-times"></i></div>
					<p>Delete Account</p>
				</div>

				<div class="col-2" id="locked">
					<div><i class="fa fa-user-lock"></i></div>
					<p>Locked Account</p>
				</div>
				<div class="col-2" id="bug">
					<div><i class="fa fa-bug"></i></div>
					<p>Bug Report</p>
				</div>
				<div class="col-2" id="partner">
					<div><i class="fa fa-handshake"></i></div>
					<p>Partner Integration</p>
				</div>
				<div class="col-2" id="media">
					<div><i class="fa fa-video"></i></div>
					<p>Media Inqury</p>
				</div>
				<div class="col-2" id="verification">
					<div><i class="fa fa-shield-alt"></i></div>
					<p>Public Figure Verification</p>
				</div>
				
			</div>
		</div>
		<!-- end Ticket Type Boxes -->

		<div class="form-controls-row">
			<!-- delete form -->
			<div class="hidden" id="form-delete">
				<ul class="flex-outer">
					<li>
						<label for="delete-user-name">Username</label>
						<input type="text" id="delete-user-name" name="delete-user-name" placeholder="Enter your username here">
					</li>
					<li>
						<label for="delete-email">Account Email</label>
						<input type="delete-email" id="delete-email" name="delete-email" placeholder="Enter your email here">
					</li>
					<li>
						<label for="delete-message">Message</label>
						<textarea rows="6" id="message" placeholder="Enter your message here" name = "delete-message"></textarea>
					</li>
					<li>
						<label style="visibility: hidden;">Submit</label>
												<input type="submit" class="btn btn-primary" name = "parler-ticket-submit-button" id = "parler-ticket-submit-button" value = "Submit" />
					</li>
				</ul>
			</div>
			<!-- delete form -->


			<!-- locked account form -->
			<div class="hidden" id="form-locked">
				<ul class="flex-outer">
					<li>
						<label for="locked-user-name">Username</label>
						<input type="text" id="locked-user-name" name="locked-user-name" placeholder="Enter your username here">
					</li>
					<li>
						<label for="locked-email">Account Email</label>
						<input type="email" id="locked-email" name="locked-email" placeholder="Enter your email here">
					</li>
					<li>
						<label for="locked-message">Message</label>
						<textarea rows="6" id="locked-message" placeholder="Enter your message here" name = "locked-message" ></textarea>
					</li>
					<li>
						<label style="visibility: hidden;">Submit</label>
												<input type="submit" class="btn btn-primary" name = "parler-ticket-submit-button" id = "parler-ticket-submit-button" value = "Submit" />
					</li>
				</ul>
			</div>
			<!-- locked account form -->


			<!-- bug form -->
			<div class="hidden" id="form-bug">
				<ul class="flex-outer">
					<li>
						<label for="bug-first-name">Username</label>
						<input type="text" id="bug-user-name" name="bug-user-name" placeholder="Enter your username here">
					</li>
					<li>
						<label for="bug-email">Account Email</label>
						<input type="email" id="bug-email" name="bug-email" placeholder="Enter your email here">
					</li>
					<li>
						<label for="bug-phone">Device Type</label>
						<select id="bug-inputState" class="form-control" name = "bug-device">
							<option selected>IOS</option>
							<option>Android</option>
							<option>Web</option>
							<option>Plugin</option>
						</select>
					</li>
					<li>
						<!--
						<label>Screenshot!</label>
						<div class="custom-file-upload">
							<span class="btn btn-primary" id="browse-btn">Upload File</span>
							<input type="file" id="bug-screenshot" name="bug-screenshot" />
						</div>
						-->
					</li>
					<li>
						<label for="bug-message">Message</label>
						<textarea rows="6" id="bug-message" placeholder="Enter your message here" name = "bug-message"></textarea>
					</li>
					<li>
						<label style="visibility: hidden;">Submit</label>
												<input type="submit" class="btn btn-primary" name = "parler-ticket-submit-button" id = "parler-ticket-submit-button" value = "Submit" />
					</li>
				</ul>
			</div>
			<!-- end bug form -->


			<!-- partner form -->
			<div class="hidden" id="form-partner">
				<ul class="flex-outer">
					<li>
						<label for="partner-company-name">Company Name</label>
						<input type="text" id="partner-company-name" name="partner-company-name" placeholder="Enter your company name here">
					</li>
					<li>
						<label for="partner-website">Website URL</label>
						<input type="text" id="partner-website" name="partner-website" placeholder="Enter your website here">
					</li>
					<li>
						<label for="partner-role">Your Role</label>
						<input type="text" id="partner-role" name="partner-role" placeholder="Enter your role here">
					</li>
					<li>
						<label for="partner-fist-name">First Name</label>
						<input type="text" id="partner-first-name" name="partner-first-name" placeholder="Enter your first name here">
					</li>
					<li>
						<label for="partner-laste-name">Last Name</label>
						<input type="text" id="partner-last-name" name="partner-last-name" placeholder="Enter your last name here">
					</li>
					<li>
						<label for="partner-email">Email</label>
						<input type="email" id="partner-email" name="partner-email" placeholder="Enter your email here">
					</li>
					<li>
						<label for="partner-phone-number">Phone number</label>
						<input type="text" id="partner-phone-number" name="partner-phone-number" placeholder="Enter your Phone number here">
					</li>
					<li>
						<p style="line-height: 20px;padding-right: 20px;">Dose the website have commenting currently?</p>
						<ul class="flex-inner">
							<li>
								<input type="radio" id="partner-yes" name="partner-yes">
								<label for="yes">Yes</label>
							</li>
							<li>
								<input type="radio" id=partner-"no" name="partner-no">
								<label for="no">No</label>
							</li>
						</ul>
					</li>
					<li>
						<label style="visibility: hidden;">Submit</label>
												<input type="submit" class="btn btn-primary" name = "parler-ticket-submit-button" id = "parler-ticket-submit-button" value = "Submit" />
					</li>
				</ul>
			</div>
			<!-- end partner form -->


			<!-- media form -->
			<div class="hidden" id="form-media">
				<ul class="flex-outer">
					<li>
						<label for="media-first-name">First Name</label>
						<input type="text" id="media-first-name" name="media-first-name" placeholder="Enter your company name here">
					</li>
					<li>
						<label for="media-last-name">Last Name</label>
						<input type="text" id="media-last-name" name="media-last-name" placeholder="Enter your last name here">
					</li>
					<li>
						<label for="media-role">Your Role</label>
						<input type="text" id="media-role" name="media-role" placeholder="Enter your role here">
					</li>
					<li>
						<label for="media-fist-name">First Name</label>
						<input type="text" id="media-first-name" name="media-first-name" placeholder="Enter your first name here">
					</li>
					<li>
						<label for="media-phone-number">Phone number</label>
						<input type="text" id="media-phone-number" name="media-phone-number" placeholder="Enter your Phone number here">
					</li>
					<li>
						<label for="media-email">Email</label>
						<input type="email" id="media-email" name="media-email" placeholder="Enter your email here">
					</li>
					<li>
						<label for="media-media">Media Outlet</label>
						<input type="text" id="media-media" name="media-media" placeholder="Enter your media outlet here">
					</li>
					<li>
						<label for="media-role">Role</label>
						<select id="media-role" class="form-control" name = "media-role">
							<option selected>Reporter</option>
							<option>Editor</option>
							<option>News Director</option>
							<option>Researcher</option>
							<option>Producer</option>
						</select>
					</li>
					<li>
						<label for="media-type-outlet">Type of Outlet</label>
						<select id="media-type-outlet" class="form-control" name="media-type-outlet" >
							<option selected>Online</option>
							<option>Print</option>
							<option>Radio</option>
							<option>Television</option>
							<option>Nonmedia</option>
							<option>Other</option>
						</select>
					</li>
					<li>
						<label for="media-description">Description</label>
						<textarea rows="6" id="media-description" placeholder="Enter your description here" name = "media-description"></textarea>
					</li>
					<li>
						<label for="media-deadline">Deadline date or Time</label>
						<input type="text" id="media-deadline" name="media-deadline" placeholder="Enter deadline date or time here">
					</li>
					<li>
						<label for="media-date">Date Story Runs or Air</label>
						<input type="date" id="media-date" name="media-date" placeholder="Enter story date here">
					</li>
					<li>
						<label style="visibility: hidden;">Submit</label>
												<input type="submit" class="btn btn-primary" name = "parler-ticket-submit-button" id = "parler-ticket-submit-button" value = "Submit" />
					</li>
				</ul>
			</div>
			<!-- end media form -->

			<!-- verification form -->
			<div class="hidden" id="form-verification">
				<ul class="flex-outer">
					<li>
						<label for="verification-account-handle">Account Handle</label>
						<input type="text" id="verification-account-handle" name="verification-account-handle" placeholder="Enter your account handle here">
					</li>
					<li>
						<label for="verification-email">Account Email</label>
						<input type="email" id="verification-email" name="verification-email" placeholder="Enter your email here">
					</li>
					<li>
						<label for="verification-fist-name">First Name</label>
						<input type="text" id="verification-first-name" name="verification-first-name" placeholder="Enter your first name here">
					</li>
					<li>
						<label for="verification-last-name">Last Name</label>
						<input type="text" id="verification-last-name" name="verification-last-name" placeholder="Enter your last name here">
					</li>
					<li>
						<label for="verification-type-account">Type of Account</label>
						<select id="verification-type-account" class="form-control" name = "verification-type-account" >
							<option selected>Elected official</option>
							<option>Media Personality</option>
							<option>Influencer</option>
							<option>Journalist</option>
							<option>Company/Brand</option>
							<option>Other</option>
						</select>
					</li>
					<li>
						<label for="verification-type-accountlink1">Supporting social media account link 1</label>
						<input type="text" id="verification-type-account-link1" name="verification-type-account-link1" placeholder="Enter your social media account link 1 here">
					</li>
					<li>
						<label for=""verification-type-accountlink2">Supporting social media account link 2</label>
						<input type="text" id="verification-type-accountlink2" name="verification-type-accountlink2" placeholder="Enter your social media account link 2 here">
					</li>
					<li>
						<label style="visibility: hidden;">Submit</label>
						<input type="submit" class="btn btn-primary" name = "parler-ticket-submit-button" id = "parler-ticket-submit-button" value = "Submit" />
					</li>
				</ul>
			</div>
			<!-- end verification form -->
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	

	<script type="text/javascript">
		$(".col-2").click(function(){          
			var box = $(this);
			box.addClass("hovered");
			box.siblings().removeClass("hovered");
			var theForm = $("#form-" + box.attr('id'));
			theForm.slideDown("slow").removeClass("hidden");
			theForm.siblings().slideUp("slow",function() { $(this).addClass("hidden"); });
		});

		/* Upload Event */
		const uploadButton = $('#browse-btn');
		const realInput = $('#screenshot');

		uploadButton.click(function(e){
			e.preventDefault();
			realInput.click();
		});
	</script>

</form>

output;

        return $output;

    }

    public function returnSubmittedFormHTML()
    {

        $thankYou = __('Thank you!', 'parler'); //https://codex.wordpress.org/I18n_for_WordPress_Developers
        $output =
            <<<output
<form method = "post" >
$thankYou
</form>
output;

        return $output;

    }

	public function compileContent(){
		$content = "This form hasn't been tested well yet. Contact Jim is there is a problem.";
    	foreach ($_POST as $key => $value) {
    		$x = var_Export($value, true);
    		if ($value != ""){
    			$content = $content .  "Field ".htmlspecialchars($key)." is \r\n".htmlspecialchars($value)."\r\n\r\n";
    		}
		}
		return $content;
	}
	
    public function processFormSubmission()

    {   
  
    	$content = $this->compileContent();
		
    	$TicketEmailer = new TicketEmailer;
		$subject = $TicketEmailer->sendCustomEmail($content);
		
    	
    	$my_post = array(
			'post_title'    => $subject,
			'post_content'  => $content,
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'		=> 'ticket'

		);
		wp_insert_post( $my_post );

    }

}