//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

$(function() {
    var ajaxcall = 'uploadfile';

    // preventing page from redirecting
    $("html").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("h5").text("Drag here");
    });

    $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $('.zone').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("h5").text("Drop");
    });

    // Drag over
    $('.zone').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("h5").text("Drop");
    });

    // Drop
    $('.zone').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();

        $("h5").text("Upload");

        var file = e.originalEvent.dataTransfer.files;
        var fd = new FormData();

        fd.append('uploaded_file', file[0]);
        fd.append('ajaxcall',ajaxcall);
        uploadData(fd);
    });

    // Open file selector on div click
    $("#dropZ").click(function(){
        $("#uploaded_file").click();
    });

    // file selected
    $("#uploaded_file").change(function(){
        var fd = new FormData();

        var files = $('#uploaded_file')[0].files[0];

        fd.append('uploaded_file',files);
        fd.append('ajaxcall',ajaxcall);
        uploadData(fd);
    });
});

// Sending AJAX request and upload file
function uploadData(formdata){
    $('#loading').modal('show');
    $.ajax({
        url: './ajax/ajax.php',
        type: 'post',
        dataType: 'json',
        data: formdata,
        processData: false,
        contentType: false,
        success: function(data){
                $('#loading').removeClass('in');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
                $('#loading').hide(); 
            if(data[0].uploadOk == '1'){
                $('#Success').modal('show');
                $('#text').html(data[0].statusMsg);
                $('#SuccessUpload').attr("onClick","window.location.replace('preferences.php')");
            }
            else {
                $('#Success').modal('show');
                $('#text').html(data[0].statusMsg);
                $('#SuccessUpload').attr("onClick","window.location.replace('upload.php')");
            }
        },
        error: function(xhr, ajaxOptions, thrownError){
            alert("Error");
        }
    });
}
