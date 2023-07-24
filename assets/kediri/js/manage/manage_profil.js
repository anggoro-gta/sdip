var editor = CKEDITOR.replace('konten', {
    height: 300,
    width: 700,
});

$('#submit_konten').click( function() {
    $.ajax({
        url: base_url + "d/profil/save_profil_kabupaten",
        type: 'POST',
        dataType: 'json',
        data: {
            "konten": editor.getData(),
            "id_konten": $("#id_konten").val()
        },
        success: function(data) {
            alert("Data tersimpan");
        }
    });
});