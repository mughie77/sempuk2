<div class="tab-pane fade" id="tab-i">
    <h5>I. Keterangan Perkembangan Peserta Didik</h5><hr>
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">Beasiswa</label><input type="text" name="perkembangan_beasiswa" class="form-control" value="<?php echo htmlspecialchars($perkembangan['beasiswa'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal Meninggalkan Sekolah</label><input type="date" name="perkembangan_tgl_meninggalkan" class="form-control" value="<?php echo htmlspecialchars($perkembangan['tgl_meninggalkan_sekolah'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Alasan</label><textarea name="perkembangan_alasan_meninggalkan" class="form-control"><?php echo htmlspecialchars($perkembangan['alasan_meninggalkan_sekolah'] ?? ''); ?></textarea></div>
        <div class="col-md-6"><label class="form-label">Tanggal Ijazah Akhir</label><input type="date" name="perkembangan_tgl_ijazah" class="form-control" value="<?php echo htmlspecialchars($perkembangan['tgl_ijazah_akhir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Nomor Ijazah Akhir</label><input type="text" name="perkembangan_no_ijazah" class="form-control" value="<?php echo htmlspecialchars($perkembangan['no_ijazah_akhir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Tanggal SKHUN Akhir</label><input type="date" name="perkembangan_tgl_skhun" class="form-control" value="<?php echo htmlspecialchars($perkembangan['tgl_skhun_akhir'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Nomor SKHUN Akhir</label><input type="text" name="perkembangan_no_skhun" class="form-control" value="<?php echo htmlspecialchars($perkembangan['no_skhun_akhir'] ?? ''); ?>"></div>
    </div>
</div>
