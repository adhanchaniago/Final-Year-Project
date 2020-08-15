//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#VendorTable').DataTable({
    "ajax": {
      url: ".//..//..//ajax//ajax.php",
      type: "POST",   
      datatype: 'json',   
      data: { 'ajaxcall': 'vendortable' },
    },
    columns: [
      { 'data': 'no' },
      { 'data': 'PrintName' },
      { 'data': 'Owner' },
      { 'data': 'Location' },
      { 'data': 'Details' },
      { 'data': 'Actions' },
    ]
  });

  $("#VendorTable").on('click', '.details', function(){
    var UserName = $(this).attr("value");
    //alert(UserName);
    $.ajax({
      url: ".//..//..//ajax//ajax.php",
      type: "POST",   
      datatype: 'json',   
      data: { 'ajaxcall': 'details', 'UserName':UserName},
      success:function(data){
        $('#modalDetails').modal('show');  
        $('#colour').html("<b>Colour:</b> RM"+data[0].colour);
        $('#bw').html("<b>Black & White:</b> RM"+data[0].bw);
        $('#laminate').html("<b>Laminate:</b> RM"+data[0].laminate);
        $('#bindingTape').html("<b>Binding Tape:</b> RM"+data[0].bindingTape);
        $('#bindingComb').html("<b>Binding Comb:</b> RM"+data[0].bindingComb);
        $('#coverpage').html("<b>Cover Page:</b> RM"+data[0].coverpage);
        $('#paperquality70').html("<b>Paper Quality 70gsm:</b> RM"+data[0].paperquality70);
        $('#paperquality80').html("<b>Paper Quality 80gsm:</b> RM"+data[0].paperquality80);
      },
      error:function(){
        alert("Error");
      }
    });
  });

  $("#VendorTable").on('click', '.edit', function(){
    var UserName = $(this).attr("value");
    
    $.ajax({
        type:'POST',
        url: ".//..//..//ajax//ajax.php",
        dataType: 'json',  
        data: { 'ajaxcall': 'vendordetails' , 'UserName':UserName},
        success:function(data){
            
            $('#modalEdit').modal('show');
            $('#VendorName').val(data[0].VendorName); //id from input in modal
            $('#Location').val(data[0].Location); 
            $('#UserName').val(data[0].UserName); 
            $('#Colour').val(data[0].Colour); 
            $('#BlackWhite').val(data[0].BlackWhite); 
            $('#Laminate').val(data[0].Laminate); 
            $('#BindingTape').val(data[0].BindingTape); 
            $('#BindingComb').val(data[0].BindingComb); 
            $('#PlasticCover').val(data[0].PlasticCover); 
            $('#PaperQuality70').val(data[0].PaperQuality70); 
            $('#PaperQuality80').val(data[0].PaperQuality80);
            $('#VUserName').val(data[0].UserName); 
            $('#action').val('UpdateVendor');
            $('#SaveDetail').val('Save');
        }
      });  
  });

  $('#SaveDetail').click(function(){
    var VUserName = $('#VUserName').val(); //for username vendor old one

    //to define the list of all data
    var VendorName = $('#VendorName').val();
    var Location = $('#Location').val();
    var UserName = $('#UserName').val(); //for updated 
    var Colour = $('#Colour').val();
    var BlackWhite = $('#BlackWhite').val();
    var Laminate = $('#Laminate').val();
    var BindingTape = $('#BindingTape').val();
    var BindingComb = $('#BindingComb').val();
    var PlasticCover = $('#PlasticCover').val();
    var PaperQuality70 = $('#PaperQuality70').val();
    var PaperQuality80 = $('#PaperQuality80').val();
    var ajaxcall = $('#action').val();; //sama je macam nanti ajaxcall:'UpdateVendor' tapi dh define luar
    //alert(VendorName);
    //alert(Location);
    
      $.ajax({
          type:'POST',
          url: ".//..//..//ajax//ajax.php",
          data: {ajaxcall:ajaxcall,VendorName:VendorName, Location:Location, UserName:UserName,
          Colour:Colour, BlackWhite:BlackWhite, Laminate:Laminate, BindingTape:BindingTape,
          BindingComb:BindingComb, PlasticCover:PlasticCover, PaperQuality70:PaperQuality70, 
          PaperQuality80:PaperQuality80, VUserName:VUserName
          },//kiri yang ikut dalam ajax.php, kanan ikut var dalam js tu
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

  $("#VendorTable").on('click', '.delete', function(){
    var UserName = $(this).attr("value");
    $.ajax({
      type:'POST',
      url: ".//..//..//ajax//ajax.php",
      dataType: 'json',  
      data: { 'ajaxcall': 'vendordetails' , 'UserName':UserName},
      success:function(data){
          //alert(data);
          //alert (data[0].VendorName);
          $('#modalDelete').modal('show');
          $('#yes').val(data[0].UserName);        
      }
    });   
  });
  
  $('#yes').click(function(){
    var UserName = $('#yes').val();
    $.ajax({
      type:'POST',
      url: ".//..//..//ajax//ajax.php",
      data:{'ajaxcall':'DeleteVendor', 'VUserName': UserName},
      success:function(){
        alert('Account Deleted!');
        location.reload();
      },
      error:function(){
        alert("Error");
      }
    });
  });
  
    //Export record
    function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
  }

  function exportTableToCSV(filename) {
    var csv = [];
    //var rows = document.querySelectorAll("table tr");
    var rows = document.querySelectorAll("table tr");

    for (var i = 0; i < rows.length; i++) {
      var row = [], cols = rows[i].querySelectorAll("td, th");

      for (var j = 0; j < (cols.length-1); j++) 
        row.push(cols[j].innerText);

      csv.push(row.join(","));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
  }
  
  $("#export").click(function(){ 
    var CSV = exportTableToCSV('Vendor Information.csv');
    window.navigator.msSaveBlob(CSV, 'Vendor Information.csv'); 
  });

});
