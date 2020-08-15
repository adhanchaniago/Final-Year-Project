//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

$(document).ready(function () {
    $('#signin').click(function(){
        var signin = $('#signin').val();
        var username = $('#username').val();
        var password = $('#password').val();

        $.ajax({
            type:'POST',
            url:'load.php', 
            dataType: 'json',  
            data: { 'signin': signin, 'username':username, 
                    'password':password},
            success:function(data){
                //Tentukan Jenis User
                if(data[0].user_success != undefined){
                    window.location = '../user/upload.php';
                }else if(data[0].vendor_success != undefined){
                    window.location = '../vendor/dashboard.php';
                }else if(data[0].admin_success != undefined){
                    window.location = '../admin/dashboard.php';
                }
                else{
                //Kalau Password atau Username salah
                    $('#username').val(username);
                    $('#password').val(password);

                    if(data[0].user_error != undefined || data[0].user_error != null ){
                        $(".user").attr("data-validate", data[0].user_error);
                        $(".user").addClass("alert-validate");
                    } 
                    if(data[0].password_err != undefined || data[0].password_err != null){
                        $(".pass").attr("data-validate", data[0].password_err);
                        $(".pass").addClass("alert-validate");
                    }
            }
            },
            error: function(){
                alert("Error");
            }
        });  
    });
});