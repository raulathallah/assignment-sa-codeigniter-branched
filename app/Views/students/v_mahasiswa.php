<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Mahasiswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header fs-4">
    List Mahasiswa
  </div>
  <div class="card-body">
    <a href="/student/create"><button class="btn btn-primary mb-2"><i class="bi bi-plus"></i>Add Mahasiswa</button></a>
    <form action="<?= $baseUrl ?>" method="get" class="form-inline">
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="input-group mr-2">
            <input type="text" class="form-control" name="search"
              value="<?= $params->search ?>" placeholder="Cari...">
            <div class="input-group-append">
              <button class="btn btn-secondary" type="submit">Cari</button>
            </div>
          </div>
        </div>

        <!-- STUDY PROGRAM -->
        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="study_program" class="form-control" onchange="this.form.submit()">
              <option value="">All Study Program</option>
              <?php foreach ($study_program as $sp): ?>
                <option value="<?= $sp ?>" <?= ($params->study_program == $sp) ? 'selected' : '' ?>><?= ucfirst($sp) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <!-- ACADEMIC STATUS -->
        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="academic_status" class="form-control" onchange="this.form.submit()">
              <option value="">All Academic Status</option>
              <?php foreach ($academic_status as $as): ?>
                <option value="<?= $as ?>" <?= ($params->academic_status == $as) ? 'selected' : '' ?>><?= ucfirst($as) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <!-- ENTRY YEAR -->
        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="entry_year" class="form-control" onchange="this.form.submit()">
              <option value="">All Entry Year</option>
              <?php foreach ($entry_year as $ey): ?>
                <option value="<?= $ey ?>" <?= ($params->entry_year == $ey) ? 'selected' : '' ?>><?= ucfirst($ey) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <!-- ROLES -->

        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="perPage" class="form-control" onchange="this.form.submit()">
              <option value="5" <?= ($params->perPage == 5) ? 'selected' : '' ?>>
                5 per halaman
              </option>
              <option value="10" <?= ($params->perPage == 10) ? 'selected' : '' ?>>
                10 per halaman
              </option>
              <option value="20" <?= ($params->perPage == 20) ? 'selected' : '' ?>>
                20 per halaman
              </option>
            </select>
          </div>
        </div>
        <div class="col-md-1">
          <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn btn-secondary ml-2">
            Reset
          </a>
        </div>



        <input type="hidden" name="sort" value="<?= $params->sort; ?>">
        <input type="hidden" name="order" value="<?= $params->order; ?>">

    </form>
    <div class="table-responsive p-3">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <td><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('nim', $baseUrl) ?>">
              NIM <?= $params->isSortedBy('nim') ? ($params->getSortDirection() == 'asc' ?
                    '↑' : '↓') : '↕' ?>
            </a></td>
          <td>Name</td>
          <td>Study Program</td>
          <td>GPA</td>
          <td><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('current_semester', $baseUrl) ?>">
              Current Semester <?= $params->isSortedBy('current_semester') ? ($params->getSortDirection() == 'asc' ?
                                  '↑' : '↓') : '↕' ?>
            </a></td>
          <td>Entry Year</td>
          <td>Academic Status</td>
          <td>Action</td>
        </thead>
        <?= $content ?? '' ?>
      </table>
      <?= $pager->links('students', 'custom_pager') ?>
    </div>



  </div>
</div>
<?= $this->endSection() ?>