/*
    Javascript for activity_starter
    */
    "use strict";
    var Starter = {
        url: Application.getPath() + 'Authentication/changePassword',
        init: function init() {
            this.bindListener();
        },
        bindListener: function bindListener() {
            this.handleListener();
        },
        import: function () {
            Application.import('Validation');
            Application.import('Validate');
            Application.import('Convert');
            alert(1);
            var userID=Application.getParameterByName('userID');
            $("#userID").val(userID);
        },
        validate: function () {
            $(document).on("submit", "#form-authentication-reset-paasword", async function (event) {
                event.preventDefault();
                let result = Validation.handleCheck({
                    password: {
                        isRequired: true,
                        'password':true,
                        caption: 'Password'
                    },
                    passwordConfirmation: {
                        isRequired: true,
                        matches:'password',
                        caption: 'Confirm Password'
                    },
                });
                // For removing existing validation class and setting form control border to normal
                $(".validate").remove();
                $(".form-control").css("border-color", "#D4D5D7");
    
                //Apply form validation in the fileds based on given rules 
                for (let i = 0; i < result["field"].length; i++) {
                    $("#" + result["field"][i]).css({'border': '1px solid red','border-radius': '0 0.25rem 0.25rem 0'}).after("<span class='validate pl-3'><i class='fa fa-exclamation-circle fa-fw'></i>" + result["message"][i] + "</span>");
                    $("#" + result["field"][i]).siblings(".input-group-prepend").css({'border': '1px solid red','border-radius': '0.25rem 0 0 0.25rem'});
                }
                if (result["error"] == false) {
                    let $data = Convert.toJSONString(this);
                    let Request = {
                        input: $data
                    };
                    let Response = await fetch(Starter.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(Request)
                    });
    
                    let result = await Response.json();
                    console.log(result);
                    if (result.success == 1) {
                        window.location.href = result.redirectURL;
                    } else{
                        $("#" + result.field).css({'border': '1px solid red','border-radius': '0 0.25rem 0.25rem 0'}).after("<span class='validate pl-3'><i class='fa fa-exclamation-circle fa-fw'></i>" + result["message"] + "</span>");
                        $("#" + result.field).siblings(".input-group-append").css({'border': '1px solid red','border-radius': '0.25rem 0 0 0.25rem'});
                        console.log($("#" + result.field).siblings(".input-group-append"));
                        // $("#" + result.field).parent().css("border-color", "red").removeClass("mb-3").after("<span class='validate'><i class='fa fa-exclamation-circle fa-fw'></i>" + result.message + "</span>");
                    }
                }  
            });
        },
        removeValidate: function () {
            $(".form-control").on("blur", function (event) {
                $(this).siblings(".validate").remove();
                $(this).css("border-color", "#D4D5D7");
                $(this).siblings(".input-group-prepend").css("border","none");
                
            });
        },
        togglePassword:function(){
            $(document).on("click", ".toggle", function (event) {
                var passwordInput=$(this).siblings('input[type]');
                var icon=$(this).children('i');
                var attr=passwordInput.attr("type");            
                if (attr === 'password') {
                    passwordInput.attr("type","text");
                    icon.addClass("fa-eye-slash");
                    //toggle.innerHTML = 'hide';
                  } else {
                    passwordInput.attr("type","password");
                    icon.removeClass("fa-eye-slash");
                    //toggle.innerHTML = 'show';
                  }
            });
        },
        passwordStength:function(){
            $(document).on("keyup", "#password", function (event) {
                var password = $('#password').val();
                $("#popover-password").slideDown();
                if (checkStrength(password) == false) {
                    // $('#sign-up').attr('disabled', true);
                }
            });
            $(document).on("blur", "#password", function (event) {
                $("#popover-password").slideUp();    
            });
            function checkStrength(password) {
                var strength = 0;
                
                //If password contains both lower and uppercase characters, increase strength value.
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                    strength += 1;
                    $('.low-upper-case').addClass('text-success');
                    $('.low-upper-case i').removeClass('fa-file-alt').addClass('fa-check');
                    $('#popover-password-top').addClass('hide');
    
    
                } else {
                    $('.low-upper-case').removeClass('text-success');
                    $('.low-upper-case i').addClass('fa-file-alt').removeClass('fa-check');
                    $('#popover-password-top').removeClass('hide');
                }
    
                //If it has numbers and characters, increase strength value.
                if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
                    strength += 1;
                    $('.one-number').addClass('text-success');
                    $('.one-number i').removeClass(' fa-file-alt').addClass(' fa-check');
                    $('#popover-password-top').addClass('hide');
    
                } else {
                    $('.one-number').removeClass('text-success');
                    $('.one-number i').addClass(' fa-file-alt').removeClass(' fa-check');
                    $('#popover-password-top').removeClass('hide');
                }
    
                //If it has one special character, increase strength value.
                if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                    strength += 1;
                    $('.one-special-char').addClass('text-success');
                    $('.one-special-char i').removeClass(' fa-file-alt').addClass(' fa-check');
                    $('#popover-password-top').addClass('hide');
    
                } else {
                    $('.one-special-char').removeClass('text-success');
                    $('.one-special-char i').addClass(' fa-file-alt').removeClass(' fa-check');
                    $('#popover-password-top').removeClass('hide');
                }
    
                if (password.length > 7) {
                    strength += 1;
                    $('.eight-character').addClass('text-success');
                    $('.eight-character i').removeClass(' fa-file-alt').addClass(' fa-check');
                    $('#popover-password-top').addClass('hide');
    
                } else {
                    $('.eight-character').removeClass('text-success');
                    $('.eight-character i').addClass(' fa-file-alt').removeClass(' fa-check');
                    $('#popover-password-top').removeClass('hide');
                }
    
    
    
    
                // If value is less than 2
    
                if (strength < 2) {
                    $('#result').removeClass()
                    $('#password-strength').addClass('progress-bar-danger');
    
                    $('#result').addClass('text-danger').text('Very Week');
                    $('#password-strength').css('width', '10%');
                } else if (strength == 2) {
                    $('#result').addClass('good');
                    $('#password-strength').removeClass('progress-bar-danger');
                    $('#password-strength').addClass('progress-bar-warning');
                    $('#result').addClass('text-warning').text('Week')
                    $('#password-strength').css('width', '60%');
                    return 'Week'
                } else if (strength == 4) {
                    $('#result').removeClass()
                    $('#result').addClass('strong');
                    $('#password-strength').removeClass('progress-bar-warning');
                    $('#password-strength').addClass('progress-bar-success');
                    $('#result').addClass('text-success').text('Strength');
                    $('#password-strength').css('width', '100%');
    
                    return 'Strong'
                }
    
            }
    
        },
        handleListener: function handleListener() {
            this.import();
            this.validate();
            this.removeValidate();
            this.togglePassword();
            this.passwordStength();
        }
    };
    Starter.init();