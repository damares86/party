<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Partynsieme</title>

    <!-- <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/"> -->



    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->

    <link rel="manifest" href="assets/img/manifest.json">
    <link rel="icon" href="assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#AC1819">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="assets/css/signin.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin">
        <form>
            <img class="mb-4" src="assets/img/logo_agnelli.png" alt="" width="72" height="57">
            <h1 class="mb-5 ">Partynsieme</h1>
            <h5>Cassa</h5>
            <div class="col-12 my-5">
                <label for="country" class="form-label">Tipo di cibo</label>
                <select class="form-select mb-3" id="country" required>
                    <option value="">---</option>
                    <option>United States</option>
                </select>
                <div class="row">
                    <div class="col-8">
                        <label for="code" class="form-label">Codice</label>
                        <input type="number" class="form-control" id="address" placeholder="00000" required>
                    </div>
                    <div class="col-4">
                        <label for="address" class="form-label">Lettera</label>
                        <select class="form-select mb-3" id="country" required>
                            <option value="">---</option>
                            <option>United States</option>
                        </select>
                    </div>
                </div>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Invia</button>
            <p class="mt-5 mb-3 text-muted">
                Developed by
                <a href="http://www.dmweblab.com" target="_blank">
                    <img src="assets/img/dmweblab_logo.png" alt="Logo">
                </a>
            </p>
        </form>
    </main>


</body>

</html>