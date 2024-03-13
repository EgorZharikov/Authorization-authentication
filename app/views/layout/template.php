<?php $userdata = (App\core\Auth\Auth::check_user());
if (isset($userdata)) {
    extract($userdata);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAuth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="/home">
                    OAuth
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/home">Главная</a>
                        </li>
                    </ul>
                    <?php if (isset($username)) : ?>
                        <a class="nav-link m-2" aria-current="page" style="color: green; font-weight: bold" href="/account/profile">
                            <img src="/img/user.png" alt="Logo" width="20" height="17" class="d-inline-block align-text-top">
                            <?=$username?></a>
                    <?php endif ?>

                    <?php if (!isset($username)) : ?>
                        <a href="/account/signin" class="btn btn-outline-dark">Sign in</a>
                    <?php endif ?>
                </div>
            </div>
        </nav>

    </header>
    <?php include_once VIEWS . $content_view; ?>
</body>

<footer>
    <div class="container">
        <div class="links">
            <a href="#">Контакты</a>
            <a href="#">О нас</a>
            <a href="#">Реклама</a>
        </div>
    </div>
</footer>

</html>