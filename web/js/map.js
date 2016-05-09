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
        var infobulle = '<h1>'+photos[i][1]+'</h1><img src="upload/'+photos[i][0]+'" style="max-width:300px"><p><i>'+photos[i][2]+'</i></p>';
        points[nombrepoints] = createMarker({
            position: new google.maps.LatLng(photos[i][3], photos[i][4]),
            map: carte,
            lat : photos[i][3],
            lng : photos[i][4]
        },infobulle);
        nombrepoints=nombrepoints+1;
    }
    center();
}

function affCategorie() { 											//permet d'afficher les catégories
var div = document.getElementById("categories");
div.innerHTML = "";
for (i = 0; i < nombrecategorie; i++) { 							//boucle tant qu'il y a des categories
div.innerHTML += '<div id="categorieaffichage">'+categories[0][i]+'<p id="c_'+i+'" onclick="affMsqCategorie('+i+')">&#9632;</p></div>'; //affiche les toutes le tableau categories

if(categories[1][i] === true) {
    document.getElementById("c_"+i).innerHTML = "&#9632;"; 	//regarde si la catégorie est visible sur la carte est affiche le carre correspondant
}
else {
    document.getElementById("c_"+i).innerHTML = "&#9633;";	//regarde si la catégorie est visible sur la carte est affiche le carre correspondant
}
}
}

function affMsqCategorie(indice) { 					//permet d'afficher/masquer les points d'une catégorie
if(categories[1][indice] === true) {
    for (i = 0; i < nombrepoints; i++) {  		    //boucle tant qu'il y a des points
    if(points[i].categorie === categories[0][indice]) {
        points[i].setVisible(false);
    }
}
categories[1][indice] = false;
document.getElementById("c_"+indice).innerHTML = "&#9633;";
}
else {
    for (i = 0; i < nombrepoints; i++) {
        if(points[i].categorie === categories[0][indice]) {
            points[i].setVisible(true);
        }
    }
    categories[1][indice] = true;
    document.getElementById("c_"+indice).innerHTML = "&#9632;";
}
}
