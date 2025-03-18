<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Courses
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header fs-4">
    List Courses
  </div>
  <div class="card-body">
    <a href="/course/create"><button class="btn btn-primary mb-2"><i class="bi bi-plus"></i>Add Course</button></a>

    <form action="<?= $baseUrl ?>" method="get" class="form-inline">
      <div class="row mb-4">
        <div class="col-md-5">
          <div class="input-group mr-2">
            <input type="text" class="form-control" name="search"
              value="<?= $params->search ?>" placeholder="Cari code atau nama...">
            <div class="input-group-append">
              <button class="btn btn-secondary" type="submit">Cari</button>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="credits" class="form-control" onchange="this.form.submit()">
              <option value="">All Credits</option>
              <?php foreach ($credits as $c): ?>
                <option value="<?= $c ?>" <?= ($params->credits == $c) ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group ml-2">
            <select name="semester" class="form-control" onchange="this.form.submit()">
              <option value="">All Semester</option>
              <?php foreach ($semester as $s): ?>
                <option value="<?= $s ?>" <?= ($params->semester == $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
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

    <div class="p-3">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <td><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('code', $baseUrl) ?>">
              Course Code <?= $params->isSortedBy('code') ? ($params->getSortDirection() == 'asc' ?
                            '↑' : '↓') : '↕' ?>
            </a></td>
          <td><a class="text-decoration-none text-white" href="<?= $params->getSortUrl('name', $baseUrl) ?>">
              Course Name <?= $params->isSortedBy('name') ? ($params->getSortDirection() == 'asc' ?
                            '↑' : '↓') : '↕' ?>
            </a></td>
          <td>Credits</td>
          <td>Semester</td>

          <td>Action</td>
        </thead>
        <?= $content ?? '' ?>
      </table>
      <?= $pager->links('courses', 'custom_pager') ?>

    </div>
  </div>
</div>
<?= $this->endSection() ?>