function initMap() {
    infoWindow = new google.maps.InfoWindow, 
    (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 11,
        styles: mapStyle,
        mapTypeControl: !1,
        scaleControl: !0,
        navigationControl: !1,
        streetViewControl: !1,
        zoomControl: !0,
        scrollwheel: 1,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    })).addListener("mousemove", function (e) {
        infoWindow.close()
    }), 
    map.data.loadGeoJson(base_url + "page/get_kecamatan_koordinat", {
        idPropertyName: "id_unit"
    }), 
    map.data.addListener("mouseover", function (e) {
        e.feature.setProperty("state", "hover"), 
        infoWindow.setOptions({
            content: "<h4>" + e.feature.getProperty("nama") + "</h4>",
            position: e.latLng,
            maxWidth: 350
        }), 
        infoWindow.open(map)
    }), 
    map.data.addListener("mouseout", function (e) {
        e.feature.setProperty("state", "normal")
    }), 
    map.data.addListener("click", function (e) {
        currentUnit = e.feature.getProperty("nama"), 
        currentIdUnit = e.feature.getProperty("id_unit"),
        kategori = e.feature.getProperty("kategori");
        e.feature.setProperty('isDesaSelected', false);
        if (kategori=="kecamatan"){
            map.data.forEach(function(feature) {
                map.data.remove(feature);
            });
            map.data.loadGeoJson(base_url + "page/get_desa_koordinat/"+currentIdUnit);
            findProfil(currentIdUnit, currentUnit);

            var bounds = new google.maps.LatLngBounds();
            processPoints(e.feature.getGeometry(), bounds.extend, bounds);
            map.fitBounds(bounds);
            
            $("#zoom_out_map").css("visibility", "visible");
            $("#area_title").html("Kecamatan " + currentUnit);
        } else if (kategori=="desa"){

            var color_change = '#0277BD';
            map.data.revertStyle();
            map.data.overrideStyle(e.feature, {
              fillColor: color_change
            });

            $("#area_title").html("Desa " + currentUnit);
            e.feature.setProperty('isDesaSelected', true);

            findProfil(currentIdUnit, currentUnit);
        }        
        
        //  $("#modal").iziModal("setTitle", "Kecamatan " + e.feature.getProperty("nama")),
        //  $("#modal").iziModal("setSubtitle", "Kabupaten Kediri"), $("#modal").iziModal("open")
    }), map.data.setStyle(function (e) {
        var t = .8,
            a = "#fde428",
            n = "#002e5b",
            l = 1;
        
        if ("hover" === e.getProperty("state")){
            t = l = 2, a = "#0277BD", n = "#01579B";
        }

        // if (e.getProperty('isDesaSelected')){
        //     a = "#0277BD"
        // }

        return {
            strokeWeight: t,
            strokeColor: n,
            zIndex: l,
            fillColor: a,
            fillOpacity: .6,
            visible: !0
        }
    })
}

$("#zoom_out_map").click(function(){
    $("#zoom_out_map").css("visibility", "hidden");
    findProfil($("#id_unit_home").val(), $("#id_unit_home").data("nama"));
    $("#area_title").html("Kabupaten Kediri");
    initMap();
});

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

function findElemen() {
    $.ajax({
        url: base_url + "page/get_tree_elemen",
        type: "post",
        data: $.param({
            unsur: $("#unsur").val(),
            kategori: "kabupaten",
            unit: currentIdUnit
        }),
        beforeSend: function () {
            $("#modal #tree-elemen").html('<div style="height: 200px;"></div>'), modalContent.startLoading()
        },
        complete: function () {
            modalContent.stopLoading()
        },
        success: function (e) {
            if ($("#chart-elemen").hide(), $("#tree-elemen").show(), "" != e) {
                var t = JSON.parse(e);
                currentDataPage = t.data, $("#modal #tree-elemen").html(templateDataProfil(t)), com_github_culmat_jsTreeTable.treeTable($("#modal .iziModal-content table"))
            }
        }
    })
}

function findProfil(e, t) {
    current_id_unit = e, current_nama_unit = t, $.ajax({
        // url: base_url + "page/get_elemen_home",
        url: base_url + "masterx/master/get_elemen_home",
        type: "post",
        data: $.param({
            id_unit: e,
            periode: $("#currentTahun").val()
        }),
        beforeSend: function () {
            $("#profil").hide(), $("#profil-loading").show()
        },
        complete: function () {
            $("#profil-loading").hide(), $("#profil").show()
        },
        success: function (e) {
            var t = JSON.parse(e);
            if (0 == t.length);
            else {
                mixer.remove(".mix");
                for (var a = 0; a < t.length; a++) {
                    var n = $("<div class='mix green " + t[a].unsur_singkat.toLowerCase() + "' style='background-color:" + t[a].warna + "' data-order='" + (a + 1) + "'><h5 style='text-align: center; margin:0px;'>" + t[a].keterangan + "</h5><h3 style='text-align: center;'>" + (null == t[a].jumlah ? 0 : (t[a].jumlah).toFixed(2)) + " " + t[a].satuan + "</h3></div></div>");
                    mixer.append(n)
                }
            }
            initMenuTahun()
        }
    })
}

function initMenuTahun() {
    var e = "";
    for (i = 2017; i <= $("#tahun").val(); i++) $("#currentTahun").val() != i && (e += "<li><a href='#' onclick=\"change_tahun(this,'" + i + "')\" ><i class='fa fa-calendar'></i> " + i + "</a></li>");
    $("#menuTahun").html(e)
}

function change_tahun(e, t) {
    $("#currentTahun").val(t), $("#triggerTahun b").text(t), findProfil(current_id_unit, current_nama_unit)
}

function templateDataProfil(e) {
    heading = "<thead><th>No</th><th>Keterangan</th>";
    for (var t = e.tahun.length - 1; t > -1; t--) heading += "<th>" + e.tahun[t] + "</th>";
    return heading += "<th></th>", heading += "</thead>", template = "", $.each(e.data, function (e, t) {
        template += templateDataTable(t, e + 1, 1, null)
    }), "<table class='table'>" + heading + template + "</table>"
}

function templateDataTable(e, t, a, n) {
    l = "";
    if (n) l = n + "_" + (t - 1);
    else var l = "id_" + (t - 1);
    var i = "";
    i = 1 == a ? "font-weight:bolder;" : e.child ? "font-weight:bold;" : "font-weight:400;";
    var r = "<tr data-tt-id='" + l + "' data-tt-level='" + a + "' " + (n ? "data-tt-parent-id='" + n + "'" : "") + " style='" + i + "'><td>" + (1 == a ? e.singkat : e.urut) + "</td><td>" + e.keterangan + "</td>";
    if (e.tahun) {
        for (var o in e.tahun) r += "<td class='data'>", null != e.tahun[o] && null != e.tahun[o].jumlah ? r += "<span>" + e.tahun[o].jumlah + " " + e.satuan + "</span>" : r += "<span> - " + e.satuan + "</span>", r += "</td>";
        r += "<td class='data'><button type='button' class='btn btn-xs btn-primary' onclick=\"show_chart(this, '" + e.id_elemen + "')\"><i class='fa fa-bar-chart'></i></button></td>"
    } else r += "<td class='data' colspan='4'></td>";
    if (r += "</tr>", e.child)
        for (o = 0; o < e.child.length; o++) r += templateDataTable(e.child[o], o + 1, a + 1, l);
    return r
}

function show_chart(e, t) {
    currentElemen = null, currentCell = e, findElemenFromTree(currentDataPage, t);
    var a = [],
        n = [];
    for (tahun in currentElemen.tahun) a.push(tahun), n.push(null != currentElemen.tahun[tahun].jumlah ? currentElemen.tahun[tahun].jumlah : 0);
    chartConfig.data.datasets = [], chartConfig.data.datasets[0] = {}, chartConfig.data.datasets[0].label = currentElemen.keterangan, chartConfig.data.datasets[0].data = n, chartConfig.data.labels = a, $("#tree-elemen").hide(), $("#chart-elemen").slideDown("fast", function () {
        null == chart ? chart = new Chart(document.getElementById("chart").getContext("2d"), chartConfig) : chart.update(), console.log(chart)
    })
}

function show_tree() {
    $("#chart-elemen").hide(), $("#tree-elemen").slideDown("fast", function () {})
}

function findElemenFromTree(e, t) {
    for (var a = 0; a < e.length; a++) e[a].id_elemen == t && (currentElemen = e[a]), e[a].child && findElemenFromTree(e[a].child, t)
}
var map, defaultPositionLat = -7.78005,
    defaultPositionLon = 112.254456,
    currentUnit = null,
    currentIdUnit = null,
    infoWindow = null,
    currentElemen = null,
    currentDataPage = [],
    mixer = mixitup("#profil", {
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
            block: "filter",
            elementFilter: "filter-btn",
            elementSort: "sort-btn"
        },
        selectors: {
            target: ".mix",
            control: ".filter-btn"
        }
    }),
    chartConfig = {
        type: "line",
        data: {
            labels: [],
            datasets: [{
                label: "",
                data: []
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: !0
                    }
                }]
            }
        }
    },
    chart = null,
    modalContent = null;
$(".filter-container").slimScroll({
    width: "100%",
    height: "100%",
    opacity: 0
}), $("#modal").iziModal({
    radius: 1,
    headerColor: "#001c38",
    icon: "fa fa-home",
    background: "#fff",
    theme: "dark",
    width: 800,
    transitionIn: "bounceInDown",
    transitionOut: "bounceOutUp",
    fullscreen: !0,
    zindex: 1e3,
    overlayColor: "rgba(255, 255, 255, 0.6)",
    timeoutProgressbarColor: "rgba(255,255,255,0.5)",
    overlay: !1,
    onOpening: function (e) {
        modalContent = e, $("#unsur").val() && findElemen()
    },
    onClosed: function (e) {
        $("#modal #tree-elemen").html('<div style="height: 200px;"></div>')
    },
    afterRender: function () {
        $("#unsur").select2().on("select2:select", function (e) {
            findElemen()
        }), $(".select2").css("width", "100%")
    }
});
var current_id_unit, current_nama_unit;
findProfil($("#id_unit_home").val(), $("#id_unit_home").data("nama"));
var mapStyle = [{
    featureType: "all",
    elementType: "geometry",
    stylers: [{
        visibility: "off"
    }]
}, {
    featureType: "all",
    elementType: "labels",
    stylers: [{
        visibility: "off"
    }]
}, {
    featureType: "administrative",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "on"
    }, {
        color: "#000000"
    }]
}, {
    featureType: "administrative",
    elementType: "labels.text.fill",
    stylers: [{
        color: "#000000"
    }, {
        visibility: "on"
    }]
}, {
    featureType: "administrative",
    elementType: "labels.text.stroke",
    stylers: [{
        color: "#ffffff"
    }, {
        visibility: "on"
    }]
}, {
    featureType: "administrative.province",
    elementType: "geometry",
    stylers: [{
        visibility: "off"
    }]
}, {
    featureType: "administrative.locality",
    elementType: "labels",
    stylers: [{
        visibility: "off"
    }]
}, {
    featureType: "administrative.neighborhood",
    elementType: "all",
    stylers: [{
        visibility: "on"
    }]
}, {
    featureType: "administrative.land_parcel",
    elementType: "all",
    stylers: [{
        visibility: "off"
    }]
}, {
    featureType: "landscape",
    elementType: "all",
    stylers: [{
        visibility: "on"
    }, {
        color: "#ffffff"
    }]
}, {
    featureType: "landscape.man_made",
    elementType: "all",
    stylers: [{
        color: "#ececec"
    }, {
        visibility: "off"
    }]
}, {
    featureType: "landscape.man_made",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "off"
    }]
}, {
    featureType: "landscape.man_made",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "off"
    }, {
        color: "#b80000"
    }]
}, {
    featureType: "landscape.natural",
    elementType: "all",
    stylers: [{
        visibility: "on"
    }]
}, {
    featureType: "landscape.natural.landcover",
    elementType: "all",
    stylers: [{
        visibility: "on"
    }]
}, {
    featureType: "landscape.natural.landcover",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#e4f0e6"
    }]
}, {
    featureType: "landscape.natural.terrain",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#e4f0e6"
    }]
}, {
    featureType: "poi",
    elementType: "all",
    stylers: [{
        visibility: "on"
    }, {
        color: "#e4f0e6"
    }]
}, {
    featureType: "poi",
    elementType: "labels",
    stylers: [{
        visibility: "off"
    }]
}, {
    featureType: "road",
    elementType: "geometry",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.highway",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.highway",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.highway.controlled_access",
    elementType: "geometry",
    stylers: [{
        visibility: "on"
    }]
}, {
    featureType: "road.highway.controlled_access",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.highway.controlled_access",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "on"
    }, {
        hue: "#ff0000"
    }]
}, {
    featureType: "road.arterial",
    elementType: "geometry",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.arterial",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.arterial",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.local",
    elementType: "geometry",
    stylers: [{
        visibility: "off"
    }, {
        hue: "#ff0000"
    }]
}, {
    featureType: "road.local",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "off"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.local",
    elementType: "geometry.stroke",
    stylers: [{
        color: "#6b6b6b"
    }, {
        visibility: "off"
    }]
}, {
    featureType: "water",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#cde1e9"
    }]
}];