var carte;
var infoWindow = new google.maps.InfoWindow();
var nombrepoints = 0;
var points = new Array();

function initialisation() {                								// Initialisation de la carte et mes markers
    var centreCarte = new google.maps.LatLng(49.183333, -0.35); 		//instanciation de la carte
    var optionsCarte = {
        zoom: 8,
        center: centreCarte,
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
            // categorie : photos[i][3]  A VOIR PLUS TARD POUR LES TAGS
        },infobulle);
        nombrepoints=nombrepoints+1;
    }



}

function formulaire(formulaire) {	 				//permet d'ajouter un point à la suite de la validation du formulaire
    if (formulaire.lat.value != "" && formulaire.long.value != "" && formulaire.titre.value != "" && formulaire.description.value != "") {   //vérife que le formulaire est bien rempli
        function createMarker(options, description) {
            var marker = new google.maps.Marker(options);
            if(description) {
                google.maps.event.addListener(marker, "click", function () {
                    infoWindow.setContent(description);	//infobulle
                    infoWindow.open(options.map, this);
                });
            }
            return marker;
        }
        points[nombrepoints] = createMarker({
            position: new google.maps.LatLng(formulaire.lat.value, formulaire.long.value),
            map: carte,							//récupération des données du formulaire
            titre: formulaire.titre.value,		//récupération des données du formulaire
            lat : formulaire.lat.value,			//récupération des données du formulaire
            lng : formulaire.long.value,		//récupération des données du formulaire
            categorie : formulaire.cat.value	//récupération des données du formulaire
        }, formulaire.description.value);		//récupération des données du formulaire
        nombrepoints=nombrepoints+1; 				//incrémentation du nombre de points
    }
    document.getElementById("titre").value="";   	//remise à zéro du formulaire
    document.getElementById("description").value="";//remise à zéro du formulaire
    document.getElementById("lat").value="";		//remise à zéro du formulaire
    document.getElementById("long").value="";		//remise à zéro du formulaire
    return false;
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
    for (i = 0; i < nombrepoints; i++) {  		//boucle tant qu'il y a des points
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
