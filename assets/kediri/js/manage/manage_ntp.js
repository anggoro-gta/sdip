var map, defaultPositionLat = -7.848016,
    defaultPositionLon = 112.017829,
    selectedUnitKecamatan = null,
    currentPage = 1,
    currentNtpRecord = null,
    rowPerPage = 10,
    MONTH = {
        ID: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
    };

var komoditasPemasukanChanges = {}, komoditasPengeluaranChanges = {};

initPage();

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

function initPage() {
    get_ntp_records(currentPage, rowPerPage, !0);
    buildKomoditasTable("#index_komoditas_pemasukan", index_komoditas.pemasukan);
    buildKomoditasTable("#index_komoditas_pengeluaran", index_komoditas.pengeluaran);
    recordKomoditasChange("#index_komoditas_pemasukan", komoditasPemasukanChanges);
    recordKomoditasChange("#index_komoditas_pengeluaran", komoditasPengeluaranChanges);
}

function buildKomoditasTable(idTag, data) {
    $(idTag).empty();
    var template = "";
    var heading = "<thead><th>Index</th><th>Komoditas</th><th>Kualitas</th><th>Satuan</th><th>Jumlah</th></thead>";
    $.each(data, function (e, a) {
        template += templateKomoditasDataTable(a, e + 1, 1, null)
    });
    $(idTag).append(heading + template);
    com_github_culmat_jsTreeTable.treeTable($(idTag))
}

function templateKomoditasDataTable(e, idx, level, tt_parent_id, r) {
    tt_id = "";
    if (tt_parent_id) tt_id = tt_parent_id + "_" + (idx - 1);
    else var tt_id = "id_" + (idx - 1);
    var style_i = "";
    style_i = 1 == level ? "font-weight:bolder;" :
        e.child ? "font-weight:bold;" :
            "font-weight:400;";
    var d = "<tr data-tt-id='" + tt_id + "' data-tt-level='" + level + "' " + (tt_parent_id ? "data-tt-parent-id='" + tt_parent_id + "'" : "") + " style='" +
        style_i + "'><td>" + e.id_ntp_komoditas + "</td><td>" + e.nama_komoditas + "</td>";

    if (level > 1) {
        const komoditasInput = ["kualitas", "satuan", "jumlah"];
        for (const inp of komoditasInput) {
            d += `<td class='data'><input type="text" data-inp="${inp}" data-id="${e.id_ntp_komoditas}" class="form-control"/></td>`;
        }
    } else {
        d += "<td class='data' colspan='3'></td>";
    }


    if (d += "</tr>", e.child)
        for (o = 0; o < e.child.length; o++)
            d += templateKomoditasDataTable(e.child[o], o + 1, level + 1, tt_id, e);
    return d
}

function recordKomoditasChange(idTag, objX) {
    $(idTag + " input[type=text]").on("change", function () {
        var komoditasValue = $(this).val();
        var komoditasKey = $(this).data('inp');
        var id = $(this).data('id');

        if (objX[id]) {
            objX[id][komoditasKey] = komoditasValue;
        } else {
            objX[id] = {
                [komoditasKey]: komoditasValue
            }
        }
    });
}

function searchNtpRecords() {
    get_ntp_records(currentPage, rowPerPage, !0);
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
});

$("[name='jenis_kelamin']").select2({
    minimumResultsForSearch: -1
});

$(".datepicker").datepicker({
    format: "yyyy-mm-dd"
});

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$("#form_ntp").submit(function (e) {
    e.preventDefault();
    $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form");
    $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan Survei?");
    $("#confirmModal").modal("show");
});

$("#confirmModal").find("#confirmAcceptModal").click(function () {
    var formNTPObj = $("#form_ntp").serializeObject();
    formNTPObj['pemasukanChanges'] = komoditasPemasukanChanges;
    formNTPObj['pengeluaranChanges'] = komoditasPengeluaranChanges;

    $.ajax({
        url: base_url + "d_secure/ntp/simpan_ntp",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(formNTPObj),
        type: "POST",
        cache: !1,
        processData: !1,
        success: function (e) {
            $("#confirmModal").modal("hide");
            get_ntp_records(currentPage, rowPerPage, !0);
            hideNTPForm();
        }
    })
});

function get_ntp_records(e, t, a) {
    $.ajax({
        url: base_url + "d_secure/ntp/get_ntp_records",
        type: "post",
        data: $.param({
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
                $(".jml_info").text("Terdapat " + r.jumlah + " NTP record"),
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
        <li><a href='#' onclick="editNtpRecord(this,'${e.id_ntp_record}')" class='btn-edit-post'><i class='fa fa-pencil'></i> Edit</a></li>
        <li><a href='#' onclick="deleteNTPRecord(this,'${e.id_ntp_record}')" class='btn-edit-post'><i class='fa fa-trash'></i> Delete</a></li>
    </ul></div></td></tr>`
}

function createNtpRecord() {
    currentNtpRecord = {lat: defaultPositionLat, lon: defaultPositionLon};
    openNtpRecordForm();
}

function editNtpRecord(e, t) {
    findFromTable(currentDataPage, t);

    $.ajax({
        contentType: "application/json",
        url: base_url + "d_secure/ntp/get_ntp_komoditas_value",
        data: JSON.stringify({
            id_ntp_record: currentNtpRecord.id_ntp_record
        }),
        dataType: "json",
        type: "post",
        success: function (e) {
            openNtpRecordForm();
            $("#keterangan_elemen").val(currentNtpRecord.keterangan_elemen);

            var tmpData = e.length === undefined ? [e] : e;
            tmpData.forEach(function (item) {
                var $kualitasInp = $("#form_ntp").find(`[data-inp='kualitas'][data-id='${item.id_ntp_komoditas}']`),
                    $jumlahInp = $("#form_ntp").find(`[data-inp='jumlah'][data-id='${item.id_ntp_komoditas}']`),
                    $satuanInp = $("#form_ntp").find(`[data-inp='satuan'][data-id='${item.id_ntp_komoditas}']`);

                $kualitasInp.val(item.kualitas);
                $jumlahInp.val(item.jumlah);
                $satuanInp.val(item.satuan);
            });
        }
    })
}

function openNtpRecordForm() {
    $("#form_ntp").find("[name='id_ntp_record']").remove();
    $("#form_ntp")[0].reset();
    $("#form_ntp select.ajax").empty().trigger('change');

    $("#content_table_survei").is(":visible") && ($("#content_table_survei").hide("fast"),
            $("#content_form_ntp").show("fast", function () {
                currentNtpRecord.id_ntp_record && $("#form_ntp").prepend("<input type='hidden' value='" + currentNtpRecord.id_ntp_record + "' name='id_ntp_record' />");
                currentNtpRecord.id_elemen && $("#form_ntp").find("#id_elemen").append(templateoption(currentNtpRecord.id_elemen, currentNtpRecord.keterangan_elemen, !0));
                currentNtpRecord.id_unit && $("#form_ntp").find("#id_unit").append(templateoption(currentNtpRecord.id_unit, currentNtpRecord.nama_unit, !0));
                currentNtpRecord.id_desa && $("#form_ntp").find("#id_desa").append(templateoption(currentNtpRecord.id_desa, currentNtpRecord.nama_desa, !0));
                $("#form_ntp").find("[name='jenis_kelamin']").val(currentNtpRecord.jenis_kelamin).trigger('change');
                $("#form_ntp").find("[name='tgl_survey']").val(currentNtpRecord.tgl_survey);
                $("#form_ntp").find("[name='nama_responden']").val(currentNtpRecord.nama_responden);
                $("#form_ntp").find("[name='jumlah_anggota_kk']").val(currentNtpRecord.jumlah_anggota_kk);
                $("#form_ntp").find("[name='usia']").val(currentNtpRecord.usia);
                $("#form_ntp").find("[name='pendidikan']").val(currentNtpRecord.pendidikan);
                $("#form_ntp").find("[name='telp']").val(currentNtpRecord.telp);
                if (currentNtpRecord.lat && currentNtpRecord.lon) {
                    $("#form_ntp").find("[name='lat']").val(currentNtpRecord.lat);
                    $("#form_ntp").find("[name='lon']").val(currentNtpRecord.lon);
                    google.maps.event.trigger(map, "resize");
                    marker.setPosition(new google.maps.LatLng(currentNtpRecord.lat, currentNtpRecord.lon));
                    map.setCenter(new google.maps.LatLng(currentNtpRecord.lat, currentNtpRecord.lon));
                }
            })
    )
}

function findFromTable(e, t) {
    for (var a = 0; a < e.length; a++)
        if (e[a].id_ntp_record == t)
            return void (currentNtpRecord = e[a])
}

function cancelSubmission() {
    hideNTPForm();
}

function hideNTPForm() {
    $("#form_ntp").find("[name='id_ntp_record']").remove();
    $("#form_ntp")[0].reset();

    $("#content_form_ntp").is(":visible") &&
    ($("#content_table_survei").show("fast"),
        $("#content_form_ntp").hide("fast"))
}

function templateoption(e, t, a) {
    return "<option value=" + e + " " + (a ? "selected='selected'" : "") + ">" + t + "</option>"
}

function deleteNTPRecord(e, t) {
    findFromTable(currentDataPage, t),
        $("#confirmModal").find(".modal-title").text("Konfirmasi Hapus Survei"),
        $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan Menghapus Survei ini ?"),
        $("#confirmModal").find("#confirmAcceptModal").click(function () {
            $.ajax({
                url: base_url + "d_secure/ntp/hapus_ntp",
                data: $.param({
                    id_ntp_record: t
                }),
                type: "post",
                success: function (e) {
                    get_ntp_records(currentPage, rowPerPage, !0), $("#confirmModal").modal("hide")
                }
            })
        }), $("#confirmModal").modal("show")
}