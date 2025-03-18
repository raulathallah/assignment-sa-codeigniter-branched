<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Enrollments
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session('errors')) : ?>
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
    Enrollment Form
  </div>
  <div class="card-body">
    <form id="formData" action="<?= $action; ?>" method="post" style="display: grid; gap: 5px">

      <input
        type="text"
        id="id"
        name="id"
        hidden
        value="<?= $enrollment->id ?>"
        autofocus />


      <div class="row">
        <?php if (in_groups('admin')): ?>
          <div class="col">
            <div class="form-element mb-3">
              <label
                class="form-label">
                Student
              </label>
              <select name="student_id" id="student_id" class="form-control">
                <option value="" hidden>Select Student</option>
                <?php foreach ($students as $row): ?>
                  <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>

          </div>
        <?php endif; ?>
        <div class="col">
          <div class="form-element mb-3">
            <label
              class="form-label">
              Course
            </label>
            <select name="course_id" id="course_id" class="form-control">
              <option value="" hidden>Select Course</option>
              <?php foreach ($courses as $row): ?>
                <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col">
          <div class="form-element mb-3">
            <label class="form-label">Academic Year</label>
            <input
              type="number"
              id="academic_year"
              name="academic_year"
              class="form-control"
              placeholder="Academic Year" />
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="width: fit-content;">Enroll</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  let pristine;
  window.onload = function() {
    let form = document.getElementById("formData");
    var pristine = new Pristine(form, {
      classTo: 'form-element',
      errorClass: 'is-invalid',
      successClass: 'is-valid',
      errorTextParent: 'form-element',
      errorTextTag: 'div',
      errorTextClass: 'text-danger'
    });

    form.addEventListener('submit', function(e) {
      var valid = pristine.validate();
      if (!valid) {
        e.preventDefault();
      }
    });

  };
</script>
<?= $this->endSection() ?>