<?php
use Lib\Helpers;
?>

<div id="ts"></div>
<header id="header">
    <div id="topbar">
        <div class="container">
            <div class="social-links">
                <a class="twitter" href="#"><i class="fa fa-twitter"></i></a>
                <a class="facebook" href="#"><i class="fa fa-facebook"></i></a>
                <a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a>
                <a class="instagram" href="#"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="logo float-left">
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <h1 class="text-light"><a href="#intro" class="scrollto"><span>Rapid</span></a></h1>-->
            <a class="scrollto navbar-brand" href="#header">
                <img
                        alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/logo-via-logohub.png"
                        style="transform: perspective(100px)translate3d(0px, 0px, 29px)scale3d(1, 1, 1.5);!important;"
                />
            </a>
        </div>
        <nav class="main-nav float-right d-none d-lg-block">
            <ul>
                <li class="active"><a href="#intro">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#team">Team</a></li>
                <!--<li class="drop-down"><a href="">Drop Down</a>
                    <ul>
                        <li><a href="#">Drop Down 1</a></li>
                        <li class="drop-down"><a href="#">Drop Down 2</a>
                            <ul>
                                <li><a href="#">Deep Drop Down 1</a></li>
                                <li><a href="#">Deep Drop Down 2</a></li>
                                <li><a href="#">Deep Drop Down 3</a></li>
                                <li><a href="#">Deep Drop Down 4</a></li>
                                <li><a href="#">Deep Drop Down 5</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Drop Down 3</a></li>
                        <li><a href="#">Drop Down 4</a></li>
                        <li><a href="#">Drop Down 5</a></li>
                    </ul>
                </li>-->
                <li><a href="#footer">Contact Us</a></li>
            </ul>
        </nav><!-- .main-nav -->

    </div>
</header><!-- #header -->

<!--==========================
  Intro Section
============================-->
<section class="clearfix" id="intro">
    <div class="container d-flex h-100">
        <div class="row justify-content-center align-self-center">
            <div class="col-md-6 intro-info order-md-first order-last">
                <h2>Rapid Solutions<br>for Your <span>Business!</span></h2>
                <div>
                    <a class="btn-get-started scrollto" href="#about">Get Started</a>
                </div>
            </div>

            <div class="col-md-6 intro-img order-md-last order-first">
                <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/intro-img.svg">
            </div>
        </div>

    </div>
</section><!-- #intro -->

<main id="main">

    <!--==========================
      About Us Section
    ============================-->
    <section id="about">

        <div class="container">
            <div class="row">

                <div class="col-lg-5 col-md-6">
                    <div class="about-img">
                        <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/about-img.jpg">
                    </div>
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="about-content">
                        <h2>About Us</h2>
                        <h3>Odio sed id eos et laboriosam consequatur eos earum soluta.</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.</p>
                        <p>Aut dolor id. Sint aliquam consequatur ex ex labore. Et quis qui dolor nulla dolores neque.
                            Aspernatur consectetur omnis numquam quaerat. Sed fugiat nisi. Officiis veniam molestiae. Et
                            vel ut quidem alias veritatis repudiandae ut fugit. Est ut eligendi aspernatur nulla
                            voluptates veniam iusto vel quisquam. Fugit ut maxime incidunt accusantium totam repellendus
                            eum error. Et repudiandae eum iste qui et ut ab alias.</p>
                        <ul>
                            <li><i class="ion-android-checkmark-circle"></i> Ullamco laboris nisi ut aliquip ex ea
                                commodo consequat.
                            </li>
                            <li><i class="ion-android-checkmark-circle"></i> Duis aute irure dolor in reprehenderit in
                                voluptate velit.
                            </li>
                            <li><i class="ion-android-checkmark-circle"></i> Ullamco laboris nisi ut aliquip ex ea
                                commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta
                                storacalaperda mastiro dolore eu fugiat nulla pariatur.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- #about -->


    <!--==========================
      Services Section
    ============================-->
    <section class="section-bg" id="services">
        <div class="container">

            <header class="section-header">
                <h3>Services</h3>
                <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant
                    vituperatoribus.</p>
            </header>

            <div class="row">

                <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
                    <div class="box">
                        <div class="icon" style="background: #fceef3;"><i class="ion-ios-analytics-outline"
                                                                          style="color: #ff689b;"></i></div>
                        <h4 class="title"><a href="">Lorem Ipsum</a></h4>
                        <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias
                            excepturi sint occaecati cupiditate non provident</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
                    <div class="box">
                        <div class="icon" style="background: #fff0da;"><i class="ion-ios-bookmarks-outline"
                                                                          style="color: #e98e06;"></i></div>
                        <h4 class="title"><a href="">Dolor Sitema</a></h4>
                        <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat tarad limino ata</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
                    <div class="box">
                        <div class="icon" style="background: #e6fdfc;"><i class="ion-ios-paper-outline"
                                                                          style="color: #3fcdc7;"></i></div>
                        <h4 class="title"><a href="">Sed ut perspiciatis</a></h4>
                        <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                            dolore eu fugiat nulla pariatur</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
                    <div class="box">
                        <div class="icon" style="background: #eafde7;"><i class="ion-ios-speedometer-outline"
                                                                          style="color:#41cf2e;"></i></div>
                        <h4 class="title"><a href="">Magni Dolores</a></h4>
                        <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                            deserunt mollit anim id est laborum</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s">
                    <div class="box">
                        <div class="icon" style="background: #e1eeff;"><i class="ion-ios-world-outline"
                                                                          style="color: #2282ff;"></i></div>
                        <h4 class="title"><a href="">Nemo Enim</a></h4>
                        <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis
                            praesentium voluptatum deleniti atque</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.2s" data-wow-duration="1.4s">
                    <div class="box">
                        <div class="icon" style="background: #ecebff;"><i class="ion-ios-clock-outline"
                                                                          style="color: #8660fe;"></i></div>
                        <h4 class="title"><a href="">Eiusmod Tempor</a></h4>
                        <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero
                            tempore, cum soluta nobis est eligendi</p>
                    </div>
                </div>

            </div>

        </div>
    </section><!-- #services -->

    <!--==========================
      Why Us Section
    ============================-->
    <section class="wow fadeIn" id="why-us">
        <div class="container-fluid">

            <header class="section-header">
                <h3>Why choose us?</h3>
                <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant
                    vituperatoribus.</p>
            </header>

            <div class="row">

                <div class="col-lg-6">
                    <div class="why-us-img">
                        <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/why-us.jpg">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="why-us-content">
                        <p>Molestiae omnis numquam corrupti omnis itaque. Voluptatum quidem impedit. Odio dolorum
                            exercitationem est error omnis repudiandae ad dolorum sit.</p>
                        <p>
                            Explicabo repellendus quia labore. Non optio quo ea ut ratione et quaerat. Porro facilis
                            deleniti porro consequatur
                            et temporibus. Labore est odio.

                            Odio omnis saepe qui. Veniam eaque ipsum. Ea quia voluptatum quis explicabo sed nihil
                            repellat..
                        </p>

                        <div class="features wow bounceInUp clearfix">
                            <i class="fa fa-diamond" style="color: #f058dc;"></i>
                            <h4>Corporis dolorem</h4>
                            <p>Commodi quia voluptatum. Est cupiditate voluptas quaerat officiis ex alias dignissimos et
                                ipsum. Soluta at enim modi ut incidunt dolor et.</p>
                        </div>

                        <div class="features wow bounceInUp clearfix">
                            <i class="fa fa-object-group" style="color: #ffb774;"></i>
                            <h4>Eum ut aspernatur</h4>
                            <p>Molestias eius rerum iusto voluptas et ab cupiditate aut enim. Assumenda animi occaecati.
                                Quo dolore fuga quasi autem aliquid ipsum odit. Perferendis doloremque iure nulla
                                aut.</p>
                        </div>

                        <div class="features wow bounceInUp clearfix">
                            <i class="fa fa-language" style="color: #589af1;"></i>
                            <h4>Voluptates dolores</h4>
                            <p>Voluptates nihil et quis omnis et eaque omnis sint aut. Ducimus dolorum aspernatur. Totam
                                dolores ut enim ullam voluptas distinctio aut.</p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="container">
            <div class="row counters">

                <div class="col-lg-3 col-6 text-center">
                    <span data-toggle="counter-up">274</span>
                    <p>Clients</p>
                </div>

                <div class="col-lg-3 col-6 text-center">
                    <span data-toggle="counter-up">421</span>
                    <p>Projects</p>
                </div>

                <div class="col-lg-3 col-6 text-center">
                    <span data-toggle="counter-up">1,364</span>
                    <p>Hours Of Support</p>
                </div>

                <div class="col-lg-3 col-6 text-center">
                    <span data-toggle="counter-up">18</span>
                    <p>Hard Workers</p>
                </div>

            </div>

        </div>
    </section>

    <!--==========================
      Call To Action Section
    ============================-->
    <section class="wow fadeInUp" id="call-to-action">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 text-center text-lg-left">
                    <h3 class="cta-title">Call To Action</h3>
                    <p class="cta-text"> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                        fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                        deserunt mollit anim id est laborum.</p>
                </div>
                <div class="col-lg-3 cta-btn-container text-center">
                    <a class="cta-btn align-middle" href="#">Call To Action</a>
                </div>
            </div>

        </div>
    </section><!-- #call-to-action -->

    <!--==========================
      Features Section
    ============================-->
    <section id="features">
        <div class="container">

            <div class="row feature-item">
                <div class="col-lg-6 wow fadeInUp">
                    <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/features-1.svg">
                </div>
                <div class="col-lg-6 wow fadeInUp pt-5 pt-lg-0">
                    <h4>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h4>
                    <p>
                        Ipsum in aspernatur ut possimus sint. Quia omnis est occaecati possimus ea. Quas molestiae
                        perspiciatis occaecati qui rerum. Deleniti quod porro sed quisquam saepe. Numquam mollitia
                        recusandae non ad at et a.
                    </p>
                    <p>
                        Ad vitae recusandae odit possimus. Quaerat cum ipsum corrupti. Odit qui asperiores ea corporis
                        deserunt veritatis quidem expedita perferendis. Qui rerum eligendi ex doloribus quia sit. Porro
                        rerum eum eum.
                    </p>
                </div>
            </div>

            <div class="row feature-item mt-5 pt-5">
                <div class="col-lg-6 wow fadeInUp order-1 order-lg-2">
                    <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/features-2.svg">
                </div>

                <div class="col-lg-6 wow fadeInUp pt-4 pt-lg-0 order-2 order-lg-1">
                    <h4>Neque saepe temporibus repellat ea ipsum et. Id vel et quia tempora facere reprehenderit.</h4>
                    <p>
                        Delectus alias ut incidunt delectus nam placeat in consequatur. Sed cupiditate quia ea quis.
                        Voluptas nemo qui aut distinctio. Cumque fugit earum est quam officiis numquam. Ducimus corporis
                        autem at blanditiis beatae incidunt sunt.
                    </p>
                    <p>
                        Voluptas saepe natus quidem blanditiis. Non sunt impedit voluptas mollitia beatae. Qui esse
                        molestias. Laudantium libero nisi vitae debitis. Dolorem cupiditate est perferendis iusto.
                    </p>
                    <p>
                        Eum quia in. Magni quas ipsum a. Quis ex voluptatem inventore sint quia modi. Numquam est aut
                        fuga mollitia exercitationem nam accusantium provident quia.
                    </p>
                </div>

            </div>

        </div>
    </section><!-- #about -->

    <!--==========================
      Portfolio Section
    ============================-->
    <section class="section-bg" id="portfolio">
        <div class="container">

            <header class="section-header">
                <h3 class="section-title">Our Portfolio</h3>
            </header>

            <div class="row">
                <div class="col-lg-12">
                    <ul id="portfolio-flters">
                        <li class="filter-active" data-filter="*">All</li>
                        <li data-filter=".filter-app">App</li>
                        <li data-filter=".filter-card">Card</li>
                        <li data-filter=".filter-web">Web</li>
                    </ul>
                </div>
            </div>

            <div class="row portfolio-container">

                <div class="col-lg-4 col-md-6 portfolio-item filter-app">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/app1.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">App 1</a></h4>
                            <p>App</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="App 1"
                                   href="img/portfolio/app1.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-web" data-wow-delay="0.1s">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/web3.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">Web 3</a></h4>
                            <p>Web</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="Web 3"
                                   href="img/portfolio/web3.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-app" data-wow-delay="0.2s">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/app2.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">App 2</a></h4>
                            <p>App</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="App 2"
                                   href="img/portfolio/app2.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-card">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/card2.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">Card 2</a></h4>
                            <p>Card</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="Card 2"
                                   href="img/portfolio/card2.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-web" data-wow-delay="0.1s">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/web2.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">Web 2</a></h4>
                            <p>Web</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="Web 2"
                                   href="img/portfolio/web2.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-app" data-wow-delay="0.2s">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/app3.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">App 3</a></h4>
                            <p>App</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="App 3"
                                   href="img/portfolio/app3.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-card">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/card1.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">Card 1</a></h4>
                            <p>Card</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="Card 1"
                                   href="img/portfolio/card1.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-card" data-wow-delay="0.1s">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/card3.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">Card 3</a></h4>
                            <p>Card</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="Card 3"
                                   href="img/portfolio/card3.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item filter-web" data-wow-delay="0.2s">
                    <div class="portfolio-wrap">
                        <img alt="" class="img-fluid"
                             src="<?= Helpers::baseURL() ?>src/view/assets/img/portfolio/web1.jpg">
                        <div class="portfolio-info">
                            <h4><a href="#">Web 1</a></h4>
                            <p>Web</p>
                            <div>
                                <a class="link-preview" data-lightbox="portfolio" data-title="Web 1"
                                   href="img/portfolio/web1.jpg" title="Preview"><i class="ion ion-eye"></i></a>
                                <a class="link-details" href="#" title="More Details"><i
                                            class="ion ion-android-open"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section><!-- #portfolio -->

    <!--==========================
      Clients Section
    ============================-->
    <section id="testimonials">
        <div class="container">

            <header class="section-header">
                <h3>Testimonials</h3>
            </header>

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="owl-carousel testimonials-carousel wow fadeInUp">

                        <div class="testimonial-item">
                            <img alt="" class="testimonial-img"
                                 src="<?= Helpers::baseURL() ?>src/view/assets/img/testimonial-1.jpg">
                            <h3>Saul Goodman</h3>
                            <h4>Ceo &amp; Founder</h4>
                            <p>
                                Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit
                                rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam,
                                risus at semper.
                            </p>
                        </div>

                        <div class="testimonial-item">
                            <img alt="" class="testimonial-img"
                                 src="<?= Helpers::baseURL() ?>src/view/assets/img/testimonial-2.jpg">
                            <h3>Sara Wilsson</h3>
                            <h4>Designer</h4>
                            <p>
                                Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid
                                cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet
                                legam anim culpa.
                            </p>
                        </div>

                        <div class="testimonial-item">
                            <img alt="" class="testimonial-img"
                                 src="<?= Helpers::baseURL() ?>src/view/assets/img/testimonial-3.jpg">
                            <h3>Jena Karlis</h3>
                            <h4>Store Owner</h4>
                            <p>
                                Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam
                                duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.
                            </p>
                        </div>

                        <div class="testimonial-item">
                            <img alt="" class="testimonial-img"
                                 src="<?= Helpers::baseURL() ?>src/view/assets/img/testimonial-4.jpg">
                            <h3>Matt Brandon</h3>
                            <h4>Freelancer</h4>
                            <p>
                                Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat
                                minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore
                                labore illum veniam.
                            </p>
                        </div>

                    </div>

                </div>
            </div>


        </div>
    </section><!-- #testimonials -->

    <!--==========================
      Team Section
    ============================-->
    <section class="section-bg" id="team">
        <div class="container">
            <div class="section-header">
                <h3>Team</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
            </div>

            <div class="row">

                <div class="col-lg-3 col-md-6 wow fadeInUp">
                    <div class="member">
                        <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/team-1.jpg">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Walter White</h4>
                                <span>Chief Executive Officer</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="member">
                        <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/team-2.jpg">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Sarah Jhonson</h4>
                                <span>Product Manager</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="member">
                        <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/team-3.jpg">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>William Anderson</h4>
                                <span>CTO</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="member">
                        <img alt="" class="img-fluid" src="<?= Helpers::baseURL() ?>src/view/assets/img/team-4.jpg">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Amanda Jepson</h4>
                                <span>Accountant</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section><!-- #team -->

    <!--==========================
      Clients Section
    ============================-->
    <section class="wow fadeInUp" id="clients">
        <div class="container">

            <header class="section-header">
                <h3>Our Clients</h3>
            </header>

            <div class="owl-carousel clients-carousel">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-1.png">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-2.png">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-3.png">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-4.png">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-5.png">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-6.png">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-7.png">
                <img alt="" src="<?= Helpers::baseURL() ?>src/view/assets/img/clients/client-8.png">
            </div>

        </div>
    </section><!-- #clients -->


    <!--==========================
      Pricing Section
    ============================-->
    <section class="wow fadeInUp section-bg" id="pricing">

        <div class="container">

            <header class="section-header">
                <h3>Pricing</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
            </header>

            <div class="row flex-items-xs-middle flex-items-xs-center">

                <!-- Basic Plan  -->
                <div class="col-xs-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3><span class="currency">$</span>19<span class="period">/month</span></h3>
                        </div>
                        <div class="card-block">
                            <h4 class="card-title">
                                Basic Plan
                            </h4>
                            <ul class="list-group">
                                <li class="list-group-item">Odio animi voluptates</li>
                                <li class="list-group-item">Inventore quisquam et</li>
                                <li class="list-group-item">Et perspiciatis suscipit</li>
                                <li class="list-group-item">24/7 Support System</li>
                            </ul>
                            <a class="btn" href="#">Choose Plan</a>
                        </div>
                    </div>
                </div>

                <!-- Regular Plan  -->
                <div class="col-xs-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3><span class="currency">$</span>29<span class="period">/month</span></h3>
                        </div>
                        <div class="card-block">
                            <h4 class="card-title">
                                Regular Plan
                            </h4>
                            <ul class="list-group">
                                <li class="list-group-item">Odio animi voluptates</li>
                                <li class="list-group-item">Inventore quisquam et</li>
                                <li class="list-group-item">Et perspiciatis suscipit</li>
                                <li class="list-group-item">24/7 Support System</li>
                            </ul>
                            <a class="btn" href="#">Choose Plan</a>
                        </div>
                    </div>
                </div>

                <!-- Premium Plan  -->
                <div class="col-xs-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3><span class="currency">$</span>39<span class="period">/month</span></h3>
                        </div>
                        <div class="card-block">
                            <h4 class="card-title">
                                Premium Plan
                            </h4>
                            <ul class="list-group">
                                <li class="list-group-item">Odio animi voluptates</li>
                                <li class="list-group-item">Inventore quisquam et</li>
                                <li class="list-group-item">Et perspiciatis suscipit</li>
                                <li class="list-group-item">24/7 Support System</li>
                            </ul>
                            <a class="btn" href="#">Choose Plan</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section><!-- #pricing -->

    <!--==========================
      Frequently Asked Questions Section
    ============================-->
    <section id="faq">
        <div class="container">
            <header class="section-header">
                <h3>Frequently Asked Questions</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
            </header>

            <ul class="wow fadeInUp" id="faq-list">
                <li>
                    <a class="collapsed" data-toggle="collapse" href="#faq1">Non consectetur a erat nam at lectus urna
                        duis? <i class="ion-android-remove"></i></a>
                    <div class="collapse" data-parent="#faq-list" id="faq1">
                        <p>
                            Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non
                            curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
                        </p>
                    </div>
                </li>

                <li>
                    <a class="collapsed" data-toggle="collapse" href="#faq2">Feugiat scelerisque varius morbi enim nunc
                        faucibus a pellentesque? <i class="ion-android-remove"></i></a>
                    <div class="collapse" data-parent="#faq-list" id="faq2">
                        <p>
                            Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit
                            laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium.
                            Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa
                            tincidunt dui.
                        </p>
                    </div>
                </li>

                <li>
                    <a class="collapsed" data-toggle="collapse" href="#faq3">Dolor sit amet consectetur adipiscing elit
                        pellentesque habitant morbi? <i class="ion-android-remove"></i></a>
                    <div class="collapse" data-parent="#faq-list" id="faq3">
                        <p>
                            Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar
                            elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus
                            pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at
                            elementum eu facilisis sed odio morbi quis
                        </p>
                    </div>
                </li>

                <li>
                    <a class="collapsed" data-toggle="collapse" href="#faq4">Ac odio tempor orci dapibus. Aliquam
                        eleifend mi in nulla? <i class="ion-android-remove"></i></a>
                    <div class="collapse" data-parent="#faq-list" id="faq4">
                        <p>
                            Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit
                            laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium.
                            Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa
                            tincidunt dui.
                        </p>
                    </div>
                </li>

                <li>
                    <a class="collapsed" data-toggle="collapse" href="#faq5">Tempus quam pellentesque nec nam aliquam
                        sem et tortor consequat? <i class="ion-android-remove"></i></a>
                    <div class="collapse" data-parent="#faq-list" id="faq5">
                        <p>
                            Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est
                            ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing
                            bibendum est. Purus gravida quis blandit turpis cursus in
                        </p>
                    </div>
                </li>

                <li>
                    <a class="collapsed" data-toggle="collapse" href="#faq6">Tortor vitae purus faucibus ornare. Varius
                        vel pharetra vel turpis nunc eget lorem dolor? <i class="ion-android-remove"></i></a>
                    <div class="collapse" data-parent="#faq-list" id="faq6">
                        <p>
                            Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer
                            malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem
                            dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque. Pellentesque diam volutpat
                            commodo sed egestas egestas fringilla phasellus faucibus. Nibh tellus molestie nunc non
                            blandit massa enim nec.
                        </p>
                    </div>
                </li>

            </ul>

        </div>
    </section><!-- #faq -->

</main>

<!--==========================
  Footer
============================-->
<footer class="section-bg" id="footer">
    <div class="footer-top">
        <div class="container">

            <div class="row">

                <div class="col-lg-6">

                    <div class="row">

                        <div class="col-sm-6">

                            <div class="footer-info">
                                <h3>Rapid</h3>
                                <p>Cras fermentum odio eu feugiat lide par naso tierra. Justo eget nada terra videa
                                    magna derita valies darta donna mare fermentum iaculis eu non diam phasellus.
                                    Scelerisque felis imperdiet proin fermentum leo. Amet volutpat consequat mauris nunc
                                    congue.</p>
                            </div>

                            <div class="footer-newsletter">
                                <h4>Our Newsletter</h4>
                                <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna veniam enim
                                    veniam illum dolore legam minim quorum culpa amet magna export quem.</p>
                                <form action="" method="post">
                                    <input name="email" type="email"><input type="submit" value="Subscribe">
                                </form>
                            </div>

                        </div>

                        <div class="col-sm-6">
                            <div class="footer-links">
                                <h4>Useful Links</h4>
                                <ul>
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">About us</a></li>
                                    <li><a href="#">Services</a></li>
                                    <li><a href="#">Terms of service</a></li>
                                    <li><a href="#">Privacy policy</a></li>
                                </ul>
                            </div>

                            <div class="footer-links">
                                <h4>Contact Us</h4>
                                <p>
                                    A108 Adam Street <br>
                                    New York, NY 535022<br>
                                    United States <br>
                                    <strong>Phone:</strong> +1 5589 55488 55<br>
                                    <strong>Email:</strong> info@example.com<br>
                                </p>
                            </div>

                            <div class="social-links">
                                <a class="twitter" href="#"><i class="fa fa-twitter"></i></a>
                                <a class="facebook" href="#"><i class="fa fa-facebook"></i></a>
                                <a class="instagram" href="#"><i class="fa fa-instagram"></i></a>
                                <a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="form">

                        <h4>Send us a message</h4>
                        <p>Eos ipsa est voluptates. Nostrum nam libero ipsa vero. Debitis quasi sit eaque numquam
                            similique commodi harum aut temporibus.</p>
                        <form action="" class="contactForm" method="post" role="form">
                            <div class="form-group">
                                <input class="form-control" data-msg="Please enter at least 4 chars"
                                       data-rule="minlen:4" id="name" name="name"
                                       placeholder="Your Name" type="text"/>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" data-msg="Please enter a valid email" data-rule="email"
                                       id="email"
                                       name="email" placeholder="Your Email"
                                       type="email"/>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" data-msg="Please enter at least 8 chars of subject"
                                       data-rule="minlen:4" id="subject"
                                       name="subject" placeholder="Subject"
                                       type="text"/>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" data-msg="Please write something for us"
                                          data-rule="required" name="message"
                                          placeholder="Message" rows="5"></textarea>
                                <div class="validation"></div>
                            </div>

                            <div id="sendmessage">Your message has been sent. Thank you!</div>
                            <div id="errormessage"></div>

                            <div class="text-center">
                                <button title="Send Message" type="submit">Send Message</button>
                            </div>
                        </form>
                    </div>

                </div>


            </div>

        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong>h4rd1ideveloper</strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://github.com/h4rd1ideveloper"><i class="fa fa-address-card"></i></a>
        </div>
    </div>
</footer>
<a class="back-to-top" href="#"><i class="fa fa-chevron-up"></i></a>
<!-- Uncomment below i you want to use a preloader -->
<div id="preloader"></div>
<script>
    (
        function ($, axios, Swal) {
            $('document').ready(function () {
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }
    )
    ($ = window.jQuery, axios = window.axios, Swal = window.Swal)
</script>