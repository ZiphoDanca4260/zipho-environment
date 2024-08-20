<!-- BANNER SECTION -->
<section class="boxcar-banner-section-v1 admin-alt">
    <div class="banner-content top-100">
        <h2 class="wow fadeInUp mb-1 pb-1 mt-5 pt-5 pb-md-5 mb-md-5 mt-md-0 pt-md-0" data-wow-delay="100ms">Are You Authorized?</h2>
    </div>
</section>
<!-- BANNER SECTION END -->

<section>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card h-auto p-3 shadow-lg">
                    <div class="card-body" data-fr-section="login-form">
                        <h2 class="card-title text-center mb-4">Login</h2>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" data-fr-name0="user_mail" placeholder="Enter email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" data-fr-name0="user_password" placeholder="Password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" onclick="logIn($(this))">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>