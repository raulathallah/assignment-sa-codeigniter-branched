<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Upload Diploma
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
    Upload Diploma
  </div>
  <div class="card-body">

    <?= form_open_multipart('upload/upload', ['id' => 'upload-form', 'class' =>
    'pristine-validate']) ?>
    <div class="form-group d-flex flex-column gap-4">

      <div class="d-flex flex-column">
        <label for="userFile">Choose Diploma file (PDF - Max 5MB):</label>
        <input type="file" name="userfile" id="userfile" required
          data-pristine-required-message="Silakan pilih file untuk diunggah" accept=".pdf" />

        <div id="file-type-error" class="text-danger mt-2" style="display: none;">
          File harus berupa PDF (Max 5MB)
        </div>
        <div id="file-size-error" class="text-danger mt-2" style="display: none;">
          Ukuran file tidak boleh melebihi 5MB
        </div>
      </div>

      <div id="pdf-preview-container" style="display:none;"></div>

    </div>

    <button type="submit" class="my-4 custom-primary btn">Upload</button>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("upload-form");
    var pristine = new Pristine(form);
    var fileInput = document.getElementById('userfile');
    var fileTypeError = document.getElementById('file-type-error');
    var fileSizeError = document.getElementById('file-size-error');
    var pdfPreviewContainer = document.getElementById('pdf-preview-container');
    //var maxSize = 5 * 1024 * 1024; //5mb in binary terms
    var maxSize = 5; //5mb in binary terms
    //var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

    var allowedExtensions = ['.pdf'];

    pristine.addValidator(fileInput, function(value) {

      fileTypeError.style.display = 'none';
      fileSizeError.style.display = 'none';
      pdfPreviewContainer.style.display = 'none'; // Hide the preview initially

      if (fileInput.files.length === 0) {
        return true; // No file selected
      }

      var file = fileInput.files[0];

      var fileName = file.name.toLowerCase();
      var validExtension = allowedExtensions.some(function(ext) {
        return fileName.endsWith(ext);
      });

      if (!validExtension) {
        fileTypeError.style.display = 'block';
        fileTypeError.style.color = 'red';
        fileTypeError.style.fontSize = '12px';
        fileTypeError.style.fontWeight = 'bold';
        return false;
      }

      if (file.size > maxSize) {
        fileSizeError.style.display = 'block';
        fileSizeError.style.color = 'red';
        fileSizeError.style.fontSize = '12px';
        fileSizeError.style.fontWeight = 'bold';
        return false;
      }

      var reader = new FileReader();

      reader.onload = function(e) {
        // Create an iframe to preview the PDF
        var iframe = document.createElement('iframe');
        iframe.src = e.target.result;
        iframe.width = "100%";
        iframe.height = "500px"; // Adjust height as needed
        pdfPreviewContainer.innerHTML = ""; // Clear previous preview if any
        pdfPreviewContainer.appendChild(iframe); // Append new iframe for preview
        pdfPreviewContainer.style.display = 'block'; // Show the preview
      }

      reader.readAsDataURL(file); // Read the file as a Data URL for the iframe

      return true;

    }, "Invalid file.", 5, false);


    form.addEventListener('submit', function(e) {

      var valid = pristine.validate();

      if (!valid) {
        e.preventDefault(); // Prevent form submission if invalid
      }

    });

    fileInput.addEventListener('change', function() {

      fileTypeError.style.display = 'none';
      fileSizeError.style.display = 'none';

      pristine.validate(fileInput); // Validate when file is selected

    });

  });
</script>
<?= $this->endSection() ?>