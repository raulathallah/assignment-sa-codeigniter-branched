<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Course
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session('errors')) : ?>
    <div class="alert alert-danger w-100">
        <ul>
            <?php foreach (session('errors') as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
<div class="card">
    <div class="card-header fs-4">
        <?= $type ?> Course Form
    </div>
    <div class="card-body">
        <form action="/course/<?= $action; ?>" id="formData" method="post" style="display: grid; gap: 5px" novalidate>
            <div class="form-element mb-3">
                <label
                    class="form-label"
                    <?php if ($type == "Create"): ?>
                    <?php else: ?>
                    hidden
                    <?php endif; ?>>
                    Code
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="code"
                    name="code"
                    <?php if ($type == "Create"): ?>
                    <?php else: ?>
                    hidden
                    <?php endif; ?>
                    value="<?= $course->code ?>"
                    data-pristine-required
                    data-pristine-required-message="Code harus diisi!"
                    data-pristine-minlength="3"
                    data-pristine-minlength-message="Code minimal 3 karakter (client)"
                    autofocus />
            </div>

            <div class="form-element mb-3">
                <label class="form-label">Nama</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    value="<?= $course->name  ?>"
                    name="name"
                    data-pristine-required
                    data-pristine-required-message="Name harus diisi!"
                    data-pristine-minlength="3"
                    data-pristine-minlength-message="Name minimal 3 karakter (client)" />
            </div>
            <div class="form-element mb-3">
                <label class="form-label">Credits</label>
                <input
                    type="number"
                    class="form-control"
                    id="credits"
                    value="<?= $course->credits  ?>"
                    data-pristine-required
                    data-pristine-required-message="Credits harus diisi!"
                    name="credits" />
            </div>

            <div class="form-element mb-3">
                <label class="form-label">Semester</label>
                <input
                    type="number"
                    class="form-control"
                    id="semester"
                    value="<?= $course->semester  ?>"
                    data-pristine-required
                    data-pristine-required-message="Semester harus diisi!"
                    name="semester" />
            </div>

            <input
                type="text"
                id="id"
                name="id"
                hidden
                value="<?= $course->id ?>"
                autofocus />

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