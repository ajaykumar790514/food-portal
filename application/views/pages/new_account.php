<style>
    .card-registration .select-input.form-control[readonly]:not([disabled]) {
font-size: 1rem;
line-height: 2.15;
padding-left: .75em;
padding-right: .75em;
}
.card-registration .select-arrow {
top: 13px;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
#otpdiv{

display: none;
}
#verifyotp{

display: none;
}
#resend_otp{
display: none;
font-size: 1.2rem;
}
#resend_otp:hover{

text-decoration:underline;

}
</style>
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>New Account</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="<?=base_url();?>">Home</a>
                        <span>New Account</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
      <div class="col">
        <div class="card card-registration my-4">
        <div class="row g-0 justify-content-center">
            <div class="col-xl-6 col-sm-12 col-md-6   d-xl-block text-center pt-5">
                <img src="<?=base_url();?>assets/img/register.png" alt="Sample photo" class="img-fluid"
                    style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
            </div>

            <div class="col-xl-6 col-sm-12 col-md-6">
              <div class="card-body p-md-5 text-black">
                <h3 class="mb-5 text-uppercase">New Account Creation</h3>
                <div id="mobile-number">
                <div class="row">
                  <div class="col-md-12 mb-4">
                  <div id="message3"></div>
                    <div class="form-outline">
                    <label class="form-label" for="form3Example1m1">Mobile Number</label>
                      <input type="number" id="form3Example1m1" class="form-control form-control-lg mobile-number" placeholder="Enter Mobile Number " id="mobile-number" name="mobile-number"  onkeyup='validates(this)' />
                      <input type="hidden" class="number" id="number">
                      <div class="success text-success" style="display: none;"></div>
                      <div class="error text-danger" style="display: none;"></div>
                    </div>
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-4">
                  <button type="button" id="mobileBtn" class="btn btn-success btn-lg ms-2">Next</button>
                  </div>
                  <div class="col-md-4"></div>
                </div>
                </div>
                <div id="mobile-otp" style="display: none;">
                <div class="row">
                  <div class="col-md-12 mb-4">
                  <div id="message"></div>
                    <div class="form-outline">
                    <label class="form-label" for="form3Example1m1">Enter Otp</label>
                      <input type="number" id="create-otp" class="form-control form-control-lg" placeholder="Enter OTP"   />
                    <div class="countdown"></div>
                    <a href="#" id="resend_otp" class="text-primary" type="button">Resend</a>
                    <br>
                    <div id="otp"></div>
                    </div>
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-4">
                  <button type="button" id="otpbtn" class="btn btn-success btn-lg ms-2">Next</button>
                  </div>
                  <div class="col-md-4"></div>
                </div>
                </div>
                <div id="account-div" style="display:none">
                <form id="myForm" enctype="multipart/form-data">
                <div class="row">
                <div id="message2"></div>
                <div id="success" class="text-success"></div>
                <div id="error" class="text-danger"></div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                    <label class="form-label "for="form3Example1m1">Mobile Number</label>
                      <input type="number"   id="account_mobile" name="mobile"  class="form-control form-control-lg account_mobile" readonly />
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                    <label class="form-label" for="form3Example1n1">Profile Photo</label>
                      <input type="file" name="image" id="form3Example1n1" class="form-control form-control-lg" required accept="image/*" data-parsley-trigger="change" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                    <label class="form-label" for="form3Example1m">First name</label>
                      <input type="text" id="form3Example1m" name="fname" class="form-control form-control-lg" placeholder="Enter first name" required data-parsley-trigger="change" />
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                    <label class="form-label" for="form3Example1n">Last name</label>
                      <input type="text" id="form3Example1n" name="lname" class="form-control form-control-lg" placeholder="Enter last name" required data-parsley-trigger="change" />
                    </div>
                  </div>
                </div>
                <div class="d-md-flex justify-content-start align-items-center mb-4 py-2">
                <h6 class="mb-0 me-4">Gender: </h6>
                <div class="form-check form-check-inline mb-0 me-4">
                    <input class="form-check-input" type="radio" name="gender" id="femaleGender" value="Female" required data-parsley-required="true" data-parsley-trigger="change" />
                    <label class="form-check-label" for="femaleGender">Female</label>
                </div>
                <div class="form-check form-check-inline mb-0 me-4">
                    <input class="form-check-input" type="radio" name="gender" id="maleGender" value="Male" required data-parsley-required="true" data-parsley-trigger="change" />
                    <label class="form-check-label" for="maleGender">Male</label>
                </div>
                <div class="form-check form-check-inline mb-0">
                    <input class="form-check-input" type="radio" name="gender" id="otherGender" value="Other" required data-parsley-required="true" data-parsley-trigger="change" />
                    <label class="form-check-label" for="otherGender">Other</label>
                </div>
            </div>
                <div class="form-outline mb-4">
                <label class="form-label" for="form3Example9">DOB</label>
                  <input type="date" id="form3Example9" name="dob" class="form-control form-control-lg" required data-parsley-type="dob" data-parsley-trigger="change" />
                </div>
                <div class="form-outline mb-4">
                <label class="form-label" for="form3Example97">Email ID</label>
                  <input type="text" id="form3Example97" class="form-control form-control-lg" placeholder="Enter email address" name="email" required data-parsley-type="email" data-parsley-trigger="change" />
                </div>
                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                    <label class="form-label" for="form3Example1m">Password</label>
                      <input type="password" name="password" id="form3Example1m" class="form-control form-control-lg" placeholder="Enter your password" required data-parsley-trigger="change" data-parsley-maxlength="8" />
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                    <label class="form-label" for="form3Example1n">Confirm Password</label>
                      <input type="password" name="cpassword" id="form3Example1n" class="form-control form-control-lg" placeholder="Enter your confirm password" required data-parsley-trigger="change" data-parsley-maxlength="8"/>
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-end pt-3">
                  <button type="button" class="btn btn-danger mr-2 btn-lg">Reset all</button>
                  <button type="submit" id="submitBtn" class="btn btn-success btn-lg ms-2">Submit form</button>
                </div>
              </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->
<script>
   function validates(phoneNum) {
        var phoneNumRegex = /^\+?([0-9]{3})\)?[ -]?([0-9]{3})[ -]?([0-9]{4})$/;
        if(phoneNum.value.match(phoneNumRegex)) {
            $(".success").html("Valid").fadeIn();
            $('.number').val(phoneNum.value);
            $(".error").hide();
            $("#mobileBtn").show();
            window.setTimeout(function(){
                $(".success").fadeOut();
            }, 5000);
        }  
        else {  
            $(".error").html("Please enter a valid number").fadeIn();
            $(".success").hide();
            $("#mobileBtn").hide();
            window.setTimeout(function(){
                $(".error").fadeOut();
            }, 1000);
        }
    }
        // Opt area BY AJAY KUMAR
$(document).ready(function(){

function send_otp(number){
if(number==''){
showMessage('Please Enter your Mobile Number', 'error');
}else{
$.ajax({
url:"<?=base_url();?>create-account",
type:"POST",
data:{mobile:number},
success:function(data)
{
   // console.log(data);
     data = JSON.parse(data);
    
    if (data.res=='success') {
    showMessage('OTP send you mobile number', 'success');
    timer();
    $('#otp').html(data.otp);
    $(".account_mobile").val(data.number);
    $("#mobile-otp").show();
    $("#mobile-number").hide();
   }
   
   if(data.res=='error')
   {
    showMessage(data.msg, 'error');
    $("#mobile-number").show();
    $("#mobile-otp").hide();
   }
}
});
}
};

// send otp
    $('#mobileBtn').click(function(){
    var number = $('#number').val();
    send_otp(number);
    });
//resend otp function
    $('#resend_otp').click(function(){
    var number = $('#number').val();
    send_otp(number);
    $(this).hide();
    });
});
function timer(){

var timer2 = "00:31";
var interval = setInterval(function() {


var timer = timer2.split(':');
//by parsing integer, I avoid all extra string processing
var minutes = parseInt(timer[0], 10);
var seconds = parseInt(timer[1], 10);
--seconds;
minutes = (seconds < 0) ? --minutes : minutes;

seconds = (seconds < 0) ? 59 : seconds;
seconds = (seconds < 10) ? '0' + seconds : seconds;
//minutes = (minutes < 10) ?  minutes : minutes;
$('.countdown').html("Resend otp in:  <b class='text-primary'>"+ minutes + ':' + seconds + " seconds </b>");
//if (minutes < 0) clearInterval(interval);
if ((seconds <= 0) && (minutes <= 0)){
clearInterval(interval);
$('.countdown').html('');
$('#resend_otp').css("display","block");
} 
timer2 = minutes + ':' + seconds;
}, 1000);

}

//end of timer
$(document).ready(function(){
              // check otp
              
        $("#otpbtn").on('click',function(){
           var otp = $('#create-otp').val();
          if(otp==''){
          showMessage('Please Enter Otp', 'error');
          }else{
          $.ajax({
          url:"<?=base_url();?>create-account/check_otp",
          type:"POST",
          data:{otp:otp},
          success:function(data)
          {

             if(data==1)
             {
                $("#account-div").show();
                $("#mobile-number").hide();
                $("#mobile-otp").hide();
             }else
             {
               
                showMessage('OTP not Correct', 'error');
             }
         }
      });
      }
      })
    });
    $(document).ready(function () {
    $('#myForm').parsley();

    $('#myForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Disable the submit button and change its text to "Processing..."
        $('#submitBtn').prop('disabled', true).text('Processing...');

        $.ajax({
            url: '<?=base_url();?>create-account/new', // Change this to your backend endpoint
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Parse the response JSON
                var data = JSON.parse(response);
                if (data.res == 'success') {
                    $('#success').html(data.msg);
                    setTimeout(function() {
                        window.location.href = '<?=base_url();?>';
                    }, 2000);
                } else {
                    $('#error').html(data.msg);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                $('#error').html(data.msg);
            },
            complete: function() {
                // Re-enable the submit button and change its text back to "Submit"
                $('#submitBtn').prop('disabled', false).text('Submit');
            }
        });
    });
});


    function showMessage(message, type) {
            var messageDiv = $('#message');
            messageDiv.html('<div class="' + (type === 'success' ? 'text-success' : 'text-danger') + '">' + message + '</div>').show();

            // Hide the message after 5 seconds
            setTimeout(function() {
                messageDiv.fadeOut();
            }, 5000);
            var messageDiv2 = $('#message2');
            messageDiv2.html('<div class="' + (type === 'success' ? 'text-success' : 'text-danger') + '">' + message + '</div>').show();

            // Hide the message after 5 seconds
            setTimeout(function() {
                messageDiv2.fadeOut();
            }, 5000);
            var messageDiv3 = $('#message3');
            messageDiv3.html('<div class="' + (type === 'success' ? 'text-success' : 'text-danger') + '">' + message + '</div>').show();

            // Hide the message after 5 seconds
            setTimeout(function() {
                messageDiv3.fadeOut();
            }, 5000);
            var messageDiv4 = $('#message4');
            messageDiv4.html('<div class="' + (type === 'success' ? 'text-success' : 'text-danger') + '">' + message + '</div>').show();

            // Hide the message after 5 seconds
            setTimeout(function() {
                messageDiv4.fadeOut();
            }, 5000);
            var messageDiv5 = $('#message5');
            messageDiv5.html('<div class="' + (type === 'success' ? 'text-success' : 'text-danger') + '">' + message + '</div>').show();

            // Hide the message after 5 seconds
            setTimeout(function() {
                messageDiv5.fadeOut();
            }, 5000);
        }
</script>    