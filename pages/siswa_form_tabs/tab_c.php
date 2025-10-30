<div class="tab-pane fade" id="tab-c">
    <h5>C. Keterangan Kesehatan</h5><hr>
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Golongan Darah</label><input type="text" name="golongan_darah" class="form-control" value="<?php echo htmlspecialchars($siswa['golongan_darah'] ?? ''); ?>"></div>
        <div class="col-md-8"><label class="form-label">Penyakit yang pernah diderita</label><input type="text" name="penyakit_diderita" class="form-control" value="<?php echo htmlspecialchars($siswa['penyakit_diderita'] ?? ''); ?>"></div>
        <div class="col-md-12"><label class="form-label">Kelainan Jasmani</label><input type="text" name="kelainan_jasmani" class="form-control" value="<?php echo htmlspecialchars($siswa['kelainan_jasmani'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tinggi Badan (cm)</label><input type="number" name="tinggi_badan" class="form-control" value="<?php echo htmlspecialchars($siswa['tinggi_badan'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Berat Badan (kg)</label><input type="number" name="berat_badan" class="form-control" value="<?php echo htmlspecialchars($siswa['berat_badan'] ?? ''); ?>"></div>
    </div>
</div>
