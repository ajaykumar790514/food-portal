   <!-- Contact Section Begin -->
   <section class="contact spad">
        <div class="container">
            <div class="contact__address">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="contact__address__item">
                            <h6>Prashansha Bakery</h6>
                            <ul>
                                <li>
                                    <span class="icon_pin_alt"></span>
                                    <p>128 B  GUPTA SOCIETY INDIRA NAGAR KALYANPUR-18 NAGAR NIGAM FOOD SAFETY ZONE-23 ,Kanpur Nagar, Uttar Pradesh - 208026</p>
                                </li>
                                <li><span class="icon_headphones"></span>
                                    <p>+91 7054375306</p>
                                </li>
                                <li><span class="icon_mail_alt"></span>
                                    <p>prashanshabakeryknp@gmail.com</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact__text">
                        <h3>Contact With us</h3>
                        <ul>
                            <li>Representatives or Advisors are available:</li>
                            <li>Monday - Friday: 08:00 am – 08:30 pm</li>
                            <li>Saturday: 10:00 am – 16:30 pm</li>
                            <li>Sunday: 10:00 am – 16:30 pm</li>
                        </ul>
                        <img src="img/cake-piece.png" alt="">
                    </div>
                </div>
                <div class="col-lg-8">
                <div class="contact__form">
                <form id="contact-form" action="#" method="post">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" id="name" name="name" placeholder="Name" value="<?php echo set_value('name'); ?>">
                            <div id="name-error" class="text-danger"></div>
                        </div>
                        <div class="col-lg-6">
                            <input type="email" id="email" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
                            <div id="email-error" class="text-danger"></div>
                        </div>
                        <div class="col-lg-12">
                            <input type="number" id="number" name="mobile" placeholder="Mobile" value="<?php echo set_value('mobile'); ?>">
                            <div id="mobile-error" class="text-danger"></div>
                        </div>
                        <div class="col-lg-12">
                            <textarea id="message" name="message" placeholder="Message"><?php echo set_value('message'); ?></textarea>
                            <div id="message-error" class="text-danger"></div>
                            <button type="submit" class="site-btn">Send Us</button>
                        </div>
                    </div>
                </form>
            </div>


                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->