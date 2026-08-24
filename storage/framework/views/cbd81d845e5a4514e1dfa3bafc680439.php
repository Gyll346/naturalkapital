<?php $__env->startSection('title', 'Manajemen Tim & Pengurus'); ?>

<?php $__env->startSection('content'); ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark);">
            Struktur Pengurus & Anggota Tim YNKI
        </h2>
        <a href="<?php echo e(route('admin.teams.create')); ?>" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Pengurus Baru
        </a>
    </div>

    <?php if(session('success')): ?>
        <div style="background: #dafbe1; color: #1a7f37; padding: 12px 16px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card-table">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Kategori Struktur</th>
                        <th>Jabatan / Posisi</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($member->photo_path): ?>
                                    <img src="/storage/<?php echo e($member->photo_path); ?>" alt="<?php echo e($member->full_name); ?>" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: #e8f5ed; color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                                        <?php echo e(substr($member->full_name, 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($member->full_name); ?></strong><br>
                                <?php if($member->linkedin_url): ?>
                                    <a href="<?php echo e($member->linkedin_url); ?>" target="_blank" style="font-size: 11.5px; color: #0a66c2; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.6 1.6 0 0 0-1.6 1.6 1.6 1.6 0 0 0 1.6 1.6 1.6 1.6 0 0 0 1.6-1.6 1.6 1.6 0 0 0-1.6-1.6z"/></svg>
                                        LinkedIn
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($member->category->category_name ?? '-'); ?></td>
                            <td><?php echo e($member->position); ?></td>
                            <td><?php echo e($member->sort_order); ?></td>
                            <td>
                                <?php if($member->status === 'active'): ?>
                                    <span class="badge badge-success">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line></svg>
                                        Alumni
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="<?php echo e(route('admin.teams.edit', $member->id)); ?>" class="btn-action btn-outline">Edit</a>
                                    <form action="<?php echo e(route('admin.teams.destroy', $member->id)); ?>" method="POST" onsubmit="return confirm('Hapus anggota tim ini?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-action" style="background: #ffebe9; color: #cf222e; border: none;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Belum ada pengurus atau anggota tim yang ditambahkan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            <?php echo e($members->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/teams/index.blade.php ENDPATH**/ ?>