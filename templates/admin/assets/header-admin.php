<!-- Main Header-->
<header class="boxcar-header header-style-v1 header-default">
    <div class="header-inner">
        <div class="inner-container pe-0">
            <!-- Main box -->
            <div class="c-box">
                <div class="logo-inner">
                    <div class="logo"><a href="/">
                            <div class="logo-div"></div>
                        </a></div>
                    <div class="layout-search">
                        <div class="search-box">
                            <svg class="icon" width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.29301 1.2876C3.9872 1.2876 1.29431 3.98048 1.29431 7.28631C1.29431 10.5921 3.9872 13.2902 7.29301 13.2902C8.70502 13.2902 10.0036 12.7954 11.03 11.9738L13.5287 14.4712C13.6548 14.5921 13.8232 14.6588 13.9979 14.657C14.1725 14.6552 14.3395 14.5851 14.4631 14.4617C14.5867 14.3382 14.6571 14.1713 14.6591 13.9967C14.6611 13.822 14.5947 13.6535 14.474 13.5272L11.9753 11.0285C12.7976 10.0006 13.293 8.69995 13.293 7.28631C13.293 3.98048 10.5988 1.2876 7.29301 1.2876ZM7.29301 2.62095C9.87824 2.62095 11.9584 4.70108 11.9584 7.28631C11.9584 9.87153 9.87824 11.9569 7.29301 11.9569C4.70778 11.9569 2.62764 9.87153 2.62764 7.28631C2.62764 4.70108 4.70778 2.62095 7.29301 2.62095Z" fill="white"></path>
                            </svg>
                            <input type="search" placeholder="&nbsp;&nbsp;Search Cars e.g. Audi Q7" class="show-search" name="name" tabindex="2" value="" aria-required="true" required="">

                        </div>
                        <div class="box-content-search" id="box-content-search">
                            <ul class="box-car-search">

                                <!--- DYNAMIC CONTENT --->

                            </ul>
                            <a href="inventory-page-single.html" class="btn-view-search">
                                View Details
                                <svg width="14" height="14" viewbox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_3114_6864)">
                                        <path d="M13.6109 0H5.05533C4.84037 0 4.66643 0.173943 4.66643 0.388901C4.66643 0.603859 4.84037 0.777802 5.05533 0.777802H12.6721L0.113697 13.3362C-0.0382246 13.4881 -0.0382246 13.7342 0.113697 13.8861C0.18964 13.962 0.289171 14 0.388666 14C0.488161 14 0.587656 13.962 0.663635 13.8861L13.222 1.3277V8.94447C13.222 9.15943 13.3959 9.33337 13.6109 9.33337C13.8259 9.33337 13.9998 9.15943 13.9998 8.94447V0.388901C13.9998 0.173943 13.8258 0 13.6109 0Z" fill="#405FF2"></path>
                                    </g>
                                    <defs>
                                        <clippath id="clip0_3114_6864">
                                            <rect width="14" height="14" fill="white"></rect>
                                        </clippath>
                                    </defs>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!--Nav Box-->
                <div class="nav-out-bar">
                    <nav class="nav main-menu">
                        <ul class="navigation" id="navbar">
                            <li>
                                <span>
                                    <a href="/"><span><i class="fa-thin fa-lg fa-house"></i>&nbsp;&nbsp;Home</span></a>
                                </span>
                            </li>
                            <li class="current-dropdown">
                                <span>
                                    <i class="fa-thin fa-lg fa-cars"></i>&nbsp;&nbsp;&nbsp;Inventory&nbsp;&nbsp;<i class="fa-thin fa-lg fa-angle-down"></i>
                                </span>

                                <ul class="dropdown">
                                    <li><a href="/inventory"><i class="fa-thin fa-lg fa-cars" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;All Cars</a></li>
                                    <li><a href="/inventory?sort_by[vehicle_price]=descending"><i class="fa-thin fa-lg fa-gauge-max" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Premium Cars</a></li>
                                    <li><a href="/inventory"><i class="fa-thin fa-lg fa-badge-dollar" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Priced To Go Special</a></li>
                                    <li><a href="/inventory?vehicle_price[min]=20000&vehicle_price[max]=450000&sort_by[vehicle_price]=descending"><i class="fa-solid fa-lg fa-tags" style="color: #FFD700; transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Cars Under R450k</a></li>
                                    <li><a href="/inventory?vehicle_price[min]=20000&vehicle_price[max]=300000&sort_by[vehicle_price]=descending"><i class="fa-solid fa-lg fa-tags" style="color: #C0C0C0; transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Cars Under R300k</a></li>
                                    <li><a href="/inventory?vehicle_price[min]=20000&vehicle_price[max]=150000&sort_by[vehicle_price]=descending"><i class="fa-solid fa-lg fa-tags" style="color: #CD7F32; transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Cars Under R150k</a></li>
                                </ul>
                            </li>
                            <li class="current-dropdown">
                                <span>
                                    <i class="fa-thin fa-lg fa-building-columns"></i>&nbsp;&nbsp;Finance&nbsp;&nbsp;<i class="fa-thin fa-lg fa-angle-down"></i>
                                </span>

                                <ul class="dropdown">
                                    <li><a href="/finance/arrange-finance"><i class="fa-thin fa-lg fa-money-check-dollar-pen" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Arrange Finance</a></li>
                                    <li><a href="/finance/warranties"><i class="fa-thin fa-lg fa-award" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Warranties & Service Plans</a></li>
                                    <li><a href="/finance/we-buy-cars"><i class="fa-thin fa-lg fa-money-bill-trend-up" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;We Buy Cars</a></li>
                                </ul>
                            </li>
                            <li class="current-dropdown">
                                <span>
                                    <i class="fa-thin fa-lg fa-list-timeline"></i>&nbsp;&nbsp;About&nbsp;&nbsp;<i class="fa-thin fa-lg fa-angle-down"></i>
                                </span>

                                <ul class="dropdown">
                                    <li><a href="/about/about-us"><i class="fa-thin fa-lg fa-list-timeline"></i>&nbsp;&nbsp;About Us</a></li>
                                    <li><a href="/about/mission-statement"><i class="fa-thin fa-lg fa-rectangles-mixed" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Mission Statement</a></li>
                                    <li><a href="/about/meet-our-team"><i class="fa-thin fa-lg fa-people-group" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Meet Our Team</a></li>
                                    <li><a href="/about/client-reviews"><i class="fa-thin fa-lg fa-star" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Client Reviews</a></li>
                                    <li><a href="/about/join-primo-executive"><i class="fa-thin fa-lg fa-location-dot" style="transform: rotateY(180deg) scaleX(-1);"></i>&nbsp;&nbsp;Join The Team At Primo Executive Cars</a></li>
                                </ul>
                            </li>
                            <li>
                                <span>
                                    <a href="/blog"><i class="fa-thin fa-lg fa-blog"></i>&nbsp;&nbsp;Blog</a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="/contact"><i class="fa-thin fa-lg fa-address-book"></i>&nbsp;&nbsp;Contact</a>
                                </span>
                            </li>
                            <li class="dark-mode-switch">
                                <input type="checkbox" id="dark-mode-switch" />

                                <div class="switch-btn">
                                    <label for="dark-mode-switch">
                                        <div class="icons">
                                            <img src="/assets/default/image/moon-icon.png" alt="moon" />
                                            <img src="/assets/default/image/sun-icon.png" alt="sun" />
                                        </div>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <!-- Main Menu End-->
                </div>
                <div class="right-box">
                    <div class="mobile-navigation">
                        <a href="#nav-mobile" title="">
                            <!-- <i class="fa fa-bars"></i> -->
                            <svg width="22" height="11" viewbox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="22" height="2" fill="white"></rect>
                                <rect y="9" width="22" height="2" fill="white"></rect>
                            </svg>
                        </a>
                    </div>
                    <div class="dark-mode-switch ms-3 me-2 d-xl-none">
                        <input type="checkbox" id="dark-mode-switch-mobile" />
                        <div class="switch-btn">
                            <label for="dark-mode-switch-mobile">
                                <div class="icons">
                                    <img src="/assets/default/image/moon-icon.png" alt="moon" />
                                    <img src="/assets/default/image/sun-icon.png" alt="sun" />
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu  -->
        </div>
    </div>

    <!-- Header Search -->
    <div class="search-popup">
        <span class="search-back-drop"></span>
        <button class="close-search"><span class="fa fa-times"></span></button>

        <div class="search-inner">
            <form method="post" action="index.html">
                <div class="form-group">
                    <input type="search" name="search-field" value="" placeholder="Search..." required="">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Header Search -->

    <div id="nav-mobile"></div>
</header>
<!-- End header-section -->