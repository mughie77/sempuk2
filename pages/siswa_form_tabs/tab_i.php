<div class="tab-pane fade" id="tab-i">
    <h5>I. Keterangan Perkembangan Peserta Didik</h5><hr>
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Nama Beasiswa</label><input type="text" name="perkembangan_beasiswa_nama" class="form-control" value="<?php echo htmlspecialchars($perkembangan['beasiswa_nama'] ?? ''); ?>"></div>
        <div class="col-md-4"><label class="form-label">Tahun</label><input type="text" name="perkembangan_beasiswa_tahun" class="form-control" value="<?php echo htmlspecialchars($perkembangan['beasiswa_tahun'] ?? ''); ?>"></div>
        <div class="col-md-4"><label class="form-label">Dari</label><input type="text" name="perkembangan_beasiswa_dari" class="form-control" value="<?php echo htmlspecialchars($perkembangan['beasiswa_dari'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal Meninggalkan Sekolah</label><input type="date" name="perkembangan_meninggalkan_tanggal" class="form-control" value="<?php echo htmlspecialchars($perkembangan['meninggalkan_sekolah_tanggal'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Alasan</label><textarea name="perkembangan_meninggalkan_alasan" class="form-control"><?php echo htmlspecialchars($perkembangan['meninggalkan_sekolah_alasan'] ?? ''); ?></textarea></div>
        <div class="col-md-6"><label class="form-label">Tanggal Akhir Pendidikan</label><input type="date" name="perkembangan_akhir_tanggal" class="form-control" value="<?php echo htmlspecialchars($perkembangan['akhir_pendidikan_tanggal'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Nomor Ijazah</label><input type="text" name="perkembangan_akhir_no_ijazah" class="form-control" value="<?php echo htmlspecialchars($perkembangan['akhir_pendidikan_no_ijazah'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Nomor SKHUN</label><input type="text" name="perkembangan_akhir_no_skhun" class="form-control" value="<?php echo htmlspecialchars($perkembangan['akhir_pendidikan_no_skhun'] ?? ''); ?>"></div>
    </div>
</div>
