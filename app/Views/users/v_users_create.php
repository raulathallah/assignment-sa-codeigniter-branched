<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Edit Create
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
        <form action="<?= base_url('admin/users/store'); ?>" method="post">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control <?= (session('errors.username')) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= old('username'); ?>" required>
                <div class="invalid-feedback">
                    <?= session('errors.username'); ?>
                </div>
            </div>


            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?= old('email'); ?>" required>
                <div class="invalid-feedback">
                    <?= session('errors.email'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control <?= (session('errors.password')) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                <div class="invalid-feedback">
                    <?= session('errors.password'); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="pass_confirm" class="form-label">Password Confirmation</label>
                <input type="password" class="form-control <?= (session('errors.pass_confirm')) ? 'is-invalid' : ''; ?>" id="pass_confirm" name="pass_confirm" required>
                <div class="invalid-feedback">
                    <?= session('errors.pass_confirm'); ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="group" class="form-label">Grup</label>
                <select class="form-select <?= (session('errors.group')) ? 'is-invalid' : ''; ?>" id="group" name="group" required>
                    <option value="">Pilih Grup</option>
                    <?php foreach ($groups as $group) : ?>
                        <option value="<?= $group->id; ?>" <?= (old('group') == $group->id) ? 'selected' : ''; ?>><?= $group->name; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?= session('errors.group'); ?>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?= base_url('admin/users'); ?>" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>