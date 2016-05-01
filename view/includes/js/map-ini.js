
function initialize() {
  var myLatlng = new google.maps.LatLng($('#lat').val(),$('#lng').val());
  var mapOptions = {
    zoom: 8,
    center: myLatlng,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,    
    zoomControl:false,
    streetViewControl:false,
    rotateControl:false,
    panControl:false,scrollwheel:false, mapTypeId: google.maps.MapTypeId.TERRAIN
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map
    
  });
}

google.maps.event.addDomListener(window, 'load', initialize);