<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUCXlkrB9E1qZYjF3j5OZxXoeD9gYGRPs&callback=initMap" ></script>

<script type="text/javascript">
    var map, infoWindow;
    function initMap() 
    {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 16
        });
        infoWindow = new google.maps.InfoWindow;
        // Try HTML5 geolocation.
        if (navigator.geolocation) 
        {
            navigator.geolocation.getCurrentPosition(function(position) 
            {
                var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
                };

                infoWindow.setPosition(pos);
                infoWindow.setContent('Location found.');
                infoWindow.open(map);
                map.setCenter(pos);
                $('#map_localization_lat').val(pos['lat']);
                $('#map_localization_lng').val(pos['lng']);
            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } 
        else 
        {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }

      
    function handleLocationError(browserHasGeolocation, infoWindow, pos) 
    {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }

</script>