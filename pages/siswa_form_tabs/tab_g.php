<div class="tab-pane fade" id="tab-g">
    <h5>G. Keterangan Tentang Wali</h5><hr>
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">Nama</label><input type="text" name="wali_nama" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['nama'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tempat Lahir</label><input type="text" name="wali_tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['tempat_lahir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal Lahir</label><input type="date" name="wali_tanggal_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['tanggal_lahir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Agama</label><input type="text" name="wali_agama" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['agama'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Kewarganegaraan</label><input type="text" name="wali_kewarganegaraan" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['kewarganegaraan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pendidikan</label><input type="text" name="wali_pendidikan" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['pendidikan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pekerjaan</label><input type="text" name="wali_pekerjaan" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['pekerjaan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pengeluaran Perbulan</label><input type="text" name="wali_pengeluaran_perbulan" class="form-control" value="<?php echo htmlspecialchars($ortu['Wali']['pengeluaran_perbulan'] ?? ''); ?>"></div>
        <div class="col-md-12"><label class="form-label">Alamat & No. HP</label><textarea name="wali_alamat" class="form-control"><?php echo htmlspecialchars($ortu['Wali']['alamat'] ?? ''); ?></textarea></div>
    </div>
</div>
