//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';


$(function(){
  $.ajax({
      url: ".//..//..//ajax//ajax.php",
      method: "POST",
      data: { 'ajaxcall': 'piechart' },
      success: function (data) {
          console.log(data);

          var usertype = [];
          var total = [];
          var sum = 0;

          for(var x in data){
            sum = sum + data[x].value;
          }

          for (var i in data) {
              usertype.push(data[i].label);
              total.push(data[i].value);
          }
          var ctx = document.getElementById("myPieChart");
          var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: usertype,
              datasets: [{
                data: total,
                backgroundColor: ['#1E90FF', '#FF4500'],
              }],
            },
          });
      }
  });
});