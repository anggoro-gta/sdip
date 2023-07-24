function initMap() {
    (map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: defaultPositionLat,
            lng: defaultPositionLon
        },
        zoom: 11
    })).data.loadGeoJson(base_url + "module/smc/master/get_kecamatan_koordinat", {
        idPropertyName: "id_unit"
    }), map.data.addListener("mouseover", function (e) {
        e.feature.setProperty("state", "hover"), $(".map-tips").html("<h6 style='margin:0px; padding: 10px 20px; font-weight:bold;'>Kecamatan " + e.feature.getProperty("nama") + "</h6>")
    }), map.data.addListener("mouseout", function (e) {
        e.feature.setProperty("state", "normal"), null == currentUnit ? $(".map-tips").html("") : $(".map-tips").html("<h6 style='margin:0px; padding: 10px 20px; font-weight:bold;'>Kecamatan " + currentUnit + "</h6>")
    }), map.data.addListener("click", function (e) {
        currentUnit = e.feature.getProperty("nama"), currentIdUnit = e.feature.getProperty("id_unit"), search()
    }), map.data.setStyle(function (e) {
        var t = .5,
            a = 1;
        return "hover" === e.getProperty("state") && (t = a = 2), {
            strokeWeight: t,
            strokeColor: "#fff",
            zIndex: a,
            fillColor: "#222222",
            fillOpacity: .2,
            visible: !0
        }
    })
}

function search() {
    null == $("#elemen-2").val() || null == $("#elemen-1").val() || null == currentUnit ? ($("#confirmModal").find(".modal-title").text("Kesalahan Pencarian"), $("#confirmModal").find(".modal-body").text("Mohon untuk melengkapi filter pencarian"), $("#confirmModal").find("#confirmAcceptModal").hide(), $("#confirmModal").modal("show")) : get_elemen(currentPage, rowPerPage, !0)
}

function cek_filter() {
    null == $("#elemen-1").val() ? $("#elemen-1").select2("open") : null == $("#elemen-2").val() && $("#elemen-2").select2("open")
}

function get_elemen(e, t, a) {
    $.ajax({
        url: base_url + "module/smc/rekap/get_tree_rekap",
        type: "post",
        data: $.param({
            id_parent: currentParent,
            unit: currentUnit,
            page: e,
            rowPerPage: t
        }),
        success: function (n) {
            var r = JSON.parse(n);
            if (currentIdUnit = r.id_elemen, 0 == r.jumlah) return $("#tabel_nothing").show(), $("#data_table").hide(), void $("#page-content").hide();
            $("#tabel_nothing").hide(), $("#data_table").show(), $("#data_table").parent().parent().find(".panel-title").html("Data Profil <i style='text-transform:capitalize;'>Kecamatan '" + currentUnit + "'</i>"), $("#page-content").show(), 1 == a && pagination(r.jumlah, t, 5, e, $(".pagination"), get_elemen, !1), $("#data_table").empty(), template = "", heading = "<thead><th>Nama Elemen</th><th>Singkat</th>";
            for (var l in r.data[0].tahun) heading += "<th>" + l + "</th>";
            heading += "<th></th>", heading += "</thead>", $.each(r.data, function (e, t) {
                template += templateDataTable(t, e + 1, 1, null)
            }), $("#data_table").append(heading + template), $("[data-toggle='tooltip']").tooltip(), $("[data-toggle='popover']").popover({
                trigger: "hover"
            }), currentDataPage = r.data, currentPage = e, com_github_culmat_jsTreeTable.treeTable($("#data_table")), $("#dataModal").modal("show")
        },
        error: function () {},
        beforeSend: function () {},
        complete: function () {}
    })
}

function templateDataTable(e, t, a, n) {
    r = "";
    if (n) r = n + "_" + (t - 1);
    else var r = "id_" + (t - 1);
    var l = "<tr data-tt-id='" + r + "' data-tt-level='" + a + "' " + (n ? "data-tt-parent-id='" + n + "'" : "") + "><td>" + e.singkat + "</td><td>" + e.keterangan + "</td>";
    for (var i in e.tahun) l += "<td class='data'>", null != e.tahun[i] && (l += "<span>" + e.tahun[i].jumlah + " " + e.tahun[i].satuan + "</span>"), l += " <a href='#' onclick=\"add_edit_rekap(this,'" + e.id_elemen + "','" + i + "','" + e.keterangan + "')\"><i class='fa fa-pencil'></i></a>", l += "</td>";
    if (l += "<td class='data'><a class='btn btn-xs btn-warning' onclick=\"show_chart(this,'" + e.id_elemen + "')\"><i class='fa fa-bar-chart'></i></a></td>", l += "</tr>", e.child)
        for (i = 0; i < e.child.length; i++) l += templateDataTable(e.child[i], i + 1, a + 1, r);
    return l
}

function add_edit_rekap(e, t, a, n) {
    currentRekap = null, currentCell = e, findFromTree(currentDataPage, t, a), $("#form_rekap")[0].reset(), $("#form_rekap").find("[name='periode']").val(a), $("#form_rekap").find("[name='id_elemen']").val(t), $("#form_rekap").find("[name='id_unit']").val(currentIdUnit), null != currentRekap && ($("#form_rekap").prepend("<input type='hidden' value='" + currentRekap.id_rekap + "' name='id_rekap' />"), "0" != currentRekap.id_skpd && null != currentRekap.id_skpd && $("#form_rekap").find("[name='id_skpd']").append(templateoption(currentRekap.id_skpd, currentRekap.nama_skpd, !0)), $("#form_rekap").find("[name='satuan']").val(currentRekap.satuan), $("#form_rekap").find("[name='jumlah']").val(currentRekap.jumlah), $("#form_rekap").find("[name='jenis_data']").val(currentRekap.jenis_data), $("#form_rekap").find("[name='is_valid']").prop("checked", 1 == currentRekap.is_valid)), isAddEdit = !0, $("#dataModal").modal("hide"), $("#formModal").find(".modal-title").html(n + " Periode : " + a)
}

function show_chart(e, t) {
    currentElemen = null, currentCell = e, findElemenFromTree(currentDataPage, t, periode), isShowChart = !0, $("#dataModal").modal("hide"), $("#chartModal").find(".modal-title").html("Grafik '" + currentElemen.keterangan + "'")
}

function findElemenFromTree(e, t) {
    for (var a = 0; a < e.length; a++) e[a].id_elemen == t && (currentElemen = e[a]), e[a].child && findElemenFromTree(e[a].child, t)
}

function findFromTree(e, t, a) {
    for (var n = 0; n < e.length; n++) {
        if (e[n].id_elemen == t)
            for (j in e[n].tahun)
                if (j == a) return void(currentRekap = e[n].tahun[j]);
        e[n].child && findFromTree(e[n].child, t, a)
    }
}

function setToTree(e, t, a, n) {
    for (var r = 0; r < e.length; r++) {
        if (e[r].id_elemen == t)
            for (j in e[r].tahun)
                if (j == a) return void(e[r].tahun[j] = n);
        e[r].child && findFromTree(e[r].child, t, a)
    }
}

function templateselect(e) {
    return e.kategori ? "<h5><b>" + e.text + "<b><br/><small style='text-transform:capitalize'>" + e.kategori + "</small></h5>" : "<h5><small>" + e.text + "</small></h5>"
}

function templateoption(e, t, a) {
    return "<option value=" + e + " " + (a ? "selected='selected'" : "") + ">" + t + "</option>"
}

function intToChar(e) {
    return (e >= 26 ? idOf((e / 26 >> 0) - 1) : "") + "abcdefghijklmnopqrstuvwxyz" [e % 26 >> 0]
}
var map, parent = "~",
    currentParent = "~",
    currentPage = 1,
    rowPerPage = 10,
    currentDataPage = [],
    currentUnit = null,
    currentIdUnit = null,
    isAddEdit = !1,
    isShowChart = !1,
    currentRekap = null,
    currentElemen = null,
    currentCell = null,
    defaultPositionLat = -7.848016,
    defaultPositionLon = 112.017829;
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
get_elemen(currentPage, rowPerPage, !0), $("#confirmModal").on("hidden.bs.modal", function (e) {
    $("#confirmModal").find("#confirmAcceptModal").unbind("click"), $("#confirmModal").find("#confirmAcceptModal").show(), cek_filter()
}), $("#formModal").on("hidden.bs.modal", function (e) {
    $("#form_rekap").find("[name='id_rekap']").remove(), isAddEdit = !1, $("#dataModal").modal("show")
}), $("#chartModal").on("hidden.bs.modal", function (e) {
    isShowChart = !1, $("#dataModal").modal("show")
}), $("#chartModal").on("shown.bs.modal", function (e) {
    var t = [],
        a = [];
    for (tahun in currentElemen.tahun) t.push(tahun), a.push(null != currentElemen.tahun[tahun] ? currentElemen.tahun[tahun].jumlah : 0);
    chartConfig.data.labels = t, chartConfig.data.datasets[0].label = currentElemen.keterangan, chartConfig.data.datasets[0].data = a, chart.update()
}), $("#dataModal").on("hidden.bs.modal", function (e) {
    isAddEdit && $("#formModal").modal("show"), isShowChart && $("#chartModal").modal("show")
}), $("#id_skpd").select2({
    ajax: {
        url: base_url + "module/smc/master/get_select2_unit",
        dataType: "json",
        data: function (e, t) {
            return {
                q: e.term,
                kategori: "skpd",
                page_limit: 10
            }
        },
        processResults: function (e, t) {
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
}), $("#elemen-1").select2().on("select2:select", function (e) {
    parent = e.params.data.id
}), $("#elemen-2").select2({
    ajax: {
        url: base_url + "module/smc/master/get_select2_elemen",
        dataType: "json",
        data: function (e, t) {
            return {
                q: e.term,
                id_parent: parent,
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
    escapeMarkup: function (e) {
        return e
    }
}).on("select2:select", function (e) {
    currentParent = e.params.data.id
}), $("#form_rekap").submit(function (e) {
    e.preventDefault(), $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form"), $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan rekap ini?");
    var t = new FormData($("#form_rekap")[0]);
    $("#confirmModal").find("#confirmAcceptModal").click(function () {
        $.ajax({
            url: base_url + "module/smc/rekap/simpan_rekap",
            data: t,
            type: "post",
            cache: !1,
            contentType: !1,
            processData: !1,
            success: function (e) {
                setToTree(currentDataPage, t.get("id_elemen"), t.get("periode"), {
                    id_elemen: t.get("id_elemen"),
                    id_unit: t.get("id_unit"),
                    id_skpd: t.get("id_skpd"),
                    jumlah: t.get("jumlah"),
                    satuan: t.get("satuan"),
                    jenis_data: t.get("jenis_data"),
                    is_valid: t.has("is_valid") ? "1" : "0",
                    periode: t.get("periode")
                }), $(currentCell).parent().find("span").text(t.get("jumlah") + " " + t.get("satuan")), $("#confirmModal").modal("hide"), $("#formModal").modal("hide"), get_elemen(currentPage, rowPerPage, !0)
            }
        })
    }), $("#confirmModal").modal("show")
});