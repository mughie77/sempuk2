<div class="tab-pane fade" id="tab-d">
    <h5>D. Keterangan Pendidikan</h5><hr>
    <h6>Pendidikan Sebelumnya</h6>
    <div class="row g-3">
       <div class="col-md-6"><label class="form-label">Tamatan dari</label><input type="text" name="pendidikan_nama_sekolah" class="form-control" value="<?php echo htmlspecialchars($pendidikan['nama_sekolah'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Lama Belajar</label><input type="text" name="pendidikan_lama_belajar" class="form-control" value="<?php echo htmlspecialchars($pendidikan['lama_belajar'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Tanggal Ijazah</label><input type="date" name="pendidikan_tgl_ijazah" class="form-control" value="<?php echo htmlspecialchars($pendidikan['tgl_ijazah'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Nomor Ijazah</label><input type="text" name="pendidikan_no_ijazah" class="form-control" value="<?php echo htmlspecialchars($pendidikan['no_ijazah'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Tanggal SKHUN</label><input type="date" name="pendidikan_tgl_skhun" class="form-control" value="<?php echo htmlspecialchars($pendidikan['tgl_skhun'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Nomor SKHUN</label><input type="text" name="pendidikan_no_skhun" class="form-control" value="<?php echo htmlspecialchars($pendidikan['no_skhun'] ?? ''); ?>"></div>
    </div>
    <h6 class="mt-4">Pindahan</h6>
     <div class="row g-3">
       <div class="col-md-6"><label class="form-label">Dari Sekolah</label><input type="text" name="pindahan_dari_sekolah" class="form-control" value="<?php echo htmlspecialchars($pindahan['dari_sekolah'] ?? ''); ?>"></div>
       <div class="col-md-6"><label class="form-label">Alasan</label><input type="text" name="pindahan_alasan" class="form-control" value="<?php echo htmlspecialchars($pindahan['alasan'] ?? ''); ?>"></div>
    </div>
</div>
