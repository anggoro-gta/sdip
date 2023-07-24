	$(function(){

	  function stripTrailingSlash(str) {
	    if(str.substr(-1) == '/') {
	      return str.substr(0, str.length - 1);
	    }
	    return str;
	  }

	  var url = window.location.pathname;  
	  //var activePage = stripTrailingSlash(url);
	  var activePage = 'http://175.45.187.253'.concat(url);

	  $('.nav > li > a').each(function(){  

	    var currentPage = $(this).attr('href');
	    if (activePage == currentPage) {
	      $(this).parent().addClass('active'); 
	    }

	  });

	  $('.nav > li > ul > li > a').each(function(){  

	    var currentPage = $(this).attr('href');
	    if (activePage == currentPage) {
	      $(this).parent().addClass('active'); 
	      $(this).parent().parent().addClass('in');
	      $(this).parent().parent().prev().find('i.fa-angle-down').addClass('fa-flip-vertical');
	    }

	  });

	});  

$( document ).ready(function() {

	/* TEST MINIMIZE SIDEBAR
	$('#hideme').click(function(event) {
		if($('.side-nav').width() !== 70){
			$('.side-nav').css('width', '70px');
			$('#wrapper').css('padding-left', '70px');
			$('.logo').css('width', '70px');
		}else{
			$('.side-nav').css('width', '225px');
			$('#wrapper').css('padding-left', '225px');
			$('.logo').css('width', '225px');
		}
		//$('#wrapper').css('padding-left', '10px');
	});*/

	 $('[rel="tooltip"]').tooltip({container: 'body'});//tooltip

	 //refresh button
	 $('.refresh').click(function(e) {

	 	var c = $(this).parents('.content');
	 	var p = $(this).parents('.header');
	 	var loading = $(' <div class="loading"><i class="fa fa-refresh fa-spin"></i></div>');

	 	loading.appendTo(c);
	 	loading.appendTo(p);
	 	loading.fadeIn();
	 	
	    setTimeout(function() {
	        loading.fadeOut();
	      }, 2000);
      
     	 e.preventDefault();

	 });

	 //modal
	 //$('#modalDemo').modal();

	 //accordion arrow
	 $('.panel-title').click(function(e) {
	 	
	 	var caCordion = $(this).children('.fa-angle-right');
	 	caCordion.toggleClass('rotate');

	 	e.preventDefault();
	 });

	 $('.anchor-dropdown').click(function(e) {
	 	var p = $(this).parent();
	 	var ps = p.siblings();
	 	var arrow = $(this).children('.fa-angle-down');
	 	var dropOpen = ps.children('#drop-sidebar');

	 	arrow.toggleClass('fa-flip-vertical');
	 	if(dropOpen.hasClass('in')){
	 		//alert('hasIn');
	 	}else{
	 		//alert('not has');
	 	}

	 	e.preventDefault();
	 });

	 //select2
	 $("#e1").select2();

	 $("#e13").select2();

	 $("#example").select2({
	    allowClear: true,
	    placeholder: 'Contoh auto tagging select2',
	    tokenSeparators: [",", " "],
	    tags: [{id: 1, text: "Ini tagging dari data tags"}]
	});

	 //match height
	 $(function(){
	 	$('.tile-alumni > .content-alumni').matchHeight();
	 })

	 //datetimepicker
	 $('.datetimepickertest').datetimepicker({
	 	 minDate: '1/1/2010',
    	 maxDate: '1/1/2020',
    	 defaultDate: '7/1/2014'
	 });

	 $('.datetimepickertestnoday').datetimepicker({
	 	//pickTime: false
	 	pickDay: false
	 });

	 $('.setDateTest').click(function(){
	 	$('.datetimepickertest').data("DateTimePicker").setDate("10/23/2013");
	 })



	//data table
	$('#example-table').DataTable(); 

	//tinymce
	tinymce.init({
	    selector: "textarea#cobaTinyMCE",
	    skin: 'light'
	});

	//growl notification
	$('.coba-growl').click(function(event) {
		$.growl({
				title: "<h4>Pesan baru!</h4>",
				message: "Anda mendapatkan 1 pesan baru dari Administrator",
				url: "https://ptiik.ub.ac.id"
			},{
				timer: 	3000,
				type: 'base',
				mouse_over: 'pause',
				offset: {
					x: 0,
					y: 60
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutRight'
				}
			});
	});

	$('.coba-growl-style-orange').click(function(event) {
		$.growl({
				title: "<h4>Pesan baru!</h4>",
				message: "Anda mendapatkan 1 pesan baru dari Administrator",
				url: "https://ptiik.ub.ac.id"
			},{
				timer: 	3000,
				type: 'theme',
				mouse_over: 'pause',
				offset: {
					x: 0,
					y: 60
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutRight'
				}
			});
	});

	$('.coba-growl-style-success').click(function(event) {
		$.growl({
				title: "<h4>Pesan baru!</h4>",
				message: "Anda mendapatkan 1 pesan baru dari Administrator",
				url: "https://ptiik.ub.ac.id"
			},{
				timer: 	3000,
				type: 'success',
				mouse_over: 'pause',
				offset: {
					x: 0,
					y: 60
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutRight'
				}
			});
	});

	$('.coba-growl-style-red').click(function(event) {
		$.growl({
				title: "<h4>Pesan baru!</h4>",
				message: "Anda mendapatkan 1 pesan baru dari Administrator",
				url: "https://ptiik.ub.ac.id"
			},{
				timer: 	3000,
				type: 'red',
				mouse_over: 'pause',
				offset: {
					x: 0,
					y: 60
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutRight'
				}
			});
	});

	$('.coba-growl-style-blue').click(function(event) {
		$.growl({
				title: "<h4>Pesan baru!</h4>",
				message: "Anda mendapatkan 1 pesan baru dari Administrator",
				url: "https://ptiik.ub.ac.id"
			},{
				timer: 	3000,
				type: 'blue',
				mouse_over: 'pause',
				offset: {
					x: 0,
					y: 60
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutRight'
				}
			});
	});

	$('.coba-growl-style-dark').click(function(event) {
		$.growl({
				title: "<h4>Pesan baru!</h4>",
				message: "Anda mendapatkan 1 pesan baru dari Administrator",
				url: "https://ptiik.ub.ac.id"
			},{
				timer: 	3000,
				type: 'dark',
				mouse_over: 'pause',
				offset: {
					x: 0,
					y: 60
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutRight'
				}
			});
	});

	$('.coba-growl-image').click(function(event) {
		$.growl({
				icon: "img/newmail.png",
				message: "Anda mendapatkan pesan dari administrator",
				url: "https://ptiik.ub.ac.id"
			},{
				icon_type: 'image',
				timer: 	900000,
				type: 'base',
				mouse_over: 'pause',
				offset: {
					x: 0,
					y: 60
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutRight'
				}
			});
	});

});