var map;
function initMap() {
    (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 12
    })).addListener("click", function(e) {
        placeMarkerAndPanTo(e.latLng)
    }), marker = new google.maps.Marker({
        position: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        draggable: true,
        map: map,
        title: "Pilih Lokasi"
    }), map.setCenter(new google.maps.LatLng(defaultPositionLat, defaultPositionLon));
    google.maps.event.addListener(marker, 'dragend', function(marker) {
        placeMarkerAndPanTo(marker.latLng)
      });
}

$("#my_location").click(function (){
    if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){
                infoWindow = new google.maps.InfoWindow({map: map});
                var pos = {lat: position.coords.latitude, lng: position.coords.longitude};
                // infoWindow.setPosition(pos);
                // infoWindow.setContent("Found your location <br />Lat : "+position.coords.latitude+" </br>Lang :"+ position.coords.longitude);
                map.setCenter(new google.maps.LatLng(pos));
                map.panTo(pos);
                placeMarkerAndPanTo(pos);
            });
        }else{
            console.log("Browser doesn't support geolocation!");
    }
});

function placeMarkerAndPanTo(e) {
    marker.setPosition(e), map.panTo(e), $("#form_survei").find("[name='lat']").val(e.lat), $("#form_survei").find("[name='lon']").val(e.lng)
}

function search() {
    get_survei(currentPage, rowPerPage, !0)
}

function exkspor() {
    $("#ekspor_form [name='keyword']").val($("#keyword_filter").val()), 
    $("#id_unit_filter").val($("#filter_unit").val()), 
    $("#id_elemen_filter").val($("#filter_elemen").val()), 
    $("#ekspor_form").submit()
}

function get_survei(e, t, a) {
    $.ajax({
        url: base_url + "d_secure/survei/get_survei",
        type: "post",
        data: $.param({
            search: $("#keyword_filter").val(),
            unit: $("#id_unit_filter").val(),
            elemen: $("#id_elemen_filter").val(),
            page: e,
            rowPerPage: t
        }),
        dataType: "json",
        success: function(r) {
            if (currentDataPage = r.data, currentPage = e, 0 == r.jumlah) 
                return $("#tabel_nothing").show(), 
                $("#data_table").hide(), 
                $("#page-content").hide(), 
                void $("#info-content").hide();

            $("#tabel_nothing").hide(), 
            $("#data_table").show(), 
            $("#page-content").show(), 
            $("#info-content").show(), 
            1 == a && 
                pagination(r.jumlah, t, 5, e, $(".pagination"), get_survei, !1), 
                $("#data_table").empty(), 
                template = "<thead><th>No</th><th>Unit</th><th>Narasumber</th><th>Alamat</th><th>Catatan</th><th>Tanggal</th><th></th></thead>", 
                $.each(r.data, function(e, t) {
                    template += templateDataTable(t, e + 1, 1, null)
                }), 
                $(".jml_info").text("Terdapat " + r.jumlah + " Survei"), 
                $(".page_info").text("Halaman " + e + " dari " + Math.ceil(r.jumlah / t)), 
                $("#data_table").append(template), 
                $("[data-toggle='tooltip']").tooltip(), 
                $("[data-toggle='popover']").popover({
                    trigger: "hover"
                })
        },
        error: function() {},
        beforeSend: function() {},
        complete: function() {}
    })
}

function buat_survei() {
    $("#fields").empty();
    $("#form_survei").find("[name='id_survey']").remove(), 
    $("#form_survei")[0].reset(), 
    $("#content_table_survei").is(":visible") && ($("#content_table_survei").hide("fast"), 
    $("#content_form_survei").show("fast", function() {
        google.maps.event.trigger(map, "resize"), 
        map.setCenter(new google.maps.LatLng(defaultPositionLat, defaultPositionLon)), 
        marker.setPosition(new google.maps.LatLng(defaultPositionLat, defaultPositionLon)), 
        $("#form_survei").find("[name='lat']").val(defaultPositionLat), 
        $("#form_survei").find("[name='lon']").val(defaultPositionLon)
    }))
}

function edit_survei(e, t) {
    findFromTable(currentDataPage, t);
    open_edit_survei_form();
    $("#keterangan_elemen").val(currentSurvei.keterangan_elemen);

    if(currentSurvei.fields_value==null){
        open_edit_survei_form();
        $("#fields").empty();
        $.ajax({
            url: base_url + "d/fields/get_fields_value",
            type: "post",
            data: $.param({
                id_survey: currentSurvei.id_survey
            }),
            dataType: "json",
            success: function(d) {
                currentSurvei.fields_value = d;  
                fill_survey_field_value(d);              
            }
        });
    }else{
        fill_survey_field_value(currentSurvei.field_values);
    }; 
}

function open_edit_survei_form(){
    $("#form_survei").find("[name='id_survey']").remove(), 
    $("#form_survei")[0].reset(), 
    $("#content_table_survei").is(":visible") && ($("#content_table_survei").hide("fast"), 
    $("#content_form_survei").show("fast", function() {
        $("#form_survei").prepend("<input type='hidden' value='" + currentSurvei.id_survey + "' name='id_survey' />"), 
        $("#form_survei").find("#id_unit").append(templateoption(currentSurvei.id_unit, currentSurvei.nama_unit, !0)), 
        currentSurvei.id_desa && $("#form_survei").find("[name='id_desa']").append(templateoption(currentSurvei.id_desa, currentSurvei.nama_desa, !0)), 
        currentSurvei.id_skpd && $("#form_survei").find("[name='id_skpd']").append(templateoption(currentSurvei.id_skpd, currentSurvei.nama_skpd, !0)), 
        $("#form_survei").find("#id_elemen").append(templateoption(currentSurvei.id_elemen, currentSurvei.keterangan_elemen, !0)), 
        $("#form_survei").find("[name='tgl_survey']").val(currentSurvei.tgl_survey), 
        $("#form_survei").find("[name='nama_surveyor']").val(currentSurvei.nama_surveyor), 
        $("#form_survei").find("[name='nama_ppl']").val(currentSurvei.nama_ppl), 
        $("#form_survei").find("[name='nama_narasumber']").val(currentSurvei.nama_narasumber), 
        $("#form_survei").find("[name='telp']").val(currentSurvei.telp), 
        $("#form_survei").find("[name='nama_kelompok_tani']").val(currentSurvei.nama_kelompok_tani), 
        $("#form_survei").find("[name='nama_gapoktan']").val(currentSurvei.nama_gapoktan), 
        $("#form_survei").find("[name='alamat_kelompok_tani']").val(currentSurvei.alamat_kelompok_tani), $("#form_survei").find("[name='is_valid']").prop("checked", 1 == currentSurvei.is_valid), $("#form_survei").find("[name='is_potensial']").prop("checked", 1 == currentSurvei.is_potensial), $("#form_survei").find("[name='catatan']").val(currentSurvei.catatan), $("#form_survei").find("[name='lat']").val(currentSurvei.lat), $("#form_survei").find("[name='lon']").val(currentSurvei.lon), google.maps.event.trigger(map, "resize"), marker.setPosition(new google.maps.LatLng(currentSurvei.lat, currentSurvei.lon)), map.setCenter(new google.maps.LatLng(currentSurvei.lat, currentSurvei.lon))
    }))
}

function delete_survei(e, t) {
    findFromTable(currentDataPage, t), $("#confirmModal").find(".modal-title").text("Konfirmasi Hapus Survei"), $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan Menghapus Survei ini ?"), $("#confirmModal").find("#confirmAcceptModal").click(function() {
        $.ajax({
            url: base_url + "survei/hapus_survei",
            data: $.param({
                id_survey: t
            }),
            type: "post",
            success: function(e) {
                get_survei(currentPage, rowPerPage, !0), $("#confirmModal").modal("hide")
            }
        })
    }), $("#confirmModal").modal("show")
}

function findFromTable(e, t) {
    for (var a = 0; a < e.length; a++)
        if (e[a].id_survey == t) 
            return void(currentSurvei = e[a])
}

function cancel_form() {
    $("#form_survei").find("[name='id_survey']").remove(), 
    $("#form_survei")[0].reset(), 
    $("#content_form_survei").is(":visible") && 
        ($("#content_table_survei").show("fast"), 
        $("#content_form_survei").hide("fast"))
}

function templateselect(e) {
    return e.id ? "<h5><b>" + e.text + "</b><br/><small style='text-transform:capitalize'>" + e.kategori + "</small></h5>" : "<h5><small>" + e.text + "</small></h5>"
}

function templateDataTable(e, t) {
    return "<tr><td>" + ((currentPage - 1) * rowPerPage + t) + "</td><td><b>" + e.keterangan_elemen + "</b><br/><span class='text-info'>Kecamatan " + e.nama_unit + "</span><br>SKPD : " + e.nama_skpd + "</td><td class='data'>Surveyor : " +(e.nama_surveyor?e.nama_surveyor:"-") + "<br/>Tgl Survei : " + formatDate(e.tgl_survey) + "<br/>NaraSumber : " + (e.nama_narasumber?e.nama_narasumber:"-") + "</td><td class='data'>Nama Kelompok : " + e.nama_kelompok_tani + "</br>Alamat : " + e.alamat_kelompok_tani + " <a href='http://maps.google.com/maps?&z=11&q=" + e.lat + "+" + e.lon + "&ll=" + e.lat + "+" + e.lon + "' target='_blank'><i class='fa fa-map-marker'></i></a></td><td class='data' width='15%'>" + (e.catatan ? e.catatan : "-") + "</td><td>" + formatDate(e.created_date) + "<br/>" + formatTime(e.created_date) + "</td><td style='vertical-align:middle;'><div class='dropdown pull-right'><a class='dropdown-toggle btn btn-table' id='drop4' role='button' data-toggle='dropdown' href='#'>Action <b class='caret'></b></a><ul id='menu1' class='dropdown-menu pull-right' role='menu' aria-labelledby='drop4'><li><a href='" + base_url + "survei/detail/" + e.uri_code + "' class='btn-edit-post' ><i class='fa fa-table'></i> Detail Survei</a></li><li class='divider'></li><li><a href='#' onclick=\"edit_survei(this,'" + e.id_survey + "')\"  class='btn-edit-post' ><i class='fa fa-pencil'></i> Edit</a></li><li><a href='#' onclick=\"delete_survei(this,'" + e.id_survey + "')\"  class='btn-edit-post' ><i class='fa fa-trash'></i> Delete</a></li></ul></div></td></tr>"
}

function templateoption(e, t, a) {
    return "<option value=" + e + " " + (a ? "selected='selected'" : "") + ">" + t + "</option>"
}

function intToChar(e) {
    return (e >= 26 ? idOf((e / 26 >> 0) - 1) : "") + "abcdefghijklmnopqrstuvwxyz" [e % 26 >> 0]
}

function formatDate(e) {
    if (!e || null == e) return e;
    var t = e.split(/[- :]/);
    return t[2] + " " + MONTH.ID[t[1] - 1] + " " + t[0]
}

function formatTime(e) {
    if (!e || null == e) return e;
    var t = e.split(/[- :]/);
    return t[3] + ":" + t[4] + ":" + t[5]
}

var MONTH = {
        ID: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
    },
    currentPage = 1,
    rowPerPage = 10,
    currentDataPage = [];
    get_survei(currentPage, rowPerPage, !0);

var map, defaultPositionLat = -7.848016,
    defaultPositionLon = 112.017829,
    marker = null,
    delay = function() {
        var e = 0;
        return function(t, a) {
            clearTimeout(e), e = setTimeout(t, a)
        }
    }();

$("[name='lat'],[name='lon']").keyup(function() {
    delay(function() {
        marker.setPosition(new google.maps.LatLng($("[name='lat']").val(), $("[name='lon']").val())), 
        map.panTo(new google.maps.LatLng($("[name='lat']").val(), 
        $("[name='lon']").val()))
    }, 800)
}), $("#confirmModal").on("hidden.bs.modal", function(e) {
    $("#confirmModal").find("#confirmAcceptModal").unbind("click"), 
    $("#confirmModal").find("#confirmAcceptModal").show()
});

var selectedUnitKecamatan = null;
$("[name='id_unit']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_unit",
        dataType: "json",
        data: function(e, t) {
            return {
                q: e.term,
                kategori: "kecamatan",
                page_limit: 10
            }
        },
        processResults: function(e, t) {
            return {
                results: e
            }
        }
    },
    templateSelection: templateselect,
    templateResult: templateselect,
    minimumInputLength: 0,
    templateSelection: function(e) {
        return e.text
    },
    escapeMarkup: function(e) {
        return e
    }
}).on("select2:select", function(e) {
    selectedUnitKecamatan = e.params.data.id
}), $("[name='id_desa']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_unit",
        dataType: "json",
        data: function(e, t) {
            return {
                q: e.term,
                kategori: "desa",
                id_parent: selectedUnitKecamatan,
                page_limit: 10
            }
        },
        processResults: function(e, t) {
            return {
                results: e
            }
        }
    },
    templateSelection: templateselect,
    templateResult: templateselect,
    minimumInputLength: 0,
    templateSelection: function(e) {
        return e.text
    },
    escapeMarkup: function(e) {
        return e
    }
}).on("select2:select", function(e) {}), 
$("[name='id_elemen']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_elemen",
        dataType: "json",
        data: function(e, t) {
            return {
                q: e.term,
                kategori: "unsur",
                page_limit: 10
            }
        },
        processResults: function(e, t) {
            return {
                results: e
            }
        }
    },
    templateSelection: templateselect,
    templateResult: templateselect,
    minimumInputLength: 0,
    templateSelection: function(e) {
        return e.text
    },
    escapeMarkup: function(e) {
        return e
    }
}).on("select2:select", function(e) {
    var data = e.params.data;
    $("#keterangan_elemen").val(data.text);
    if(this.id!="id_elemen_filter"){
        $.ajax({
            url: base_url + "d/fields/get_fields/",
            type: 'POST',
            data: $.param({
                id_elemen_parent: data.id
            }),
            dataType: 'json',
            success: function(data) {
                fill_survey_field_value(data);
            }
        });
    }
}), $("[name='id_skpd']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_unit",
        dataType: "json",
        data: function(e, t) {
            return {
                q: e.term,
                kategori: "skpd",
                page_limit: 10
            }
        },
        processResults: function(e, t) {
            return {
                results: e
            }
        }
    },
    templateSelection: templateselect,
    templateResult: templateselect,
    minimumInputLength: 0,
    templateSelection: function(e) {
        return e.text
    },
    escapeMarkup: function(e) {
        return e
    }
}).on("select2:select", function(e) {}), $(".datepicker").datepicker({
    format: "yyyy-mm-dd"
}),
$("#form_survei").submit(function(e) {
    e.preventDefault(), 
    $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form"), 
    $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan Survei?"), 
    $("#confirmModal").find("#confirmAcceptModal").click(function() {
        $.ajax({
            url: base_url + "survei/simpan_survei",
            data: new FormData($("#form_survei")[0]),
            type: "post",
            cache: !1,
            contentType: !1,
            processData: !1,
            success: function(e) {
                "duplicate" == e.toLowerCase().trim() ? 
                ($("#confirmModal").find(".modal-title").text("Duplicate ID"), $("#confirmModal").find(".modal-body").text("Kesalahan penambahan data dikarena ada keterangan yang sama"), $("#confirmModal").find("#confirmAcceptModal").hide(), $("#confirmModal").find(".modal-content").hide().slideDown("fast")) : 
                ($("#confirmModal").modal("hide"), get_survei(currentPage, rowPerPage, !0), cancel_form())
            }
        })
    }), 
    $("#confirmModal").modal("show")
});
var currentSurvei = null;
function template_survey_field(t){
    var val = '';
    if (t.value){
        val = 'value="'+t.value+'"';
    }
    // var str = '<div class="form-group"><label>'+t.nama_field+'</label><input type="text" class="form-control" name="'+t.key_field+'" id="'+t.key_field+'" required="" '+val+'/></div>'
    var str = '<div class="col-sm-6"><label>'+t.nama_field+'</label><input type="text" class="form-control" name="'+t.key_field+'" id="'+t.key_field+'" required="" '+val+'/></div>'
    return str;
}

function fill_survey_field_value(field_values){
    $("#fields").empty();
    var form_template="";
    $.each(field_values, function(e, t) {    
        form_template += template_survey_field(t);                    
    }),
    $("#fields").append(form_template);
    console.log(form_template);
}