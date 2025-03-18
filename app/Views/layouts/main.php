<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <title><?= $this->renderSection('title') ?></title>
    <script src="<?= base_url('assets/js/pristine.js') ?>"></script>

    <style>
        .custom-primary {
            background-color: #00423F;
            color: whitesmoke;
        }

        .custom-secondary {
            background-color: #FFC15E;
            color: black;
        }

        .custom-secondary:hover {
            opacity: 90%;
        }

        .custom-footer {
            background-color: #121212;
            text-align: center;
        }

        main {
            /* Adjust the main content's left margin to avoid sidebar overlap */
            flex-grow: 1;
        }
    </style>

</head>

<body class="d-flex flex-column h-100" style="background-color: gainsboro;">
    <!-- Header -->
    <header>
        <?= $this->include('components/header') ?>
    </header>

    <!-- Main Content -->
    <main class="d-flex ">

        <?= $this->include('components/sidebar') ?>

        <div class="mx-5 my-4 w-100 h-100">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <?= $this->include('components/footer') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>