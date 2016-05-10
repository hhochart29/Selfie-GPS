var carte;
var infoWindow = new google.maps.InfoWindow();
var nombrepoints = 0;
var points = new Array();

function center() { // centrer la carte en fonction de points visibles
    var bound = new google.maps.LatLngBounds();
    for (i = 0; i < nombrepoints; i++) {
        if(points[i].getVisible()) { bound.extend(new google.maps.LatLng(points[i].lat, points[i].lng)); }
    }
    bound.getCenter()
    carte.fitBounds(bound);
}

function initialisation() {              								// Initialisation de la carte et mes markers
    var optionsCarte = {
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    carte = new google.maps.Map(document.getElementById("carte"), optionsCarte);
    for (var i = 0; i < nombrephotos; i++) {
        function createMarker(options, infobulle) {
            var marker = new google.maps.Marker(options);
            if(infobulle) {
                google.maps.event.addListener(marker, "click", function () {
                    infoWindow.setContent(infobulle);	//infobulle
                    infoWindow.open(options.map, this);
                });
            }
            return marker;
        }
        var infobulle = '<h1>'+photos[i][1]+'</h1><img src="upload/'+photos[i][0]+'" style="max-width:300px"><p><i>'+photos[i][2]+'</i></p><p style="font-size:0.9em">'+photos[i][5]+'</p>';
        points[nombrepoints] = createMarker({
            position: new google.maps.LatLng(photos[i][3], photos[i][4]),
            map: carte,
            lat : photos[i][3],
            lng : photos[i][4],
        },infobulle);
        nombrepoints=nombrepoints+1;
    }
    center();
}
