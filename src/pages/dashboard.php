<?php

use App\lib\Components;
use App\lib\Helpers;

$baseUrl = Helpers::baseURL();
?>
<?= Components::headerHTML([
    'title' => 'Yan Policarpo',
    'stylesheet' => [
        'https://bootswatch.com/4/litera/bootstrap.min.css',
        $baseUrl . 'src/pages/css/profile.css',
        $baseUrl . 'src/pages/css/background.svg.css',
        'https://bootswatch.com/4/litera/bootstrap.min.css'
    ],
    'raw' => '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">'
]);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow ">
    <div class="container">
        <a class="navbar-brand" href="#"><b>Yan</b> <b class="text-primary"">Policarpo</b></a>
            <button class=" navbar-toggler" type="button" data-toggle="collapse" data-target="#homeNavbar" aria-controls="homeNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="homeNavbar">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link text-secondary" href="#">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="#">Resume</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="#">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="#">Contect</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Administrative</a>
                        </li>
                    </ul>
                </div>
    </div>
</nav>
<?php require(dirname(__FILE__) . '/img/background.svg'); ?>
<main id="profile" class="container-lg">
    <div id="profile_header" class="d-flex flex-column justify-content-center align-items-center bg_gradient">
        <img id="avatar" src="<?= $baseUrl . 'src/pages/img/avatar.jpg'; ?>" alt="" class="mb-4 shadown">
        <h1 class="h1 text-light mb-2">Yan Policarpo</h1>
        <h2 class="h5 text-white-50 " id="features"></h2>
        <ul id="social_list">
            <li class="social_icon"><i class="fab fa-linkedin"></i></li>
            <li class="social_icon"><i class="fab fa-github"></i></li>
            <li class="social_icon"><i class="fab fa-instagram"></i></li>
            <li class="social_icon"><i class="fab fa-facebook"></i></li>
        </ul>
    </div>
    <div id="profile_body" class="bg-light shadow-lg">
       <h3 class="text-primary">About Me</h3><br />
        <p> Hello! I'm Manuel Pinto, a passionate UI/UX Designer & Front-End Developer from Bengaluru, India.
            I'm a Front-End Developer at Static Design Studio and I've been working in the web industry for the past 6 years. I focus on building clean and minimalistic user interfaces.
        </p>
    </div>
    </div>
</main>
<?= Components::scripts(['//cdn.jsdelivr.net/npm/typed.js@2.0.9'], '<script defer src="https://use.fontawesome.com/releases/v5.1.1/js/all.js" integrity="sha384-BtvRZcyfv4r0x/phJt9Y9HhnN5ur1Z+kZbKVgzVBAlQZX4jvAuImlIz+bG7TS00a" crossorigin="anonymous"></script>'); ?>
<script>
    $(document).ready(function() {
        let configuracao = {
            strings: ["Front-End Developer", "React Js", "Vue Js", "HTML CSS Javascript", "PHP", "Node"],
            typeSpeed: 80,
            showCursor: false,
            startDelay: 2000,
            backDelay: 500,
            smartBackspace: true,
            loop: true,
            loopCount: Infinity,
        }
        let typed = new Typed("#features", configuracao);
    })
</script>
<?= Components::closeView(); ?>