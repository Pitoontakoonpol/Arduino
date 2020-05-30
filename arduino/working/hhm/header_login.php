
<script>

function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
console.log('statusChangeCallback');
console.log(response);                   // The current login status of the person.
  if (response.status === 'connected') {   // Logged into your webpage and Facebook.
    testAPI();
  } else {                                 // Not logged into your webpage or we are unable to tell.
    document.getElementById('status').innerHTML = 'Please log ' + 'into this webpage.';
  }
}

function checkLoginState() {               // Called when a person is finished with the Login Button.
FB.getLoginStatus(function(response) {   // See the onlogin handler
  statusChangeCallback(response);
});
}


window.fbAsyncInit = function() {
FB.init({
  appId      : '231234281284247',
  cookie     : true,                     // Enable cookies to allow the server to access the session.
  xfbml      : true,                     // Parse social plugins on this webpage.
  version    : 'v6.0'                   // Use this Graph API version for this call.
});


FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
  statusChangeCallback(response);        // Returns the login status.
});
};


(function(d, s, id) {                      // Load the SDK asynchronously
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/en_US/sdk.js";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
var fb_msg_header='';
var fb_msg='';
var fbid='';

function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
console.log('Welcome!  Fetching your information.... ');
FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
  function (response) {
    fb_msg=response.id+'__19__';
    fb_msg+=response.first_name+'__19__';
    fb_msg+=response.last_name+'__19__';
    fb_msg+=response.email+'__19__';
    fb_msg+=response.gender+'__19__';
    //fb_msg+=response.locale+'__19__';
    fb_msg+=response.picture.data.url+'__19__';
    fb_msg_header="FBID__19__Name__19__Lastname__19__Email__19__Gender__19__FB_Img__19__";
    fbid=response.id;

    facebook_login_check();
      $("#status").text(fb_msg);
  });
}

</script>

  <div style="text-align:center;">Login Using Facebook:</div>
  <div style="text-align:center;margin:30px;">
  <div class="fb-login-button" data-size="large" data-button-type="continue_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="true" data-width="" onlogin="checkLoginState();"></div>
  <div style="margin-top:100px;" class="fb-login-button" data-size="small" data-button-type="continue_with" data-layout="default" data-auto-logout-link="true" data-use-continue-as="true" data-width=""></div></div>
