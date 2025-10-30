<div class="tab-pane fade" id="tab-h">
    <h5>H. Kegemaran Peserta Didik</h5><hr>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Kesenian</label><input type="text" name="kegemaran_kesenian" class="form-control" value="<?php echo htmlspecialchars($kegemaran['kesenian'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Olah Raga</label><input type="text" name="kegemaran_olah_raga" class="form-control" value="<?php echo htmlspecialchars($kegemaran['olah_raga'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Kemasyarakatan/Organisasi</label><input type="text" name="kegemaran_organisasi" class="form-control" value="<?php echo htmlspecialchars($kegemaran['organisasi'] ?? ''); ?>"></div>
        <div class="col-md-6"><label class="form-label">Lain-lain</label><input type="text" name="kegemaran_lain_lain" class="form-control" value="<?php echo htmlspecialchars($kegemaran['lain_lain'] ?? ''); ?>"></div>
    </div>
</div>
