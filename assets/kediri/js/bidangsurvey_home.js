function getrekapbidang(){
    $.ajax({
        url: base_url + 'd/summary/get_bidangsurvey_summary',
        method: 'POST',
        data: {
            periode: $("#periode").val(),
            bidangsurvey: $("#unsur").val()
        },
        dataType: 'json',
        success: function (data) {                      
            var template = "<thead><th>No</th><th>Kecamatan</th><th>Jumlah UMKM</th><th>Satuan</th></thead>";
            $("#data_table").empty();            

            let template_table = "";
            for (let index = 0; index < data.length; index++) {
                template_table+="<tr><td>" + (index+1) + "</td><td>" + data[index]['kecamatan'] + "</td><td>" + data[index]['jumlah_umkm'] + "</td><td>Unit</td></tr>";
            }
            template+=template_table;            
           
            $("#data_table").append(template);
        }
    });
}
// function templateDataTable(i, d) {
//     var tgl_data = new Date(d.tgl_data);
//     var ntp = parseFloat(d.nilai_ntp);
//     const month = tgl_data.toLocaleString('id-ID', { month: 'long' });
//     return "<tr><td>" + (i+1) + "</td><td>" + month + "</td><td>" + ntp.toFixed(2) + " %</td></tr>"
// }