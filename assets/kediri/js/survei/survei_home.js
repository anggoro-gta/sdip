var defaultPositionLat = -7.848016,
    defaultPositionLon = 112.017829,
    markers = [],
    markerCluster = null,
    markersInfoWindowOpen = [];

function initMap() {
    currentUnit=null;
    (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 11
    })).data.loadGeoJson(base_url + "masterx/master/get_kecamatan_koordinat", {
        idPropertyName: "id_unit"
    }), map.data.addListener("mouseover", function (a) {
        a.feature.setProperty("state", "hover"),
            $(".map-tips").html("<h6 style='margin:0px; padding: 10px 20px; font-weight:bold;'>Kecamatan " + a.feature.getProperty("nama") + "</h6>")
    }), map.data.addListener("mouseout", function (a) {
        a.feature.setProperty("state", "normal"),
            null == currentUnit ? $(".map-tips").html("") : $(".map-tips").html("<h6 style='margin:0px; padding: 10px 20px; font-weight:bold;'>Kecamatan " + currentUnit + "</h6>")
    }), map.data.setStyle(function (a) {
        var t = .5,
            e = 1;
        return "hover" === a.getProperty("state") && (t = e = 2), {
            strokeWeight: t,
            strokeColor: "#004d40",
            zIndex: e,
            fillColor: "#222222",
            fillOpacity: .2,
            visible: !0
        }
    }), markerCluster = new MarkerClusterer(map, markers, {
        imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m"
    })
}

function get_mark() {
    var markerTitle = '';
    switch ($("#unsur").val()) {     
        case '1.01':
            markerTitle='Sekolah';
            break;   
        case '1.02':
            markerTitle='Unit Kesehatan';
            break;
        case '1.06':
            markerTitle='Rumah Penduduk';
            break;
        default:
            markerTitle='Gapoktan';
            break;
    }
    
    $.ajax({
        url: base_url + "d/survei/get_lokasi_kelompok_simple",
        type: "post",
        data: $.param({
            unsur: $("#unsur").val(),
            periode: $("#periode").val()
        }),
        success: function (a) {
            var t = JSON.parse(a);
            clear_marker();
            
            markers = t.map(function (a, t) {
                var e = new google.maps.Marker({
                    position: new google.maps.LatLng(a.lat, a.lon),
                    title: markerTitle,
                    infoWindow: new google.maps.InfoWindow({
                        content: '<div class="ajaxLoading">Mohon tunggu sebentar . . .</div>'                    
                    })
                });
                return e.addListener("click", function () {
                    
                    markersInfoWindowOpen.length > 0 && 
                                (markersInfoWindowOpen[0].infoWindow.close(), 
                                markersInfoWindowOpen.pop()),
                                e.infoWindow.open(map, e),
                                markersInfoWindowOpen.push(e);

                    $.ajax({  
                        url: base_url + "d/survei/get_info_kelompok/",  
                        type: "post",
                        dataType: 'json',
                        data:$.param({
                            id_survey: a.id_survey,
                            id_elemen: a.id_elemen
                        }),
                        success: function(d) {
                            e.infoWindow.setContent("<h4>" + d.info.header + "</h4><p>" + d.info.content + "<div style='text-align:right'></div></p>");                            
                        }  
                    });                    
                }), e.infoWindow.addListener("closeclick", function () {
                    markersInfoWindowOpen.pop()
                }), e
            }), markerCluster.addMarkers(markers)
        }
    })
}

function clear_marker() {
    markerCluster.clearMarkers()
}

$("#periode").change(function (a) {
        "" == $("#unsur").val() || get_mark()
    }),

    $("#unsur").change(function (a) {
        "" == $(this).val() || get_mark()
    });