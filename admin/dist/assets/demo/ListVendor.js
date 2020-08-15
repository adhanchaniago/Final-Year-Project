//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Call the dataTables jQuery plugin
$(document).ready(function() {
    $('#VendorList').DataTable({
      "ajax": {
        url: ".//..//..//ajax//ajax.php",
        type: "POST",   
        datatype: 'json',   
        data: { 'ajaxcall': 'ViewVendor' },
      },
      columns: [
        { 'data': 'no' },
        { 'data': 'VendorName' },
        { 'data': 'Email' },
        { 'data': 'Action' },
      ]
    });
 
    $("#VendorList").on('click', '.edit', function(){
      var id = $(this).attr("value");
       $.ajax({
          type:'POST',
          url: ".//..//..//ajax//ajax.php",
          dataType: 'json',  
          data: { 'ajaxcall': 'EditLogin', 'id':id },
          success:function(data){
              
              $('#modalEdit').modal('show');
              $('.modal-title').html("<b>Edit Printing Vendor Account</b>");
              $('#UserName').val(data[0].username); 
              $('#Email').val(data[0].email); 
              $('#id').val(data[0].id);
              $('#passlabel').attr('hidden','');
              $('#pass').attr('hidden','');
              $('#pass').removeAttr('required');
              $('#confirmpasslabel').attr('hidden','');
              $('#confirmpass').attr('hidden','');
              $('#confirmpass').removeAttr('required'); 
              $('#action').val('UpdateVendorLogin');
              $('#SaveDetail').val('Save');
              $('#SaveDetail').html('Save');
          }
        });  
      });

    $('#SaveDetail').click(function(){
      var id = $('#id').val(); //new username
      var username = $('#UserName').val();
      var email = $('#Email').val();
      var ajaxcall = $('#action').val();
      var password = $('#pass').val();
      var confirmpass = $('#confirmpass').val(); 
      
      $.ajax({
          type:'POST',
          url: ".//..//..//ajax//ajax.php",
          data: { ajaxcall:ajaxcall, username:username, email:email, id:id, password:password, confirmpass:confirmpass},
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

    $("#VendorList").on('click', '.delete', function(){
      var id = $(this).attr("value");
      $.ajax({
        type:'POST',
        url: ".//..//..//ajax//ajax.php",
        dataType: 'json',  
        data: { 'ajaxcall': 'EditLogin' , 'id':id}, //EditLogin $_POST['username'];
        success:function(data){
            $('#modalDelete').modal('show');
            $('#yes').val(data[0].id);        
        }
      });   
    });
    
    $('#yes').click(function(){
      var id = $('#yes').val();
      $.ajax({
        type:'POST',
        url: ".//..//..//ajax//ajax.php",
        data:{'ajaxcall':'DeleteVendorLogin', 'id': id},
        success:function(){
          alert('Account Deleted!');
          location.reload();
        },
        error:function(){
          alert("Error");
        }
      });
    });

    $('#add').click(function(){
      $('#recordForm')[0].reset();
      $('#modalEdit').modal('show');
      $('.modal-title').html("<b>Add New Printing Vendor</b>");
      $('#passlabel').removeAttr('hidden');
      $('#pass').removeAttr('hidden');
      $('#confirmpasslabel').removeAttr('hidden');
      $('#confirmpass').removeAttr('hidden');
      $('#pass').attr('required','');
      $('#confirmpass').attr('required','');
      $('#SaveDetail').html('Add');
      $('#action').val('AddVendor');  
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
    var CSV = exportTableToCSV('List of Printing Vendor.csv');
    window.navigator.msSaveBlob(CSV, 'List of Printing Vendor.csv'); 
  });
  
  });
  