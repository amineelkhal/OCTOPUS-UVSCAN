<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4 lprDiv">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="">ANPR</h6>
                                <h4 class="mb-2 number-font">INDIA 14</h4>
                                <p class="text-muted mb-0">
                                    Loop Status <span id="loopscan1" class="badge bg-danger">OFF</span>
                                </p>
                                <input type="file" id="scanImage1" style="display: none;" />
                            </div>
                            <div class="col col-auto">
                                <div class="lprcam" id="lpr1">
                                    <!--<img width="100%" src="http://10.10.2.12:9901/livepic.mjpeg?id=92">-->
                                    <!--<img width="100%" src="assets/nolpr.jpg" data-intended-src="http://10.10.2.12:9901/livepic.mjpeg?id=92">-->
                                    <img width="100%" src="http://10.10.2.12:9901/livepic.mjpeg?id=92" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4 lprDiv">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="">ANPR</h6>
                                <h4 class="mb-2 number-font">INDIA 5</h4>
                                <p class="text-muted mb-0">
                                    Loop Status <span id="loopscan2" class="badge bg-danger">OFF</span>
                                </p>
                                <input type="file" id="scanImage2" style="display: none;" />
                            </div>
                            <div class="col col-auto">
                                <div class="lprcam" id="lpr2">
                                    <!--<img width="100%" src="assets/nolpr.jpg" data-intended-src="http://10.10.3.12:9901/livepic.mjpeg?id=92">-->
                                    <!--<img width="100%" src="http://10.10.3.12:9901/livepic.mjpeg?id=92" onerror="this.onerror=null;this.src='assets/nolpr.jpg';">-->
                                    <img width="100%" src="http://10.10.3.12:9901/livepic.mjpeg?id=92" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4 lprDiv">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="">ANPR</h6>
                                <h4 class="mb-2 number-font">SPA</h4>
                                <p class="text-muted mb-0">
                                    Loop Status <span id="loopscan3" class="badge bg-danger">OFF</span>
                                </p>
                                <input type="file" id="scanImage3" style="display: none;" />
                            </div>
                            <div class="col col-auto">
                                <div class="lprcam" id="lpr3">
                                    <a href="assets/nolpr.jpg" data-lightbox="image-1" data-title="Image Title">
                                        <img width="100%" src="assets/nolpr.jpg" alt="Thumbnail">
                                    </a>
                                    <!--<img width="100%" src="http://10.10.1.12:9901/livepic.mjpeg?id=92" onerror="this.onerror=null;this.src='assets/nolpr.jpg';">-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xl-12">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <marquee behavior="" direction="" style="font-size: 20px;">
                                    <span class="">Total Vehicles Today</span>
                                    <span id="today_count" class="mb-2 number-font badge bg-warning">---</span>
                                    <span class="">Total Vehicles This month</span>
                                    <span id="month_count" class="mb-2 number-font badge bg-success">---</span>
                                </marquee>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .lprcam {
        width: 250px;
        height: 142px;
        background-color: blue;
        border: 2px solid #fff;
    }

    .lprDiv {
        transition: transform 0.3s ease-in-out;
    }

    .lprDiv:hover {
        transform: scale(1.3);
        /* Adjust the scale factor as desired */
        position: relative;
        z-index: 8;
    }
</style>

<!-- Add Lightbox2 CSS and JS files -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
    });
</script>


<script>
    window.onload = function() {
        // Function to check the image's accessibility
        function checkImage(src, successCallback, errorCallback) {
            let img = new Image();
            img.onload = successCallback;
            img.onerror = errorCallback;
            img.src = src;
        }

        // Check image accessibility for each image and update the src if accessible
        function updateImageSrc(imgElement) {
            let intendedSrc = $(imgElement).data('intended-src'); // Using a 'data-' attribute to store the original/intended src
            checkImage(intendedSrc,
                function() {
                    $(imgElement).attr('src', intendedSrc);
                },
                function() {
                    // Do nothing, or you can also set to a default image 
                    //console.log(imgElement);
                    $(imgElement).attr('src', "assets/nolpr.jpg");
                    console.log("No LPR")
                }
            );
        }

        // Iterate over images and check accessibility
        $('.lprcam img').each(function() {
            //updateImageSrc(this);
        });

        // Periodically re-check every 5 minutes (300000ms)
        /*setInterval(function() {
            console.log("Checking...")
            $('.lprcam img').each(function() {
                updateImageSrc(this);
            });

        }, 10000);*/
    }
</script>