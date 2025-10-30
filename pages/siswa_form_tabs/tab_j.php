<div class="tab-pane fade" id="tab-j">
    <h5>J. Keterangan Setelah Selesai Pendidikan</h5><hr>
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">Melanjutkan ke</label><input type="text" name="lulus_melanjutkan_ke" class="form-control" value="<?php echo htmlspecialchars($setelah_lulus['melanjutkan_ke'] ?? ''); ?>"></div>
        <div class="col-md-12"><label class="form-label">Bekerja di</label><input type="text" name="lulus_bekerja_di" class="form-control" value="<?php echo htmlspecialchars($setelah_lulus['bekerja_di'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal Mulai Bekerja</label><input type="date" name="lulus_tgl_mulai_bekerja" class="form-control" value="<?php echo htmlspecialchars($setelah_lulus['tgl_mulai_bekerja'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Penghasilan</label><input type="text" name="lulus_penghasilan" class="form-control" value="<?php echo htmlspecialchars($setelah_lulus['penghasilan'] ?? ''); ?>"></div>
    </div>
</div>
