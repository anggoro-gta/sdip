
function reload_filter() {
    "kabupaten" == $("#unit").val() ? 
    ($(".form_group_skpd").show(), 
    $(".form_group_kecamatan").hide(), 
    $("#form_rekap").find("[name='id_skpd']").parent().show())
    : 
    "kecamatan" == $("#unit").val() && 
    ($(".form_group_kecamatan").show(), 
    $(".form_group_skpd").hide(), 
    $("#form_rekap").find("[name='id_skpd']").parent().hide())
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

function get_elemen() {
    var e = {
        id_parent: $("#unsur").val(),
        kategori: "kecamatan" == $("#unit").val() ? "kecamatan" : "skpd",
        unit: "kecamatan" == $("#unit").val() ? $("#id_kecamatan").val() : $("#id_skpd").val()
    };
    "kecamatan" == $("#unit").val() && $("#id_desa").length > 0 && (e.sub_unit = $("#id_desa").val()), $.ajax({
        url: base_url + "rekap/get_tree_elemen",
        type: "post",
        data: $.param(e),
        beforeSend: function () {
            $("#main-box").hide(), $("#data-loading").show("fast")
        },
        complete: function () {
            $("#data-loading").hide("fast", function () {
                $("#main-box").slideDown("fast")
            })
        },
        success: function (e) {
            var a = JSON.parse(e);
            if (currentIdUnit = a.id_elemen, 0 == a.jumlah) 
                return $("#tabel_nothing").show(), 
                        $("#data_table").hide(), 
                        void $("#page-content").hide();

            $("#tabel_nothing").hide(), 
            $("#data_table").show(), 
            $("#page-content").show(), 
            $("#data_table").empty(), 
            template = "", 
            heading = "<thead><th>Nama Elemen</th><th>Singkat</th>";
            for (var t = a.tahun.length - 1; t > -1; t--) 
                heading += "<th>" + a.tahun[t] + "</th>";

            heading += "<th></th>", 
            heading += "</thead>", 
            $.each(a.data, function (e, a) {
                template += templateDataTable(a, e + 1, 1, null)
            }), 
            $("#data_table").append(heading + template), 
            $("[data-toggle='tooltip']").tooltip(), 
            $("[data-toggle='popover']").popover({
                trigger: "hover"
            }), 
            currentDataPage = a.data, 
            com_github_culmat_jsTreeTable.treeTable($("#data_table")), 
            $("#dataModal").modal("show")
        }
    })
}

function templateDataTable(e, a, t, n, r) {    
    l = "";
    if (n) l = n + "_" + (a - 1);
    else var l = "id_" + (a - 1);
    var i = "";
    i = 1 == t ? "font-weight:bolder;" : 
                e.child ? "font-weight:bold;" : 
                    "font-weight:400;";
    var d = "<tr data-tt-id='" + l + "' data-tt-level='" + t + "' " + (n ? "data-tt-parent-id='" + n + "'" : "") + " style='" + i + "'><td>" + e.singkat + "</td><td>" + e.keterangan + "</td>";
    if ("tahun" in e) {        
        for (var o in e.tahun) 
            d += "<td class='data'>", 
            d += "<span>" + (e.tahun[o] && null != e.tahun[o].jumlah ? 
                e.tahun[o].jumlah : "0") + " " + e.satuan + "</span>", 
                "kabupaten" == $("#unit").val() && "1" == (r ? r.has_unit_elemen : e.has_unit_elemen) || (d += " <button class='btn btn-xs btn-info' type='button' onclick=\"add_edit_rekap(this,'" + e.id_elemen + "','" + o + "','" + e.keterangan + "')\"><i class='fa fa-pencil'></i></button>"), d += "</td>";
        d += "<td class='data'><button type='button' class='btn btn-xs btn-warning' onclick=\"show_chart(this,'" + e.id_elemen + "')\"><i class='fa fa-bar-chart'></i></button></td>"
    } else if ("1" == e.has_unit_elemen) {
        for (var u = [], o = 0; o < e.child.length; o++)
            for (var c in e.child[o].tahun) u["index_" + c] || (u["index_" + c] = 0), u["index_" + c] += parseFloat(null != e.child[o].tahun[c] && null != e.child[o].tahun[c].jumlah ? e.child[o].tahun[c].jumlah : 0);
        var m = !1;
        for (var o in u) d += "<td class='data'>", d += "<span>" + u[o] + " " + e.satuan + "</span>", d += "</td>", m = !0;
        d += m ? "<td class='data'><button type='button' class='btn btn-xs btn-danger' onclick=\"show_chart_unit(this,'" + e.id_elemen + "')\"><i class='fa fa-bar-chart'></i></button></td>" : "<td class='data' colspan='4'></td>"
    } else 
        d += "<td class='data' colspan='4'></td>";

    if (d += "</tr>", e.child)
        for (o = 0; o < e.child.length; o++) 
            d += templateDataTable(e.child[o], o + 1, t + 1, l, e);
    return d
}

function add_edit_rekap(e, a, t, n) {
    $("#form_rekap [name='id_rekap']").remove();
    currentRekap = null, currentElemen = null, currentCell = e, findFromTree(currentDataPage, a, t), $("#form_rekap .panel-title").html(n + "<br/><small>" + currentElemen.elemen + "</small>"), $("#form_rekap")[0].reset(), $("#form_rekap").find("[name='periode']").val(t), $("#form_rekap").find("[name='satuan']").val(currentElemen.satuan), $("#form_rekap").find("[for='jumlah']").html("Jumlah/Nilai <code>" + currentElemen.satuan + "</code>"), $("#form_rekap").find("[name='id_skpd']").empty(), "id_unit" in currentElemen ? ($("#form_rekap").find("[name='id_unit']").val(currentElemen.id_unit), $("#form_rekap").find("[name='id_elemen']").val(currentElemen.id_parent)) : ($("#form_rekap").find("[name='id_unit']").val(""), $("#form_rekap").find("[name='id_elemen']").val(a)), null != currentRekap ? ($("#form_rekap").prepend("<input type='hidden' value='" + (currentRekap.id_rekap ? currentRekap.id_rekap : "") + "' name='id_rekap' />"), $("#form_rekap").find("[name='jumlah']").val(currentRekap.jumlah), $("#form_rekap").find("[name='jenis_data']").val(currentRekap.jenis_data), null != currentRekap.id_skpd && "" != currentRekap.id_skpd && $("#form_rekap").find("[name='id_skpd']").append(templateoption(currentRekap.id_skpd, currentRekap.nama_skpd, !0))) : "kabupaten" == $("#unit").val() && $("#form_rekap").find("[name='id_skpd']").append(templateoption($("#id_skpd").val(), $("#id_skpd").find("[value='" + $("#id_skpd").val() + "']").text(), !0)), $("#main-box").is(":visible") && $("#main-box").slideUp("fast", function () {
        $("#form-panel").slideDown("fast")
        
    })
}

function show_chart_unit(e, a) {
    currentElemen = null, currentCell = e, findElemenFromTree(currentDataPage, a, periode);
    var t = [];
    chartConfig.data.datasets = [];
    for (var n = 0; n < currentElemen.child.length; n++) {
        var r = [];
        for (tahun in currentElemen.child[n].tahun) 0 == n && t.push(tahun), r.push(null != currentElemen.child[n].tahun[tahun] && null != currentElemen.child[n].tahun[tahun].jumlah ? currentElemen.child[n].tahun[tahun].jumlah : 0);
        chartConfig.data.datasets[n] = {}, chartConfig.data.datasets[n].label = currentElemen.child[n].keterangan, chartConfig.data.datasets[n].data = r, chartConfig.data.datasets[n].backgroundColor = hexToRgbA(getHexColor(), .5)
    }
    chartConfig.data.labels = t, $("#chartModal").find(".modal-title").html("Grafik '" + currentElemen.keterangan + "'"), $("#chartModal").modal("show")
}

function show_chart(e, a) {
    currentElemen = null, currentCell = e, findElemenFromTree(currentDataPage, a, periode);
    var t = [],
        n = [];
    for (tahun in currentElemen.tahun) 
        t.push(tahun), 
        n.push(null != currentElemen.tahun[tahun] && null != currentElemen.tahun[tahun].jumlah ? currentElemen.tahun[tahun].jumlah : 0);

    chartConfig.data.datasets = [], 
    chartConfig.data.datasets[0] = {}, 
    chartConfig.data.datasets[0].label = currentElemen.keterangan, 
    chartConfig.data.datasets[0].data = n, 
    chartConfig.data.labels = t, $("#chartModal").find(".modal-title").html("Grafik '" + currentElemen.keterangan + "'"), 
    $("#chartModal").modal("show")
}

function cancel_form() {
    $("#form_rekap").find("[name='id_rekap']").remove(), $("#form_rekap")[0].reset(), $("#form-panel").is(":visible") && ($("#form-panel").hide(), $("#main-box").fadeIn("fast", function () {
        $("html, body").animate({
            scrollTop: $(currentCell).parent().parent().offset().top - 100
        }, 500)
    }))
}

function findElemenFromTree(e, a) {
    for (var t = 0; t < e.length; t++) e[t].id_elemen == a && (currentElemen = e[t]), e[t].child && findElemenFromTree(e[t].child, a)
}

function findFromTree(e, a, t) {
    for (var n = 0; n < e.length; n++) {
        if (e[n].id_elemen == a) {
            currentElemen = e[n];
            for (var r in e[n].tahun)
                if (r == t) return void(currentRekap = e[n].tahun[r])
        }
        e[n].child && findFromTree(e[n].child, a, t)
    }
}

function setToTree(e, a, t, n) {
    for (var r = 0; r < e.length; r++) {
        if (e[r].id_elemen == a)
            for (var l in e[r].tahun)
                if (l == t) return void(e[r].tahun[l] = n);
        e[r].child && setToTree(e[r].child, a, t, n)
    }
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
    })
}

function templateselect(e) {
    return e.kategori ? "<h5><b>" + e.text + "<b><br/><small style='text-transform:capitalize'>" + e.kategori + "</small></h5>" : "<h5><small>" + e.text + "</small></h5>"
}

function templateoption(e, a, t, n) {
    return "<option value=" + e + " " + (t ? "selected='selected'" : "") + " " + (n ? "locked='locked'" : "") + ">" + a + "</option>"
}

function intToChar(e) {
    return (e >= 26 ? idOf((e / 26 >> 0) - 1) : "") + "abcdefghijklmnopqrstuvwxyz" [e % 26 >> 0]
}

function getHexColor() {
    for (var e = "0123456789ABCDEF".split(""), a = "#", t = 0; t < 6; t++) a += e[Math.floor(16 * Math.random())];
    return a
}

function hexToRgbA(e, a) {
    var t;
    if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(e)) return 3 == (t = e.substring(1).split("")).length && (t = [t[0], t[0], t[1], t[1], t[2], t[2]]), t = "0x" + t.join(""), "rgba(" + [t >> 16 & 255, t >> 8 & 255, 255 & t].join(",") + "," + a + ")";
    throw new Error("Bad Hex")
}
var currentDataPage = [],
    currentRekap = null,
    currentElemen = null,
    currentCell = null,
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
    chart = new Chart(document.getElementById("chart").getContext("2d"), chartConfig);
$("#chartModal").on("shown.bs.modal", function (e) {
    chart.update()
}), $("#confirmModal").on("hidden.bs.modal", function (e) {
    $("#confirmModal").find("#confirmAcceptModal").unbind("click"), $("#confirmModal").find("#confirmAcceptModal").show()
}), $("#unit").select2().on("select2:select", reload_filter), initSelect2($("#form_rekap [name='id_skpd']"), base_url + "masterx/master/get_select2_unit", function (e, a) {
    return {
        q: e.term,
        kategori: "skpd",
        page_limit: 10
    }
}, null), initSelect2($("#unsur"), base_url + "masterx/master/get_select2_elemen", function (e, a) {
    return {
        q: e.term,
        kategori: "unsur",
        page_limit: 10
    }
}, null), 1 == $("#id_skpd").data("select") && initSelect2($("#id_skpd"), base_url + "masterx/master/get_select2_unit", function (e, a) {
    return {
        q: e.term,
        kategori: "skpd",
        page_limit: 10
    }
}, null), 1 == $("#id_kecamatan").data("select") && initSelect2($("#id_kecamatan"), base_url + "masterx/master/get_select2_unit", function (e, a) {
    return {
        q: e.term,
        kategori: "kecamatan",
        page_limit: 10
    }
}, null), $("#form_rekap").submit(function (e) {
    e.preventDefault(), $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form"), $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan rekap ini?");
    var a = new FormData($("#form_rekap")[0]);
    $("#confirmModal").find("#confirmAcceptModal").click(function () {
        $.ajax({
            url: base_url + "rekap/simpan_rekap",
            data: a,
            type: "post",
            cache: !1,
            contentType: !1,
            processData: !1,
            success: function (e) {
                setToTree(currentDataPage, currentElemen.id_elemen, a.get("periode"), {
                    id_elemen: a.get("id_elemen"),
                    id_unit: a.get("id_unit"),
                    id_skpd: a.get("id_skpd"),
                    jumlah: a.get("jumlah"),
                    satuan: a.get("satuan"),
                    jenis_data: a.get("jenis_data"),
                    is_valid: a.has("is_valid") ? "1" : "0",
                    periode: a.get("periode")
                }), $(currentCell).parent().find("span").text(a.get("jumlah") + " " + a.get("satuan")), $("#confirmModal").modal("hide"), $("#form-panel").is(":visible") && ($("#form-panel").hide(), $("#main-box").fadeIn("fast", function () {
                    $("html, body").animate({
                        scrollTop: $(currentCell).parent().parent().offset().top - 100
                    }, 500), $(currentCell).parent().parent().addClass("edited"), setTimeout(function () {
                        $(currentCell).parent().parent().removeClass("edited")
                    }, 1e3)
                }))
            }
        })
    }), $("#confirmModal").modal("show")
}), reload_filter();