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