<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map-canvas { /*height: 100%*/
      width: 800px;height: 600px; }
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXVOeDEcjG-3L-wvR0KWEJoSEOYHCIgDY">
    </script>
    <script type="text/javascript">
      var geocoder;
      var map;
      
      function initialize() {
      	geocoder = new google.maps.Geocoder();
      	//var latlng = new google.maps.LatLng(-34.397, 150.644);
      	var latlng = new google.maps.LatLng(37.904,-95.712);
        var mapOptions = {
          center: latlng,
          zoom: 4,
          disableDefaultUI: true,
          zoomControl: true
        };
        map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);
            
        /*var marker = new google.maps.Marker({
           position: map.getCenter(),
           map: map,
           title: 'Click to zoom'
         });*/
      }
      
      function codeAddress() {
        var address = document.getElementById('address').value;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            
           // console.log(results[0].geometry.location);
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
      
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
   <input type="text" name="address" id="address" value="" />
    <a href="javascript:;" onclick="codeAddress()">GeoCode</a>
	<br />
    <div id="map-canvas"></div>
    
  </body>
</html>