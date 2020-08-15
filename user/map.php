<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

  // Initialize the session
  session_start();
  $filepreview = $_SESSION['filepreview'];
  

  //Setup connection
  require_once "../dbconfig.php";

  //Check if the user is logged in, if not then redirect him to login page 
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: ../login/login.php");
      exit;
  }

  $username = $_SESSION["username"];
  $id = $_SESSION["id"];

  //For money user account
  $queryMoney = "select money from login where id = '$id'";
  $resultMoney = mysqli_query($link, $queryMoney);

  $rows = mysqli_fetch_assoc($resultMoney);
  $money = number_format((float)$rows['money'], 2, '.', ' ');

  //take row 1, 3, 5 (odd) in database vendor
  $query1 = "select * from vendor";
  $result1 = mysqli_query($link, $query1);

  //take row 2, 4, 6 (even) database vendor
  /*$query2 = "select * from  (select  *, @rn := @rn + 1 as rn from vendor join (select @rn := 0) i) s where rn mod 2 = 0";
  $result2 = mysqli_query($link, $query2);*/

  $orderid = $_SESSION['orderid'];
  if (isset($_POST['vendor'])){

    $vendor = $_POST['vendor'];
    $_SESSION['vendor'] = $vendor;
    $sql = "UPDATE userorder SET VendorName ='$vendor' where Id = '$orderid'";
    mysqli_query($link, $sql);

  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title> E-Print System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../login/fonts/material-icon/css/material-design-iconic-font.min.css">
    
    <!-- Image -->
    <link rel="icon" type="image/png" href="../login/images/printer.png"/>

    <!-- Main css -->
    <link rel="stylesheet" href="../user/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- Mapbox -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.10.0/mapbox-gl.css" rel="stylesheet" />

    <!-- Turf.js -->
    <script src='https://unpkg.com/@turf/turf/turf.min.js'></script>

    <style>
      .center {position: absolute; left:0; top:20%; width: 100%; border:1px solid black}
      h4 {text-align:center; margin-top:20px;}
      #map {position:absolute; margin-top:15px; bottom:0; width:60%; height:60%; margin-left:20%;}
      .marker {
          background-image: url('location.png');
          background-size: cover;
          width: 40px;
          height: 40px;
          border-radius: 50%;
          cursor: pointer;
      }
      .mapboxgl-popup {
          width: 150px;
          height: 40px;
      }

      .mapboxgl-popup-content {
          text-align: center;
          font-family: 'Open Sans', sans-serif;
      }
    </style>

</head>
<body style="background-color:#F0FFFF;"> 
   <!-- Header in user page -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" style="margin-left:15px;"><img src="../login/images/print(1).png" style="margin-right:10px"> E-Print System </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="upload.php" style="margin-left:90px;"> Print <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="PrintHistory.php" style="margin-left:20px;"> Print History </a>
          </li>
          <li class="nav-item dropdown" style="margin-left:610px;">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> 
                <img src="../login/images/user.png" style="margin-right:10px;"> <?php echo $username ?> </a>
            <div class="dropdown-menu" style="margin-left:-50px;text-align:center;">
              <a class="dropdown-item" href="profile.php">My Profile</a>
              <a class="dropdown-item" href="#">Balance: RM<?php echo $money?></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="../logout.php"><i class="fa fa-sign-out" aria-hidden="true" style="margin-right:5px;"></i> Log out </a>
            </div>
          </li>  
        </ul>  
      </div>
    </nav>

    <!-- map -->
    <h4><img src="../login/images/map.png" style="margin-right:10px;"><b>Select a vendor</b></h4>

    <div class="center" id="map"></div>
    <script>
      mapboxgl.accessToken = 'pk.eyJ1IjoiYWxpYXl1c29mIiwiYSI6ImNrOXJ3dzdmNDBzZHMzZmx6eXpxY216dmIifQ.DEDgG-_66I2ncj_SCcep5g';
      var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/mapbox/streets-v11',
        center:  [110.429913, 1.469662], // starting position [lng, lat]
        zoom: 15 // starting zoom
      });
      
      var geojson = {    
        type: 'FeatureCollection',
        features: [
          <?php
            $query = "SELECT VendorName, lng, lat, Location FROM vendor";
            $result = mysqli_query($link, $query) or die (mysqli_error());

            //loop for php
            while($row=mysqli_fetch_assoc($result)) {  
              echo
              "{
                type: 'Feature',
                geometry: {
                  type: 'Point',
                  coordinates: [".$row['lng']."," .$row['lat']."]
                },
                properties: {
                  title: '".$row['VendorName']."',
                  description: '".$row['Location']."'
                }
              },";
            }
          ?>
        ]
      };

      geojson.features.forEach(function(marker) {
        // create a HTML element for each feature
        var el = document.createElement('div');
        el.className = 'marker';

        // make a marker for each feature and add to the map
        new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
          .setHTML('<b>' + marker.properties.title + '</b><p> at ' + marker.properties.description + '</p>'))
          .addTo(map);
      });

      //Add geolocate control to the map
      map.addControl(
        new mapboxgl.GeolocateControl({
        positionOptions: {
        enableHighAccuracy: true
        },
        trackUserLocation: true
        })
      );

      // Add zoom and rotation controls to the map
      map.addControl(new mapboxgl.NavigationControl());
    </script>
  
    <!-- List of vendor -->
    <div class="row" style="margin:50px; margin-top:35%; margin-left:100px;">
      <p id="distance" style="margin-left:33%;" hidden></p>   
        <div class="column">
        <form action='map.php' method='POST' style='margin-left:20px;'>
          <table>
            <?php
              $rows = array();
              $i=0;
                while($row=mysqli_fetch_assoc($result1)) {
                    $rows['VendorName'][$i] = $row['VendorName'];
                    $rows['Image'][$i] = $row['Image'];
                    $i++;
                }

              $noOfColumns = mysqli_num_rows($result1);
              $noOfRows = ceil($noOfColumns/4);
              $noOfColumn = 4;
              $k = 0;

              if ($noOfColumns < 4){
                  for($i=1;$i<=$noOfRows;$i++)
                  {
                  echo "<tr>";
                  for ($j=0;$j<$noOfColumns;$j++)
                    {
                    echo "
                    <td>
                      <button class='vendor border border-dark p-3' type='button' style='background-color:#FFFFFF;' value='".$rows['VendorName'][$k]."'>  
                        <table style='display:inline-block;width:100%;'>  
                          <tr>
                            <td>
                              <img src='../login/images/".$rows['Image'][$k]."' width='100' height='100' style='border-radius:10px;' alt='".$rows['VendorName'][$k]."'>
                            </td>
                            <td style='width:2000%;height:200%;'> 
                              <label>
                                  <b>".$rows['VendorName'][$k]."</b>
                              </label>       
                              <br/><button class='btn btn-primary btn-sm viewdetail' type='button' name='viewdetail' value='".$rows['VendorName'][$k]."' style='margin-left:20px;'>
                              <i class='fa fa-info-circle' aria-hidden='true' style='margin-right:5px;'></i>View Details</button>              
                            </td>  
                          </tr>
                        </table>
                      </button>
                    </td>";
                    $k++;
                    }
                    echo "</tr>";
                  }
              }else{
                  for($i=1;$i<=$noOfRows;$i++)
                  {
                  echo "<tr>";
                  for ($j=0;$j<$noOfColumn;$j++)
                    {
                    echo "
                    <td>
                      <button class='vendor border border-dark p-3' type='button' style='background-color:#FFFFFF;' value='".$rows['VendorName'][$k]."'>  
                        <table style='display:inline-block;width:100%;'>  
                          <tr>
                            <td>
                              <img src='../login/images/".$rows['Image'][$k]."' width='100' height='100' style='border-radius:10px;' alt='".$rows['VendorName'][$k]."'>
                            </td>
                            <td style='width:2000%;height:200%;'> 
                              <label>
                                  <b>".$rows['VendorName'][$k]."</b>
                              </label>       
                              <br/><button class='btn btn-primary btn-sm viewdetail' type='button' name='viewdetail' value='".$rows['VendorName'][$k]."' style='margin-left:20px;'>
                              <i class='fa fa-info-circle' aria-hidden='true' style='margin-right:5px;'></i>View Details</button>              
                            </td>  
                          </tr>
                        </table>
                      </button>
                    </td>";
                    $k++;
                    }
                    $noOfColumns = $noOfColumns - 4;
                    if($noOfColumns<=4 && $noOfColumns >=0){
                      $noOfColumn = $noOfColumns;
                    }
                    echo "</tr>";
                    }
              }      
            ?>
          </table>
        </form>
        </div>
    </div>

    <div class="form-group" style="margin:200px; margin-top:50px;">
      <!-- Back to preferences -->
      <button type="reset" class="btn btn-primary float-left" style="margin-left:10px;" 
          onClick="window.location.replace('preferences.php')"><i class="fa fa-chevron-left" aria-hidden="true" style="margin-right:5px;"></i> Edit Preferences</button>
      
      <!-- Save to database and proceed to payment -->
      <button type="button" id="submit" name="submit" class="btn btn-primary float-right" style="margin-right:15px;" hidden> Confirm <i class="fa fa-chevron-right" aria-hidden="true" style="margin-left:5px;"></i></button>
    </div>

    <!-- Modal Popup when click -->
    <div id="detailModal" class="modal fade">
      <div class="modal-dialog">
        <form method="post" id="recordForm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="font-weight:bold">Vendor Name</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <table>
              <tr>
              <td>
              <div class="form-group">
                <label class="control-label"  id="location">Info: Location</label>
              
                <label class="control-label"  id="colour">Colour:</label>              
              
                <label class="control-label"  id="bw">Black & White:</label>            
              
                <label class="control-label"  id="laminate">Laminate:</label>                 
              
                <label class="control-label"  id="tape">Binding Tape:</label>             
              
                <label class="control-label"  id="comb">Binding Comb:</label>             
              
                <label class="control-label"  id="pc">Plastic Cover:</label>           
              
                <label class="control-label"  id="gsm70">Paper Quality (70gsm):</label>          
              
                <label class="control-label"  id="gsm80">Paper Quality (80gsm):</label>  
              </div>
              </td>
              <td>
                <img id='vImage'src="" width="250" height="200" style="border-radius:10px;" alt="">
              </td>
              </tr>
            </table>

            </div>
            <div class="modal-footer">
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
          </div>
        </form>
      </div>
    </div>

    <!-- JS -->
    <script>
      $('.viewdetail').click(function(){
        var viewdetail = $(this).val();
        $.ajax({
            type:'POST',
            url:'./ajax/ajax.php',
            dataType:'json',
            data:{'viewdetail': viewdetail, 'ajaxcall':'showmodaldata'},
            success:function(data){
              $('#detailModal').modal('show');
              $('.modal-title').html(data[0].VendorName);
              $('#location').html(data[0].Location);
              $('#colour').html(data[0].Colour);
              $('#bw').html(data[0].BlackWhite);
              $('#laminate').html(data[0].Laminate);
              $('#tape').html(data[0].BindingTape);
              $('#comb').html(data[0].BindingComb);
              $('#pc').html(data[0].PlasticCover);
              $('#gsm70').html(data[0].PaperQuality70);
              $('#gsm80').html(data[0].PaperQuality80);
              $('#vImage').attr("src", data[0].Image);
              $('#vImage').attr("alt", data[0].VendorName);		
            },
            error:function(){
              alert("Error");
            }
          });	
      });

      $('.vendor').click(function(){
        var vendor = $(this).val();
        var uLat;
        var uLng;
        $('.vendor').css("background-color", "#FFFFFF");
        $(this).css("background-color", "#ADD8E6");
        $('#submit').removeAttr("hidden");
        $('#submit').val(vendor);
        $.ajax({
            type:'POST',
            url:'./ajax/ajax.php',
            dataType:'json',
            data:{'ajaxcall':'locateMap', 'VendorName': vendor},
            success:function(data){

              var lng = data[0].lng;
              var lat = data[0].lat;

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
                var from = turf.point([uLng, uLat]);
                var to = turf.point([lng, lat]);
                var options = {units: 'kilometers'};
                var distance = turf.distance(from, to, options);
                var message = "The shop distance from your location is " + distance.toFixed(2) +"km";
                $('#distance').html(message);
                $('#distance').removeAttr("hidden");
              }
              getLocation();
            },
            error:function(){
              alert("Error");
            }
          });
      });

      $('#submit').click(function(){
        var vendor = $('#submit').val();
        $.ajax({
          type: "POST",
          url : 'map.php',
          data: {'vendor': vendor},
          success:function(data){
            window.location.replace('payment.php');
          }
        });
      });
    </script>
    
</body>
</html>