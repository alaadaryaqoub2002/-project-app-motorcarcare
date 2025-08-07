<footer class="ftco-footer ftco-bg-dark" style="background-color: #026dfe; padding: 40px 0;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="ftco-footer-widget mb-2">
                    <h2 class="ftco-heading-2"><a href="#" class="logo" style="color: #ffffff;">Motor<span style="color: #ffffff;">CarCare</span></a></h2>
                    <p style="font-size: 14px; color: #ffffff;">Your trusted partner for car maintenance and care.</p>
                    <ul class="ftco-footer-social list-unstyled mt-3">
                        <li class="ftco-animate" style="display: inline;"><a href="#"><span class="icon-twitter" style="color: #ffffff; font-size: 20px; margin-right: 10px;"></span></a></li>
                        <li class="ftco-animate" style="display: inline;"><a href="#"><span class="icon-facebook" style="color: #ffffff; font-size: 20px; margin-right: 10px;"></span></a></li>
                        <li class="ftco-animate" style="display: inline;"><a href="#"><span class="icon-instagram" style="color: #ffffff; font-size: 20px;"></span></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ftco-footer-widget mb-2 ml-md-5">
                    <h2 class="ftco-heading-2" style="color: #ffffff;">Information</h2>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="py-1 d-block" style="color: #ffffff;">About Us</a></li>
                        <li><a href="services.php" class="py-1 d-block" style="color: #ffffff;">Our Services</a></li>
                        <li><a href="#" class="py-1 d-block" style="color: #ffffff;">Terms and Conditions</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ftco-footer-widget mb-2">
                    <h2 class="ftco-heading-2" style="color: #ffffff;">Customer Support</h2>
                    <ul class="list-unstyled">
                        <li><a href="#" class="py-1 d-block" style="color: #ffffff;">FAQ</a></li>
                        <li><a href="#" class="py-1 d-block" style="color: #ffffff;">Payment Options</a></li>
                        <li><a href="#" class="py-1 d-block" style="color: #ffffff;">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ftco-footer-widget mb-2">
                    <h2 class="ftco-heading-2" style="color: #ffffff;">Have Questions?</h2>
                    <div class="block-23 mb-3">
                        <ul class="list-unstyled">
                            <li><span class="icon icon-map-marker" style="color: #ffffff; margin-right: 5px;"></span><span class="text" style="color: #ffffff;">Motor PS</span></li>
                            <li><a href="tel:+1234567890" style="color: #ffffff;"><span class="icon icon-phone" style="margin-right: 5px;"></span><span class="text">+972 598030511</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p style="color: #ffffff; font-size: 14px;">&copy; 2023 MotorCarCare. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<!-- Core Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Additional Plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.stellar/0.6.2/jquery.stellar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-animateNumber/0.0.14/jquery.animateNumber.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>

<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="mWdAoSMUhex8pl2xXW36u";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>
<!-- Firebase (if needed) -->
<?php if(isset($use_firebase) && $use_firebase): ?>
<script src="js/firebase-config.js"></script>
<?php endif; ?>
<!-- Google Maps (if needed) -->
<?php if(isset($include_google_maps) && $include_google_maps): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<?php endif; ?>
<!-- Custom Scripts -->
<script src="../js/common.js"></script>
<script src="../js/main.js"></script>

