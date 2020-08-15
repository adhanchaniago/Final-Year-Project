//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#OrderTable').DataTable({
    "ajax": {
      url: ".//..//..//ajax//ajax.php",
      type: "POST",   
      datatype: 'json',   
      data: { 'ajaxcall': 'ordertable' },
    },
    columns: [
      { 'data': 'no' },
      { 'data': 'docName' },
      { 'data': 'userName' },
      { 'data': 'details' },
      { 'data': 'date' },
      { 'data': 'time' },
      { 'data': 'status' },
      { 'data': 'action' },
    ]
  });

  $("#OrderTable").on('click', '.details', function(){
    var id= $(this).val();
    $.ajax({
      type:'POST',
      url: ".//..//..//ajax//ajax.php",
      dataType:"json",
      data:{'ajaxcall':'details', 'id': id},
      success:function(data){
        $('#modalDetails').modal('show');  
        $('#colour').html("Colour: "+data[0].Colour);
        $('#sidespaper').html("Sides of Paper: "+data[0].SidesPaper);
        $('#laminate').html("Laminate: "+data[0].Laminate);
        $('#coverpage').html("Cover Page: "+data[0].CoverPage);
        $('#pagesprint').html("Pages to Print: "+data[0].PagesToPrint);
        $('#slidespage').html("Slides per Page: "+data[0].SlidesPerPage);
        $('#paperquality').html("Paper Quality: "+data[0].PaperQuality);
        $('#binding').html("Binding: "+data[0].Binding);
        $('#copies').html("No. of Copies: "+data[0].NoOfCopies);
        $('#download').attr("href","download.php?fileName="+data[0].fileName);
      },
      error:function(){
        alert("Error");
      }
    });
  });

  $("#OrderTable").on('click', '.proceed', function(){
    var id= $(this).val();
    $.ajax({
      type:'POST',
      url: ".//..//..//ajax//ajax.php",
      dataType:"json",
      data:{'ajaxcall':'details', 'id': id},
      success:function(data){
        $('#modalproceed').modal('show');
        $('#yes').val(data[0].id);   
        
      },
      error:function(){
        alert("Error");
      }
    });      
  });

  $('#yes').click(function(){
    var id = $(this).val();
    $.ajax({
      type:'POST',
      url: ".//..//..//ajax//ajax.php",
      data:{'ajaxcall':'updatestatusorder', 'id': id},
      success:function(){
        alert('Status Updated!');
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
    var CSV = exportTableToCSV('List of Customer Order.csv');
    window.navigator.msSaveBlob(CSV, 'List of Customer Order.csv'); 
  });
  
});
