<?php $this->load->view('header') ?>
<ol class="breadcrumb">
   <li><a href="<?php echo BASE_URL().'apps' ?>">Home</a></li>
  <li class="active"><a href="#">Profil</a></li>
</ol>

<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-3">
                <img src="<?php echo BASE_URL()."assets/kediri/upload/".($this->session->userdata('smc_avatar') ? $this->session->userdata('smc_avatar') : "assets/smc/dist/img/avatar.png");?>" style="width:100%;" id="foto_profil"/>
                <a href="#" class="btn btn-primary" id="foto" style="position:absolute;left: 15px;right: 15px;bottom: 0;margin: 0px;">ubah foto</a>
                <form id="form_foto" style="display:none;"><input type="file" name="file_foto" accept="image/*"></form>
            </div>
            <div class="col-sm-9">
                <div class="inline-edit">
                    <span style="font-family: Raleway,sans serif;font-weight: 200;font-size: 24px;"><?php echo $this->session->userdata('smc_nama');?></span>
                    <a href="#" style="display:none;"><i class="fa fa-pencil"></i></a>
                    <div class="input-group" style="display:none;">
                        <input type="text" name="nama" class="form-control" value="<?php echo $this->session->userdata('smc_nama'); ?>" >
                        <div class="input-group-btn">
                            <button class="btn btn-secondary btn-cancel" type="button"><i class="fa fa-remove"></i></button>
                        </div>
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-submit" type="button"><i class="fa fa-check"></i></button>
                        </div>
                    </div>
                </div>
                <h5><?php echo $this->session->userdata('smc_role'); ?></h5>
                <hr/>
                <div class="row form-group">
                    <!--Email-->
                    <div class="col-sm-2">Email/Username</div>
                    <div class="col-sm-10 inline-edit">
                        <span><?php echo $this->session->userdata('smc_email'); ?></span>
                        <a href="#" style="display:none;"><i class="fa fa-pencil"></i></a>
                        <div class="input-group" style="display:none;">
                            <input type="email" name="email" class="form-control" value="<?php echo $this->session->userdata('smc_email'); ?>" >
                            <div class="input-group-btn">
                                <button class="btn btn-secondary btn-cancel" type="button"><i class="fa fa-remove"></i></button>
                            </div>
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-submit" type="button"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--No KTP-->
                <div class="row form-group">
                    <div class="col-sm-2">No KTP</div>
                    <div class="col-sm-10 inline-edit">
                        <span><?php echo $user->no_ktp; ?></span>
                        <a href="#" style="display:none;"><i class="fa fa-pencil"></i></a>
                        <div class="input-group" style="display:none;">
                            <input type="text" name="no_ktp" class="form-control" value="<?php echo $user->no_ktp; ?>" >
                            <div class="input-group-btn">
                                <button class="btn btn-secondary btn-cancel" type="button"><i class="fa fa-remove"></i></button>
                            </div>
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-submit" type="button"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--No KTP-->
                <div class="row form-group">
                    <div class="col-sm-2">No HP</div>
                    <div class="col-sm-10 inline-edit">
                        <span><?php echo $user->no_hp; ?></span>
                        <a href="#" style="display:none;"><i class="fa fa-pencil"></i></a>
                        <div class="input-group" style="display:none;">
                            <input type="text" name="no_hp" class="form-control" value="<?php echo $user->no_hp; ?>" >
                            <div class="input-group-btn">
                                <button class="btn btn-secondary btn-cancel" type="button"><i class="fa fa-remove"></i></button>
                            </div>
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-submit" type="button"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <!--No Telp-->
                    <div class="col-sm-2">No Telp</div>
                    <div class="col-sm-10 inline-edit">
                        <span><?php echo $user->no_telp; ?></span>
                        <a href="#" style="display:none;"><i class="fa fa-pencil"></i></a>
                        <div class="input-group" style="display:none;">
                            <input type="text" name="no_telp" class="form-control" value="<?php echo $user->no_telp; ?>" >
                            <div class="input-group-btn">
                                <button class="btn btn-secondary btn-cancel" type="button"><i class="fa fa-remove"></i></button>
                            </div>
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-submit" type="button"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Alamat-->
                <div class="row form-group">
                    <div class="col-sm-2">Alamat</div>
                    <div class="col-sm-10 inline-edit">
                        <span><?php echo $user->alamat; ?></span>
                        <a href="#" style="display:none;"><i class="fa fa-pencil"></i></a>
                        <div class="input-group" style="display:none; width:100%;">
                            <textarea type="text" name="alamat" class="form-control"><?php echo $user->alamat; ?></textarea>
                            <div>
                                <button class="btn btn-secondary btn-cancel" type="button"><i class="fa fa-remove"></i></button>
                                <button class="btn btn-primary btn-submit" type="button"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Unit-->
                <div class="row form-group">
                    <div class="col-sm-2">Unit Kerja</div>
                    <div class="col-sm-10">
                    <?php 
                        if($this->session->userdata('smc_kategori_unit')=="kabupaten" || $this->session->userdata('smc_kategori_unit')=="skpd"){
                            echo ucwords($this->session->userdata('smc_nama_unit_kerja'));
                        }
                        else if($this->session->userdata('smc_kategori_unit')=="kecamatan"){
                            echo "Kecamatan ". ucwords($this->session->userdata('smc_nama_unit_kerja'));
                        }
                        else if($this->session->userdata('smc_kategori_unit')=="desa"){
                            echo "Kecamatan ". ucwords($this->session->userdata('smc_nama_unit_kerja')) . ", Desa " . ucwords($this->session->userdata('smc_nama_parent_unit'));
                        }
                       else {
                           echo " - ";    
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>