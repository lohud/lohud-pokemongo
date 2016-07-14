<?php
  include('header.php');
?> 
    <div class="row" style="width:100%;">
      <div class="head-wrap">
        <h2>#POKEMONCHATTER HERE</h2>
        <div style="padding-top:3px;">
          <a href="https://twitter.com/share" class="twitter-share-button" data-text="POKEMONNNNN" data-via="lohud" data-hashtags="#pokemongo" data-size="small">Tweet</a>
          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
          <div class="fb-like" data-href="http://data.lohud.com/embeds/pokemongo/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true" style="top:-3px;padding-bottom:5px;"></div>
        </div>
        <p>CHATTER HERE</p>
        <p style="font-size:12px;">Right click on the map to add a marker. On mobile, tap and hold to add a marker.</p>
      </div>
    </div>
    
    <div class="row" style="height:100vh;">
      <div class="large-11 small-centered columns" id="map" style="margin-top:20px; margin-bottom:20px; height:75vh;">
      </div>
    </div>
    <div id="myModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
      <h2>Success!</h2>
      <p>Your submission has been recorded and saved.</p>
	  <p><a href="#" onclick="location.reload();">Refresh map</a></p>
      <a class="close-reveal-modal">&#215;</a>
    </div>
    <!-- Map time -->
    
    <script type="text/javascript">

      var map = new L.Map('map').setView([41.033986, -73.762910],12);

      // Define the different layers and default layer
      var defaultLayer = L.tileLayer.provider('HikeBike.HikeBike', {maxZoom:18}).addTo(map);


      var baseLayers = {
            'Default': L.tileLayer.provider('HikeBike.HikeBike'),
            'Watercolor': L.tileLayer.provider('Stamen.Watercolor'),
          };

      var layerControl = new L.control.layers(baseLayers).addTo(map);
      
      var popup = L.popup();

      

      function onMapClick(e) {

          var form = '<form class="row" id="inputform" enctype="multipart/form-data" class="well">'+
                  '<select id="type" name="type">'+
                    '<option id="type" name="type" value="" selected="yes">Select type</option>'+
                    '<option id="type" name="type" value="Gym">Gym</option>'+
                    '<option id="type" name="type" value="Pokemon">Pokemon</option>'+
                    '<option id="type" name="type" value="PokeStop">PokeStop</option>'+
                  '</select>'+
                  '<div class="large-6 columns" style="width:100%; padding:0px;" id="pokemon">'+
                    // '<label>Pokemon name'+
                      // '<input type="text" placeholder="" id="pokename">'+
                    // '</label>'+
                  '</div>'+
                  '<input style="display: none;" type="text" id="lat" name="lat" value="'+ e.latlng.lat.toFixed(6)+'" />'+
                  '<input style="display: none;" type="text" id="lng" name="lng" value="'+ e.latlng.lng.toFixed(6)+'" /><br><br>'+
                  '<div class="row">'+
                    '<div class="small-10 columns"><button type="button small radius" id="button" onclick="insertUser()" class="btn radius medium" style="color:#f2f2f2;">Submit</button></div>'+
                  '</div>'+
                  '</form>';

        popup
          .setLatLng(e.latlng)
          .setContent(form)
          .openOn(map);

        var marker = new L.marker(e.latlng).addTo(map);

        $('#type').change(function(){
          var selection = $(this).val();
          if (selection == 'Pokemon') {
            $('#pokemon').append('<label>Pokemon name<input type="text" placeholder="" id="pokename"></label>')
          } else {
            $('#pokemon').empty();
          }
        })
      }

      
      map.on('contextmenu', onMapClick);

      var gymmarker = L.markerClusterGroup({
        maxClusterRadius: 60,
        iconCreateFunction: function(cluster) {
          var gymmarker = cluster.getAllChildMarkers();
          var n = 0;
          for (var i = 0; i < gymmarker.length; i++) {
            n += gymmarker[i].number;
          }
            return new L.DivIcon({ html: '<div style="width: 30px; height: 30px; margin-left: 5px; margin-top: 5px; text-align: center; border-radius: 15px; font: 12px, Arial, Helvetica, sans-serif;background-clip: padding-box; border-radius: 20px; background-color:#E6A963; line-height: 30px;margin-left:-10px; margin-top:-10px;color:#f2f2f2;">' + cluster.getChildCount() + '</div>' });
        },
        polygonOptions: {
          fillColor: '#E6A963',
          color: '#E6A963',
          weight: 2,
          opacity: 0.75,
          fillOpacity: 0.5
        },
      });
      var pokemarker = L.markerClusterGroup({
        maxClusterRadius: 120,
        iconCreateFunction: function(cluster) {
            var pokemarker = cluster.getAllChildMarkers();
            var n = 0;
            for (var i = 0; i < pokemarker.length; i++) {
              n += pokemarker[i].number;
            }
            return new L.DivIcon({ html: '<div style="width: 30px; height: 30px; margin-left: 5px; margin-top: 5px; text-align: center; border-radius: 15px; font: 12px, Arial, Helvetica, sans-serif;background-clip: padding-box; border-radius: 20px; background-color:#DC4554; line-height: 30px;margin-left:-10px; margin-top:-10px;color:#f2f2f2;">' + cluster.getChildCount() + '</div>' });
        },
        polygonOptions: {
          fillColor: '#DC4554',
          color: '#DC4554',
          weight: 2,
          opacity: 0.75,
          fillOpacity: 0.5
        },
      });

      var stopmarkers = L.markerClusterGroup({
        maxClusterRadius: 120,
        iconCreateFunction: function(cluster) {
            var stopmarkers = cluster.getAllChildMarkers();
            var n = 0;
            for (var i = 0; i < stopmarkers.length; i++) {
              n += stopmarkers[i].number;
            }
            return new L.DivIcon({ html: '<div style="width: 30px; height: 30px; margin-left: 5px; margin-top: 5px; text-align: center; border-radius: 15px; font: 12px, Arial, Helvetica, sans-serif;background-clip: padding-box; border-radius: 20px; background-color:#8FCCE0; line-height: 30px;margin-left:-10px; margin-top:-10px;color:#f2f2f2;">' + cluster.getChildCount() + '</div>' });
        },
        polygonOptions: {
          fillColor: '#8FCCE0',
          color: '#8FCCE0',
          weight: 2,
          opacity: 0.75,
          fillOpacity: 0.5
        },
      });

      var shownLayer, polygon;

      function removePolygon() {
        if (shownLayer) {
          shownLayer.setOpacity(1);
          shownLayer = null;
        }
        if (polygon) {
          map.removeLayer(polygon);
          polygon = null;
        }
      };

      gymmarker.on('clustermouseover', function (a) {
        removePolygon();

        a.layer.setOpacity(0.2);
        shownLayer = a.layer;
        polygon = L.polygon(a.layer.getConvexHull());
        map.addLayer(polygon);
      });
      gymmarker.on('clustermouseout', removePolygon);

      pokemarker.on('clustermouseover', function (a) {
        removePolygon();

        a.layer.setOpacity(0.2);
        shownLayer = a.layer;
        polygon = L.polygon(a.layer.getConvexHull());
        map.addLayer(polygon);
      });
      pokemarker.on('clustermouseout', removePolygon);

      stopmarkers.on('clustermouseover', function (a) {
        removePolygon();

        a.layer.setOpacity(0.2);
        shownLayer = a.layer;
        polygon = L.polygon(a.layer.getConvexHull());
        map.addLayer(polygon);
      });
      stopmarkers.on('clustermouseout', removePolygon);

      map.on('zoomend', removePolygon);


      var gymlist = [];
      var pokelist = [];
      var stoplist = [];

      $.getJSON('entries.json', function(data){
            myItems = data;

            for (var i = 0; i < myItems.length; i++) {

              var gymicon = L.icon({
                iconUrl: 'gym.png',
                iconSize: [48, 48],
              });

              var stopicon = L.icon({
                iconUrl: 'stop.png',
                iconSize: [58, 58],
              });

              var pokemonicon = L.icon({
                iconUrl: 'pokemon.png',
                iconSize: [48, 48],
              });

              var a = myItems[i];
              if (a.Active === "Yes" && a.Type === "Gym") {
                var type = a.Type;
                var marker = L.marker(L.latLng(a.Lat, a.Lng), { type: type, icon: gymicon });
                marker.bindPopup(type);
                gymlist.push(marker);

                gymmarker.addLayers(gymlist);
                map.addLayer(gymmarker);
              } else if (a.Active === "Yes" && a.Type === "Pokemon") {
                var type = a.Type;
                var pokemon = a.Pokemon;
                var marker = L.marker(L.latLng(a.Lat, a.Lng), { type: type, icon:pokemonicon });
                marker.bindPopup(
                  "Pokemon: "+pokemon);
                pokelist.push(marker);

                pokemarker.addLayers(pokelist);
                map.addLayer(pokemarker);
              } else if (a.Active === "Yes" && a.Type === "PokeStop") {
                var type = a.Type;
                var marker = L.marker(L.latLng(a.Lat, a.Lng), { type: type, icon:stopicon });
                marker.bindPopup(type);
                stoplist.push(marker);

                stopmarkers.addLayers(stoplist);
                map.addLayer(stopmarkers);
              }
            }
        })

      // Form submission

      function insertUser() {
        $("#loading-mask").show();
        $("#loading").show();
        var type = $("#type").val();
        var lat = $("#lat").val();
        var lng = $("#lng").val();
        var pokemon = $("#pokename").val();
        if (type.length == 0) {
          alert("Select a type first!");
          return false;
        }

        console.log(pokemon);
        var dataString = '&type=' + type + '&pokemon=' + pokemon + '&lat=' + lat + '&lng=' + lng ;
        // + '&pokemon=' + pokename
        // console.log(dataString);
        $.ajax({
          type: "POST",
          url: "insert.php",
          data: dataString,
        });

        $('#myModal').foundation('reveal', 'open');
        console.log('fired');

        // return false;

      }

    </script>
    
<?php
  include('footer.php');
?>