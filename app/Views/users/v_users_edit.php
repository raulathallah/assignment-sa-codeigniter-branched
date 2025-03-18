<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Edit User
<?= $this->endSection() ?>
<?= $this->section('content'); ?>
<?php if (session()->has('errors')) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
<div class="card">
    <div class="card-header fs-4">
        <?= $title; ?>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/users/update/' . $user->id); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control <?= (session('errors.username')) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= old('username', $user->username); ?>" required>
                <div class="invalid-feedback">
                    <?= session('errors.username'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?= old('email', $user->email); ?>" required>
                <div class="invalid-feedback">
                    <?= session('errors.email'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" class="form-control <?= (session('errors.password')) ? 'is-invalid' : ''; ?>" id="password" name="password">
                <div class="invalid-feedback">
                    <?= session('errors.password'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="pass_confirm" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control <?= (session('errors.pass_confirm')) ? 'is-invalid' : ''; ?>" id="pass_confirm" name="pass_confirm">
                <div class="invalid-feedback">
                    <?= session('errors.pass_confirm'); ?>
                </div>
            </div>


            <!-- <div class="mb-3">
                <label for="group" class="form-label">Grup</label>
                <select class="form-select <?= (session('errors.group')) ? 'is-invalid' : ''; ?>" id="group" name="group" required>
                    <option value="">Pilih Grup</option>
                    <?php foreach ($groups as $group) : ?>
                        <?php $selected = false; ?>
                        <?php foreach ($userGroups as $userGroup) : ?>
                            <?php if ($userGroup['group_id'] == $group->id) : ?>
                                <?php $selected = true;
                                break; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <option value="<?= $group->id; ?>" <?= ($selected) ? 'selected' : ''; ?>><?= $group->name; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?= session('errors.group'); ?>
                </div>
            </div> -->

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="status" name="status" <?= ($user->active == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">
                        Aktif
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?= base_url('admin/users'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>