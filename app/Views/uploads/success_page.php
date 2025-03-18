<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<h3>Your file was successfully uploaded!</h3>

<ul>

    <li>name: <?= esc($uploaded_fileinfo->getBasename()) ?></li>

</ul>

<p><?= anchor('upload', 'Upload Another File!') ?></p>

<?= $this->endSection(); ?>