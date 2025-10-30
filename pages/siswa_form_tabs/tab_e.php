<div class="tab-pane fade" id="tab-e">
    <h5>E. Keterangan Tentang Ayah Kandung</h5><hr>
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">Nama</label><input type="text" name="ayah_nama" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['nama'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tempat Lahir</label><input type="text" name="ayah_tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['tempat_lahir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal Lahir</label><input type="date" name="ayah_tanggal_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['tanggal_lahir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Agama</label><input type="text" name="ayah_agama" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['agama'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Kewarganegaraan</label><input type="text" name="ayah_kewarganegaraan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['kewarganegaraan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pendidikan</label><input type="text" name="ayah_pendidikan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['pendidikan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pekerjaan</label><input type="text" name="ayah_pekerjaan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['pekerjaan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Pengeluaran Perbulan</label><input type="text" name="ayah_pengeluaran_perbulan" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['pengeluaran_perbulan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Status Hidup</label><input type="text" name="ayah_status_hidup" class="form-control" value="<?php echo htmlspecialchars($ortu['Ayah']['status_hidup'] ?? 'Masih Hidup'); ?>"></div>
        <div class="col-md-12"><label class="form-label">Alamat & No. HP</label><textarea name="ayah_alamat" class="form-control"><?php echo htmlspecialchars($ortu['Ayah']['alamat'] ?? ''); ?></textarea></div>
    </div>
</div>
