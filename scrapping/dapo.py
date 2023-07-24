import urllib, json
import requests
import os

_tahun_ajar = '2016'
_semester = '2' # 1 ganjil, 2 genap
_semester_id = _tahun_ajar+_semester
_kode_kab_kediri = '051300'
_bentuk_pendidikan_list = ['sd', 'slb', 'smp', 'sma', 'smk']

currentpath = os.getcwd()  
basepath = os.path.join(currentpath, _semester_id)
if not (os.path.isdir(basepath)):
        os.mkdir(basepath)

for _bentuk_pendidikan_id in _bentuk_pendidikan_list:
        pendidikan_path = os.path.join(basepath, _bentuk_pendidikan_id)
        if not (os.path.isdir(pendidikan_path)):
                os.mkdir(pendidikan_path)

        url = "http://dapo.dikdasmen.kemdikbud.go.id/rekap/dataSekolah?id_level_wilayah=2&kode_wilayah="+_kode_kab_kediri+"&semester_id="+_semester_id

        response = urllib.urlopen(url)
        data_kecamatan = json.loads(response.read())
        for dk in data_kecamatan:
                kode_wilayah = dk['kode_wilayah'].strip()
                url = "http://dapo.dikdasmen.kemdikbud.go.id/rekap/progresSP?id_level_wilayah=3&kode_wilayah="+kode_wilayah+"&semester_id="+_semester_id+"&bentuk_pendidikan_id="+_bentuk_pendidikan_id
                
                response = urllib.urlopen(url)
                data_sekolah = json.loads(response.read())

                for ds in data_sekolah:
                        sekolah_id =  ds['sekolah_id_enkrip'].strip()
                        
                        filename = os.path.join(pendidikan_path, ds['nama']+'.xlsx')
                        if not (os.path.isfile(filename)):
                                print 'Sedang download: '+ filename
                                profile_download_link = 'http://dapo.dikdasmen.kemdikbud.go.id/getExcel/getProfilSekolah?semester_id='+_semester_id+'&sekolah_id='+sekolah_id                
                                r = requests.get(profile_download_link, allow_redirects=True)
                                open(filename, 'wb').write(r.content)      