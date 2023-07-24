<?php  $this->load->view('landing/header'); ?>
<section class="single-page">
	<section class="content content-white">
	<div class="container">
		<div class="col-md-12">
			<h2 class="title-content title-underline title-underline-invert font-black text-bold margin-top-no margin-bottom-lg">Galeri</h2>
			<div class="post-content padding-top-lg">
				<div class="row row-flex">
					<div class="col-md-12 padding-bottom-md">Berikut merupakan komoditas unggulan dari berbagai daerah di Kabupaten Kediri.</div>
					<?php
					if(isset($posts) && $posts):
						foreach($posts->result() as $value):						
							?>												
							<div class="col-md-4">
								<div class="info-box info-box-style-1 margin-bottom-md">
									<div class="info-box-img text-center">
										<img src="<?php echo $this->config->item('file_upload').$value->img_thumb; ?>" class="img img-responsive img-responsive-center" />
									</div>
									<div class="info-box-text">
										<div class="title margin-bottom-md">
											<div class="icon font-yellow"><span class="fa fa-check"></span></div>
											<h5 class="title-text"><?php echo $value->judul ?></h5>
										</div>
										<div class="text-showup">
											<p>
												<?php echo $value->keterangan ?>
											</p>
											<a href="#" class="info-box-link btnvideo" data-judul="<?php echo $value->judul ?>" 
											data-narasi="<?php echo $value->keterangan ?>"
											data-video="<?php echo $this->config->item('file_upload').$value->video_loc; ?>"	>lihat video <span class="fa fa-chevron-right font-yellow"></span></a>
										</div>
									</div>
								</div>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
			</div>
		</div>
	</div>
	</section>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="modalVideo">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header" style="background-color:#002e5b;color:#fff">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><div id="video_judul"></div></h4>
	  </div>
	  <div class="modal-body">
		<div class="row">
			<div id="video_narasi"></div>
			<div id="video_view"></div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Tutup</button>
	  </div>
	</div>
  </div>
</div>
			
<?php  $this->load->view('landing/footer'); ?>

<script>
	$('.btnvideo').click(function (){
		var video = $(this).data("video");
		var narasi = $(this).data("narasi");
		var judul = $(this).data("judul");
		//alert(url_ws);
		$("#video_view").html('<video controls class="col-md-12" id="video"><source src="'+video+'" type="video/mp4"></video>');
		$("#video_narasi").html('<div class="col-md-12">'+narasi+'</div>');
		$("#video_judul").html(judul);
		
		
		$('#modalVideo').modal({
			'backdrop':'',
			'keyboard':false,
			'show':true,
		});
	});
	$(function(){
		$('#modalVideo').modal({
			show: false
		}).on('hidden.bs.modal', function(){
			$(this).find('video')[0].pause();
		});		
	});
</script>
