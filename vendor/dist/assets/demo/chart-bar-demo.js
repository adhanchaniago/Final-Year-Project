//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Bar Chart 
$(function () {
    $.ajax({
        url: ".//..//..//ajax//ajax.php",
        dataType: 'json',
        method: "POST",
        data: { 'ajaxcall': 'WeeklyCharts' },
        success: function (data) {
            console.log(data);

            var dayname= [];
            var totalorder = [];

            for (var i in data) {
                dayname.push(data[i].DAYNAME);
                totalorder.push(data[i].TotalOrder);
            }

            var ctx = document.getElementById("myBarChart");
            var myBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dayname,
                    datasets: [{
                        label: "Order",
                        backgroundColor: "rgba(2,117,216,1)",
                        borderColor: "rgba(2,117,216,1)",
                        hoverBackgroundColor: "#2e59d9",
                        data: totalorder,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 20,
                            top: 20,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'Day'
                            },
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                maxTicksLimit: 6
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: 5,
                                maxTicksLimit: 6,
                                // Include Order
                                callback: function (value, index, values) {
                                    return number_format(value) +' '+ 'Order';
                                }
                            },
                            gridLines: {
                              display: true
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                }
            });
        }


    });

});