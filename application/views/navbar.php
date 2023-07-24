<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li>
            <div class="profil-splash">
                <img src="<?php echo BASE_URL() . ($this->session->userdata('smc_avatar') ? "assets/kediri/upload/" . $this->session->userdata('smc_avatar') : "assets/smc/dist/img/avatar.png"); ?>"
                     alt="" width="50px">
                <div class="detail">
                    <h3><?php echo $this->session->userdata('smc_nama'); ?>
                        <br><small><?php echo ucwords($this->session->userdata('smc_role')); ?></small></h3>
                </div>
            </div>
        </li>
        <li class="active">
            <a href="<?php echo BASE_URL() . 'apps'; ?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL() . 'profil/manage'; ?>"><i class="fa fa-fw fa-file-text"></i> Halaman
                Profil</a>
        </li>

        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#drop-sidebar" class="anchor-dropdown"><i
                        class="fa fa-fw fa-clipboard"></i> Data Survei <i class="fa fa-angle-down pull-right"></i></a>
            <ul id="drop-sidebar" class="collapse">

                <li>
                    <a href="<?php echo BASE_URL() . 'survei'; ?>"><i class="fa fa-fw fa-edit"></i> Survei </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL() . 'survei/rekap'; ?>"><i class="fa fa-fw fa-pie-chart"></i>
                        Geospasial </a>
                </li>

            </ul>
        </li>
        <li>
            <a href="<?php echo BASE_URL() . 'rekap'; ?>"><i class="fa fa-fw fa-pie-chart"></i> Data Statistik </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL() . 'ntp'; ?>"><i class="fa fa-fw fa-cubes"></i> Data NTP </a>
        </li>
    </ul>
</div>
<!-- /.navbar-collapse -->
</nav>
<div id="page-wrapper" class="page-wrapper" style="background:#fff">
    <div class="container-fluid">