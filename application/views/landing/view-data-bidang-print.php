<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=UMKM Bidang " . $judul_bidang . ".xls");
?>

<?= $judul_bidang; ?>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kecamatan</th>
            <th>Jumlah UMKM</th>
            <th>Satuan</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < $total_data_kecamatan; $i++) : ?>
            <tr>
                <td><?= $i + 1; ?></td>
                <td><?= $data_rekap[$i]['kecamatan']; ?></td>
                <td><?= $data_rekap[$i]['jumlah_umkm']; ?></td>
                <td>Unit</td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>