var map, defaultPositionLat = -7.848016,
    defaultPositionLon = 112.017829,
    selectedUnitKecamatan = null,
    currentPage = 1,
    rowPerPage = 10,
    MONTH = {
        ID: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
    };

get_ntp_records(currentPage, rowPerPage, !0);

function initMap() {
    (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 12
    })).addListener("click", function (e) {
        placeMarkerAndPanTo(e.latLng)
    }), marker = new google.maps.Marker({
        position: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        map: map,
        title: "Pilih Lokasi"
    }), map.setCenter(new google.maps.LatLng(defaultPositionLat, defaultPositionLon))
}

function search() {
    null == $("#unit").val() || null == $("#unsur").val() ?
        ($("#confirmModal").find(".modal-title").text("Kesalahan Filter"),
            $("#confirmModal").find(".modal-body").text("Mohon untuk melengkapi filter"),
            $("#confirmModal").find("#confirmAcceptModal").hide(),
            $("#confirmModal").modal("show")) :
        "kecamatan" == $("#unit").val() && null == $("#id_kecamatan").val() ?
            ($("#confirmModal").find(".modal-title").text("Kesalahan Filter"),
                $("#confirmModal").find(".modal-body").text("Mohon untuk melengkapi filter Kecamatan"),
                $("#confirmModal").find("#confirmAcceptModal").hide(),
                $("#confirmModal").modal("show")) :
            "kabupaten" == $("#unit").val() && null == $("#id_skpd").val() ?
                ($("#confirmModal").find(".modal-title").text("Kesalahan Filter"),
                    $("#confirmModal").find(".modal-body").text("Mohon untuk melengkapi filter SKPD"),
                    $("#confirmModal").find("#confirmAcceptModal").hide(),
                    $("#confirmModal").modal("show")) :
                get_elemen()
}

function buat_survei() {
    $("#fields").empty();
    $("#form_survei").find("[name='id_survey']").remove();
    $("#form_survei")[0].reset();
    $("#content_table_survei").is(":visible") && ($("#content_table_survei").hide("fast"),
        $("#content_form_survei").show("fast", function () {
            google.maps.event.trigger(map, "resize"),
                map.setCenter(new google.maps.LatLng(defaultPositionLat, defaultPositionLon));
                marker.setPosition(new google.maps.LatLng(defaultPositionLat, defaultPositionLon));
                $("#form_survei").find("[name='lat']").val(defaultPositionLat);
                $("#form_survei").find("[name='lon']").val(defaultPositionLon);
        }))
}

function templateselect(e) {
    return e.id ? "<h5><b>" + e.text + "</b><br/><small style='text-transform:capitalize'>" + e.kategori + "</small></h5>" : "<h5><small>" + e.text + "</small></h5>"
}

$("[name='id_elemen']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_elemen",
        dataType: "json",
        data: function (e, t) {
            return {
                q: e.term,
                kategori: "unsur",
                page_limit: 10
            }
        },
        processResults: function (e, t) {
            return {
                results: e
            }
        }
    },
    templateSelection: templateselect,
    templateResult: templateselect,
    minimumInputLength: 0,
    templateSelection: function (e) {
        return e.text
    },
    escapeMarkup: function (e) {
        return e
    }
}).on("select2:select", function (e) {
    var data = e.params.data;
    $("#keterangan_elemen").val(data.text);
})

$("[name='id_unit']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_unit",
        dataType: "json",
        data: function (e, t) {
            return {
                q: e.term,
                kategori: "kecamatan",
                page_limit: 10
            }
        },
        processResults: function (e, t) {
            return {
                results: e
            }
        }
    },
    templateSelection: templateselect,
    templateResult: templateselect,
    minimumInputLength: 0,
    templateSelection: function (e) {
        return e.text
    },
    escapeMarkup: function (e) {
        return e
    }
}).on("select2:select", function (e) {
    selectedUnitKecamatan = e.params.data.id
});

$("[name='id_desa']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_unit",
        dataType: "json",
        data: function (e, t) {
            return {
                q: e.term,
                kategori: "desa",
                id_parent: selectedUnitKecamatan,
                page_limit: 10
            }
        },
        processResults: function (e, t) {
            return {
                results: e
            }
        }
    },
    templateSelection: templateselect,
    templateResult: templateselect,
    minimumInputLength: 0,
    templateSelection: function (e) {
        return e.text
    },
    escapeMarkup: function (e) {
        return e
    }
}).on("select2:select", function (e) {
})

$(".datepicker").datepicker({
    format: "yyyy-mm-dd"
});

$("#form_survei").submit(function (e) {
    e.preventDefault(),
        $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form"),
        $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan Survei?"),
        $("#confirmModal").find("#confirmAcceptModal").click(function () {
            console.log("I")
            $.ajax({
                url: base_url + "ntp/simpan_ntp",
                data: new FormData($("#form_survei")[0]),
                type: "post",
                cache: !1,
                contentType: !1,
                processData: !1,
                success: function (e) {
                    "duplicate" == e.toLowerCase().trim() ?
                        ($("#confirmModal").find(".modal-title").text("Duplicate ID"),
                            $("#confirmModal").find(".modal-body").text("Kesalahan penambahan data dikarena ada keterangan yang sama"),
                            $("#confirmModal").find("#confirmAcceptModal").hide(),
                            $("#confirmModal").find(".modal-content").hide().slideDown("fast")) :
                        ($("#confirmModal").modal("hide"),
                            get_ntp_records(currentPage, rowPerPage, !0),
                            cancel_form())
                }
            })
        }),
        $("#confirmModal").modal("show")
});

function get_ntp_records(e, t, a) {
    $.ajax({
        url: base_url + "d_secure/ntp/get_ntp_records",
        type: "post",
        data: $.param({
            search: $("#keyword_filter").val(),
            unit: $("#id_unit_filter").val(),
            elemen: $("#id_elemen_filter").val(),
            page: e,
            rowPerPage: t
        }),
        dataType: "json",
        success: function (r) {
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
            pagination(r.jumlah, t, 5, e, $(".pagination"), get_ntp_records, !1),
                $("#data_table").empty(),
                template = "<thead><th>No</th><th>Nama Responden</th><th>Sub sektor</th><th>Desa</th><th>Kecamatan</th><th>Tanggal</th><th></th></thead>",
                $.each(r.data, function (e, t) {
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
        error: function () {
        },
        beforeSend: function () {
        },
        complete: function () {
        }
    })
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

function templateDataTable(e, t) {
    return `<tr><td>${((currentPage - 1) * rowPerPage + t)}</td>
<td><b> ${e.nama_responden} </b></td>
<td class='data'> ${e.keterangan_elemen} </td>
<td class='data'> ${e.nama_desa}</td>
<td class='data' width='15%'>${e.nama_unit}</td>
<td> ${formatDate(e.created_date)} <br/> ${formatTime(e.created_date)} </td>
<td style='vertical-align:middle;'>
    <div class='dropdown pull-right'>
    <a class='dropdown-toggle btn btn-table' id='drop4' role='button' data-toggle='dropdown' href='#'>Action <b class='caret'></b></a>
    <ul id='menu1' class='dropdown-menu pull-right' role='menu' aria-labelledby='drop4'>
        <li><a href='#' onclick="edit_ntp(this,'${e.id_ntp_record}')" class='btn-edit-post'><i class='fa fa-pencil'></i> Edit</a></li>
        <li><a href='#' onclick="delete_survei(this,'${e.id_ntp_record}')" class='btn-edit-post'><i class='fa fa-trash'></i> Delete</a></li>
    </ul></div></td></tr>`
}

function edit_ntp(e, t) {
    findFromTable(currentDataPage, t);
    console.log(currentSurvei);
    open_edit_survei_form();
    $("#keterangan_elemen").val(currentSurvei.keterangan_elemen);

    if (currentSurvei.fields_value == null) {
        open_edit_survei_form();
        $("#fields").empty();
        $.ajax({
            url: base_url + "d/fields/get_fields_value",
            type: "post",
            data: $.param({
                id_survey: currentSurvei.id_survey
            }),
            dataType: "json",
            success: function (d) {
                currentSurvei.fields_value = d;
                fill_survey_field_value(d);
            }
        });
    } else {
        fill_survey_field_value(currentSurvei.field_values);
    }
}

function open_edit_survei_form() {
    $("#form_survei").find("[name='id_survey']").remove();
    $("#form_survei")[0].reset();
    $("#content_table_survei").is(":visible") && ($("#content_table_survei").hide("fast"),
    $("#content_form_survei").show("fast", function () {
        $("#form_survei").prepend("<input type='hidden' value='" + currentSurvei.id_survey + "' name='id_survey' />");
        $("#form_survei").find("#id_unit").append(templateoption(currentSurvei.id_unit, currentSurvei.nama_unit, !0));
        currentSurvei.id_desa && $("#form_survei").find("[name='id_desa']").append(templateoption(currentSurvei.id_desa, currentSurvei.nama_desa, !0));
        currentSurvei.id_skpd && $("#form_survei").find("[name='id_skpd']").append(templateoption(currentSurvei.id_skpd, currentSurvei.nama_skpd, !0));
        $("#form_survei").find("#id_elemen").append(templateoption(currentSurvei.id_elemen, currentSurvei.keterangan_elemen, !0));
        $("#form_survei").find("[name='tgl_survey']").val(currentSurvei.tgl_survey);
        $("#form_survei").find("[name='nama_responden']").val(currentSurvei.nama_responden);
        $("#form_survei").find("[name='jumlah_anggota_kk']").val(currentSurvei.jumlah_anggota_kk);
        $("#form_survei").find("[name='usia']").val(currentSurvei.usia);
        $("#form_survei").find("[name='pendidikan']").val(currentSurvei.pendidikan);
        $("#form_survei").find("[name='telp']").val(currentSurvei.telp);
        $("#form_survei").find("[name='lat']").val(currentSurvei.lat);
        $("#form_survei").find("[name='lon']").val(currentSurvei.lon);
        google.maps.event.trigger(map, "resize");
        marker.setPosition(new google.maps.LatLng(currentSurvei.lat, currentSurvei.lon));
        map.setCenter(new google.maps.LatLng(currentSurvei.lat, currentSurvei.lon));
    })
)
}

function findFromTable(e, t) {
    for (var a = 0; a < e.length; a++)
        if (e[a].id_ntp_record == t)
            return void (currentSurvei = e[a])
}

function cancel_form() {
    console.log("cancel form");
    $("#form_survei").find("[name='id_survey']").remove(),
        $("#form_survei")[0].reset(),
    $("#content_form_survei").is(":visible") &&
    ($("#content_table_survei").show("fast"),
        $("#content_form_survei").hide("fast"))
}

function templateoption(e, t, a) {
    return "<option value=" + e + " " + (a ? "selected='selected'" : "") + ">" + t + "</option>"
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