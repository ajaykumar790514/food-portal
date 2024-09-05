<input type="hidden" name="id" value="<?= @$address->id ?>">
<p id="edit-error-msg"></p>
 <div class="row">
    <div class="form-group col-sm-6">
        <label>Address Line 1<span class="required text-danger">*</span></label>
        <input type="text" name="address_line_1" class="form-control"  value="<?= @$address->address_line_1; ?>" class="form-control" required placeholder="Address Line 1" />
    </div>    
    <div class="form-group col-md-6">
        <label>Address Line 2 <span class="required text-danger">*</span></label>
        <input type="text" name="address_line_2" class="form-control"  value="<?= @$address->address_line_2; ?>" placeholder="Address Line 2" required="">
    </div>
    <div class="form-group col-md-6">
        <label>Address Line 3 ( optional )</label>
        <input type="text" name="address_line_3"  class="form-control" value="<?= @$address->address_line_3; ?>" placeholder="Address Line 3" >
    </div>
    <div class="form-group col-md-6">
        <label>Landmark ( optional ) </label>
        <input  type="text" name="landmark" class="form-control" value="<?= @$address->landmark ?>" placeholder="Landmark"  />
    </div>
    <div class="form-group col-sm-6">
        <label class="control-label">Contact Person Name <span class="required text-danger">*</span></label>
        <input  type="text" name="contact_person_name"  value="<?= @$address->contact_person_name ?>" class="form-control" required placeholder="Contact Person Name" />
    </div>
    <div class="form-group col-sm-6">
        <label class="control-label">Mobile  Number <span class="required text-danger">*</span></label>
        <input  type="number" name="mobile"  value="<?= @$address->contact ?>" class="form-control" required placeholder="Contact Number" />
    </div>
    <div class="form-group col-sm-6">
        <label class="control-label">Select State <span class="required text-danger">*</span></label>
        <input type="text" name="state" id="state"  placeholder="State " class="form-control" value="<?= @$address->state ?>"  required>
    </div>
    <div class="form-group col-sm-6">
        <label>City <span class="required text-danger">*</span></label>
        <input type="text" class="form-control city" name="city" placeholder="City" value="<?= @$address->city ?>" required >
    </div>
    <div class="form-group col-sm-6">
        <label>Appartment Name ( optional ) </label>
        <input type="text" class="form-control city" name="apartment_name" placeholder="Appartment Name" value="<?= @$address->apartment_name ?>"  >
    </div>
    <div class="form-group col-sm-6">
        <label>Floor ( optional ) </label>
        <input type="text" class="form-control city" name="floor" placeholder="floor" value="<?= @$address->floor ?>"  >
    </div>
    <div class="form-group col-sm-6">
        <label>Pincode <span class="required text-danger">*</span></label>
        <input  type="number"  placeholder="Pincode" name="pincode" value="<?= @$address->pincode ?>" id="pin_code" oninput="validatePinCode(this)" class="form-control" minlength="6" maxlength="6"  required />
    </div>
    
</div>
<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<script>
function validatePinCode(input) {
    input.value = input.value.replace(/^0+/, '');
    if (input.value.length > 6) {
        input.value = input.value.slice(0, 6);
    }
    var regex = /^\d{6}$/;
    if (!regex.test(input.value)) {
        input.setCustomValidity("Invalid PIN code");
    } else {
        input.setCustomValidity(""); 
    }
}
</script>
<script>
    var markers = [];

function initAutocomplete() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: -33.8688, lng: 151.2195},
      zoom: 13,
      mapTypeId: 'roadmap'
    });

    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(171, 171),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            var  markers = new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location,
              draggable:true,
             title:"Drag me!"
            });

            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            $('[name=house_no]').val(place.name);
            $('[name=address_l_2]').val(place.name);

            google.maps.event.addListener(markers, 'dragend', function(event) {
                var lat = event.latLng.lat();
                var lng = event.latLng.lng();
                $('#latitude').val(lat);
                $('#longitude').val(lng);
            });

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
      map.fitBounds(bounds);
    });
}
</script>