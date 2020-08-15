//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Call the dataTables jQuery plugin
$(document).ready(function () {
    $('#Edit').click(function(){
        $.ajax({
            type:'POST',
            url: ".//..//..//ajax//ajax.php",
            dataType: 'json',  
            data: { 'ajaxcall': 'vendordetails' },
            success:function(data){
                //alert(data);
                //alert (data[0].VendorName);
                $('#modalEdit').modal('show');
                $('#VendorName').val(data[0].VendorName);
                $('#Lat').val(data[0].Lat); 
                $('#Lng').val(data[0].Lng);  //id from input in modal
                $('#Location').val(data[0].Location); 
                $('#Colour').val(data[0].Colour); 
                $('#BlackWhite').val(data[0].BlackWhite); 
                $('#Laminate').val(data[0].Laminate); 
                $('#BindingTape').val(data[0].BindingTape); 
                $('#BindingComb').val(data[0].BindingComb); 
                $('#PlasticCover').val(data[0].PlasticCover); 
                $('#PaperQuality70').val(data[0].PaperQuality70); 
                $('#PaperQuality80').val(data[0].PaperQuality80); 
                $('#action').val('UpdateVendor');
                $('#SaveDetails').val('Save');
            }
        });  
    });
    
    $('#SaveDetails').click(function(){
        var fd = new FormData(); //save in fd
        //to define the list of all data
        var VendorName = $('#VendorName').val();
        var Lat = $('#Lat').val();
        var Lng = $('#Lng').val();
        var Location = $('#Location').val();
        var Colour = $('#Colour').val();
        var BlackWhite = $('#BlackWhite').val();
        var Laminate = $('#Laminate').val();
        var BindingTape = $('#BindingTape').val();
        var BindingComb = $('#BindingComb').val();
        var PlasticCover = $('#PlasticCover').val();
        var PaperQuality70 = $('#PaperQuality70').val();
        var PaperQuality80 = $('#PaperQuality80').val();
        var fileToUpload = $('#fileToUpload')[0].files[0];
        var ajaxcall = 'UpdateVendor'; //sama je macam nanti ajaxcall:'UpdateVendor' tapi dh define luar
        
        fd.append('VendorName',VendorName); //call out from fd
        fd.append('Lat', Lat); 
        fd.append('Lng', Lng); 
        fd.append('Location', Location);    //left data use in POST in ajax.php //right data that define above
        fd.append('Colour', Colour);
        fd.append('BlackWhite', BlackWhite);
        fd.append('Laminate', Laminate);
        fd.append('BindingTape', BindingTape);
        fd.append('BindingComb', BindingComb);
        fd.append('PlasticCover', PlasticCover);
        fd.append('PaperQuality70', PaperQuality70);
        fd.append('PaperQuality80', PaperQuality80);
        fd.append('fileToUpload', fileToUpload);
        fd.append('ajaxcall', ajaxcall);

        $.ajax({
            type:'POST',
            url: ".//..//..//ajax//ajax.php",
            data: fd,
            processData: false,
            contentType: false,
            success:function(data){      
                alert(data);
                $('#recordForm')[0].reset();
                $('#modalEdit').modal('hide');
                location.reload();
            },
            error:function(){
                alert('Error')
            }
        });
    });

    $('#LocateMe').click(function(){
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                var message = "Geolocation is not supported by this browser.";
                alert(message);
            }
        }

        function showPosition(position) {
            var uLat = position.coords.latitude; 
            var uLng = position.coords.longitude;  
            $('#Lat').val(uLat);
            $('#Lng').val(uLng);
        }       
        getLocation();
    });

});
