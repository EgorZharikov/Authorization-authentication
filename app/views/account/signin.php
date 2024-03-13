<?php
$errors = App\core\Session\Session::flash('errors');
$errors = $errors ?? '';
$token = App\core\Session\Session::getToken();
$params = App\core\Auth\Auth::getParamOauth();
?>
<?php $token = $token ?? null; ?>
<div class="container">
    <div class="row account-card align-items-center">
        <div class="col col-md-3 offset-md-4">
            <div class="account-logo">
                <h3 class="h3 mb-3 fw-normal">Please sign in</h3>
            </div>
            <form name="signin-form" action="signin" method="post">
                <div class="mb-3">
                    <label for="inputLogin" class="form-label">Login</label>
                    <input type="text" name="username" class="form-control" id="inputLogin">
                    <input type="hidden" name="token" value="<?= $token ?>"> <br />
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="inputPassword">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="signin" class="btn btn-primary">Войти</button>
                </div>
                <div class="mt-1 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="save_user">
                    <label class="form-check-label" for="exampleCheck1">Сохранить вход</label>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <?php echo '<a href="http://oauth.vk.com/authorize?' . http_build_query($params) . '" class="btn btn-primary">Войти через Вконтакте</a>'; ?>
                </div>

                <div class="account-logo mt-3"><a href="/account/signup">Создать аккаунт</a></div>
                <?php if ($errors) : ?>
                <div class="alert alert-danger mt-3" role="alert"> <?=$errors ?>
                <?php endif ?>
                </div>
            </form>
        </div>
    </div>
</div>