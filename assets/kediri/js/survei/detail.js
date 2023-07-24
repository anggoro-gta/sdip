function search() {
    get_detail_survei(currentPage, rowPerPage, !0)
}

function ekspor() {
    $("#ekspor_form").submit()
}

var d_thead_survey = $.Deferred(), thead_survey_detail;
 
function get_detail_survei(e, a, t) {
    $.when( $.ajax({
        url: base_url + "survei/get_detail_survei",
        type: "post",
        datatype: "json",
        data: $.param({
            id_survey: $("#form_detail_survei").find("[name='id_survey']").val(),
            search: $("[name='keyword']").val(),
            page: e,
            rowPerPage: a
        })
    }) , d_thead_survey ).done(function ( a1, a2 ) {        
        var n = JSON.parse(a1[0]);
        
        if (0 == n.jumlah) return $("#tabel_nothing").show();

        $("#data_table").hide(), 
        void $("#page-content").hide(),
        $("#tabel_nothing").hide(),
        $("#data_table").show(), 
        $("#page-content").show(), 
        1 == t && pagination(n.jumlah, a, 5, e, $(".pagination"), get_detail_survei, !1), 
        $("#data_table").empty(),         
        template = a2, 
        $.each(n.data, function (e, a) {
            template += templateDataTable(a, e + 1, 1, null)
        }), 
        $("#data_table").append(template), 
        $("[data-toggle='tooltip']").tooltip(), 
        $("[data-toggle='popover']").popover({
            trigger: "hover"
        }), 
        currentDataPage = n.data, 
        currentPage = e
    });
    
    // Provider untuk thead_survey_detail
    if ( thead_survey_detail === undefined ) {            
        $.ajax({
            url: base_url + "survei/get_thead_survey_detail",
            type: "post",
            data: $.param({
                keterangan_unsur:$("#keterangan_unsur").text().toLowerCase()
            })
        }).success(function(rsp) {
            thead_survey_detail = rsp;
            d_thead_survey.resolve(thead_survey_detail);
        });
    }
}

function open_tambah_detail_form() {
    $("#form_detail_survei").find("[name='id_survey_detail']").remove(), 
    $("#form_detail_survei")[0].reset(), 
    $("#content_table_detail_survei").is(":visible") && ($("#content_table_detail_survei").hide("fast"), 
    $("#content_form_detail_survei").show("fast"))
}

function edit_detail_survei(e, id_survey_detail) {
    findFromTable(currentDataPage, id_survey_detail);
    cancel_form(), 
    open_tambah_detail_form(), 
    $("#form_detail_survei").prepend("<input type='hidden' value='" + currentDetailSurvei.id_survey_detail + "' name='id_survey_detail' />"), 
    $("#form_detail_survei").find("[name='id_survey']").val(currentDetailSurvei.id_survey), 
    $("#form_detail_survei").find("[name='nama']").val(currentDetailSurvei.nama), 
    $("#form_detail_survei").find("[name='jumlah_anggota']").val(currentDetailSurvei.jumlah_anggota), 
    $("#form_detail_survei").find("[name='luas_lahan']").val(currentDetailSurvei.luas_lahan), 
    $("#form_detail_survei").find("[name='pola_tanam_1']").val(currentDetailSurvei.pola_tanam_1), 
    $("#form_detail_survei").find("[name='pola_tanam_2']").val(currentDetailSurvei.pola_tanam_2), 
    $("#form_detail_survei").find("[name='pola_tanam_3']").val(currentDetailSurvei.pola_tanam_3), 
    $("#form_detail_survei").find("[name='status_anggota']").val(currentDetailSurvei.status_anggota), 
    $("#form_detail_survei").find("[name='jumlah_produksi']").val(currentDetailSurvei.jumlah_produksi), 
    $("#form_detail_survei").find("[name='potensi_utama']").val(currentDetailSurvei.potensi_utama), 
    $("#form_detail_survei").find("[name='jenis_ternak']").val(currentDetailSurvei.jenis_ternak).trigger("change.select2"), 
    $("#form_detail_survei").find("[name='kategori_produk']").val(currentDetailSurvei.kategori_produk), 
    $("#form_detail_survei").find("[name='jumlah_ternak']").val(currentDetailSurvei.jumlah_ternak), 
    $("#form_detail_survei").find("[name='produk_olahan']").val(currentDetailSurvei.produk_olahan)
}

function delete_detail_survei(e, a) {
    findFromTable(currentDataPage, a), $("#confirmModal").find(".modal-title").text("Konfirmasi Hapus Detail"), $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan Menghapus Detail ini ?"), $("#confirmModal").find("#confirmAcceptModal").click(function () {
        $.ajax({
            url: base_url + "survei/hapus_detail_survei",
            data: $.param({
                id_survey_detail: a
            }),
            type: "post",
            success: function (e) {
                get_detail_survei(currentPage, rowPerPage, !0), $("#confirmModal").modal("hide")
            }
        })
    }), $("#confirmModal").modal("show")
}

function findFromTable(e, a) {
    for (var t = 0; t < e.length; t++)
        if (e[t].id_survey_detail == a) return void(currentDetailSurvei = e[t])
}

function cancel_form() {
    $("#form_detail_survei").find("[name='id_survey_detail']").remove(), 
    $("#form_detail_survei")[0].reset(), 
    $("#content_form_detail_survei").is(":visible") && ($("#content_table_detail_survei").show("fast"), 
    $("#content_form_detail_survei").hide("fast"))
}

function templateDataTable(e) {
    var a = "<tr style='text-align:center'><td>" + e.nama + "</td><td>" + e.jumlah_anggota + "</td>";
    return -1 === $("#keterangan_unsur").text().toLowerCase().indexOf("pertanian") && 
           -1 === $("#keterangan_unsur").text().toLowerCase().indexOf("perkebunan") || 
           (a += "<td>" + ("0" != e.luas_lahan ? e.luas_lahan : "-") + "</td><td>" + (e.pola_tanam_1 ? e.pola_tanam_1 : "-") + "</td><td>" + (e.pola_tanam_2 ? e.pola_tanam_2 : "-") + "</td><td>" + (e.pola_tanam_3 ? e.pola_tanam_3 : "-") + "</td><td>" + ("0" != e.jumlah_produksi ? e.jumlah_produksi : "-") + "</td>"), 
           -1 === $("#keterangan_unsur").text().toLowerCase().indexOf("peternakan") && 
           -1 === $("#keterangan_unsur").text().toLowerCase().indexOf("perikanan") || 
           (a += "<td>" + (e.jenis_ternak ? e.jenis_ternak : "-") + "</td><td>" + (e.kategori_produk ? e.kategori_produk : "-") + "</td><td>" + (e.jumlah_ternak ? e.jumlah_ternak : "-") + "</td>"), 
           a += "<td>" + (e.potensi_utama ? e.potensi_utama : "-") + "</td><td>" + (e.produk_olahan ? e.produk_olahan : "-") + "</td>", 
           a += "<td style='vertical-align:middle;'><div class='dropdown pull-right'><a class='dropdown-toggle btn btn-table' id='drop4' role='button' data-toggle='dropdown' href='#'>Action <b class='caret'></b></a><ul id='menu1' class='dropdown-menu pull-right' role='menu' aria-labelledby='drop4'><li><a href='#' onclick=\"edit_detail_survei(this,'" + e.id_survey_detail + "')\"  class='btn-edit-post' ><i class='fa fa-pencil'></i> Edit</a></li><li><a href='#' onclick=\"delete_detail_survei(this,'" + e.id_survey_detail + "')\"  class='btn-edit-post' ><i class='fa fa-trash'></i> Delete</a></li></ul></div></td></tr>"
}

var currentPage = 1,
    rowPerPage = 10,
    currentDataPage = [];

get_detail_survei(currentPage, rowPerPage, !0), 
$("#confirmModal").on("hidden.bs.modal", function (e) {
    $("#confirmModal").find("#confirmAcceptModal").unbind("click"), 
    $("#confirmModal").find("#confirmAcceptModal").show()
}), 
$("#jenis_ternak").select2().on("select2:select", function (e) {}), 
$("#form_detail_survei").submit(function (e) {
    e.preventDefault(), 
    $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form"), 
    $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan Detail Survei?"), 
    $("#confirmModal").find("#confirmAcceptModal").click(function () {
        $.ajax({
            url: base_url + "survei/simpan_detail_survei",
            data: new FormData($("#form_detail_survei")[0]),
            type: "post",
            cache: !1,
            contentType: !1,
            processData: !1,
            success: function (e) {
                $("#confirmModal").modal("hide"), get_detail_survei(currentPage, rowPerPage, !0), cancel_form()
            }
        })
    }), $("#confirmModal").modal("show")
});
var currentDetailSurvei = null;