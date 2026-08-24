<?php $__env->startSection('title', 'Edit Proyek Portfolio'); ?>

<?php $__env->startSection('content'); ?>
    <div style="max-width: 850px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="<?php echo e(route('admin.portfolios.index')); ?>" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Daftar Portfolio
            </a>
        </div>

        <div class="card-table">
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 24px;">
                Edit Proyek Portfolio
            </h3>

            <?php if($errors->any()): ?>
                <div style="background: #ffebe9; color: #cf222e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.portfolios.update', $project->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="form-group">
                    <label class="form-label" for="project_title">Nama / Judul Proyek</label>
                    <input type="text" class="form-control" id="project_title" name="project_title" value="<?php echo e(old('project_title', $project->project_title)); ?>" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="category">Kategori Pilar Lanskap</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Landscape Governance" <?php echo e($project->category === 'Landscape Governance' ? 'selected' : ''); ?>>Landscape Governance</option>
                            <option value="Natural Capital & Restoration" <?php echo e($project->category === 'Natural Capital & Restoration' ? 'selected' : ''); ?>>Natural Capital & Restoration</option>
                            <option value="Sustainable Commodity System" <?php echo e($project->category === 'Sustainable Commodity System' ? 'selected' : ''); ?>>Sustainable Commodity System</option>
                            <option value="Landscape Intelligence & Innovation" <?php echo e($project->category === 'Landscape Intelligence & Innovation' ? 'selected' : ''); ?>>Landscape Intelligence & Innovation</option>
                            <option value="Institutional Sustainability & Partnership" <?php echo e($project->category === 'Institutional Sustainability & Partnership' ? 'selected' : ''); ?>>Institutional Sustainability & Partnership</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status Pelaksanaan</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="ongoing" <?php echo e($project->status === 'ongoing' ? 'selected' : ''); ?>>Sedang Berjalan (Ongoing)</option>
                            <option value="completed" <?php echo e($project->status === 'completed' ? 'selected' : ''); ?>>Selesai (Completed)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="location">Lokasi / Wilayah</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo e(old('location', $project->location)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="partner_donor">Mitra / Donor</label>
                        <input type="text" class="form-control" id="partner_donor" name="partner_donor" value="<?php echo e(old('partner_donor', $project->partner_donor)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="period">Periode Pelaksanaan</label>
                        <input type="text" class="form-control" id="period" name="period" value="<?php echo e(old('period', $project->period)); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="summary">Ringkasan Capaian & Intervensi Proyek</label>
                    <textarea class="form-control" id="summary" name="summary" rows="3" required><?php echo e(old('summary', $project->summary)); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="image_cover">Ganti Foto Sampul (JPG/PNG/WebP)</label>
                        <input type="file" class="form-control" id="image_cover" name="image_cover" accept="image/*">
                        <?php if($project->image_cover_path): ?>
                            <img src="/storage/<?php echo e($project->image_cover_path); ?>" alt="Sampul" style="height: 60px; margin-top: 8px; border-radius: 4px;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="document_pdf">Ganti Laporan Proyek (PDF)</label>
                        <input type="file" class="form-control" id="document_pdf" name="document_pdf" accept="application/pdf">
                        <?php if($project->document_pdf_path): ?>
                            <p style="font-size: 12px; margin-top: 6px;"><a href="/storage/<?php echo e($project->document_pdf_path); ?>" target="_blank" style="color: #cf222e; font-weight: 600;">Lihat PDF Terlampir</a></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 24px; font-size: 14px;">Simpan Perubahan</button>
                    <a href="<?php echo e(route('admin.portfolios.index')); ?>" class="btn-action btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/portfolios/edit.blade.php ENDPATH**/ ?>