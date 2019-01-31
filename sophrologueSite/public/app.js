/*
///////////// *** Script de création de la map + ses marqueurs *** ///////////////
*/

function initMap() {
    //Création d'un tableau d'objet littéral avec les coordonnées et informations par marqueurs
    var tableauMarqueurs = [{
            lat: 43.079511,
            lng: 0.672059,
            title:"Cabinet à Labarthe Rivière",
            url:"https://www.google.com/maps/place/8+Place+du+Mar%C3%A9chal+Gallieni,+31800+Labarthe-Rivi%C3%A8re/@43.0790789,0.6718638,17z/data=!4m13!1m7!3m6!1s0x12a8f95c22d8262f:0xf776b4e87d987838!2s8+Place+du+Mar%C3%A9chal+Gallieni,+31800+Labarthe-Rivi%C3%A8re!3b1!8m2!3d43.0792841!4d0.6720057!3m4!1s0x12a8f95c22d8262f:0xf776b4e87d987838!8m2!3d43.0792841!4d0.6720057"
        },
        {
            lat: 43.110086,
            lng: 0.731294,
            title:"Cabinet à Saint Gaudens",
            url:"https://www.google.com/maps/place/31+Avenue+Francois+Mitterrand,+31800+Saint-Gaudens/@43.1098664,0.7290199,17z/data=!3m1!4b1!4m5!3m4!1s0x12a8fc9425a0e67d:0xec886232eadfc1ae!8m2!3d43.1098664!4d0.7312086"
        },
    ];
    //Instance de la classe google LatLngBounds qui représente un rectangle virtuel en coordonnées géographiques dans lequel l'ensemble des marqueurs seront contenus
    var zoneMarqueurs = new google.maps.LatLngBounds();
    var optionsCarte = {
        zoom: 13,
        center: {
            // lat: 43.080026,
            // lng: 0.673081
            lat: 43.094608,
            lng: 0.705339
        }
    }
    //Création de la carte google map dans la div
    var maCarte = new google.maps.Map(document.getElementById("map"), optionsCarte);
    
    //forEach qui extrait les informations de chaque marqueur,
    tableauMarqueurs.forEach(function (latlng) {
        var latitude = latlng.lat,
            longitude = latlng.lng;
            title = latlng.title;
            url = latlng.url;
        //1) Créé le marqueur sur la carte
        var optionsMarqueur = {
            map: maCarte,
            position: new google.maps.LatLng(latitude, longitude),
            title:this.title,
            url:this.url,
        };
        //2) Affiche le marqueur sur la carte via la classe Marker
        var marqueur = new google.maps.Marker(optionsMarqueur);
        //3) Met à jour le rectangle virtuel nommé zoneMarqueurs.
        zoneMarqueurs.extend(marqueur.getPosition());

        //4) Au clique sur le marqueur = redirection vers l'adresse google du marqueur
        google.maps.event.addListener(marqueur, 'click', function() {
            //window.location.href = this.url; => ouvre le lien sur la même page !
            window.open(this.url,'_blank'); // ouvre le lien sur une nouvelle page !

        });
    });
    // //une fois la boucle terminée, la méthode fitBounds() de la classe Map permet d'optimiser l'affichage de la carte (centre et niveau de zoom optimum)
    // maCarte.fitBounds(zoneMarqueurs);
}
