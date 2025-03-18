<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>
Register
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?= view('Myth\Auth\Views\_message_block') ?>
<div class="">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header"><?= lang('Auth.register') ?></h2>
                <div class="card-body">


                    <form action="<?= url_to('register') ?>" id="formData" method="post" class="d-grid gap-2">
                        <?= csrf_field() ?>

                        <h5>Account Information</h5>

                        <div class="row row-cols-2">
                            <div class="col">
                                <div class="form-element mb-3">
                                    <label for="email"><?= lang('Auth.email') ?></label>
                                    <input
                                        type="email"
                                        class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                                        name="email"
                                        aria-describedby="emailHelp"
                                        placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>"
                                        data-pristine-required
                                        data-pristine-required-message="Email harus diisi!">
                                    <!-- <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small> -->
                                </div>

                                <div class="form-element mb-3">
                                    <label for="username"><?= lang('Auth.username') ?></label>
                                    <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>"
                                        data-pristine-required
                                        data-pristine-required-message="Username harus diisi!">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-element mb-3">
                                    <label for="password"><?= lang('Auth.password') ?></label>
                                    <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off"
                                        data-pristine-required
                                        data-pristine-required-message="Password harus diisi!">
                                </div>

                                <div class="form-element mb-3">
                                    <label for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                                    <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off"
                                        data-pristine-required
                                        data-pristine-required-message="Konfirmasi password harus diisi!">
                                </div>
                            </div>
                        </div>


                        <!-- <div class="form-group">
                            <label for="role_group">Role</label>
                            <select class="form-select" id="role_group" name="role_group" aria-label="Default select example">
                                <option selected hidden>Select role</option>
                                <option value="admin">Admin</option>
                                <option value="lecturer">Lecturer</option>
                                <option value="student">Student</option>
                            </select>
                        </div> -->

                        <h5 class="mt-3">Student Information</h5>

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
                                        placeholder="NIM"
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
                                        placeholder="Nama"
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
                                        name="study_program"
                                        data-pristine-required
                                        placeholder="Study Program"
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
                                        placeholder="Current Semester"
                                        name="current_semester" />

                                </div>
                            </div>
                            <div class="col">
                                <div class="form-element mb-3">
                                    <label for="role_group">Academic Status</label>
                                    <select class="form-select" id="academic_status" name="academic_status" aria-label="Default select example">
                                        <option selected hidden>Select status</option>
                                        <option value="active">Active</option>
                                        <option value="on leave">On Leave</option>
                                        <option value="graduated">Graduated</option>
                                    </select>
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
                                        placeholder="Entry Year"
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
                                        placeholder="GPA"
                                        name="gpa" />
                                </div>

                            </div>

                        </div>

                        <br>

                        <button type="submit" class="btn custom-primary btn-block"><?= lang('Auth.register') ?></button>
                    </form>


                    <hr>

                    <p><?= lang('Auth.alreadyRegistered') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.signIn') ?></a></p>
                </div>
            </div>

        </div>
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