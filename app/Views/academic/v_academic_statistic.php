<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
  Academic Statistic
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div>
    <h3>Academic Statistic</h3>
    <hr>
    <h5>Total Course : <?= $count; ?></h5>

</div>
<?= $this->endSection() ?>
