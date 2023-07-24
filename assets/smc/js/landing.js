function initMap() {
    infoWindow = new google.maps.InfoWindow, (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 11,
        styles: mapStyle
    })).addListener("mousemove", function (e) {
        infoWindow.close()
    }), map.data.loadGeoJson(base_url + "page/get_kecamatan_koordinat", {
        idPropertyName: "id_unit"
    }), map.data.addListener("mouseover", function (e) {
        e.feature.setProperty("state", "hover"), infoWindow.setOptions({
            content: "<h4>" + e.feature.getProperty("nama") + "</h4>",
            position: e.latLng,
            maxWidth: 350
        }), infoWindow.open(map)
    }), map.data.addListener("mouseout", function (e) {
        e.feature.setProperty("state", "normal")
    }), map.data.addListener("click", function (e) {
        currentUnit = e.feature.getProperty("nama"), currentIdUnit = e.feature.getProperty("id_unit"), $("#modal").iziModal("setTitle", "Kecamatan " + e.feature.getProperty("nama")), $("#modal").iziModal("setSubtitle", "Kabuaten Kediri"), "block" == $("#modal").css("display") ? ($("#modal .iziModal-content").html('<div style="height: 200px;"></div>'), modalContent.startLoading(), $.get(base_url + "page/get_data_landing?unit=" + currentIdUnit, function (e) {
            var t = JSON.parse(e);
            $("#modal .iziModal-content").html(templateDataProfil(t)), com_github_culmat_jsTreeTable.treeTable($("#modal .iziModal-content table")), modalContent.stopLoading()
        })) : $("#modal").iziModal("open")
    }), map.data.setStyle(function (e) {
        var t = .5,
            a = "#80CBC4",
            l = "#00796B",
            i = 1;
        return "hover" === e.getProperty("state") && (t = i = 2, a = "#0277BD", l = "#01579B"), {
            strokeWeight: t,
            strokeColor: l,
            zIndex: i,
            fillColor: a,
            fillOpacity: .2,
            visible: !0
        }
    })
}

function templateDataProfil(e) {
    heading = "<thead><th>No</th><th>Keterangan</th>";
    for (var t in e.data[0].tahun) heading += "<th>" + t + "</th>";
    return heading += "<th></th>", heading += "</thead>", template = "", $.each(e.data, function (e, t) {
        template += templateDataTable(t, e + 1, 1, null)
    }), "<table class='table'>" + heading + template + "</table>"
}

function templateDataTable(e, t, a, l) {
    i = "";
    if (l) i = l + "_" + (t - 1);
    else var i = "id_" + (t - 1);
    var o = "<tr data-tt-id='" + i + "' data-tt-level='" + a + "' " + (l ? "data-tt-parent-id='" + l + "'" : "") + "><td>" + e.singkat + "</td><td>" + e.keterangan + "</td>";
    for (var r in e.tahun) o += "<td class='data'>", null != e.tahun[r] ? o += "<span>" + e.tahun[r].jumlah + " " + e.tahun[r].satuan + "</span>" : o += "<span> - </span>", o += "</td>";
    if (o += "</tr>", e.child)
        for (r = 0; r < e.child.length; r++) o += templateDataTable(e.child[r], r + 1, a + 1, i);
    return o
}
var map, defaultPositionLat = -7.848016,
    defaultPositionLon = 112.276669,
    currentUnit = null,
    currentIdUnit = null,
    infoWindow = null,
    hoverUnit = null,
    modalContent = null;
$("#modal").iziModal({
    radius: 1,
    headerColor: "#00E5FF",
    icon: "fa fa-home",
    background: "#fff",
    theme: "light",
    transitionIn: "bounceInDown",
    transitionOut: "bounceOutUp",
    fullscreen: !0,
    overlayColor: "rgba(255, 255, 255, 0.6)",
    timeoutProgressbarColor: "rgba(255,255,255,0.5)",
    overlay: !1,
    onOpening: function (e) {
        modalContent = e, e.startLoading(), $.get(base_url + "page/get_data_landing?unit=" + currentIdUnit, function (t) {
            var a = JSON.parse(t);
            $("#modal .iziModal-content").html(templateDataProfil(a)), com_github_culmat_jsTreeTable.treeTable($("#modal .iziModal-content table")), e.stopLoading()
        })
    },
    onClosed: function (e) {
        $("#modal .iziModal-content").html('<div style="height: 200px;"></div>')
    }
});
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
        visibility: "on"
    }]
}, {
    featureType: "administrative.locality",
    elementType: "geometry",
    stylers: [{
        visibility: "on"
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
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.highway",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.highway",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "on"
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
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.arterial",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.arterial",
    elementType: "geometry.stroke",
    stylers: [{
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.local",
    elementType: "geometry",
    stylers: [{
        visibility: "on"
    }, {
        hue: "#ff0000"
    }]
}, {
    featureType: "road.local",
    elementType: "geometry.fill",
    stylers: [{
        visibility: "on"
    }, {
        color: "#6b6b6b"
    }]
}, {
    featureType: "road.local",
    elementType: "geometry.stroke",
    stylers: [{
        color: "#6b6b6b"
    }, {
        visibility: "on"
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