<?php $this->load->view('header') ?>
<style>
    .select2-results__option.select2-results__option--highlighted small{
        color: #fff;
    }
    .ajaxLoading {
        background: #FFF url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDQiIGhlaWdodD0iNDQiIHZpZXdCb3g9IjAgMCA0NCA0NCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHJva2U9IiM5OTkiPiAgICA8ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHN0cm9rZS13aWR0aD0iMiI+ICAgICAgICA8Y2lyY2xlIGN4PSIyMiIgY3k9IjIyIiByPSIxIj4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJyIiAgICAgICAgICAgICAgICBiZWdpbj0iMHMiIGR1cj0iMS40cyIgICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAyMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMTY1LCAwLjg0LCAwLjQ0LCAxIiAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIgLz4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJzdHJva2Utb3BhY2l0eSIgICAgICAgICAgICAgICAgYmVnaW49IjBzIiBkdXI9IjEuNHMiICAgICAgICAgICAgICAgIHZhbHVlcz0iMTsgMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMywgMC42MSwgMC4zNTUsIDEiICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgICAgPC9jaXJjbGU+ICAgICAgICA8Y2lyY2xlIGN4PSIyMiIgY3k9IjIyIiByPSIxIj4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJyIiAgICAgICAgICAgICAgICBiZWdpbj0iLTAuOXMiIGR1cj0iMS40cyIgICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAyMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMTY1LCAwLjg0LCAwLjQ0LCAxIiAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIgLz4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJzdHJva2Utb3BhY2l0eSIgICAgICAgICAgICAgICAgYmVnaW49Ii0wLjlzIiBkdXI9IjEuNHMiICAgICAgICAgICAgICAgIHZhbHVlcz0iMTsgMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMywgMC42MSwgMC4zNTUsIDEiICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgICAgPC9jaXJjbGU+ICAgIDwvZz48L3N2Zz4=) no-repeat 50% 50%;
        height: 200px;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 9;
    }
    .ajaxLoading h4{
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        text-align: center;
        color: #c1c1c1;
    }
    #data_table tr{
        transition: background-color .5s linear; 
        -moz-transition: background-color .5s linear;  
        -webkit-transition: background-color .5s linear; 
        -ms-transition: background-color .5s linear; 
        background-color : #FFFFFF;
    }
    #data_table tr.edited{
        background-color : #B3E5FC;
    }
</style>
<ol class="breadcrumb">
   <li><a href="<?php echo BASE_URL().'apps' ?>">Home</a></li>
    <li class="active">Halaman Profil</li>
</ol>
<div class="row">
    <div class="col-md-3">
        <h4>Profil Kabupaten</h4>
    </div>
    <div class="col-md-9" id="data-loading" style="display: none;">
        <div class="ajaxLoading">
            <h4>Mohon tunggu sebentar . . .</h4>
        </div>
    </div>        
</div>

<form id="form_konten">
    <?php if (count($posts)>0 AND $posts[0]->id_konten){ ?>
        <input type=hidden id="id_konten" value="<?php echo $posts[0]->id_konten; ?>"/>
    <?php } ?>
    <textarea cols="180" id="konten" name="konten" rows="10" ><?php echo count($posts)>0?$posts[0]->keterangan:""; ?></textarea><br/>
    <button id="submit_konten" class="btn btn-twitter" type="button"><i class="fa fa-floppy-o"></i> Simpan</button>
<form>
<div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
<?php $this->load->view('footer') ?>