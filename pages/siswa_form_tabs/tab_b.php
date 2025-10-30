<div class="tab-pane fade" id="tab-b">
    <h5>B. Keterangan Tempat Tinggal</h5><hr>
    <div class="row g-3">
       <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control"><?php echo htmlspecialchars($siswa['alamat'] ?? ''); ?></textarea></div>
       <div class="col-md-6"><label class="form-label">No. Telepon/HP</label><input type="text" name="telepon" class="form-control" value="<?php echo htmlspecialchars($siswa['telepon'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Tinggal Dengan</label><input type="text" name="tinggal_dengan" class="form-control" value="<?php echo htmlspecialchars($siswa['tinggal_dengan'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Jarak ke Sekolah</label><input type="text" name="jarak_ke_sekolah" class="form-control" value="<?php echo htmlspecialchars($siswa['jarak_ke_sekolah'] ?? ''); ?>"></div>
    </div>
</div>
