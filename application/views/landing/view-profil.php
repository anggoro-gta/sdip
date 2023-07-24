<?php  $this->load->view('landing/header'); ?>

<section class="single-page">

	<section class="content content-white">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-content title-underline title-underline-invert font-black text-bold margin-top-no margin-bottom-lg">Profil</h2>
				</div>
				<div class="col-md-12">
					<?php if (count($posts)>0){
						echo $posts[0]->keterangan;
					} ?>
				</div>
			</div>
		</div>
	</section>
</section>

<?php  $this->load->view('landing/footer'); ?>