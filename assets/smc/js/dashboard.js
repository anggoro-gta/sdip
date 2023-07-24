function initMap() {
    infoWindow = new google.maps.InfoWindow, (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 10
    })).addListener("mousemove", function (t) {
        infoWindow.close()
    }), map.data.loadGeoJson(base_url + "page/get_kecamatan_koordinat", {
        idPropertyName: "id_unit"
    }), map.data.addListener("mouseover", function (t) {
        t.feature.setProperty("state", "hover"), infoWindow.setOptions({
            content: "<h4>" + t.feature.getProperty("nama") + "</h4>",
            position: t.latLng,
            maxWidth: 350
        }), infoWindow.open(map)
    }), map.data.addListener("mouseout", function (t) {
        t.feature.setProperty("state", "normal")
    }), map.data.addListener("click", function (t) {
        // currentUnit = t.feature.getProperty("nama"), get_profil(currentIdUnit = t.feature.getProperty("id_unit"), "Kecamatan " + currentUnit)
        currentUnit = t.feature.getProperty("nama");
        currentIdUnit = t.feature.getProperty("id_unit");
        kategori = t.feature.getProperty("kategori");
        t.feature.setProperty('isDesaSelected', false);
        if (kategori=="kecamatan"){
            map.data.forEach(function(feature) {
                map.data.remove(feature);
            });
            map.data.loadGeoJson(base_url + "page/get_desa_koordinat/"+currentIdUnit);
            get_profil(currentIdUnit, currentUnit);

            var bounds = new google.maps.LatLngBounds();
            processPoints(t.feature.getGeometry(), bounds.extend, bounds);
            map.fitBounds(bounds);
            
            $("#zoom_out_map").css("visibility", "visible");
            $("#area_title").html("Kecamatan " + currentUnit);
        } else if (kategori=="desa"){

            var color_change = '#0277BD';
            map.data.revertStyle();
            map.data.overrideStyle(t.feature, {
              fillColor: color_change
            });

            $("#area_title").html("Desa " + currentUnit);
            t.feature.setProperty('isDesaSelected', true);

            get_profil(currentIdUnit, currentUnit);
        }  
    }), map.data.setStyle(function (t) {
        var e = .5,
            n = "#80CBC4",
            a = "#00796B",
            i = 1;
        return "hover" === t.getProperty("state") && (e = i = 2, n = "#0277BD", a = "#01579B"), {
            strokeWeight: e,
            strokeColor: a,
            zIndex: i,
            fillColor: n,
            fillOpacity: .2,
            visible: !0
        }
    })
}

function get_profil(t, e) {
    current_id_unit = t, current_nama_unit = e, $.ajax({
        url: base_url + "masterx/master/get_elemen_home",
        type: "post",
        data: $.param({
            id_unit: t,
            periode: $("#currentTahun").val()
        }),
        beforeSend: function () {
            $("#profil").hide(), $("#profil-loading").show("fast")
        },
        complete: function () {
            $("#profil-loading").hide("fast", function () {
                $("#profil").slideDown("fast")
            })
        },
        success: function (t) {
            var n = "";
            for (i = 2017; i <= $("#tahun").val(); i++) $("#currentTahun").val() != i && (n += "<li><a href='#' onclick=\"change_tahun(this,'" + i + "')\" ><i class='fa fa-calendar'></i> " + i + "</a></li>");
            $("#profil-title").html(e + "<br/><small style='font-size: 90%;'>Tahun <div class='dropdown' style='display:inline-block'><a class='dropdown-toggle btn btn-table' id='drop4' role='button' data-toggle='dropdown' href='#' style='padding: 0px 10px;'>" + $("#currentTahun").val() + " <b class='caret'></b></a><ul id='menu1' class='dropdown-menu' role='menu' aria-labelledby='drop4'>" + n + "</ul></div></small>"), mixer.remove(".mix");
            var a = JSON.parse(t);
            if (0 == a.length);
            else {
                mixer.remove(".mix");
                for (var i = 0; i < a.length; i++) {
                    var r = $("<div class='mix " + a[i].unsur_singkat.toLowerCase().split(" ")[0] + "' style='background : " + a[i].warna + "' data-order='" + (i + 1) + "'><h5 style='text-align: center; margin:0px;'>" + a[i].keterangan + "</h5><h3 style='text-align: center;'>" + (null == a[i].jumlah ? 0 : (a[i].jumlah).toFixed(2)) + " " + a[i].satuan + "</h3></div></div>");
                    mixer.append(r)
                }
            }
        }
    })
}

function change_tahun(t, e) {
    $("#currentTahun").val(e), get_profil(current_id_unit, current_nama_unit)
}
var map, defaultPositionLat = -7.848016,
    defaultPositionLon = 112.017829,
    currentUnit = null,
    currentIdUnit = null,
    infoWindow = null,
    mixer = mixitup("#profil-content", {
        load: {
            sort: "order:asc"
        },
        animation: {
            duration: 250,
            nudge: !0,
            reverseOut: !1,
            effects: "fade scale(0.01) translateX(20%)"
        },
        classNames: {
            elementFilter: "filter-btn",
            elementSort: "sort-btn"
        },
        selectors: {
            target: ".mix",
            control: ".filter-btn"
        },
        callbacks: {
            onMixClick: function (t, e) {}
        }
    });
$(".filter-container").slimScroll({
    width: "100%",
    height: "100%",
    opacity: 0
}), get_profil($("#id_unit_home").val(), $("#id_unit_home").data("nama"));
var current_id_unit, current_nama_unit;

function processPoints(geometry, callback, thisArg) {
    if (geometry instanceof google.maps.LatLng) {
      callback.call(thisArg, geometry);
    } else if (geometry instanceof google.maps.Data.Point) {
      callback.call(thisArg, geometry.get());
    } else {
      geometry.getArray().forEach(function(g) {
        processPoints(g, callback, thisArg);
      });
    }
}