<?php $errors = App\core\Session\Session::flash('errors');
$errors = $errors ?? '';
$params = App\core\Auth\Auth::getParamOauth();
?>
<div class="container">
    <div class="row account-card align-items-center">
        <div class="col col-md-3 offset-md-4">
            <div class="account-logo">
                <h3 class="h3 mb-3 fw-normal">Please sign up</h3>
            </div>
            <form name="signup_form" method="post" action="signup">
                <div class="mb-3">
                    <label for="inputLogin" class="form-label">Login</label>
                    <input type="text" name="username" class="form-control" id="inputLogin">
                </div>
                <div class="mb-3">
                    <label for="InputPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="InputPassword">
                </div>
                <div class="mb-3">
                    <label for="inputConfirmPassword" class="form-label">Confirm password</label>
                    <input type="password" name="confirm" class="form-control" id="inputConfirmPassword">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="signup" id="signIn" class="btn btn-primary">Отправить</button>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <?='<a href="http://oauth.vk.com/authorize?' . http_build_query( $params ) . '" class="btn btn-primary">Войти через Вконтакте</a>'; ?>
                </div>
                <?php if ($errors) : ?>
                <div class="alert alert-danger mt-3" role="alert"> <?=$errors ?>
                <?php endif ?>
            </div>
            </form>
        </div>
    </div>
</div>