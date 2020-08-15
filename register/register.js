//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

$(document).ready(function () {
    $('#signup').click(function(){
        var signup = $('#signup').val();
        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirm_password = $('#confirm_password').val();
        var usertype = $("input[type='radio'][name='usertype']:checked").val();

        $.ajax({
            type:'POST',
            url:'load.php', 
            dataType: 'json',  
            data: { 'signup': signup, 'username':username, 
                    'email':email, 'password':password, 
                    'confirm_password':confirm_password, 'usertype':usertype},
            success:function(data){
                if(data[0].success != undefined || data[0].success != null){
                    window.location = '../login/login.php';
                }else{
                    $('#username').val(username);
                    $('#email').val(email);
                    $('#password').val(password);
                    $('#confirm_password').val(confirm_password);

                    if(data[0].user_error != undefined || data[0].user_error != null ){
                        $(".user").attr("data-validate", data[0].user_error);
                        $(".user").addClass("alert-validate");
                    } 
                    if(data[0].email_err != undefined || data[0].email_err != null){
                        $(".mail").attr("data-validate", data[0].email_err);
                        $(".mail").addClass("alert-validate");
                    }
                    if(data[0].password_err != undefined || data[0].password_err != null){
                        $(".pass").attr("data-validate", data[0].password_err);
                        $(".pass").addClass("alert-validate");
                    }
                    if(data[0].confirm_password_err != undefined || data[0].confirm_password_err != null){
                        $(".conf_pass").attr("data-validate", data[0].confirm_password_err);
                        $(".conf_pass").addClass("alert-validate");
                    }
            }
            },
            error: function(){
                alert("Error");
            }
        });  
    });
});