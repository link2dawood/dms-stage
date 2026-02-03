<?php 
   function divi_child_business_hours_menu() {
            $hours  = '<div class="header-hours justify-content-between d-none">
                <div class="w-50">
                    <h3 class="text-uppercase font-helvetica font-weight-bold font-sm pb-0 mb-20 text-grey-3">Store Hours</h3>
                    <div class="mb-30 hours-wrapper">
                        <div class="mb-20 d-flex justify-content-between pr-3" data-day="1">
                            <span class="text-grey-3 font-helvetica font-sm">Monday</span>
                            <span class="text-grey-3 font-helvetica font-sm">8:00 - 6:00 pm</span>
                        </div>
                        <div class="mb-20 d-flex justify-content-between pr-3" data-day="2">
                            <span class="text-grey-3 font-helvetica font-sm">Tuesday</span>
                            <span class="text-grey-3 font-helvetica font-sm">8:00 - 6:00 pm</span>
                        </div>
                        <div class="mb-20 d-flex justify-content-between pr-3" data-day="3">
                            <span class="text-grey-3 font-helvetica font-sm">Wednesday</span>
                            <span class="text-grey-3 font-helvetica font-sm">8:00 - 6:00 pm</span>
                        </div>
                        <div class="mb-20 d-flex justify-content-between pr-3" data-day="4">
                            <span class="text-grey-3 font-helvetica font-sm">Thursday</span>
                            <span class="text-grey-3 font-helvetica font-sm">8:00 - 6:00 pm</span>
                        </div>
                        <div class="mb-20 d-flex justify-content-between pr-3" data-day="5">
                            <span class="text-grey-3 font-helvetica font-sm">Friday</span>
                            <span class="text-grey-3 font-helvetica font-sm">8:00 - 6:00 pm</span>
                        </div>
                        <div class="mb-20 d-flex justify-content-between pr-3" data-day="6">
                            <span class="text-grey-3 font-helvetica font-sm">Saturday</span>
                            <span class="text-grey-3 font-helvetica font-sm">9:00 - 4:00 pm</span>
                        </div>
                        <div class="mb-20 d-flex justify-content-between pr-3" data-day="0">
                            <span class="text-grey-3 font-helvetica font-sm">Sunday</span>
                            <span class="text-grey-3 font-helvetica font-sm">closed</span>
                        </div>
                    </div>
                    <h2 class="mb-2 text-primary font-weight-bold font-md font-helvetica pb-0 nested-popup-wrapper position-relative">Service & Parts Hours <i class="fa-regular fa-clock"></i>
                        <div class="d-none position-absolute w-100 border border-dark bg-white p-2 rounded shadow-primary nested-popup">
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Monday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">7:00 - 5:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Tuesday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">7:00 - 5:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Wednesday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">7:00 - 5:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Thursday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">7:00 - 5:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Friday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">7:00 - 5:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Saturday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">8:00 - 4:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Sunday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">Closed</span>
                            </div>
                        </div>
                    </h2>
                    <span class="d-flex font-md">Schedule Service 
                    <a class="font-xs" href="'.site_url().'/service-and-parts/schedule-express-service-durango-co/">HERE</a>.</span>
                    <h2 class="font-md font-helvetica nested-popup-wrapper font-weight-bold text-primary mb-20 position-relative">Durango Motor Co Hours <i class="fa-regular fa-clock"></i>
                        <div class="nested-popup d-none position-absolute w-100 border border-dark bg-white p-2 rounded shadow-primary">
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Monday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">8:00 - 6:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Tuesday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">8:00 - 6:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Wednesday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">8:00 - 6:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Thursday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">8:00 - 6:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Friday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">8:00 - 6:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Saturday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">9:00 - 4:00 pm</span>
                            </div>
                            <div class="mb-15 d-flex justify-content-between pr-3">
                                <span class="font-helvetica font-sm font-weight-light text-grey-3">Sunday</span>
                                <span class="text-grey-3 font-helvetica font-sm font-weight-light">Closed</span>
                            </div>
                        </div>
                    </h2>
                </div>
                <div class="d-flex flex-column w-50">
                    <h2 class="text-uppercase font-helvetica mb-20 font-weight-bold font-sm pb-0 mb-20 text-grey-3">Directions</h2>
                    <a href="https://goo.gl/maps/3138ZmwJxDZr8GHA7" target="_blank"> 1240 Escalante Dr. Durango, CO 81303</a>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3176.861462363776!2d-107.86379198530084!3d37.22726355124376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x873c0332609fc183%3A0x2c671b0ad9d35215!2s1240%20Escalante%20Dr%2C%20Durango%2C%20CO%2081303%2C%20USA!5e0!3m2!1sen!2s!4v1658910684136!5m2!1sen!2s"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" class="h-100 flex-grow-1 hours-iframe"></iframe>
                </div>
            </div>';
    return $hours;
    }