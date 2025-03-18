<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Mahasiswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $content ?? '' ?>
<?= $this->endSection() ?>