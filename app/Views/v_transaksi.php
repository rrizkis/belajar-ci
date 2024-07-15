<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<a href="<?= base_url('transaksi/download') ?>" class="btn btn-success mb-3">Download Data</a>

<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Total Harga</th>
            <th scope="col">Alamat</th>
            <th scope="col">Ongkir</th>
            <th scope="col">Status</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?= $transaction['id'] ?></td>
                <td><?= $transaction['username'] ?></td>
                <td><?= number_to_currency($transaction['total_harga'], 'IDR') ?></td>
                <td><?= $transaction['alamat'] ?></td>
                <td><?= number_to_currency($transaction['ongkir'], 'IDR') ?></td>
                <td><?= $transaction['status'] ? '1' : '0' ?></td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editStatusModal<?= $transaction['id'] ?>">Ubah Status</button>
                </td>
            </tr>
            <div class="modal fade" id="editStatusModal<?= $transaction['id'] ?>" tabindex="-1" aria-labelledby="editStatusModalLabel<?= $transaction['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('transaksi/update_status/' . $transaction['id']) ?>" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editStatusModalLabel<?= $transaction['id'] ?>">Edit Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <select name="status" class="form-select" required>
                                    <option value="0" <?= $transaction['status'] == 0 ? 'selected' : '' ?>>0</option>
                                    <option value="1" <?= $transaction['status'] == 1 ? 'selected' : '' ?>>1</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
