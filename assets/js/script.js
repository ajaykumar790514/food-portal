function user_losgin(btn) {
    dataString = $("#login-form").serialize();
    $.ajax({
        type: "POST",
        url: "<?=base_url();?>Login/user_login",
        data: dataString,
        dataType: 'json',
        beforeSend: function() {
            $(btn).attr("disabled", true);
            $(btn).text("Process...");
        },
        success: function(data){ 
        // console.log(data);             
          if (data.status == false) {
              $(btn).text("Login").removeAttr("disabled");
            $("#error-login-form").html('');
            $("#error-login-form").html(data.error);
          }

          if (data.status == true) {
            $("#error-login-form").html('');
           // window.location.href = base_url+'profile';   
            // window.location.href = <?=base_url();?>;               
          }
        }
    });
    return false;  //stop the actual form post !important!
}