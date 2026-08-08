<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Prevent Browser Cache
|--------------------------------------------------------------------------
*/

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

?>

<!--
|--------------------------------------------------------------------------
| Prevent Back/Forward Cache Issue
|--------------------------------------------------------------------------
-->

<script>
window.addEventListener('pageshow', function (event) {

    if (event.persisted) {
        window.location.reload();
    }

});
</script>


<nav class="navbar navbar-expand-lg custom-navbar py-3">

    <div class="container">

        <!-- Logo -->

        <a
            class="navbar-brand fw-bold fs-3"
            href="/EventEase/"
        >

            <span class="text-info">Event</span><span class="text-white">Ease</span>

        </a>


        <!-- Mobile Menu Button -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar"
            aria-controls="navbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- Navbar Menu -->

        <div
            class="collapse navbar-collapse"
            id="navbar"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- Home -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/EventEase/"
                    >

                        Home

                    </a>

                </li>


                <!-- About -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/EventEase/pages/about.php"
                    >

                        About

                    </a>

                </li>


                <!-- Services -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/EventEase/pages/services.php"
                    >

                        Services

                    </a>

                </li>


                <!-- Events -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/EventEase/pages/events.php"
                    >

                        Events

                    </a>

                </li>


                <!-- Gallery -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/EventEase/pages/gallery.php"
                    >

                        Gallery

                    </a>

                </li>


                <!-- Contact -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/EventEase/pages/contact.php"
                    >

                        Contact

                    </a>

                </li>


                <!-- ADMIN LOGGED IN -->

                <?php if (
                    isset($_SESSION['admin_role']) &&
                    $_SESSION['admin_role'] === 'Admin'
                ): ?>

                    <li class="nav-item ms-lg-3">

                        <a
                            href="/EventEase/admin/dashboard.php"
                            class="btn btn-outline-info rounded-pill px-4"
                        >

                            Admin Dashboard

                        </a>

                    </li>


                    <li class="nav-item ms-lg-2">

                        <a
                            href="/EventEase/auth/logout.php"
                            class="btn btn-danger rounded-pill px-4"
                        >

                            Logout

                        </a>

                    </li>


                <!-- USER LOGGED IN -->

                <?php elseif (
                    isset($_SESSION['user_role']) &&
                    $_SESSION['user_role'] === 'User'
                ): ?>

                    <li class="nav-item ms-lg-3">

                        <a
                            href="/EventEase/user/dashboard.php"
                            class="btn btn-outline-info rounded-pill px-4"
                        >

                            Dashboard

                        </a>

                    </li>


                    <li class="nav-item ms-lg-2">

                        <a
                            href="/EventEase/auth/logout.php"
                            class="btn btn-danger rounded-pill px-4"
                        >

                            Logout

                        </a>

                    </li>


                <!-- NOT LOGGED IN -->

                <?php else: ?>

                    <li class="nav-item dropdown ms-lg-3">

                        <a
                            class="login-dropdown-btn nav-link dropdown-toggle btn rounded-pill px-4"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >

                            Login

                        </a>


                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="/EventEase/auth/login.php"
                                >

                                    👤 User Login

                                </a>

                            </li>


                            <li>

                                <a
                                    class="dropdown-item"
                                    href="/EventEase/admin/login.php"
                                >

                                    🔐 Admin Login

                                </a>

                            </li>

                        </ul>

                    </li>


                    <li class="nav-item ms-lg-2">

                        <a
                            href="/EventEase/auth/register.php"
                            class="btn btn-info rounded-pill px-4"
                        >

                            Register

                        </a>

                    </li>

                <?php endif; ?>


            </ul>

        </div>

    </div>

</nav>