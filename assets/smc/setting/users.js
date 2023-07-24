function search() {
    get_user(currentPage, rowPerPage, !0)
}

function get_user(e, t, a) {
    $.ajax({
        url: base_url + "masterx/master/get_users",
        type: "post",
        data: $.param({
            search: $("#filter_keyword").val(),
            page: e,
            rowPerPage: t
        }),
        success: function (r) {
            var n = JSON.parse(r);
            if (0 == n.jumlah) return $("#tabel_nothing").show(), $("#data_table").hide(), void $("#page-content").hide();
            $("#tabel_nothing").hide(), $("#data_table").show(), $("#page-content").show(), 1 == a && pagination(n.jumlah, t, 5, e, $(".pagination"), get_user, !1), $("#data_table").empty(), template = "<thead><th>Nama</th><th>Unit Kerja</th><th>Keterangan</th><th></th></thead>", $.each(n.data, function (e, t) {
                template += templateDataTable(t, e + 1, 1, null)
            }), $("#data_table").append(template), $("[data-toggle='tooltip']").tooltip(), $("[data-toggle='popover']").popover({
                trigger: "hover"
            }), currentDataPage = n.data, currentPage = e
        },
        error: function () {},
        beforeSend: function () {},
        complete: function () {}
    })
}

function buat_user() {
    $("#form_user").find("[name='is_user']").remove(), $("#form_user")[0].reset(), $("#content_table_user").is(":visible") && ($("#content_table_user").hide("fast"), $("#content_form_user").show("fast"))
}

function cancel_form() {
    $("#unit_kerja").slideUp("fast"), $("#unit_desa").slideUp("fast"), $("#form_user").find("[name='is_user']").remove(), $("#form_user")[0].reset(), $("#content_form_user").is(":visible") && ($("#content_table_user").show("fast"), $("#content_form_user").hide("fast"))
}

function findFromTable(e, t) {
    for (var a = 0; a < e.length; a++)
        if (e[a].id_user == t) return void(currentUser = e[a]);
    return null
}

function templateDataTable(e) {
    return "<tr><td>" + e.nama + "<code>(" + e.role + ")</code><br/>Username/Email : <code>" + e.user_name + "</code></td><td class='data'>" + template_unit_kerja(e) + "</td><td class='data'>No Ktp :" + e.no_ktp + "<br/>No Hp :" + e.no_hp + "<br/>Alamat :" + e.alamat + "<br/><td><div class='dropdown pull-right'><a class='dropdown-toggle btn btn-table' id='drop4' role='button' data-toggle='dropdown' href='#'>Action <b class='caret'></b></a><ul id='menu1' class='dropdown-menu pull-right' role='menu' aria-labelledby='drop4'><li><a href='#' onclick=\"edit_user(this,'" + e.id_user + "')\"  class='btn-edit-post' ><i class='fa fa-pencil'></i> Edit</a></li><li><a href='#' onclick=\"banned_user(this,'" + e.id_user + "')\"  class='btn-edit-post' ><i class='fa fa-remove'></i> Banned</a></li></ul></div></td></tr>"
}

function edit_user(e, t) {
    findFromTable(currentDataPage, t), $("#form_user").find("[name='id_user']").remove(), $("#form_user")[0].reset(), $("#content_table_user").is(":visible") && ($("#content_table_user").hide("fast"), $("#content_form_user").show("fast", function () {
        $("#form_user").prepend("<input type='hidden' value='" + currentUser.id_user + "' name='id_user' />"), $("#form_user").find("[name='nama']").val(currentUser.nama), $("#form_user").find("[name='email']").val(currentUser.email), $("#form_user").find("[name='id_role']").val(currentUser.id_role), $("#form_user").find("[name='kategori_unit']").val(currentUser.kategori_unit), "desa" == currentUser.kategori_unit ? ($("#form_user #unit_kerja label").text("Kecamatan"), id_unit_kerja = currentUser.id_parent_unit, $("#form_user").find("[name='id_unit_kerja']").val(currentUser.id_parent_unit), $("#form_user [name='id_unit_kerja']").append(templateoption(currentUser.id_parent_unit, currentUser.nama_parent_unit, !0)).trigger("change"), $("#unit_kerja").slideDown("fast"), $("#form_user").find("[name='id_unit']").val(currentUser.id_unit_kerja), $("#form_user [name='id_unit']").append(templateoption(currentUser.id_unit_kerja, currentUser.nama_unit, !0)).trigger("change"), $("#unit_desa").slideDown("fast")) : "kecamatan" == currentUser.kategori_unit || "skpd" == currentUser.kategori_unit ? ($("#unit_desa").slideUp("fast"), $("#unit_kerja label").text("skpd" == currentUser.kategori_unit ? "SKPD" : "Kecamatan"), $("#form_user").find("[name='id_unit_kerja']").val(currentUser.id_unit_kerja), $("#form_user [name='id_unit_kerja']").append(templateoption(currentUser.id_unit_kerja, currentUser.nama_unit, !0)).trigger("change"), $("#unit_kerja").slideDown("fast")) : ($("#unit_kerja").slideUp("fast"), $("#unit_desa").slideUp("fast")), $("#form_user").find("[name='password']").val(currentUser.password), $("#form_user").find("[name='no_ktp']").val(currentUser.no_ktp), $("#form_user").find("[name='no_hp']").val(currentUser.no_hp), $("#form_user").find("[name='no_telp']").val(currentUser.no_telp), $("#form_user").find("[name='alamat']").val(currentUser.alamat), $("#form_user").find("[name='is_aktif']").prop("checked", "1" == currentUser.is_aktif), currentUser.foto && $("#form_user").find("[name='file_foto']").parent().find("img").attr("src", base_url + "upload/" + currentUser.foto)
    }))
}

function template_unit_kerja(e) {
    return "desa" == e.kategori_unit ? "Desa " + e.nama_unit + ", Kecamatan " + e.nama_parent_unit : "kecamatan" == e.kategori_unit ? "Kecamatan " + e.nama_unit : "kabupaten" == e.kategori_unit ? e.nama_unit : "skpd" == e.kategori_unit ? "SKPD " + e.nama_unit : ""
}

function templateselect(e) {
    return e.id ? "<h5><b>" + e.text + "</b><br/><small style='text-transform:capitalize'>" + e.kategori + "</small></h5>" : "<h5><small>" + e.text + "</small></h5>"
}

function templateoption(e, t, a) {
    return "<option value=" + e + " " + (a ? "selected='selected'" : "") + ">" + t + "</option>"
}

function intToChar(e) {
    return (e >= 26 ? idOf((e / 26 >> 0) - 1) : "") + "abcdefghijklmnopqrstuvwxyz" [e % 26 >> 0]
}
var currentPage = 1,
    rowPerPage = 10,
    currentDataPage = [],
    currentUser = null;
get_user(currentPage, rowPerPage, !0);
var id_unit_kerja = "";
$("#confirmModal").on("hidden.bs.modal", function (e) {
    $("#confirmModal").find("#confirmAcceptModal").unbind("click"), $("#confirmModal").find("#confirmAcceptModal").show()
}), $("#form_user [name='id_unit_kerja']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_unit",
        dataType: "json",
        data: function (e, t) {
            return {
                q: e.term,
                kategori: "desa" == $("#form_user [name='kategori_unit']").val() ? "kecamatan" : $("#form_user [name='kategori_unit']").val(),
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
}).on("select2:select", function (e) {
    id_unit_kerja = e.params.data.id, $("#form_user [name='id_unit']").empty().trigger("change")
}), $("#form_user [name='id_unit']").select2({
    ajax: {
        url: base_url + "masterx/master/get_select2_unit",
        dataType: "json",
        data: function (e, t) {
            return {
                q: e.term,
                kategori: "desa",
                id_parent: id_unit_kerja,
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
}), $("#foto").click(function () {
    $("#form_user [name='file_foto']").click()
}), $("#form_user [name='file_foto']").change(function () {
    if (this.files && this.files[0]) {
        var e = $(this).parent().find("img"),
            t = new FileReader;
        t.onload = function (t) {
            e.attr("src", t.target.result)
        }, t.readAsDataURL(this.files[0])
    }
}), $("#form_user [name='kategori_unit']").change(function () {
    $("#form_user [name='id_unit_kerja']").empty().trigger("change"), $("#form_user [name='id_unit']").empty().trigger("change"), "desa" == $("#form_user [name='kategori_unit']").val() ? ($("#unit_kerja label").text("Kecamatan"), $("#unit_kerja").slideDown("fast"), $("#unit_desa").slideDown("fast")) : "kecamatan" == $("#form_user [name='kategori_unit']").val() || "skpd" == $("#form_user [name='kategori_unit']").val() ? ($("#unit_kerja label").text("kecamatan" == $("#form_user [name='kategori_unit']").val() ? "Kecamatan" : "SKPD"), $("#unit_kerja").slideDown("fast"), $("#unit_desa").slideUp("fast")) : ($("#unit_desa").slideUp("fast"), $("#unit_kerja").slideUp("fast"))
}), $("#form_user").submit(function (e) {
    if (e.preventDefault(), ("desa" != $("#form_user [name='kategori_unit']") || $("#form_user [name='id_unit']").val() && $("#form_user [name='id_unit_kerja']").val()) && ("kabupaten" == $("#form_user [name='kategori_unit']") || $("#form_user [name='id_unit_kerja']").val()))
        if ($("#form_user [name='password']").val().length < 6) {
            t = $("<div class='alert alert-danger' style='display:none;'><strong>Kesalahan Formulir</strong> Password minimal 6 karakter</div>").appendTo($("#notif"));
            t.slideDown("fast", function () {
                setTimeout(function () {
                    t.slideUp("fast")
                }, 3e3)
            })
        } else $("#confirmModal").find(".modal-title").text("Konfirmasi Submit Form"), $("#confirmModal").find(".modal-body").text("Apakah anda yakin akan menyimpan akun ini?"), $("#confirmModal").find("#confirmAcceptModal").click(function () {
            $.ajax({
                url: base_url + "masterx/master/simpan_user",
                data: new FormData($("#form_user")[0]),
                type: "post",
                cache: !1,
                contentType: !1,
                processData: !1,
                success: function () {
                    get_user(currentPage, rowPerPage, !0), cancel_form(), $("#confirmModal").modal("hide")
                }
            })
        }), $("#confirmModal").modal("show");
    else {
        var t;
        (t = $("<div class='alert alert-danger' style='display:none;'><strong>Kesalahan Formulir</strong> Unit Kerja tidak boleh kosong</div>").appendTo($("#notif"))).slideDown("fast", function () {
            setTimeout(function () {
                t.slideUp("fast")
            }, 3e3)
        })
    }
});