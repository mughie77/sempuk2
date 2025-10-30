<div class="tab-pane fade show active" id="tab-a">
    <h5>A. Keterangan Diri Peserta Didik</h5><hr>
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($siswa['nama_lengkap'] ?? ''); ?>" required></div>
        <div class="col-md-4"><label class="form-label">Nama Panggilan</label><input type="text" name="nama_panggilan" class="form-control" value="<?php echo htmlspecialchars($siswa['nama_panggilan'] ?? ''); ?>"></div>
        <div class="col-md-4"><label class="form-label">Jenis Kelamin</label><select name="jk" class="form-select"><option value="L" <?php echo (($siswa['jk'] ?? '') == 'L') ? 'selected' : ''; ?>>Laki-laki</option><option value="P" <?php echo (($siswa['jk'] ?? '') == 'P') ? 'selected' : ''; ?>>Perempuan</option></select></div>
        <div class="col-md-6"><label class="form-label">NIS</label><input type="text" name="nis" class="form-control" value="<?php echo htmlspecialchars($siswa['nis'] ?? ''); ?>" required></div>
        <div class="col-md-6"><label class="form-label">NISN</label><input type="text" name="nisn" class="form-control" value="<?php echo htmlspecialchars($siswa['nisn'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($siswa['tempat_lahir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="<?php echo htmlspecialchars($siswa['tanggal_lahir'] ?? ''); ?>"></div>
        <div class="col-md-4"><label class="form-label">Agama</label><input type="text" name="agama" class="form-control" value="<?php echo htmlspecialchars($siswa['agama'] ?? ''); ?>"></div>
        <div class="col-md-4"><label class="form-label">Kewarganegaraan</label><input type="text" name="kewarganegaraan" class="form-control" value="<?php echo htmlspecialchars($siswa['kewarganegaraan'] ?? ''); ?>"></div>
        <div class="col-md-4"><label class="form-label">Anak Ke-</label><input type="number" name="anak_ke" class="form-control" value="<?php echo htmlspecialchars($siswa['anak_ke'] ?? ''); ?>"></div>
        <div class="col-md-3"><label class="form-label">Jml Sdr. Kandung</label><input type="number" name="jml_saudara_kandung" class="form-control" value="<?php echo htmlspecialchars($siswa['jml_saudara_kandung'] ?? ''); ?>"></div>
        <div class="col-md-3"><label class="form-label">Jml Sdr. Tiri</label><input type="number" name="jml_saudara_tiri" class="form-control" value="<?php echo htmlspecialchars($siswa['jml_saudara_tiri'] ?? ''); ?>"></div>
        <div class="col-md-3"><label class="form-label">Jml Sdr. Angkat</label><input type="number" name="jml_saudara_angkat" class="form-control" value="<?php echo htmlspecialchars($siswa['jml_saudara_angkat'] ?? ''); ?>"></div>
        <div class="col-md-3"><label class="form-label">Status Yatim</label><input type="text" name="status_yatim" class="form-control" value="<?php echo htmlspecialchars($siswa['status_yatim'] ?? ''); ?>"></div>
        <div class="col-md-12"><label class="form-label">Bahasa Sehari-hari</label><input type="text" name="bahasa_sehari_hari" class="form-control" value="<?php echo htmlspecialchars($siswa['bahasa_sehari_hari'] ?? ''); ?>"></div>
    </div>
</div>
