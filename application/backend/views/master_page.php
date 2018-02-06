<!DOCTYPE html>
<html>
<head>
	<title>My CMS</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/backend/semactic-ui/semantic.css'); ?>">
	<script type="text/javascript" src="<?php echo base_url('public/backend/semactic-ui/semantic.js'); ?>"></script>
  	<script type="text/javascript">
  		$(function() {
		  // Handler for .ready() called.
		  $('select.dropdown').dropdown();
		});
  	</script>
</head>
<body id="sink">

<div class="ui two column centered grid">
    <div class="column">
    	
    	<form class="ui form">
		  <h4 class="ui dividing header">Personal Information</h4>
		  <div class="two fields">
		    <div class="field">
		      <label>Name</label>
		      <div class="two fields">
		        <div class="field">
		          <input type="text" name="first-name" placeholder="First Name">
		        </div>
		        <div class="field">
		          <input type="text" name="last-name" placeholder="Last Name">
		        </div>
		      </div>
		    </div>
		    <div class="field">
		      <label>Gender</label>
		      <select class="ui dropdown">
			      <option value="">Gender</option>
			      <option value="1">Male</option>
			      <option value="0">Female</option>
			  </select>
		    </div>
		  </div>
		  <div class="two fields">
		    <div class="field">
		      <label>State</label>
		      <select class="ui search dropdown">
		        <option value="">State</option>
		        <option value="AL">Alabama</option>
		        <option value="AL">Alabama</option>
		        <option value="AK">Alaska</option>
		        <option value="AZ">Arizona</option>
		        <option value="AR">Arkansas</option>
		        <option value="CA">California</option>
		        <option value="CO">Colorado</option>
		        <option value="CT">Connecticut</option>
		        <option value="DE">Delaware</option>
		        <option value="DC">District Of Columbia</option>
		        <option value="FL">Florida</option>
		        <option value="GA">Georgia</option>
		        <option value="HI">Hawaii</option>
		        <option value="ID">Idaho</option>
		        <option value="IL">Illinois</option>
		        <option value="IN">Indiana</option>
		        <option value="IA">Iowa</option>
		        <option value="KS">Kansas</option>
		        <option value="KY">Kentucky</option>
		        <option value="LA">Louisiana</option>
		        <option value="ME">Maine</option>
		        <option value="MD">Maryland</option>
		        <option value="MA">Massachusetts</option>
		        <option value="MI">Michigan</option>
		        <option value="MN">Minnesota</option>
		        <option value="MS">Mississippi</option>
		        <option value="MO">Missouri</option>
		        <option value="MT">Montana</option>
		        <option value="NE">Nebraska</option>
		        <option value="NV">Nevada</option>
		        <option value="NH">New Hampshire</option>
		        <option value="NJ">New Jersey</option>
		        <option value="NM">New Mexico</option>
		        <option value="NY">New York</option>
		        <option value="NC">North Carolina</option>
		        <option value="ND">North Dakota</option>
		        <option value="OH">Ohio</option>
		        <option value="OK">Oklahoma</option>
		        <option value="OR">Oregon</option>
		        <option value="PA">Pennsylvania</option>
		        <option value="RI">Rhode Island</option>
		        <option value="SC">South Carolina</option>
		        <option value="SD">South Dakota</option>
		        <option value="TN">Tennessee</option>
		        <option value="TX">Texas</option>
		        <option value="UT">Utah</option>
		        <option value="VT">Vermont</option>
		        <option value="VA">Virginia</option>
		        <option value="WA">Washington</option>
		        <option value="WV">West Virginia</option>
		        <option value="WI">Wisconsin</option>
		        <option value="WY">Wyoming</option>
		      </select>
		    </div>
		    <div class="field"></div>
		  </div>
		  <div class="field">
		    <label>Biography</label>
		    <textarea></textarea>
		  </div>
		  <h4 class="ui dividing header">Account Info</h4>
		  <div class="two fields">
		    <div class="required field">
		      <label>Username</label>
		      <div class="ui icon input">
		        <input type="text" placeholder="Username">
		        <i class="user icon"></i>
		      </div>
		    </div>
		    <div class="required field">
		      <label>Password</label>
		      <div class="ui icon input">
		        <input type="password">
		        <i class="lock icon"></i>
		      </div>
		    </div>
		  </div>
		   <h4 class="ui block top attached header">Optional Survey</h4>
		  <div class="ui bottom attached secondary segment">
		    <div class="grouped fields">
		      <label for="alone">Are you a human?</label>
		      <div class="field">
		        <div class="ui radio checkbox checked">
		          <input type="radio" checked="" name="alone">
		          <label>Yes</label>
		        </div>
		      </div>
		      <div class="field">
		        <div class="ui radio checkbox">
		          <input type="radio" name="alone">
		          <label>No</label>
		        </div>
		      </div>
		    </div>
		  </div>
		   <h4 class="ui dividing header">Settings</h4>
		  <h5 class="ui header">Privacy</h5>
		  <div class="field">
		    <div class="ui toggle checkbox">
		      <input type="radio" name="privacy">
		      <label>Allow <b>anyone</b> to see my account</label>
		    </div>
		  </div>
		  <div class="field">
		    <div class="ui toggle checkbox">
		      <input type="radio" name="privacy">
		      <label>Allow <b>only friends</b> to see my account</label>
		    </div>
		  </div>
		  <h5 class="ui header">Newsletter Subscriptions</h5>
		  <div class="field">
		    <div class="ui slider checkbox">
		      <input type="checkbox" name="top-posts">
		      <label>Top Posts This Week</label>
		    </div>
		  </div>
		  <div class="field">
		    <div class="ui slider checkbox">
		      <input type="checkbox" name="hot-deals">
		      <label>Hot Deals</label>
		    </div>
		  </div>
		  <div class="ui hidden divider"></div>
		  <div class="field">
		    <div class="ui checkbox">
		      <input type="checkbox" name="hot-deals">
		      <label>I agree to the <a href="#">Terms of Service</a>.</label>
		    </div>
		  </div>
		  <div class="ui error message">
		    <div class="header">We noticed some issues</div>
		  </div>
		  <div class="ui submit button">Register</div>
		</form>


    </div>
    <div class="four column centered row">
      <div class="column"></div>
      <div class="column"></div>
    </div>
</div>

</body>
</html>