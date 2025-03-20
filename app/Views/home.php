<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Home
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>

        <li style="color: red;"><?= esc($error) ?></li>

    <?php endforeach ?>
<?php endif; ?>
<?php if (logged_in()): ?>
    <p>Welcome, <?= user()->username; ?>!</p>

    <?php if (!empty(user()->getRoles())): ?>
        <span>Role(s)</span>
        <ul>
            <?php foreach (user()->getRoles() as $role): ?>
                <li><span class="fw-bold"><?= $role ?></span></li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

    <!-- <a href="/email">Send Email</a> -->
<?php else: ?>
    <h2>Home Page</h2>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?= $this->endSection() ?>