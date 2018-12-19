@php

    // Diferentes valores que puede tomar latitud y longitud

    if(old("latitude") == null)
    {
        if(isset($headquarters)) { $lat = $headquarters->latitude; }else{ $lat = '0'; }
    }
    else
    {
        $lat = old("latitude");
    }

    if(old("longitude") == null)
    {
        if(isset($headquarters)) { $lng = $headquarters->longitude; }else{ $lng = '0'; }
    }
    else
    {
        $lng = old("longitude");
    }

@endphp

<script>

    $.getScript('{{ resource_path("lang/es/words.php") }}', function( data, textStatus, jqxhr )
    {
        console.log(data);
        /* var iconos='<div id="text"></div><ul>';
        $.each(fA, function(key, value){
            iconos += '<li title="'+value+'"><i data-icon="'+value+'" class="fa '+value+'"></i></li>';
        });

        iconos+="</ul>";

        $('#icon').parent().after("<div class='oculto'>"+iconos+"</div>"); */
    });

</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUCXlkrB9E1qZYjF3j5OZxXoeD9gYGRPs&callback=initMap" ></script>

<script type="text/javascript">
    
    var map, infoWindow;
    function initMap() 
    {

        //Valores iniciales

        var pos = {
            lat: {{$lat}},
            lng: {{$lng}}
        };

        // Si esta en editar aumente el zoom pero si esta en crear y no permite la geolocalización defina el zoom al minimo
        var zoom = pos.lat == 0 ? 1 : 16;

        map = new google.maps.Map(document.getElementById('map'), {
            center: pos,
            zoom: zoom
        });

        infoWindow = new google.maps.InfoWindow;

        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            title: 'Location',
            draggable: true,
            animation: google.maps.Animation.DROP,
        });

        $('#latitude').val(pos.lat);
        $('#longitude').val(pos.lng);

        marker.addListener('dragend', markerDragEnd);

        // Try HTML5 geolocation.
        if (navigator.geolocation) 
        {
            navigator.geolocation.getCurrentPosition(function(position) {

                if(pos.lat == 0){
                    pos.lat = position.coords.latitude;
                    pos.lng = position.coords.longitude;
                }

                $('#latitude').val(pos.lat);
                $('#longitude').val(pos.lng);

                map.setCenter(pos);
                map.setZoom(16);

                marker.setPosition(pos);

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

    function markerDragEnd(event)
    {
        console.log('Arrastro');
        console.log('https://maps.googleapis.com/maps/api/geocode/json?latlng='+event.latLng.lat()+','+event.latLng.lng()+'&key=AIzaSyBUCXlkrB9E1qZYjF3j5OZxXoeD9gYGRPs');
        $('#latitude').val(event.latLng.lat());
        $('#longitude').val(event.latLng.lng());

        // ajax parameters: url, Method, data, Function done, Function error(optional)
        ajax(
            'https://maps.googleapis.com/maps/api/geocode/json?latlng='+event.latLng.lat()+','+event.latLng.lng()+'&key=AIzaSyBUCXlkrB9E1qZYjF3j5OZxXoeD9gYGRPs',
            'POST',
            null,
            (res) => {                    
                $('#address').val(res.results[0].formatted_address);
            }
        );
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