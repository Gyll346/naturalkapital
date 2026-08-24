<?php $__env->startSection('title', 'Edit Pengurus / Tim'); ?>

<?php $__env->startSection('content'); ?>
    <div style="max-width: 680px; margin: 0 auto;">
        <div class="card-table">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                Ubah Data Anggota Tim / Pengurus
            </h2>

            <form action="<?php echo e(route('admin.teams.update', $member->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kategori Struktur *</label>
                    <select name="category_id" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e($member->category_id == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->category_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Lengkap & Gelar *</label>
                    <input type="text" name="full_name" value="<?php echo e(old('full_name', $member->full_name)); ?>" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jabatan / Posisi *</label>
                    <input type="text" name="position" value="<?php echo e(old('position', $member->position)); ?>" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Perbarui Foto Profil</label>
                    <?php if($member->photo_path): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="/storage/<?php echo e($member->photo_path); ?>" alt="<?php echo e($member->full_name); ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo" accept="image/*" style="width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tautan Profil LinkedIn</label>
                    <input type="url" name="linkedin_url" value="<?php echo e(old('linkedin_url', $member->linkedin_url)); ?>" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Biografi Singkat</label>
                    <textarea name="bio" rows="3" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;"><?php echo e(old('bio', $member->bio)); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Urutan Tampilan</label>
                        <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $member->sort_order)); ?>" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Status *</label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: #ffffff;">
                            <option value="active" <?php echo e($member->status === 'active' ? 'selected' : ''); ?>>Aktif</option>
                            <option value="alumni" <?php echo e($member->status === 'alumni' ? 'selected' : ''); ?>>Alumni</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 20px;">Simpan Perubahan</button>
                    <a href="<?php echo e(route('admin.teams.index')); ?>" class="btn-action btn-outline" style="padding: 10px 20px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/teams/edit.blade.php ENDPATH**/ ?>