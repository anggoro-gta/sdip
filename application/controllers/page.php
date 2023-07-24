<?php class page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('conf');
    }

    public function landing()
    {
        $data['view_slide'] = '1';
        $this->load->view('landing/index.php', $data);
    }

    public function index()
    {
        $data['view_slide'] = '1';
        $this->load->view('landing/index.php', $data);
    }

    public function galeri()
    {
        $this->load->model('info');
        $data["smenu"] = 'galeri';
        $data['posts'] =  $this->info->get_konten('', 'video');
        $this->load->view('landing/view-galeri.php', $data);
    }

    public function profil()
    {
        $this->load->model('info');
        $data["smenu"] = 'profil';
        $data['posts'] =  $this->info->get_konten('', 'profil_kabupaten')->result();
        $this->load->view('landing/view-profil.php', $data);
    }

    // menampilkan halaman data statistik
    public function statistik()
    {
        $data["smenu"] = 'statistik';
        $data['styles'] = array("assets/smc/plugins/izi-modal/iziModal.min.css", "assets/smc/plugins/select2/select2.min.css");
        $data['scripts'] = array(
            "assets/smc/plugins/mixitup/mixitup.min.js",
            "assets/smc/plugins/izi-modal/iziModal.min.js",
            "assets/smc/plugins/select2/select2.min.js",
            "assets/smc/plugins/chartjs/Chart.min.js",
            "assets/smc/js/treeTable.js",
            "assets/smc/plugins/slimScroll/jquery.slimscroll.min.js",
            "assets/smc/js/datum.js"
        );
        $data['scripts_post'] = array("https://maps.googleapis.com/maps/api/js?key=AIzaSyALcj9sgW4DClNXnTDHvBzPxSImtJQixTM&callback=initMap");
        $data["unsur_profil"] =  $this->conf->get_unsur_home();
        $data["unsur"] =  $this->conf->get_elemen_parent("0");
        $data['unit'] =  $this->conf->get_unit(null, null, null, "Kabupaten Kediri");
        $this->load->view('landing/view-data-statistik.php', $data);
    }

    // menampilkan halaman lkpj
    public function lkpj()
    {
        $data["smenu"] = 'lkpj';
        $data['styles'] = array(
            "assets/smc/plugins/izi-modal/iziModal.min.css",
            "assets/smc/plugins/select2/select2.min.css"
        );
        $data['scripts'] = array(
            "assets/smc/plugins/mixitup/mixitup.min.js",
            "assets/smc/plugins/izi-modal/iziModal.min.js",
            "assets/smc/plugins/select2/select2.min.js",
            "assets/smc/plugins/chartjs/Chart.min.js",
            "assets/smc/js/treeTable.js",
            "assets/smc/plugins/slimScroll/jquery.slimscroll.min.js",
            "assets/kediri/js/lkpj.js"
        );
        $data['scripts_post'] = array("");
        $data["unsur_profil"] =  $this->conf->get_unsur_home();
        $data["unsur"] =  $this->conf->get_elemen_parent("0");
        $data['unit'] =  $this->conf->get_unit(null, null, null, "Kabupaten Kediri");
        $this->load->view('landing/view-data-lkpj.php', $data);
    }

    public function ntp()
    {
        $this->load->model('m_survei');
        $this->load->model('m_elemen');
        $this->load->model('m_ntp');

        $data["smenu"] = 'ntp';
        $data['styles'] = array(
            "assets/smc/select2/css/select2.min.css"
        );
        $data['scripts'] = array(
            "assets/smc/select2/js/select2.full.min.js",
            "assets/kediri/js/ntp_home.js"
        );

        $ntp_elements = array();
        foreach ($this->m_ntp->get_distinct_ntp_element() as $element) {
            array_push($ntp_elements, $element->id_elemen);
        }
        $ntp_years = $this->m_ntp->get_distinct_ntp_year();
        $data['unsurs'] =  $this->m_elemen->get_elemen($ntp_elements);
        $data['ntp_years'] =  $ntp_years;

        $this->load->view('landing/view-data-ntp.php', $data);
    }

    // menampilkan halaman survei
    public function survei()
    {
        $this->load->model('m_survei');
        $data["smenu"] = 'survey';
        $data['scripts'] = array(
            "assets/smc/select2/js/select2.full.min.js",
            "assets/kediri/js/survei/survei_home.js"
        );
        $data['scripts_post'] = array(
            "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js",
            "https://maps.googleapis.com/maps/api/js?key=AIzaSyALcj9sgW4DClNXnTDHvBzPxSImtJQixTM&callback=initMap"
        );
        $data['styles'] = array("assets/smc/select2/css/select2.min.css");
        $data['unsurs'] =  $this->m_survei->get_elemen_survei();
        $this->load->view('landing/view-data-survei.php', $data);
    }

    // menampilkan halaman data kemiskinan
    public function kemiskinan()
    {
        $data["smenu"] = 'kemiskinan';
        $data['styles'] = array(
            "assets/smc/select2/css/select2.min.css"
        );
        $data['scripts'] = array(
            "assets/smc/select2/js/select2.full.min.js",
            "assets/kediri/js/kemiskinan_home.js",
            "assets/smc/treeTable.js"
        );
        $data['scripts_post'] = array(
            "https://maps.googleapis.com/maps/api/js?key=AIzaSyALcj9sgW4DClNXnTDHvBzPxSImtJQixTM&callback=initMap"
        );

        $this->load->view('landing/view-data-kemiskinan.php', $data);
    }

    // menampilkan halaman kontak
    public function kontak()
    {
        $data["smenu"] = 'kontak';
        $this->load->view('landing/view-kontak.php', $data);
    }

    public function get_tree_elemen()
    {
        $data =  $this->conf->get_elemen_parent($_POST['unsur']);
        $resultData = array();
        if ($data) {
            $resultData = $this->get_elemen_tree_child($_POST['unsur'], $_POST['unit'], $_POST['kategori'], isset($_POST['sub_unit']) ? $_POST['sub_unit'] : null);
        }
        $tahun_rekap = array();
        for ($i = date("Y"); $i > date("Y") - 3; $i--) {
            array_push($tahun_rekap, $i);
        }
        $result = array("jumlah" => count($resultData), "data" => $resultData, "tahun" => $tahun_rekap);
        echo json_encode($result);
    }

    public function get_elemen_tree_child($id_parent, $unit, $kategori = null, $sub_unit = null)
    {
        $result =  $this->conf->get_elemen_parent($id_parent);
        $return = array();
        if ($result != null) {
            foreach ($result as $key => $value) {
                $value->child = $this->get_elemen_tree_child($value->id_elemen, $unit, $kategori, $sub_unit);
                if ($value->child != null && $value->has_unit_elemen === "1") {
                    $value->has_unit_elemen = "0";
                }
                if ($value->child == null) { /* Daun */
                    if ($kategori === "kabupaten") { /* Ambil 3 tahun terakhir dari masing2 kecamatan */
                        if ($value->has_unit_elemen === "1") {
                            $isExist = true;
                            $value->child = array();
                            $kecamatan =  $this->conf->get_unit(null, "kecamatan");
                            $urut = 1;
                            foreach ($kecamatan->result() as $kec_key => $kec) {
                                if ($unit == null || $unit === $kec->id_unit) {
                                    $element_kecamatan = array('tahun' => array(), 'id_elemen' => $value->id_elemen . "." . ($kec_key + 1), 'keterangan' => "Kecamatan " . $kec->nama, 'singkat' => $value->id_elemen . "." . ($kec_key + 1), 'kategori' => 'elemen', 'urut' => $urut, 'id_parent' => $value->id_elemen, 'satuan' => $value->satuan);
                                    $urut++;
                                    $desa =  $this->conf->get_unit(null, null, $kec->id_unit);
                                    $query_in_desa = "'',";
                                    if ($desa) {
                                        foreach ($desa->result() as $des) {
                                            $query_in_desa .= "'" . $des->id_unit . "',";
                                        }
                                    }
                                    $query_in_desa = substr($query_in_desa, 0, strlen($query_in_desa) - 1);
                                    for ($i = date("Y"); $i > date("Y") - 3; $i--) {
                                        $element_kecamatan['tahun'][$i] =  $this->conf->get_rekap_jumlah($i, $value->id_elemen, $query_in_desa);
                                    }
                                    array_push($value->child, $element_kecamatan);
                                }
                            }
                            array_push($return, $value);
                        }
                    } elseif ($kategori === "kecamatan") {
                        if ($value->has_unit_elemen === "1") { /* Ambil 3 Tahun dari terakhir masing2 desa */
                            $value->child = array();
                            $desa =  $this->conf->get_unit(null, null, $unit);
                            $urut = 1;
                            foreach ($desa->result() as $des_key => $des) {
                                if ($sub_unit == null || $sub_unit === $des->id_unit) {
                                    $element_desa = array('tahun' => array(), 'id_elemen' => $value->id_elemen . "." . ($des_key + 1), 'id_unit' => $des->id_unit, 'keterangan' => "Desa " . $des->nama, 'singkat' => $urut, 'kategori' => 'elemen', 'urut' => $urut, 'id_parent' => $value->id_elemen, 'satuan' => $value->satuan);
                                    for ($i = date("Y"); $i > date("Y") - 3; $i--) {
                                        $element_desa['tahun'][$i] =  $this->conf->get_rekap($i, $value->id_elemen, $des->id_unit);
                                    }
                                    $urut++;
                                    array_push($value->child, $element_desa);
                                }
                            }
                            array_push($return, $value);
                        }
                    } elseif ($kategori === "skpd") { /* Ambil 3 tahun terakhir dari masing2 kecamatan */
                        if ($value->has_unit_elemen === "1") {
                            $value->child = array();
                            $kecamatan =  $this->conf->get_unit(null, "kecamatan");
                            foreach ($kecamatan->result() as $kec_key => $kec) {
                                $element_kecamatan = array('tahun' => array(), 'id_elemen' => $value->id_elemen . "." . ($kec_key + 1), 'keterangan' => "Kecamatan " . $kec->nama, 'singkat' => $value->id_elemen . "." . ($kec_key + 1), 'kategori' => 'elemen', 'urut' => ($kec_key + 1), 'id_parent' => $value->id_elemen, 'satuan' => $value->satuan);
                                $desa =  $this->conf->get_unit(null, null, $kec->id_unit);
                                $query_in_desa = "'',";
                                if ($desa) {
                                    foreach ($desa->result() as $des) {
                                        $query_in_desa .= "'" . $des->id_unit . "',";
                                    }
                                }
                                $query_in_desa = substr($query_in_desa, 0, strlen($query_in_desa) - 1);
                                for ($i = date("Y"); $i > date("Y") - 3; $i--) {
                                    $element_kecamatan['tahun'][$i] =  $this->conf->get_rekap_jumlah($i, $value->id_elemen, $query_in_desa);
                                }
                                array_push($value->child, $element_kecamatan);
                            }
                            array_push($return, $value);
                        } elseif ($value->has_unit_elemen === "0") { /* Ambil 3 Tahun terakhir */
                            $value->tahun = array();
                            for ($i = date("Y"); $i > date("Y") - 3; $i--) {
                                $value->tahun[$i] =  $this->conf->get_rekap($i, $value->id_elemen, null, $unit);
                            }
                            array_push($return, $value);
                        }
                    }
                } else { /* cabang ada DAUN-NYA */
                    array_push($return, $value);
                }
            }
            if (count($return) == 0) {
                return null;
            } else {
                return $return;
            }
        }
        return $return;
    }

    /* Profil Home */
    public function get_elemen_home()
    {
        /* UNIT */
        $unit =  $this->conf->get_unit($_POST['id_unit']);
        $rekapDesa = array();
        $rekapSkPD = array();

        if ($unit->kategori == "kabupaten" || $unit->kategori == "kota") {
            /* SKPD */
            $skpd =  $this->conf->get_unit(null, "skpd");
            foreach ($skpd->result() as $sk) {
                array_push($rekapSkPD, $sk->id_unit);
            }
            $kecamatan =  $this->conf->get_unit_parent($_POST['id_unit']);
            foreach ($kecamatan->result() as $kec) {
                $desa =  $this->conf->get_unit_parent($kec->id_unit);
                if ($desa) {
                    foreach ($desa->result() as $des) {
                        array_push($rekapDesa, $des->id_unit);
                    }
                }
            }
        } elseif ($unit->kategori == "kecamatan") {
            $desa =  $this->conf->get_unit_parent($_POST['id_unit']);
            foreach ($desa->result() as $des) {
                array_push($rekapDesa, $des->id_unit);
            }
        } elseif ($unit->kategori == "desa") {
            array_push($rekapDesa, $_POST['id_unit']);
        }

        /* looping elemen */
        $report = array();
        $elemen =  $this->conf->get_elemen(null, null, null, null, "1");
        $rekap_unit = "'" . implode("','", $rekapDesa) . "'";
        $rekap_skpd = "'" . implode("','", $rekapSkPD) . "'";
        if ($elemen == null) {
            $elemen = array();
        }
        foreach ($elemen as $value) {
            if ($value->has_unit_elemen === "1" || ($value->has_unit_elemen === "0" && $unit->kategori == "kabupaten")) {

                $splitElemenUsur = explode(".", $value->id_elemen);
                $unsur =  $this->conf->get_elemen($splitElemenUsur[0] . "." . $splitElemenUsur[1]);
                $value->unsur = $unsur->keterangan;
                $value->unsur_singkat = $unsur->singkat_keterangan;
                $value->id_unsur = $unsur->id_elemen;
                $value->warna = $unsur->warna;
                $jumlah = $this->get_rekap_jumlah($_POST['periode'], $value, $unit, $rekap_unit, $rekap_skpd);
                $value->jumlah = $jumlah > 0 ? number_format($jumlah, 2, ",", ".") : $jumlah; // 132,3123=> 132,31; 0.00 => 0
                array_push($report, $value);
            }
        }
        echo json_encode($report);
    }

    private function get_rekap_jumlah($periode, $elemen, $unit, $rekap_unit, $rekap_skpd)
    {
        $child =  $this->conf->get_elemen_parent($elemen->id_elemen);
        $result_jumlah = 0;
        if ($child == null) {
            if ($elemen->has_unit_elemen === "1") {
                $jumlah =  $this->conf->get_rekap_jumlah($_POST['periode'], $elemen->id_elemen, $rekap_unit);
                $result_jumlah += ($jumlah == null ? 0 : $jumlah->jumlah);
            }
            if ($elemen->has_unit_elemen === "0" && $unit->kategori == "kabupaten") {
                $jumlah =  $this->conf->get_rekap_jumlah($_POST['periode'], $elemen->id_elemen, null, $rekap_skpd);
                $result_jumlah += ($jumlah == null ? 0 : $jumlah->jumlah);
            }
        } else {
            foreach ($child as $key => $value) {
                $result_jumlah += $this->get_rekap_jumlah($periode, $value, $unit, $rekap_unit, $rekap_skpd);
            }
        }
        return $result_jumlah;
    }

    /* Kecamatan Koordinat */
    public function get_kecamatan_koordinat()
    {
        $data['type'] = "FeatureCollection";
        $data['features'] = array();
        $kecamatan =  $this->conf->get_unit(null, "kecamatan", (isset($_POST["id_parent"]) ? $_POST["id_parent"] : null));

        foreach ($kecamatan->result() as $kec) {
            $data['features'][] = array(
                "type" => "Feature",
                "geometry" => array(
                    "type" => "Polygon",
                    "coordinates" => $kec->koordinat ? json_decode($kec->koordinat) : []
                ),
                "properties" => array(
                    "id_unit" => $kec->id_unit,
                    "nama" => $kec->nama,
                    "kategori" => $kec->kategori,
                    "last_update" => $kec->last_update
                )
            );
        }
        echo json_encode($data);
    }

    public function get_desa_koordinat($id_unit_kecamatan = null)
    {
        $data['type'] = "FeatureCollection";
        $data['features'] = array();
        $desa =  $this->conf->get_unit(null, "desa", $id_unit_kecamatan);
        foreach ($desa->result() as $d) {
            $data['features'][] = array(
                "type" => "Feature",
                "geometry" => array(
                    "type" => "Polygon",
                    "coordinates" => $d->koordinat ? json_decode($d->koordinat) : []
                ),
                "properties" => array(
                    "id_unit" => $d->id_unit,
                    "nama" => $d->nama,
                    "kategori" => $d->kategori,
                    "last_update" => $d->last_update
                )
            );
        }
        echo json_encode($data);
    }
}
