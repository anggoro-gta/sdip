var chartData = {};

function drawChartOnCanvas(chartTitle){
  new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: chartData,
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: chartTitle
      }
    }
  });
}

function getDataAndDrawChart(){
  $.ajax({
    url: base_url + 'd/lkpj/get_lkpj_data',
    method: 'POST',
    data: {
      unit: $("#unit").val(),
      id_kecamatan: $("#id_kecamatan").val(),
      unsur: $("#unsur").val(),
    },
    dataType: 'json',
    success: function (d) {
       chartData = {
            labels: d.tahun,
            datasets: [
              {
                data: d.data,
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850","#8e5ea2","#3cba9f","#e8c3b9","#c45850","#3cba9f","#3e95cd","#8e5ea2"]
              }
            ]            
        };
        
        drawChartOnCanvas(d.chartTitle);
    }
  });  
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
  return e.kategori ? 
  "<h5><b>" + e.text + "<b><br/><small style='text-transform:capitalize'>" + e.kategori + "</small></h5>" : 
  "<h5><small>" + e.text + "</small></h5>"
}

function show_lkpj_report(){
  getDataAndDrawChart();
}

$(document).ready(function() {
  $(window).resize(drawChartOnCanvas);
  
  initSelect2($("#unsur"), base_url + "d/lkpj/get_lkpj_elemen", function (e, a) {
    return {
        q: e.term,
        kategori: "unsur",
        page_limit: 10
    }
  }, null)
});

$("#unit").change(function(){
    if($(this).val()=='kecamatan'){
        $(".form_group_kecamatan").show();
        $("#id_kecamatan").data("select") && initSelect2($("#id_kecamatan"), base_url + "masterx/master/get_select2_unit", function (e, a) {
            return {
                q: e.term,
                kategori: "kecamatan",
                page_limit: 10
            }
        }, null);
    }else{
      $(".form_group_kecamatan").hide();
    }

});
