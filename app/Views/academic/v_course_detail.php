<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Course
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $content ?? '' ?>

<?= $this->endSection() ?>