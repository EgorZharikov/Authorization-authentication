<div class="container col-sm-5">
    <div class="row profile-card ">
        <div class="col border border-primary-subtle align-items-center">
            <div>
                <h4 class="m-3">Мои данные:</h4>
            </div>
            <?php if (isset($_SESSION['auth'])) : ?>
                <div class="border">
                    Имя пользователя: <?php echo $username ?>
                </div>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'vk_user') : ?>
                <img src="/img/vk_user.png" alt="img for vk users">
            <?php endif; ?>
            <form method="post" action="signout">
                <button type="submit" name="signout" class="btn btn-primary m-3">Выйти</button>
            </form>
        </div>
    </div>
</div>