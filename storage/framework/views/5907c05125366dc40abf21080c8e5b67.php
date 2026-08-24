<?php $__env->startSection('title', 'Portfolio Program & Proyek'); ?>

<?php $__env->startSection('content'); ?>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 700; color: var(--primary-dark);">
            Daftar Portfolio Program & Proyek YNKI
        </h2>
        <a href="<?php echo e(route('admin.portfolios.create')); ?>" class="btn-action btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Proyek Baru
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
                        <th>Judul Proyek</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Mitra / Donor</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($p->project_title); ?></strong><br>
                                <small style="color: var(--text-muted);"><?php echo e(Str::limit($p->summary, 80)); ?></small>
                            </td>
                            <td><span class="badge badge-success"><?php echo e($p->category); ?></span></td>
                            <td><?php echo e($p->location ?? '-'); ?></td>
                            <td><?php echo e($p->partner_donor ?? '-'); ?></td>
                            <td><?php echo e($p->period ?? '-'); ?></td>
                            <td>
                                <?php if($p->status === 'ongoing'): ?>
                                    <span class="badge badge-warning">Berjalan</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="<?php echo e(route('admin.portfolios.edit', $p->id)); ?>" class="btn-action btn-outline">Edit</a>
                                    <form action="<?php echo e(route('admin.portfolios.destroy', $p->id)); ?>" method="POST" onsubmit="return confirm('Hapus proyek portfolio ini?');">
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
                                Belum ada proyek portfolio yang ditambahkan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            <?php echo e($projects->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/portfolios/index.blade.php ENDPATH**/ ?>