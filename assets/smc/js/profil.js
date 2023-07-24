$(".inline-edit").hover(function () {
    "none" == $(this).find(".input-group").css("display") && $(this).find("a").fadeIn(200)
}, function () {
    $(this).find("a").fadeOut(100)
}), $(".inline-edit a").click(function () {
    $(this).parent().find(".input-group").show(), $(this).hide(), $(this).parent().find("span").hide()
}), $(".inline-edit .input-group .btn-cancel").click(function () {
    $(this).parent().parent().parent().find(".input-group").hide(), $(this).parent().parent().parent().fadeIn(200), $(this).parent().parent().parent().find("span").show()
}), $(".inline-edit .input-group .btn-submit").click(function () {
    var n = $(this).parent().parent().find(".form-control").attr("name"),
        t = $(this).parent().parent().find(".form-control").val(),
        a = $(this);
    $.ajax({
        url: base_url + "apps/profil_update",
        type: "post",
        data: $.param({
            name: n,
            value: t
        }),
        success: function (n) {
            a.parent().parent().parent().find("span").text(n), a.parent().parent().find(".form-control").val(n), a.parent().parent().parent().find(".input-group").hide(), a.parent().parent().parent().fadeIn(200), a.parent().parent().parent().find("span").show()
        }
    })
}), $("#foto").click(function () {
    $("[name='file_foto']").click()
}), $("[name='file_foto']").change(function () {
    $("[name='file_foto']").get(0).files.length > 0 && $.ajax({
        url: base_url + "apps/profil_foto",
        type: "post",
        processData: !1,
        contentType: !1,
        data: new FormData($("#form_foto")[0]),
        success: function (n) {
            var t = JSON.parse(n);
            "1" == t.status ? location.reload() : alert(t.message)
        }
    })
});