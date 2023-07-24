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
                            e.infoWindow.setContent("<h4>" + d.info.header + "</h4><p>" + d.info.content + "<div style='text-align:right'></div></p> <a href='" + base_url + "survei/detail/" + d.uri_code + "' class='btn-edit-post' ><i class='fa fa-table'></i> Detail Survei</a>");                            
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

function initMap() {
    (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 11
    })).data.loadGeoJson(base_url + "masterx/master/get_kecamatan_koordinat", {
        idPropertyName: "id_unit"
    }), map.data.addListener("mouseover", function (a) {
        a.feature.setProperty("state", "hover"), $(".map-tips").html("<h6 style='margin:0px; padding: 10px 20px; font-weight:bold;'>Kecamatan " + a.feature.getProperty("nama") + "</h6>")
    }), map.data.addListener("mouseout", function (a) {
        a.feature.setProperty("state", "normal"), null == currentUnit ? $(".map-tips").html("") : $(".map-tips").html("<h6 style='margin:0px; padding: 10px 20px; font-weight:bold;'>Kecamatan " + currentUnit + "</h6>")
    }), map.data.addListener("click", function (a) {
        currentUnit = a.feature.getProperty("nama"), currentIdUnit = a.feature.getProperty("id_unit")
        //, get_summary() //--popup di menu data survey->Geospasial--//
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

function get_summary() {
    $("#dataModal").modal("show"), $("#dataModal .modal-title").html("Kecamatan " + currentUnit), $.ajax({
        url: base_url + "survei/get_rekap",
        type: "post",
        data: $.param({
            kecamatan: currentIdUnit
        }),
        success: function (a) {
            var t = JSON.parse(a);
            currentData = t;
            var e = "<thead><th>No</th><th>Elemen</th>";
            for (var n in t.pertanian[0].tahun) e += "<th>" + n + "</th>";
            e += "</thead>";
            for (tab in t) {
                var r = "<tbody>";
                $.each(t[tab], function (a, t) {
                    r += templateDataTable(t, a + 1, 1, null, tab)
                }), r += "</tbody>", $("#tab-" + tab + " table").empty(), $("#tab-" + tab + " table").append(e + r), com_github_culmat_jsTreeTable.treeTable($("#tab-" + tab + " table"))
            }
        }
    })
}

function show_chart(a, t, e) {
    currentElemen = null, findElemenFromTree(currentData[t], e), isShowChart = !0, $("#dataModal").modal("hide"), $("#chartModal").find(".modal-title").html("Grafik '" + currentElemen.elemen + "'")
}

function findElemenFromTree(a, t) {
    for (var e = 0; e < a.length; e++) a[e].id == t && (currentElemen = a[e]), "child" in a[e] && a[e].child && findElemenFromTree(a[e].child, t)
}

function templateDataTable(a, t, e, n, r) {
    o = "";
    if (n) o = n + "_" + (t - 1);
    else var o = "id_" + (t - 1);
    var i = "<tr data-tt-id='" + o + "' data-tt-level='" + e + "' " + (n ? "data-tt-parent-id='" + n + "'" : "") + "><td>" + a.id + "</td><td>" + a.elemen + "</td>";
    for (var l in a.tahun) i += "<td class='data' style='text-align:right;'>", null != a.tahun[l] ? (i += kalibrasi(a.tahun[l], 2), null != a.satuan && (i += " " + a.satuan)) : a.child ? i += "" : (i += "0", null != a.satuan && (i += " " + a.satuan)), i += "</td>";
    if (i += "<td class='data'><a class='btn btn-xs btn-warning' onclick=\"show_chart(this,'" + r + "','" + a.id + "')\"><i class='fa fa-bar-chart'></i></a></td>", i += "</tr>", a.child)
        for (l = 0; l < a.child.length; l++) i += templateDataTable(a.child[l], l + 1, e + 1, o, r);
    return i
}

function kalibrasi(a, t) {
    if (isNaN(a) || -1 == a.toString().indexOf(".")) return a;
    var t = t || 0,
        e = Math.pow(10, t),
        n = Math.abs(Math.round(a * e)),
        r = (a < 0 ? "-" : "") + String(Math.floor(n / e));
    if (t > 0) {
        var o = String(n % e);
        r += "." + new Array(Math.max(t - o.length, 0) + 1).join("0") + o
    }
    return r
}

function getYear(a) {
    if (!a || null == a) return a;
    return a.split(/[- :]/)[2]
}
var map, currentData = null,
    currentUnit = null,
    currentIdUnit = null,
    defaultPositionLat = -7.848016,
    defaultPositionLon = 112.017829,
    isShowChart = !1,
    currentElemen = null,
    markers = [],
    markerCluster = null,
    markersInfoWindowOpen = [];
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
};
var chart = new Chart(document.getElementById("chart").getContext("2d"), chartConfig);
$("#chartModal").on("shown.bs.modal", function (a) {
    var t = [],
        e = [];
    for (tahun in currentElemen.tahun) t.push(tahun), e.push(null != currentElemen.tahun[tahun] ? kalibrasi(currentElemen.tahun[tahun], 2) : 0);
    chartConfig.data.labels = t, chartConfig.data.datasets[0].label = currentElemen.elemen, chartConfig.data.datasets[0].data = e, chart.update()
}), $("#chartModal").on("hidden.bs.modal", function (a) {
    isShowChart = !1, $("#dataModal").modal("show")
}), $("#dataModal").on("hidden.bs.modal", function (a) {
    isShowChart && $("#chartModal").modal("show")
}), $("#periode").change(function (a) {
    "" == $("#unsur").val() || get_mark()
}), $("#unsur").change(function (a) {
    "" == $(this).val() || get_mark()
});