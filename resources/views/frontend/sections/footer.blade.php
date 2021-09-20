<footer class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="footer-content">
                        <h4>About Interesting FX</h4>
                        <p>Neque porro quisquam est qui dolore ipsum quia dolor sit amet, consectet urdipisci
                            velit nec ultricies est mauris quis lorem scelisque justo lacu.
                            <a class="read-more" href="#">Read more>></a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="footer-content">
                        <h4>Our Service</h4>
                        <ul class="list-styled">
                            <li><a href="{{config('constants.wordpress_base_url')}}fees/">Fees</a></li>
                            <li><a href="{{config('constants.wordpress_base_url')}}how-to-get-started/">How to Get Started</a></li>
                            <li><a href="{{config('constants.wordpress_base_url')}}pool-information/">Pool Information</a></li>
                            <li><a href="{{config('constants.wordpress_base_url')}}contact-us/">Contact us</a></li>
                            <li><a href="{{config('constants.wordpress_base_url')}}sitemap/">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="footer-content">
                        <h4>Legal</h4>
                        <ul class="list-styled">
                            <li><a href="{{config('constants.wordpress_base_url')}}faq/">Faqs</a></li>
                            <li><a href="{{config('constants.wordpress_base_url')}}terms/">Terms & Conditions</a></li>
                            <li><a href="{{config('constants.wordpress_base_url')}}privacy-policy/">Privacy Policy</a></li>
                            <li><a href="{{config('constants.wordpress_base_url')}}service/">Service</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="footer-content contact-detail">
                        <h4>Contact</h4>
                        <ul class="list-unstyled info-list">
                            <li>
                                <a href="mailto:settingValue('contact_email')"><span class="icon fa fa-envelope"></span>{{settingValue('contact_email')}}</a>
                            </li>
                            <li>
                                <a href="tel:settingValue('contact_number')"><span class="icon fa fa-phone"></span>{{settingValue('contact_number')}}</a>
                            </li>
                        </ul>
                        <h4 class="social-heading">Social</h4>
                        <ul class="list-unstyled social-list">
                            <li>
                                <a href="{{settingValue('facebook')}}">
                                    <span class="icon-circle fa fa-facebook"></span>
                                </a>
                            </li>
                            <li>
                                <a href="{{settingValue('twitter')}}">
                                    <span class="icon-circle fa fa-twitter"></span>
                                </a>
                            </li>
                            <li>
                                <a href="{{settingValue('google_plus')}}">
                                    <span class="icon-circle fa fa-google-plus"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom copyright">
        <div class="container">
            <div class="copyright-content">
                  <p class="copyright">&copy; {{ date('Y') }} By <a href="#"
                        target="_blank">Corporate hi-tech</a>. All Rights Reserved.</p>
                <div class="chat">
                    <img src="{{asset(env('PUBLIC_URL').'images/chat.png')}}" alt="chat icon" class="img-fluid" />
                </div>
                <div class="asoft">
                    <a href="#">
                        <img src="{{asset(env('PUBLIC_URL').'images/asoft.png')}}" alt="ArhamSoft Logo" class="img-fluid" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>


 