<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Error</title>
    </head>
    <body>
        <div class="card m-5 p-3">
            <h1 style="color: #4f4f4f">Error</h1>
            <hr>
            <p class="fw-bold" style="color: #4f4f4f"><?= $errormessage ?? $controller->getErrorMessage() ?></p>
        </div>
    </body>
</html>
