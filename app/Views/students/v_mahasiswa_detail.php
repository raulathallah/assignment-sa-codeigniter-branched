<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Mahasiswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header fs-4">
    My Profile
  </div>

  <div class="p-3">
    <a class="p-2" href="/student/upload-diploma"><button class="btn btn-primary"></i>Add or Change Diploma</button></a>

    <?php if ($diploma_path): ?>
      <a href="/download/<?= $diploma_path; ?>" target="_blank">Download Diploma</a>
    <?php endif; ?>
  </div>


  <?= $content ?? '' ?>
</div>
<?= $this->endSection() ?>