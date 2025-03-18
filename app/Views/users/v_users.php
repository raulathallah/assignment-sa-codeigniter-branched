<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?>
User Management
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message'); ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-header fs-4">
        <?= $title; ?>
    </div>

    <div class="card-body">
        <a href="<?= base_url('admin/users/create'); ?>" class="btn btn-primary">Add User</a>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Group</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= $user->id; ?></td>
                        <td><?= $user->username; ?></td>
                        <td><?= $user->email; ?></td>
                        <td>
                            <?php if ($user->active == 1) : ?>
                                <span class="badge bg-success">Active</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $groupModel = new \Myth\Auth\Models\GroupModel();
                            $groups = $groupModel->getGroupsForUser($user->id);
                            foreach ($groups as $group) {
                                echo '<span class="badge bg-info me-1">' . $group['name'] . '</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/users/edit/' . $user->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?= base_url('admin/users/delete/' . $user->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure to delete this user?')">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            <a href="<?= base_url('admin/users/addToGroup/' . $user->id); ?>" class="btn btn-sm btn-info">Add to Group</a>
                        </td>
                    </tr>

                <?php endforeach; ?>

                <?php if (empty($users)) : ?>
                    <tr>
                        <td colspan="6" class="text-center">User not found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>