function getRekapNTP() {
    $.ajax({
        url: base_url + 'd/summary/get_ntp_summary',
        method: 'POST',
        data: {
            periode: $("#periode").val(),
            id_elemen: $("#unsur").val()
        },
        dataType: 'json',
        success: function (r) {
            var template = "<thead><th>No</th><th>Bulan</th><th>Nilai Tukar Petani</th></thead>";
            $("#data_table").empty();
            $.each(r.data, function (i, data) {
                template += templateDataTable(i, data)
            });
            $("#data_table").append(template);
        }
    });
}

function templateDataTable(i, d) {
    var tgl_data = new Date(d.tgl_data);
    var ntp = parseFloat(d.nilai_ntp);
    const month = tgl_data.toLocaleString('id-ID', { month: 'long' });
    return "<tr><td>" + (i+1) + "</td><td>" + month + "</td><td>" + ntp.toFixed(2) + " %</td></tr>"
}