<?php $__env->startSection('title', 'Dashboard Ringkasan'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Metrik Statistik Utama -->
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Total Donasi Terverifikasi</span>
                <div class="stat-value">Rp <?php echo e(number_format($totalDonationAmount, 0, ',', '.')); ?></div>
            </div>
            <div class="stat-icon icon-green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 6v12"></path></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Perlu Verifikasi</span>
                <div class="stat-value" style="color: #b45309;"><?php echo e($pendingDonationsCount); ?> Transaksi</div>
            </div>
            <div class="stat-icon icon-amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Artikel & Riset Terbit</span>
                <div class="stat-value"><?php echo e($totalArticlesCount); ?> Publikasi</div>
            </div>
            <div class="stat-icon icon-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Pengurus & Tim Aktif</span>
                <div class="stat-value"><?php echo e($totalTeamMembersCount); ?> Anggota</div>
            </div>
            <div class="stat-icon icon-purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabel Donasi Terbaru -->
    <div class="card-table">
        <div class="card-header">
            <h3>Transaksi Donasi Masuk Terbaru</h3>
            <a href="<?php echo e(route('admin.donations.index')); ?>" class="btn-action btn-outline">
                Lihat Semua Donasi
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Donatur</th>
                        <th>Program</th>
                        <th>Jumlah</th>
                        <th>Rekening Tujuan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentDonations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($donation->invoice_number); ?></strong></td>
                            <td><?php echo e($donation->is_anonymous ? 'Hamba Allah (Anonim)' : $donation->donor_name); ?></td>
                            <td><?php echo e($donation->program->program_name ?? 'Donasi Umum Lanskap'); ?></td>
                            <td><strong>Rp <?php echo e(number_format($donation->amount, 0, ',', '.')); ?></strong></td>
                            <td><?php echo e($donation->account->bank_name ?? '-'); ?></td>
                            <td>
                                <?php if($donation->status === 'verified'): ?>
                                    <span class="badge badge-success">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                <?php elseif($donation->status === 'pending'): ?>
                                    <span class="badge badge-warning">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        Menunggu
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-danger">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Ditolak
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($donation->created_at->format('d M Y H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Belum ada transaksi donasi yang tercatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Log Aktivitas Admin -->
    <div class="card-table">
        <div class="card-header">
            <h3>Riwayat Aktivitas Admin (Audit Trail)</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Administrator</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Keterangan</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($log->created_at->format('d M Y H:i:s')); ?></td>
                            <td><strong><?php echo e($log->user->name ?? 'System'); ?></strong></td>
                            <td><span class="badge badge-success"><?php echo e($log->action); ?></span></td>
                            <td><?php echo e($log->module); ?></td>
                            <td><?php echo e($log->details); ?></td>
                            <td><code><?php echo e($log->ip_address); ?></code></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                Belum ada log aktivitas tercatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>