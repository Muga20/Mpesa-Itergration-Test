@include ('inc.header')


<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Donate Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">

                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-inline-block rounded-pill bg-secondary text-primary py-1 px-3 mb-3">Donate Now</div>
                    <h1 class="display-6 mb-5">Thanks For The Results Achieved With You</h1>
                    <p class="mb-0">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam
                        et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat
                        amet</p>
                </div>



                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <div class="h-100 bg-secondary p-5">
                        <form>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="btn-group d-flex justify-content-around">
                                        <input type="radio" class="btn-check" name="paymentMethod" id="mpesa"
                                            checked>
                                        <label class="btn btn-light py-3" for="mpesa">Mpesa</label>

                                        <input type="radio" class="btn-check" name="paymentMethod" id="paypal">
                                        <label class="btn btn-light py-3" for="paypal">Paypal</label>

                                        <input type="radio" class="btn-check" name="paymentMethod" id="card">
                                        <label class="btn btn-light py-3" for="card">Card</label>
                                    </div>
                                </div>

                                <div class="col-12" id="mpesaFields" style="display: none;">
                                    <!-- Fields specific to Mpesa payment method -->
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-light border-0" id="mpesaNumber"
                                            placeholder="Mpesa Number">
                                        <label for="mpesaNumber">Mpesa Number</label>
                                    </div>
                                </div>

                                <div class="col-12" id="paypalFields" style="display: none;">
                                    <!-- Fields specific to Paypal payment method -->
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-light border-0" id="paypalUsername"
                                            placeholder="Paypal Username">
                                        <label for="paypalUsername">Paypal Username</label>
                                    </div>
                                </div>

                                <div class="col-12" id="cardFields" style="display: none;">
                                    <!-- Fields specific to Card payment method -->
                                    <div class="form-floating">
                                        <input  type="text" class="form-control bg-light border-0"
                                            id="cardNumber" placeholder="Card Number">
                                        <label for="cardNumber">Card Number</label>
                                    </div>
                                </div>



                                <div class="col-12" id="amountSection">
                                    <div class="btn-group d-flex justify-content-around">
                                        <input type="radio" class="btn-check" name="amount" id="amount1" checked>
                                        <label class="btn btn-light py-3" for="amount1">$10</label>

                                        <input type="radio" class="btn-check" name="amount" id="amount2">
                                        <label class="btn btn-light py-3" for="amount2">$20</label>

                                        <input type="radio" class="btn-check" name="amount" id="amount3">
                                        <label class="btn btn-light py-3" for="amount3">$30</label>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <button class="btn btn-primary px-5" style="height: 60px;">
                                        Donate Now
                                        <div
                                            class="d-inline-flex btn-sm-square bg-white text-primary rounded-circle ms-2">
                                            <i class="fa fa-arrow-right"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    // JavaScript code to toggle the visibility of payment method fields
                    const mpesaFields = document.getElementById("mpesaFields");
                    const paypalFields = document.getElementById("paypalFields");
                    const cardFields = document.getElementById("cardFields");

                    const mpesaRadio = document.getElementById("mpesa");
                    const paypalRadio = document.getElementById("paypal");
                    const cardRadio = document.getElementById("card");

                    mpesaRadio.addEventListener("change", function() {
                        mpesaFields.style.display = "block";
                        paypalFields.style.display = "none";
                        cardFields.style.display = "none";
                    });

                    paypalRadio.addEventListener("change", function() {
                        mpesaFields.style.display = "none";
                        paypalFields.style.display = "block";
                        cardFields.style.display = "none";
                    });

                    cardRadio.addEventListener("change", function() {
                        mpesaFields.style.display = "none";
                        paypalFields.style.display = "none";
                        cardFields.style.display = "block";
                    });
                </script>






            </div>
        </div>
    </div>
    <!-- Donate End -->

    @include ('inc.footer')
