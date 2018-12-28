<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUCXlkrB9E1qZYjF3j5OZxXoeD9gYGRPs&callback=initMap" ></script>

<script type="text/javascript">

    var markers = [];

    function initMap() 
    {
        var infowindow = new google.maps.InfoWindow();
        

        map = new google.maps.Map(document.getElementById('map'));

        // Cuando los datos del dataTable se hallan cargado
        table.on( 'xhr', function () {

            var bounds  = new google.maps.LatLngBounds();

            markers.forEach(function(marker){
                marker.setMap(null);
            });

            markers = [];

            var tableDataAjax = table.ajax.json();

            $.each(tableDataAjax.data, function(key, value){
                var id = value.id;
                markers[id] = new google.maps.Marker({
                    position: {lat: parseFloat(value.latitude), lng: parseFloat(value.longitude)},
                    map: map,
                });

                markers[id].metaData = {id: id};

                bounds.extend( {lat: parseFloat(value.latitude), lng: parseFloat(value.longitude)} );

                markers[id].addListener('click', function(event){
                    infowindow.setContent( '<b>@lang("words.Name"): </b>'+value.name+'<br><b>@choice("words.Client", 1): </b>'+value.client.user.name );
                    infowindow.open(map, markers[id]);
                });
            });

            map.fitBounds(bounds);       // auto-zoom
            map.panToBounds(bounds);     // auto-center

            // Si no hay ningun dato a mostrar en la tabla defina un zoom
            if( tableDataAjax.data.length == 0 ){ 
                map.setZoom(1);   
            }
        });
    }

    function VerMapa(id)
    {
        // Comprobar si existe un marcador con el id pasado
        if( markers[id] ){
            map.panTo( markers[id].position );
            map.setZoom(16);

            markers[id].setAnimation(google.maps.Animation.BOUNCE);

            // Eliminar la animación después de un tiempo
            setTimeout(function(){ markers[id].setAnimation(null); }, 1500);
        }
        
    }
</script>