var defaultPositionLat = -7.848016,
    defaultPositionLon = 112.066689,
    map = null,
    infoWindow = null,
    currentUnit = null;

function initMap() {
    currentUnit = null;
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
        }));

    // map.data.loadGeoJson(base_url + "masterx/master/get_kecamatan_koordinat", {
    //     idPropertyName: "id_unit"
    // }), 
    map.data.addListener("mouseover", function (e) {
        e.feature.setProperty("state", "hover"),
            infoWindow.setOptions({
                content: "<h4>" + e.feature.getProperty("nama") + "</h4>",
                position: e.latLng,
                maxWidth: 350
            }),
            infoWindow.open(map)
    }), map.data.addListener("mouseout", function (e) {
        e.feature.setProperty("state", "normal")
    }), map.data.setStyle(function (a) {
        return {
            fillColor: "gray",
            strokeWeight: 0.5
        }
    }), map.data.addListener("click", function (e) {
        currentIdUnit = e.feature.getProperty("id_unit");
        var kategori = e.feature.getProperty("kategori");
        var nama_unit = e.feature.getProperty("nama");
        var kecamatan_color = e.feature.getProperty('region_color');

        if (kategori == 'kecamatan') {
            var $newOption = $("<option selected='selected'></option>").val(currentIdUnit).text(nama_unit);
            $("#id_kecamatan").append($newOption).trigger('change');
            getKemiskinanData(kecamatan_color);
        }
    });
}

function initSelect2(e, a, t, n) {
    e.select2({
        ajax: {
            url: a,
            dataType: "json",
            data: t,
            processResults: function (e, a) {
                return {
                    results: e
                }
            }
        },
        templateResult: templateselect,
        minimumInputLength: 0,
        templateSelection: function (e) {
            return e.text
        },
        escapeMarkup: function (e) {
            return e
        }
    }).on("select2:select", function (e) {
        null != n && n(e)
    });
}

function templateselect(e) {
    return e.kategori ?
        "<h5><b>" + e.text + "<b><br/><small style='text-transform:capitalize'>" + e.kategori + "</small></h5>" :
        "<h5><small>" + e.text + "</small></h5>"
}

function reloadGeoJsonAndFit(request_url) {
    map.data.forEach(function (feature) {
        map.data.remove(feature);
    });
    map.data.loadGeoJson(request_url, {
        idPropertyName: "id_unit"
    }, function () {
        // create empty bounds object
        var bounds = new google.maps.LatLngBounds();

        // loop through features
        map.data.forEach(function (feature) {
            var geo = feature.getGeometry();
            geo.forEachLatLng(function (LatLng) {
                bounds.extend(LatLng);
            });
        });

        // fit data to bounds
        map.fitBounds(bounds);
        map.setZoom(map.getZoom() + 1);

        // //labels
        // var myLatlng = bounds.getCenter();
        // console.log(myLatlng);

        // var mapLabel2 = new MapLabel({
        //     text: 'Mojowarno',
        //     position: myLatlng,
        //     map: map,
        //     fontSize: 20,
        //     align: 'left'
        // });
        // mapLabel2.set('position', myLatlng);
    });
}

function exportKemiskinanData() {
    $("#ekspor_form [name='periode']").val($("#periode").val());
    $("#ekspor_form [name='id_parameter']").val($("#id_parameter").val());
    $("#ekspor_form [name='id_sub_parameter']").val($("#id_sub_parameter").val());
    $("#ekspor_form [name='id_kecamatan']").val($("#id_kecamatan").val());    
    $("#ekspor_form").submit()
}

function getKemiskinanData(kecamatan_color = null) {
    $("#map_legends").html('');

    var id_kecamatan = $("#id_kecamatan").val();

    var d1 = $.Deferred();
    if (kecamatan_color == null) {
        if (id_kecamatan != "id_all") {
            // request tdk dijalankan ketika data yg diminta adalah semua kecamatan
            $.ajax({
                url: base_url + 'd/summary/get_kecamatan_region_color',
                method: 'POST',
                data: {
                    periode: $("#periode").val(),
                    id_parameter: $("#id_sub_parameter").val(),
                    id_kecamatan: id_kecamatan
                },
                dataType: 'json',
                success: function (d) {
                    d1.resolve(d ? d.region_color : null);
                }
            });
        } else {
            d1.resolve(null);
        }
    } else {
        d1.resolve(kecamatan_color);
    }

    function get_summary(r_color) {
        if (id_kecamatan == "id_all") {
            reloadGeoJsonAndFit(base_url + "page/get_kecamatan_koordinat");
        } else if (id_kecamatan != null) {
            reloadGeoJsonAndFit(base_url + "page/get_desa_koordinat/" + id_kecamatan);
        }

        $.ajax({
            url: base_url + 'd/summary/get_summary_survei',
            method: 'POST',
            data: {
                periode: $("#periode").val(),
                id_parameter: $("#id_sub_parameter").val(),
                id_kecamatan: id_kecamatan,
                kecamatan_color: r_color
            },
            dataType: 'json',
            success: function (r) {
                $("#tabel_nothing").hide(),
                    $("#data_table").show(),
                    $("#page-content").show(),
                    $("#data_table").empty();

                if (r.legends) r.legends.forEach(e => {
                    $("#map_legends").append(`<div class="col-md-4">
                    <i class="fa fa-square" style="color: ${e.color};"></i> ${e.label}
                </div>`);
                });

                var unit_list = r.data_row;
                map.data.setStyle(function (feature) {
                    var id_unit = feature.getProperty('id_unit');
                    var unit = unit_list.find(x => x.id_unit === id_unit);
                    var color = unit ? unit.region_color : "gray";

                    feature.setProperty('region_color', color);

                    return {
                        fillColor: color,
                        strokeWeight: 0.5
                    }
                })

                $("#data_table").empty();
                if (unit_list.length > 0) {
                    template = "<thead><th>Unit</th><th>Desil1</th><th>Desil2</th><th>Desil3</th><th>Desil4</th><th>Desil1~4</th></thead>",
                        $.each(r.data_row, function (e, t) {
                            template += templateDataTable(t, e + 1, 1, null)
                        }),
                        $("#data_table").append(template);
                }

                $("[data-toggle='tooltip']").tooltip(),
                    $("[data-toggle='popover']").popover({
                        trigger: "hover"
                    });
                // com_github_culmat_jsTreeTable.treeTable($("#data_table"));
            }
        });
    }

    $.when(d1).then(get_summary);
}

function templateDataTable(e, t) {
    return "<tr><td>" + e.nama_unit + "</td><td>" + e.value[0] + "</td><td>" + e.value[1] + "</td><td>" + e.value[2] + "</td><td>" + e.value[3] + "</td><td>" + e.total_value + "</td></tr>"
}

var selected_id_parameter = '1.3';

$(document).ready(function () {
    var idAll_option = {
        id: "id_all",
        kategori: "kecamatan",
        text: "Semua Kecamatan"
    };

    initSelect2($("#id_kecamatan"), base_url + "masterx/master/get_select2_unit", function (e, a) {
        return {
            q: e.term,
            kategori: "kecamatan",
            page_limit: 10,
            additional_options: new Array(idAll_option)
        }
    }, null);

    initSelect2($("#id_parameter"), base_url + "d/rekap/get_rekap_params", function (e, a) {
        return {
            q: e.term,
            page_limit: 10,
            use_select2_format: true,
            show_only_parent: true
        }
    }, null);

    initSelect2($("#id_sub_parameter"), base_url + "d/rekap/get_rekap_params", function (e, a) {
        return {
            q: e.term,
            page_limit: 10,
            use_select2_format: true,
            id_parent: $("#id_parameter").val()
        }
    }, null);

    var $newOption = $("<option selected='selected'></option>").val(idAll_option.id).text(idAll_option.text);
    $("#id_kecamatan").append($newOption).trigger('change');
    getKemiskinanData();
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