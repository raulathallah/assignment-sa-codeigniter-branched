<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Add User to Group
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
        <form action="<?= base_url('admin/users/addToGroupSave/' . $user->id); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="mb-3">
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
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?= base_url('admin/users'); ?>" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>