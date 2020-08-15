//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart 
$(function () {
  $.ajax({
    url: ".//..//..//ajax//ajax.php",
      dataType: 'json',
      method: "POST",
      data: { 'ajaxcall': 'areachart' },
      success: function (data) {
          console.log(data);

          var dayname= [];
          var totalorder = [];

          for (var i in data) {
              dayname.push(data[i].DAYNAME);
              totalorder.push(data[i].TotalOrder);
          }

          var ctx = document.getElementById("myAreaChart");
          var myAreaChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: dayname,
              datasets: [{
                label: "Order",
                lineTension: 0.3,
                backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 10,
                data: totalorder,
              }],
          },
          options: {
            scales: {
              xAxes: [{
                time: {
                  unit: 'date'
                },
                gridLines: {
                  display: false
                },
                ticks: {
                  maxTicksLimit: 7
                }
              }],
              yAxes: [{
                ticks: {
                  min: 0,
                  max: 6,           //nak nilai brpe dkt y-axis
                  maxTicksLimit: 6 //sifir brpe
                },
                gridLines: {
                  color: "rgba(0, 0, 0, .125)",
                }
              }],
            },
            legend: {
              display: false
            }
          }
        });
      }
  });
});