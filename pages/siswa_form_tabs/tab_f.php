<div class="tab-pane fade" id="tab-f">
    <h5>F. Keterangan Tentang Ibu Kandung</h5><hr>
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">Nama</label><input type="text" name="ibu_nama" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['nama'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tempat Lahir</label><input type="text" name="ibu_tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['tempat_lahir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal Lahir</label><input type="date" name="ibu_tanggal_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['tanggal_lahir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Agama</label><input type="text" name="ibu_agama" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['agama'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Kewarganegaraan</label><input type="text" name="ibu_kewarganegaraan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['kewarganegaraan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pendidikan</label><input type="text" name="ibu_pendidikan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['pendidikan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pekerjaan</label><input type="text" name="ibu_pekerjaan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['pekerjaan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pengeluaran Perbulan</label><input type="text" name="ibu_pengeluaran_perbulan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['pengeluaran_perbulan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Status Hidup</label><input type="text" name="ibu_status_hidup" class="form-control" value="<?php echo htmlspecialchars($ortu['Ibu']['status_hidup'] ?? 'Masih Hidup'); ?>"></div>
        <div class="col-md-12"><label class="form-label">Alamat & No. HP</label><textarea name="ibu_alamat" class="form-control"><?php echo htmlspecialchars($ortu['Ibu']['alamat'] ?? ''); ?></textarea></div>
    </div>
</div>
