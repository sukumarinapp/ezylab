<!DOCTYPE html>
<html lang="en">

<head>

<!-- meta tags -->
<meta charset="utf-8">
<meta name="keywords" content="LaB Software"/>
<meta name="description" content="LaB Software" />
<meta name="author" content="www.themeht.com" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Title -->
<title>Contact Us</title>

<!-- favicon icon -->
<link rel="shortcut icon" href="/new/frontend/images/favicon.ico" />

<!-- inject css start -->

<!--== bootstrap -->
<link href="frontend/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<!--== fonts -->
<link href="https://fonts.googleapis.com/css2?family=Livvic:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,300;1,400;1,500;1,600;1,700;1,900&amp;display=swap" rel="stylesheet"> 

<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet"> 

<!--== animate -->
<link href="frontend/css/animate.css" rel="stylesheet" type="text/css" />

<!--== line-awesome -->
<link href="frontend/css/line-awesome.min.css" rel="stylesheet" type="text/css" />

<!--== magnific-popup -->
<link href="frontend/css/magnific-popup.css" rel="stylesheet" type="text/css" />

<!--== owl.carousel -->
<link href="frontend/css/owl.carousel.css" rel="stylesheet" type="text/css" />

<!--== lightslider -->
<link href="frontend/css/lightslider.min.css" rel="stylesheet" type="text/css" />

<!--== base -->
<link href="frontend/css/base.css" rel="stylesheet" type="text/css" />

<!--== shortcodes -->
<link href="frontend/css/shortcodes.css" rel="stylesheet" type="text/css" />

<!--== spacing -->
<link href="frontend/css/spacing.css" rel="stylesheet" type="text/css" />

<!--== style -->
<link href="frontend/css/style.css" rel="stylesheet" type="text/css" />

<!--== color-customizer -->
<link href="#" data-style="styles" rel="stylesheet">
<link href="frontend/css/color-customize/color-customizer.css" rel="stylesheet" type="text/css" />

<!-- inject css end -->

</head>

<body>
<?php include "frontend_header.php"; ?>
<!-- page wrapper start -->

<div class="page-wrapper">

<!-- preloader start -->

<div id="ht-preloader">
  <div class="clear-loader d-flex align-items-center justify-content-center">
    <div class="loader">
     <span class="plus"></span>
    <span class="plus"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    </div>
  </div>
</div>


<div class="search-input" id="search-input-box">
  <div class="container ">
    <form class="d-flex justify-content-between search-inner">
      <input type="text" class="form-control" id="search-input" placeholder="Search Here">
      <button type="submit" class="btn"></button> <span class="las la-times" id="close-search" title="Close Search"></span>
    </form>
  </div>
</div>

<!--body content start-->

<div class="page-content">

<section class="page-title parallaxie" data-bg-img="frontend/images/bg/06.jpg">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="white-bg p-md-5 p-3 d-inline-block">
        <h1 class="text-theme">Contact  <span class="text-black"> Us </span></h1>
        <nav aria-label="breadcrumb" class="page-breadcrumb border-top border-light pt-3 mt-3">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i class="las la-home me-1"></i>Home</a>
            </li>
            <li class="breadcrumb-item">Pages
            </li>
            <li class="breadcrumb-item active" aria-current="page">Contact  Us</li>
          </ol>
        </nav>
        </div>
      </div>
    </div>
  </div>
</section><!--feature start-->

<div class="page-content">


<section>
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8 col-12">
        <div class="section-title">
          <h2 class="title">Drop A Line!</h2> 
          <p class="mb-0">Any Question or Remarks ? Just Write Us a Message!</p>
        </div>
      </div>
    </div>
    <div class="row g-0">
      <div class="col-lg-8">
        <div class="contact-main white-bg shadow-sm p-5">
          <form id="contact-form" class="row" method="post" action="php/contact.php">
            <div id="formmessage"></div>
            <div class="form-group col-md-6">
              <input id="form_name" type="text" name="name" class="form-control" placeholder="Name" required="required">
            </div>
            <div class="form-group col-md-6">
              <input id="form_email" type="email" name="email" class="form-control" placeholder="Email" required="required">
            </div>
            <div class="form-group col-md-6">
              <input id="form_phone" type="tel" name="phone" class="form-control" placeholder="Phone" required="required">
            </div>
            <div class="form-group col-md-6">
              <select name="select" class="form-select form-control">
                <option>- Choose Service</option>
                <option>Pathology</option>
                <option>Diabetes</option>
                <option>Chemical</option>
              </select>
            </div>
            <div class="form-group col-md-12">
              <textarea id="form_message" name="message" class="form-control" placeholder="Message" rows="3" required="required"></textarea>
            </div>
            <div class="col-md-12 text-center mt-4">
              <button class="btn btn-theme"><span>Send Messages</span>
              </button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-4 dark-bg">
        <div class="px-3 py-5 p-md-5 text-white">
          <div class="contact-media mb-4">
            <h5 class="text-white">Find Office:</h5>
            <span>423B, Road Wordwide Country, USA</span>
          </div>
          <div class="contact-media mb-4">
            <h5 class="text-white">Contact Us:</h5>
            <ul class="list-unstyled">
              <li class="mb-2">Phone: <a href="tel:+912345678900">+91-234-567-8900</a>
              </li>
              <li>Email: <a href="mailto:themeht23@gmail.com"> themeht23@gmail.com</a>
              </li>
            </ul>
          </div>
          <div class="contact-media mb-4">
            <h5 class="text-white">Working Hours:</h5>
            <span>Monday - Saturday: 9.30am To 7.00pm</span>
          </div>
          <div class="social-icons">
            <ul class="list-inline">
              <li><a href="#"><i class="lab la-facebook-f"></i></a>
              </li>
              <li><a href="#"><i class="lab la-twitter"></i></a>
              </li>
              <li><a href="#"><i class="lab la-instagram"></i></a>
              </li>
              <li><a href="#"><i class="lab la-dribbble"></i></a>
              </li>
              <li><a href="#"><i class="lab la-linkedin-in"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


</div>
</div>

<!-- Cart Modal -->
<div class="modal fade cart-modal" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Your Cart (2)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <div class="row align-items-center">
            <div class="col-5 d-flex align-items-center">
              <div class="me-4">
                <button type="submit" class="btn btn-theme btn-sm"><i class="las la-times"></i>
                </button>
              </div>
              <!-- Image -->
              <a href="product-left-image.html">
                <img class="img-fluid" src="frontend/images/product/01.jpg" alt="...">
              </a>
            </div>
            <div class="col-7">
              <!-- Title -->
              <h6><a class="link-title" href="product-single.html">Dealistic Microscope</a></h6>
              <div class="product-meta"><span class="me-2 text-theme">$120.00</span><span class="text-muted">x 1</span>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-5">
        <div>
          <div class="row align-items-center">
            <div class="col-5 d-flex align-items-center">
              <div class="me-4">
                <button type="submit" class="btn btn-theme btn-sm"><i class="las la-times"></i>
                </button>
              </div>
              <!-- Image -->
              <a href="product-single.html">
                <img class="img-fluid" src="frontend/images/product/02.jpg" alt="...">
              </a>
            </div>
            <div class="col-7">
              <!-- Title -->
              <h6><a class="link-title" href="product-left-image.html">Biotechnology Microscope</a></h6>
              <div class="product-meta"><span class="me-2 text-theme">$160.00</span><span class="text-muted">x 1</span>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-5">
        <div class="d-flex justify-content-between align-items-center mb-8"> <span class="text-muted">Subtotal:</span>  <span class="text-dark">$280.00</span> 
        </div> <a href="product-cart.html" class="btn btn-theme me-2">View Cart</a>
        <a href="product-checkout.html" class="btn btn-dark">Continue To Checkout</a>
      </div>
    </div>
  </div>
</div>

<!--color-customizer start-->

<div class="color-customizer closed">
  <div class="color-button">
    <a class="opener" href="#"> <i class="las la-spinner fa-spin"></i>
    </a>
  </div>
  <div class="clearfix color-chooser text-center">
    <ul class="colorChange clearfix">
      <li class="theme-default selected" title="theme-default" data-style="color-1"></li>
      <li class="theme-2" title="theme-2" data-style="color-2"></li>
      <li class="theme-3" title="theme-3" data-style="color-3"></li>
      <li class="theme-4" title="theme-4" data-style="color-4"></li>
    </ul>
  </div>
</div>

<!--color-customizer end-->


<!--back-to-top start-->
<?php include "frontend_footer.php"; ?>
<div class="scroll-top"><a class="smoothscroll" href="#top"><i class="las la-location-arrow"></i></a></div>

<!--back-to-top end-->

 
<!-- inject js start -->

<!--== jquery -->
<script src="frontend/js/theme.js"></script>

<!--== theme-plugin -->
<script src="frontend/js/theme-plugin.js"></script>

<!--== color-customize -->
<script src="frontend/js/color-customize/color-customizer.js"></script> 

<!--== theme-script -->
<script src="frontend/js/theme-script.js"></script>

<!-- inject js end -->

</body>


</html>