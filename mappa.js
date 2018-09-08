//creo il sorgente vettoriale e il vector layer 
 var source = new ol.source.Vector({wrapX: false});

      var vector = new ol.layer.Vector({
        source: source
      });
//creo la view
      var view = new ol.View({

        center: [0, 0],

        zoom: 2

      });


//e la mappa con i dati delle strade prese da Geoserver
   var map = new ol.Map({
    layers: [
	new ol.layer.Tile({source: new ol.source.OSM()}),
	new ol.layer.Vector({
		source: new ol.source.Vector({
			format: new ol.format.GeoJSON(),
			url:'http://localhost/Vss/prox.php?url=http://localhost:8080/geoserver/vss/wfs?'
				+'service=wfs&request=GetFeature'
				+'&typename=elementostradale'
				+'&srsname=EPSG:4326'
				+'&outputFormat=json'
				})
			}),
	new ol.layer.Tile({
		source: new ol.source.TileWMS({
		url:'http://localhost:8080/geoserver/vss/wms?',
		params:{'LAYERS':'elementostradale'}
		})
		}),
    vector
    ],
	name: 'mappa',

        target: 'map',

        controls: ol.control.defaults({

          attributionOptions: {

            collapsible: false

          }

        }),

        view: view

      });

//variabilizzo la geolocalizzazione

      var geolocation = new ol.Geolocation({
        trackingOptions: {

          enableHighAccuracy: true

        },

        projection: view.getProjection()

      });
//la avvio in modo che sia attivata di default
       geolocation.setTracking(true);

      function el(id) {

        return document.getElementById(id);

      }
//aggiorno la pagina quando cambia la posizione gps	
 geolocation.on('change', function() {
	//disabilito i punti disegnati
	vector.setVisible(false);
	//rendo visibile il layer della posizione	
	gps_position.setVisible(true);
	//Setto il centro sulla posizione e ci zoommo
	map.getView().setCenter(geolocation.getPosition());
	map.getView().setZoom(18);
      });
//se è stato selezionato il puntamento disabilito il tracking e mi centro su padova e lancio la funzione di click
	  el('pointer').addEventListener('change',function() {
		  el('track').disabled=true;
		  el('track').checked=false;
		vector.setVisible(true);
		gps_position.setVisible(false);
	  geolocation.setTracking(false);
	var center = ol.proj.transform([11.900,45.4040], 'EPSG:4326', 'EPSG:3857');
	map.getView().setCenter(center);
	map.getView().setZoom(16);
	addInteraction();
      }); 
	//creo la variabile globale disegno
	var draw;
	var exit=false;
	 function addInteraction() {
          draw = new ol.interaction.Draw({
            source: source,
            type: "Point"
          });
// disegno il punto nelle coordinate in cui ha cliccato l'utente
          map.addInteraction(draw);
		draw.on('drawend', function(e2){
		var feature = e2.feature;
		var geom = feature.getGeometry();
		var coordinates = geom.getCoordinates();
  		var lonlat = ol.proj.transform(coordinates, 'EPSG:3857', 'EPSG:4326');
  		var lon = lonlat[0];
  		var lat = lonlat[1];
		//passo le coordinate al form html che le invierà al server
		el('Lat').value=lat;
		el('Long').value=lon;
		exit=true;
		//se esco disabilito il la funzione per permettere un solo click
	if(exit){
			map.removeInteraction(draw);
			el('pointer').disabled=true;
			}
		});
      }

      var accuracyFeature = new ol.Feature();

      geolocation.on('change:accuracyGeometry', function() {

        accuracyFeature.setGeometry(geolocation.getAccuracyGeometry());

      });


      var positionFeature = new ol.Feature();

      positionFeature.setStyle(new ol.style.Style({

        image: new ol.style.Circle({

          radius: 6,

          fill: new ol.style.Fill({

            color: '#3399CC'

          }),

          stroke: new ol.style.Stroke({

            color: '#fff',

            width: 2

          })

        })

      }));
	//allo stesso modo di prima, quando varia la geolocalizzazione vario le coordiante
      geolocation.on('change:position', function() {
        var coordinates = geolocation.getPosition();

        positionFeature.setGeometry(coordinates ?

          new ol.geom.Point(coordinates) : null);

  		var lonlat = ol.proj.transform(coordinates, 'EPSG:3857', 'EPSG:4326');
  		var lon = lonlat[0];
  		var lat = lonlat[1];
		console.log("long "+lon+", lat "+lat);
		el('Lat').value=lat;
		el('Long').value=lon;
		});
//creo il layer per mettere il punto della posizione GPS
      gps_position = new ol.layer.Vector({

        map: map,

        source: new ol.source.Vector({

          features: [accuracyFeature, positionFeature]

        })
      });
