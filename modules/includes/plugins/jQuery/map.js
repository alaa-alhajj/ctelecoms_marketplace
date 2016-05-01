var geocoder = new google.maps.Geocoder();

function geocodePosition(pos) {
	geocoder.geocode({
	latLng: pos
	}, function(responses) {
	if (responses && responses.length > 0) {
	updateMarkerAddress(responses[0].formatted_address);
	} else {
	updateMarkerAddress('Cannot determine address at this location.');
	}
});
}

function updateMarkerStatus(str) {
	document.getElementById('markerStatus').innerHTML = str;
}

function updateMarkerPosition(latLng) {
	document.getElementById('info').innerHTML = [
	latLng.lat(),
	latLng.lng()
	].join(', ');
	$('#lat').val(latLng.lat());
	$('#lng').val(latLng.lng());
}

function updateMarkerAddress(str) {
	document.getElementById('address').innerHTML = str;
}



// Onload handler to fire off the app.
google.maps.event.addDomListener(window, 'load', initialize);
