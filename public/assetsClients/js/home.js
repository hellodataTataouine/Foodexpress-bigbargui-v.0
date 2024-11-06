let oldMarker;
let map;
let latg;
let lngg;
document.addEventListener("DOMContentLoaded", function () {


    document.cookie = "postal_code=;expires=" + new Date(0).toUTCString();
    document.cookie = "deliverymode=;expires=" + new Date(0).toUTCString();
    document.cookie = "clientlng=;expires=" + new Date(0).toUTCString();
    document.cookie = "clientLat=;expires=" + new Date(0).toUTCString();
    document.cookie = "clientAdress=;expires=" + new Date(0).toUTCString();
    document.cookie = "ccdate=;expires=" + new Date(0).toUTCString();
    document.cookie = "ccmode=;expires=" + new Date(0).toUTCString();
    document.cookie = "cctime=;expires=" + new Date(0).toUTCString();
    document.cookie = "clientCity=;expires=" + new Date(0).toUTCString();


    map = L.map('map').setView([48.861310089309214, 2.30034049306611], 13);
    const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: false
    }).addTo(map);
    map.invalidateSize();
    oldMarker = L.marker([48.861310089309214, 2.30034049306611]).addTo(map);
    map.setView([48.861310089309214, 2.30034049306611], 13);
    map.invalidateSize();


    // Add search control
    // var searchControl = L.Control.geocoder({
    //   defaultMarkGeocode: false,
    //   autocomplete: true
    // }).on('markgeocode', function (e) {
    //   map.fitBounds(e.geocode.bbox);
    //   latg = e.geocode.center.lat;
    //   lngg = e.geocode.center.lng;

    //   if (oldMarker !== undefined) {
    //     map.removeLayer(oldMarker);
    //   }

    //   var newMarker = L.marker([latg, lngg]).addTo(map)
    //     .bindPopup('Emplacement actuel')
    //     .openPopup();
    //   oldMarker = newMarker;
    //   map.setView([latg, lngg], 13);
    //   map.invalidateSize();
    //   getAddress(latg, lngg);
    // }).addTo(map);

    map.on('click', function (e) {
      if (oldMarker !== undefined) {
        map.removeLayer(oldMarker);
      }

      latg = e.latlng.lat;
      lngg = e.latlng.lng;

      var newMarker = L.marker([latg, lngg]).addTo(map)
        .bindPopup('Emplacement actuel')
        .openPopup();
      oldMarker = newMarker;
      map.invalidateSize();
      getAddress(latg, lngg);
    });
});

  function getAddress(latitude, longitude) {
    if (latitude && longitude) {
      fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`)
        .then(response => response.json())
        .then(data => {
          var addressComponents = data.address;

          // Extract city
          var city = addressComponents.city || addressComponents.town || addressComponents.village;
          // Extract street
          var street = addressComponents.road || addressComponents.street;
          const postalcode = data.address.postcode;
          const ville = document.querySelector("input[name='ville']");
          const roadin = document.querySelector("input[name='road']");

          ville.value = city;
          roadin.value = street;
          const compadresse = document.querySelector("input[name='address-input']");

          compadresse.value= data.display_name;

          var postal_code= document.getElementById('postal_code');
          postal_code.value = postalcode;

        })

        .catch(error => {
          console.error("Error fetching postal code:", error);
        });
    }
  }
  function geolocation(){
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
          latg= position.coords.latitude;
          lngg = position.coords.longitude;
          if (oldMarker !== undefined) {
            map.removeLayer(oldMarker);
          }

          var newMarker = L.marker([latg, lngg]).addTo(map)
            .bindPopup('Emplacement actuel')
            .openPopup();
          oldMarker = newMarker;
          map.setView([latg, lngg], 13);
          map.invalidateSize();
          getAddress(latg, lngg);
        });
      } else {
        alert("La géolocalisation n'est pas prise en charge dans ce navigateur.");
      }
  }


  document.addEventListener('DOMContentLoaded',function(){
    var deliveryTimeContainer = document.getElementById('ccdelivery-time-container');
					//var deliveryTimeButtonsContainer = document.getElementById('delivery-time-buttons');
          var livraisonContent = document.getElementById('livraisonContent');
          var deliverydate = document.getElementById('date');
          var deliverytime = document.getElementById('delivery_time');
          // var d = new Date();

          // var plus = new Date();
          // plus.setMonth(d.getMonth()+3);
              const formatDate = date => {
                return date.toISOString().split('T')[0]; // YYYY-mm-dd
            };

            const setDateRange = (inputElement, startDate, endDate) => {
                inputElement.setAttribute('min', formatDate(startDate));
                inputElement.setAttribute('max', formatDate(endDate));
            };

            const inputDate = document.getElementById('date');

            const now = new Date();
            const year = now.getFullYear();
            const nextMonth = now.getMonth() + 3;
            const nextDay = now.getDate();

            setDateRange(inputDate, now, new Date(year, nextMonth, nextDay));
          // $('#date').attr('min',d.getFullYear() +"-" +d.getMonth()+"-"+d.getDay());
          // $('#date').attr('max',plus.getFullYear() +"-" +plus.getMonth()+"-"+plus.getDay());
          // var d= new Date();
          // var plusthreemonths = d.add(3).month();
          // $('#date').attr('max',plusthreemonths);
          deliverydate.valueAsDate = new Date();
          populateDeliveryTimeOptions(deliverytime,document.getElementById('heure_ouverture').value,document.getElementById('heure_fermeture').value,deliverydate.value);

					document.querySelectorAll('.delivery-method-btn').forEach(function(button) {
						button.addEventListener('click', function() {
							var dataMethod = this.getAttribute('data-method');
							var deliveryTypeId = this.getAttribute('data-id');
					//document.getElementById('delivery_method_input').value = deliveryTypeId;

							// console.log('Delivery Method:', dataMethod);
							// console.log('Delivery Type ID:', deliveryTypeId);

							if (dataMethod === "Livraison") {
								livraisonContent.style.display = "block";
                if ("geolocation" in navigator) {
                  navigator.geolocation.getCurrentPosition(function (position) {
                    latg = position.coords.latitude;
                    lngg = position.coords.longitude;
                    if (oldMarker !== undefined) {
                      map.removeLayer(oldMarker);
                    }
                    var newMarker = L.marker([latg, lngg]).addTo(map)
                      .bindPopup('Emplacement actuel')
                      .openPopup();
                    oldMarker = newMarker;
                    map.setView([latg, lngg], 13);
                    map.invalidateSize();
                    getAddress(latg, lngg);
                  });
                } else {
                  alert("La géolocalisation n'est pas prise en charge dans ce navigateur.");
                }


							} else {
								livraisonContent.style.display = 'none';


							}
              if(dataMethod === "Click & Collect"){
                deliveryTimeContainer.style.display = 'block';
                deliverytime.setAttribute('required','required');
                deliverydate.setAttribute('required','required');
                deliverydate.valueAsDate = new Date();
                populateDeliveryTimeOptions(deliverytime,document.getElementById('heure_ouverture').value,document.getElementById('heure_fermeture').value,deliverydate.value);



              }else{
                deliveryTimeContainer.style.display = "none";
                deliverytime.removeAttribute('required');
                deliverydate.removeAttribute('required');

              }

							// Toggle active class for switchable buttons
							document.querySelectorAll('.delivery-method-btn').forEach(function(btn) {
								btn.classList.remove('active');
							});
							this.classList.add('active');
						});
					});



  });
  function populateDeliveryTimeOptions(delverytimeselect,heureouverture,heurefermeture,ndate) {
    delverytimeselect.innerHTML = '';

      var now = new Date();


    const formatDate = date => {
      return date.toISOString().split('T')[0]; // YYYY-mm-dd
  };
  var datenow = formatDate(now);
  var datenew = ndate;
    const currentHour = now.getHours()+1;



    //var heureouverture = document.getElementById('heure_ouverture').value;
    //var heurefermeture = document.getElementById('heure_fermeture').value;
    var ho = heureouverture.substr(0,2);
    var hf= heurefermeture.substr(0,2);
    var hoint = parseInt(ho);
    var hfint = parseInt(hf);
    if (ndate=== datenow){
    if (currentHour<=hoint && currentHour<=hfint){
      for (let hour = hoint+1; hour <= hfint-1; hour++) {
        for (let minute = 0; minute <= 45; minute += 15) {
          const formattedHour = hour.toString().padStart(2, '0');
          const formattedMinute = minute.toString().padStart(2, '0');

          const option = document.createElement('option');
          option.value = `${formattedHour}:${formattedMinute}`;
          option.text = `${formattedHour}:${formattedMinute}`;

          delverytimeselect.appendChild(option);
        }
      }
    }else if (currentHour>= hoint && currentHour<=hfint){
      for (let hour = currentHour; hour <= hfint-1; hour++) {
        for (let minute = 0; minute <= 45; minute += 15) {
          const formattedHour = hour.toString().padStart(2, '0');
          const formattedMinute = minute.toString().padStart(2, '0');

          const option = document.createElement('option');
          option.value = `${formattedHour}:${formattedMinute}`;
          option.text = `${formattedHour}:${formattedMinute}`;

          delverytimeselect.appendChild(option);
        }
      }
    }
  }else{

    for (let hour = hoint+1; hour <= hfint; hour++) {
      for (let minute = 0; minute <= 45; minute += 15) {
        const formattedHour = hour.toString().padStart(2, '0');
        const formattedMinute = minute.toString().padStart(2, '0');

        const option = document.createElement('option');
        option.value = `${formattedHour}:${formattedMinute}`;
        option.text = `${formattedHour}:${formattedMinute}`;

        delverytimeselect.appendChild(option);
      }
    }
  }



  }

  function ccClick(){
    var deliverydate = document.getElementById('date');
    var deliverytime = document.getElementById('delivery_time');
    document.cookie = "ccmode="+ true;
    document.cookie = "ccdate="+ deliverydate.value;
    document.cookie = "cctime="+ deliverytime.value;

  }

  $('#submitpostalcode').on('click',function(){
    if (deliveryClick(latg,lngg)){
      $('#form').submit();
    }else{
      alert('Veillez remplir tous les champs !');
    }

  });
  $('#btnsubmitccmode').on('click',function(){
      ccClick();
      $('#formcc').submit();
  });
  function deliveryClick(latitude,longitude){
          const ville = document.querySelector("input[name='ville']").value;
          const roadin = document.querySelector("input[name='road']").value;
          const cp = document.querySelector("input[name='postal_code']").value;
          const compadresse = document.querySelector("input[name='address-input']").value;
          if (!((ville==null||ville=="") || ( roadin==null|| roadin=="") || (compadresse == null || compadresse=="") || (cp==null || cp==""))){
            document.cookie = "clientAdress=" +compadresse;
            document.cookie = "clientCity="+ville;
            document.cookie = "clientLat=" + latitude;
            document.cookie = "clientlng=" + longitude;
            document.cookie = "postal_code=" + cp;
            document.cookie = "deliverymode="+ true;
            return true;

          }

            return false ;



  }

$(document).ready(function(){
    var heureouverture = document.getElementById('heure_ouverture').value;
    var heurefermeture = document.getElementById('heure_fermeture').value;
    var ho = heureouverture.substr(0,2);
    var hf= heurefermeture.substr(0,2);
    var hoint = parseInt(ho);
    var hfint = parseInt(hf);
    var now = new Date();
    var datepicker = document.getElementById('date');
    const currentHour = now.getHours()+1;
    if (currentHour>=hfint ){
      var date = new Date($("#date").val());
      const day = date.getDay();
      const selectedday = date.getDate();
      date.setDate(selectedday+1);
      datepicker.valueAsDate = date;

    }
    $.ajax({
      url: "/gethoraire/"+datepicker.value,
      type: 'GET',
      dataType: "json",
      success: function(data) {
          // log response into console
          // console.log(data);
          // console.log(data.horaire[0].heure_ouverture);
          // console.log(data.horaire[0].heure_fermeture);
          populateDeliveryTimeOptions(document.getElementById('delivery_time'),data.horaire[0].heure_ouverture,data.horaire[0].heure_fermeture,datepicker.value);
        }
  });
});
$(document).ready(function() {
var datepicker = document.getElementById('date');
  $('#date').change(function() {
      var date = new Date($(this).val());
      const day = date.getDay();
      const selectedday = date.getDate();
      if(day==0)
      {
          alert("You can't select sunday");
          date.setDate(selectedday+1);
          datepicker.valueAsDate = date;
      }
      $.ajax({
        url: "/gethoraire/"+datepicker.value,
        type: 'GET',
        dataType: "json",
        success: function(data) {
            // log response into console
            // console.log(data);
            // console.log(data.horaire[0].heure_ouverture);
            // console.log(data.horaire[0].heure_fermeture);
            populateDeliveryTimeOptions(document.getElementById('delivery_time'),data.horaire[0].heure_ouverture,data.horaire[0].heure_fermeture,datepicker.value);
          }
    });
  });

  });

function autocompleteInput(){
  $("#address-input").autocomplete({
    source: function (request, response) {
        // You can use a geocoding service here to fetch suggestions based on the input
        // For example, you can use OpenStreetMap's Nominatim service:
        $.getJSON("https://nominatim.openstreetmap.org/search?format=json", {
            q: request.term
        }, function (data) {
            response($.map(data, function (item) {

                return {
                    label: item.display_name,
                    latitude: item.lat,
                    longitude: item.lon
                };

            }));
        });
    },
    minLength: 2, // Minimum characters before triggering autocomplete
    select: function (event, ui) {
        var location = new L.LatLng(ui.item.latitude, ui.item.longitude);
        if (oldMarker !== undefined) {
          map.removeLayer(oldMarker);
        }
        var newMarker = L.marker([ui.item.latitude, ui.item.longitude]).addTo(map)
          .bindPopup('Emplacement actuel')
          .openPopup();
        oldMarker = newMarker;
        map.setView(location, 13);
        latg=ui.item.latitude;
        lngg = ui.item.longitude;
        getAddress(ui.item.latitude, ui.item.longitude);
    }
});
}
function reserver(){
  window.location = "/reservationtables";
}

function changecodepostalstate(){
  $('#showalert').hide();
}


//refresh the page after session destroy
function checkSessionStatus() {
  $.ajax({
      url: '/check-session-status',
      type: 'GET',
      success: function(response) {
          if (response.status === 'inactive') {
              // Session is deleted, take appropriate action (e.g., redirect)
              document.cookie.split(";").forEach(function(c) {
                document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
            });

              location.reload(); // Reload the page
          }
      },
      error: function(xhr, status, error) {
          console.error('Error checking session status:', error);
      }
  });
}

// Check session status every 30 minutes (adjust as needed)
setInterval(checkSessionStatus, 30 * 60 * 1000); // 30 minutes in milliseconds
