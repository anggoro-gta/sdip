	function pagination(jumlahdatasemua, jumlahdatatampil, jumlahpagetampil, page, pagenumber, callback, isNeedCallback) {
		var totalpage = Math.ceil(jumlahdatasemua / jumlahdatatampil);
		var pagemid = Math.ceil(jumlahpagetampil / 2);
		//pagenumber
		pagenumber.empty();
		var paging = '<nav><ul class="pagination" style="margin:0px;">';
		paging += '<li ' + ((page == 1) ? 'class="disabled"' : '') + '>' + '<a href="#" id="start" ' + ((page != 1) ? 'class="halaman"' : '') + ' data-page="1"><span class="fa fa-angle-double-left"></span></a></li>';
		paging += '<li ' + ((page == 1) ? 'class="disabled"' : '') + '>' + '<a href="#" id="prev" ' + ((page != 1) ? 'class="halaman"' : '') + ' data-page="' + (page - 1) + '"><span class="fa fa-angle-left"></span></a></li>';
		if (page <= pagemid)
			for ( i = 1; i <= Math.min(jumlahpagetampil, totalpage); i++) {
				paging = paging + ('<li ' + ((i == page) ? 'class="active"' : '') + '>' + '<a href="#" ' + ((i != page) ? 'class="halaman"' : '') + ' data-page="' + i + '">' + i + ' <span class="sr-only"></span></a></li>');
			}
		else if (page >= totalpage - pagemid)
			for ( i = totalpage - jumlahpagetampil + 1; i <= totalpage; i++) {
				paging = paging + ('<li ' + ((i == page) ? 'class="active"' : '') + '>' + '<a href="#" ' + ((i != page) ? 'class="halaman"' : '') + ' data-page="' + i + '">' + i + ' <span class="sr-only"></span></a></li>');
			}
		else
			for ( i = page - pagemid; i <= page + pagemid; i++) {
				paging = paging + ('<li ' + ((i == page) ? 'class="active"' : '') + '>' + '<a href="#" ' + ((i != page) ? 'class="halaman"' : '') + ' data-page="' + i + '">' + i + ' <span class="sr-only"></span></a></li>');
			}
		paging = paging + '<li ' + ((page == totalpage) ? 'class="disabled"' : '') + '><a href="#" ' + ((page != totalpage) ? 'class="halaman"' : '') + ' data-page="' + (page + 1) + '"><span class="fa fa-angle-right"></span></a></li>';
		paging = paging + '<li ' + ((page == totalpage) ? 'class="disabled"' : '') + '><a href="#" ' + ((page != totalpage) ? 'class="halaman"' : '') + ' data-page="' + totalpage + '"><span class="fa fa-angle-double-right"></span></a></li>';
		paging = paging + '</ul></nav>';

		pagenumber.append(paging);
		$('li a.halaman').click(function (){
			pagination(jumlahdatasemua, jumlahdatatampil, jumlahpagetampil, $(this).data('page'), pagenumber, callback, false);
			callback($(this).data('page'), jumlahdatatampil, false);
		});
	}