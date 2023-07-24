
		</div>  <!-- /.content-wrapper -->
	</div>	
</div>
<div class="row no-print"></div>
<script src="<?php echo BASE_URL() ?>assets/smc/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo BASE_URL() ?>assets/smc/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_URL() ?>assets/smc/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL() ?>assets/smc/dist/js/app.min.js"></script>
<?php if(isset($scripts)){ ?>
	<?php if(is_array($scripts)){ ?>
		<?php foreach ($scripts as $key => $value) { ?>
		<script src="<?php echo BASE_URL().$value ?>"></script>
		<?php } ?>
	<?php } else { ?>
		<script src="<?php echo BASE_URL().$scripts ?>"></script>
	<?php } ?>
<?php } ?>
<?php if(isset($scripts_post)){ ?>
	<?php if(is_array($scripts_post)){ ?>
		<?php foreach ($scripts_post as $key => $value) { ?>
		<script src="<?php echo $value ?>"></script>
		<?php } ?>
	<?php } else { ?>
		<script src="<?php echo $scripts_post ?>"></script>
	<?php } ?>
<?php }?>
</body>
</html>
		
		