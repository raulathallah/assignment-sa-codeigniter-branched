<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Mahasiswa
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
        <?= $type ?> Mahasiswa Form
    </div>
    <div class="card-body">
        <form id="formData" action="/student/<?= $action; ?>" method="post" style="display: grid; gap: 5px">

            <input
                type="text"
                id="id"
                name="id"
                hidden
                value="<?= $mahasiswa->id ?>"
                autofocus />

            <div class="row row-cols-2">
                <div class="col">
                    <div class="form-element mb-3">
                        <label
                            class="form-label">
                            NIM (Nomor Induk Mahasiswa)
                        </label>
                        <input
                            type="text"
                            id="student_id"
                            name="student_id"
                            class="form-control"
                            data-pristine-required
                            data-pristine-required-message="NIM harus diisi!"
                            data-pristine-minlength="3"
                            data-pristine-minlength-message="Name minimal 3 karakter (client)"
                            value="<?= $mahasiswa->student_id ?>"
                            autofocus />

                    </div>
                </div>
                <div class="col">
                    <div class="form-element mb-3">
                        <label class="form-label">Nama</label>
                        <input
                            type="text"
                            id="name"
                            class="form-control"
                            data-pristine-required
                            data-pristine-required-message="Nama harus diisi!"
                            value="<?= $mahasiswa->name  ?>"
                            name="name" />
                    </div>
                </div>
                <div class="col">
                    <div class="form-element mb-3">
                        <label class="form-label">Study Program</label>
                        <input
                            type="text"
                            id="study_program"
                            class="form-control"
                            value="<?= $mahasiswa->study_program  ?>"
                            name="study_program"
                            data-pristine-required
                            data-pristine-required-message="Study program harus diisi!" />
                    </div>
                </div>
                <div class="col">
                    <div class="form-element mb-3">
                        <label class="form-label">Current Semester</label>
                        <input
                            type="number"
                            id="current_semester"
                            class="form-control"
                            data-pristine-required
                            data-pristine-required-message="Current Semester harus diisi!"
                            data-pristine-min="1"
                            data-pristine-min-message="Current semester must be greater than or equal to 1"
                            data-pristine-max="14"
                            data-pristine-max-message="Current semester must be less than or equal to 14"
                            value="<?= $mahasiswa->current_semester  ?>"
                            name="current_semester" />

                    </div>
                </div>
                <div class="col">
                    <div class="form-element mb-3">
                        <label class="form-label">Academic Status</label>
                        <input
                            type="text"
                            class="form-control"
                            id="academic_status" data-pristine-required
                            data-pristine-required-message="Academic Status harus diisi!"
                            value="<?= $mahasiswa->academic_status ?>"
                            name="academic_status" />
                    </div>
                </div>
                <div class="col">
                    <div class="form-element mb-3">
                        <label class="form-label">Entry Year</label>
                        <input
                            type="number"
                            class="form-control"
                            id="entry_year"
                            data-pristine-required
                            data-pristine-required-message="Entry year harus diisi!"
                            value="<?= $mahasiswa->entry_year ?>"
                            name="entry_year" />
                    </div>
                </div>
                <div class="col">

                    <div class="form-element mb-3">
                        <label class="form-label">GPA</label>
                        <input
                            type="nummber"
                            id="gpa"
                            class="form-control"
                            data-pristine-required
                            data-pristine-required-message="GPA harus diisi!"
                            value="<?= $mahasiswa->gpa ?>"
                            name="gpa" />
                    </div>

                </div>

            </div>
            <button type="submit" class="btn btn-primary" style="width: fit-content;">Save</button>
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