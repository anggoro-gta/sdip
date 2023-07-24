function get_konten(n, t, e) {
    $.ajax({
        url: base_url + "konten/get_konten",
        type: "post",
        data: $.param({
            search: $("#filter_keyword").val(),
            page: n,
            rowPerPage: t
        }),
        beforeSend: function () {
            $("#data_content").hide(), $("#data-loading").fadeIn("fast")
        },
        complete: function () {
            $("#data-loading").hide(), $("#data_content").fadeIn("fast")
        },
        success: function (a) {
            var o = JSON.parse(a);
            if (0 == o.jumlah) return $("#tabel_nothing").show(), $("#data_table").hide(), $("#page-content").hide(), void $("#info-content").hide();
            $("#tabel_nothing").hide(), $("#data_table").show(), $("#page-content").show(), $("#info-content").show(), 1 == e && pagination(o.jumlah, t, 5, n, $(".pagination"), get_konten, !1), currentDataPage = o.data, $("#data_table").empty(), template = "<thead><th>Judul</th><th>Kategori</th><th></th><th></th></thead>", $.each(o.data, function (n, t) {
                template += templateDataTable(t, n + 1)
            }), $(".jml_info").text("Terdapat " + o.jumlah + " Survei"), $(".page_info").text("Halaman " + n + " dari " + Math.ceil(o.jumlah / t)), $("#data_table").append(template)
        }
    })
}

function edit_konten(n, t) {
    findFromTable(currentDataPage, t), cancel_form(), buat_form(), $("#form_konten").prepend("<input type='hidden' value='" + currentKonten.id_konten + "' name='id_konten' />"), $("#form_konten").find("[name='judul']").val(currentKonten.judul), $("#form_konten").find("[name='penulis']").val(currentKonten.penulis), $("#form_konten").find("[name='keterangan']").val(currentKonten.keterangan), $("#form_konten").find("[name='video_url']").val(currentKonten.video_url), $("#image-thumb").attr("src", base_url + "upload/" + currentKonten.img_thumb), $("#form_konten").find("[name='is_publish']").prop("checked", 1 == currentKonten.is_publish)
}

function hapus_konten(n, t) {
    findFromTable(currentDataPage, t), $("#confirmModal").find(".modal-title").text("Konfirmasi Hapus Konten"), $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan Menghapus Konten ini ?"), $("#confirmModal").find("#confirmAcceptModal").click(function () {
        $.ajax({
            url: base_url + "konten/hapus_konten",
            data: $.param({
                id_konten: t
            }),
            type: "post",
            success: function (n) {
                get_konten(currentPage, rowPerPage, !0), $("#confirmModal").modal("hide")
            }
        })
    }), $("#confirmModal").modal("show")
}

function findFromTable(n, t) {
    for (var e = 0; e < n.length; e++)
        if (console.log(n[e]), n[e].id_konten == t) return void(currentKonten = n[e])
}

function templateDataTable(n, t) {
    return "<tr><td style='width:200px;'>" + n.judul + "</td><td>" + n.kategori_konten + "</td><td> Dilihat " + n.hit + "<br/>" + formatDate(n.last_update) + " " + formatTime(n.last_update) + "</td><td><div class='dropdown pull-right'><a class='dropdown-toggle btn btn-table' id='drop4' role='button' data-toggle='dropdown' href='#'>Aksi <b class='caret'></b></a><ul id='menu1' class='dropdown-menu pull-right' role='menu' aria-labelledby='drop4'><li><a href='#' onclick=\"edit_konten(this,'" + n.id_konten + "')\"  class='btn-edit-post' ><i class='fa fa-pencil'></i> Edit</a></li><li><a href='#' onclick=\"hapus_konten(this,'" + n.id_konten + "')\"  class='btn-edit-post' ><i class='fa fa-trash'></i> Delete</a></li></ul></div></td></tr>"
}

function search() {
    get_konten(currentPage, rowPerPage, !0)
}

function buat_form() {
    $("#form_konten").find("[name='id_konten']").remove(), $("#form_konten")[0].reset(), $("#data_content").slideUp("fast", function () {
        $("#form_content").slideDown()
    })
}

function cancel_form() {
    $("#form_konten").find("[name='id_konten']").remove(), $("#form_konten")[0].reset(), $("#image-thumb").attr("src", defaultSrcThumbnail), $("#form_content").slideUp("fast", function () {
        $("#data_content").slideDown()
    })
}

function formatDate(n) {
    if (!n || null == n) return n;
    var t = n.split(/[- :]/);
    return t[2] + " " + MONTH.ID[t[1] - 1] + " " + t[0]
}

function formatTime(n) {
    if (!n || null == n) return n;
    var t = n.split(/[- :]/);
    return t[3] + ":" + t[4] + ":" + t[5]
}
var MONTH = {
        ID: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
    },
    HTML_REGEX = /(<([^>]+)>)/gi,
    currentPage = 1,
    rowPerPage = 10,
    currentDataPage = [],
    currentKonten = null;
get_konten(currentPage, rowPerPage, !0), $("#confirmModal").on("hidden.bs.modal", function (n) {
    $("#confirmModal").find("#confirmAcceptModal").unbind("click"), $("#confirmModal").find("#confirmAcceptModal").show()
}), $("#keterangan").wysihtml5(), $("#form_konten").submit(function (n) {
    if (n.preventDefault(), "" == $("#form_konten").find("[name='keterangan']").val().replace(HTML_REGEX, "").trim()) return $("#confirmModal").find(".modal-title").text("Form Salah"), $("#confirmModal").find(".modal-body").text("Mohon memberikan keterangan, setidaknya 1 kalimat . . ."), $("#confirmModal").find("#confirmAcceptModal").hide(), void $("#confirmModal").modal("show");
    $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form"), $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan Tutorial ini?"), $("#confirmModal").find("#confirmAcceptModal").click(function () {
        $.ajax({
            url: base_url + "konten/simpan_konten",
            data: new FormData($("#form_konten")[0]),
            type: "post",
            cache: !1,
            contentType: !1,
            processData: !1,
            beforeSend: function () {
                $("#confirmModal .modalcontent").hide("fast"), $("#confirmModal .ajaxLoading").fadeIn("fast")
            },
            complete: function () {
                $("#confirmModal .ajaxLoading").hide("fast"), $("#confirmModal .modalcontent").fadeIn("fast")
            },
            success: function (n) {
                "" != n ? ($("#confirmModal").find(".modal-title").text("Kesalahan upload image thumbnail"), $("#confirmModal").find(".modal-body").text(n), $("#confirmModal").find("#confirmAcceptModal").hide()) : (get_konten(currentPage, rowPerPage, !0), cancel_form(), $("#confirmModal").modal("hide"))
            }
        })
    }), $("#confirmModal").modal("show")
}), $("#thumbnail").change(function () {
    if (this.files && this.files[0]) {
        var n = new FileReader;
        n.onload = function (n) {
            $("#image-thumb").attr("src", n.target.result)
        }, n.readAsDataURL(this.files[0])
    }
});