<!doctype html>
<html lang="en">

<head>
    <?php include "includes/_inc_head.php" ?>

    <script>
        function showImage(id) {
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var img = document.getElementById("myImage");
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            img.onclick = function() {
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }
        }
    </script>
</head>

<body class="app sidebar-mini ltr sidenav-toggled dark-mode">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loading..">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!-- APP-HEADER -->
            <?php include "includes/_inc_header.php"; ?>
            <!-- /APP-HEADER -->

            <!--APP-SIDEBAR-->
            <?php include "includes/_inc_sidebar.php"; ?>
            <!--/APP-SIDEBAR-->



            <!-- APP-CONTENT OPEN -->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <div>
                                <h1 class="page-title">Dashboard</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </div>
                            <div class="ms-auto pageheader-btn">
                                <div id="bttn" style="margin-bottom: 20px;"></div>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <?php include "includes/_inc_anpr.php" ?>
                        <!-- ROW-1 END -->

                        <!-- ROW-4 -->
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card ">
                                    <div class="card-header">
                                        <h3 class="card-title mb-0">Entrances</h3>
                                    </div>
                                    <div class="card-body">
                                        <form id="adjustmentForm">
                                            Contrast:
                                            <span id="contrastValue">3.0</span>
                                            <input type="range" min="0" max="3" step="0.1" name="contrast" value="3.0" onchange="document.getElementById('contrastValue').innerText = this.value;"><br>
                                            Brightness:
                                            <span id="brightnessValue">0</span>
                                            <input type="range" min="-100" max="100" name="brightness" value="0" onchange="document.getElementById('brightnessValue').innerText = this.value;"><br>
                                            Histogram Equalization Intensity:
                                            <span id="hist_eq_intensityValue">0.8</span>
                                            <input type="range" min="0" max="1" step="0.1" name="hist_eq_intensity" value="0.8" onchange="document.getElementById('hist_eq_intensityValue').innerText = this.value;"><br>
                                            <input type="button" value="Grab Images (Or Press Space)" onclick="submitForm()">
                                        </form>
                                        <div id="result">test</div>
                                        <div class="table-responsive">
                                            <table id="data-table" class="table table-bordered table-striped text-nowrap mb-0">
                                                <thead class="border-top">
                                                    <tr>
                                                        <th class="bg-transparent border-bottom-0 w-5">ID</th>
                                                        <th class="bg-transparent border-bottom-0">Entry</th>
                                                        <th class="bg-transparent border-bottom-0">Picture</th>
                                                        <th class="bg-transparent border-bottom-0">Zoom</th>
                                                        <th class="bg-transparent border-bottom-0">Scan</th>
                                                        <th class="bg-transparent border-bottom-0">Plate</th>
                                                        <th class="bg-transparent border-bottom-0">Date</th>
                                                        <th class="bg-transparent border-bottom-0">Details</th>
                                                        <th class="bg-transparent border-bottom-0">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="resultsList">
                                                    <?php include "list.php"; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- COL END -->
                        </div>
                        <!-- ROW-4 END -->
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!-- APP-CONTENT END -->
        </div>
        <!-- FOOTER -->
        <?php include "includes/_inc_footer.php"; ?>
        <!-- FOOTER END -->
    </div>
    <!-- end main content-->
    <?php include "includes/_inc_js.php"; ?>
    <?php include "includes/_inc_modals.php"; ?>
    <script>
        function deleteLine(id) {

            swal({
                    title: "Attention",
                    text: "Do you really want to delete ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.get("delete.php?id=" + id, function() {
                            table.row($("#entry-" + id)).remove().draw(false);
                            swal("Entrance deleted", {
                                icon: "success",
                            });
                        });
                    }
                });
        }

        function showImage(src, plate, brand) {
            $("#imagePopup .modal-body").html('<img src="' + src + '" />');
            $("#imagePopup .modal-title").html('<span class="badge bg-success">' + plate + '</span> <span class="badge bg-default">' + brand + '</span>');
            $("#imagePopup").modal('show')
        }

        isDemo = false;
        scanLoop1 = 'off';
        scanLoop2 = 'off';
        scanLoop3 = 'off';
        scanStatus1 = 'off';
        scanStatus2 = 'off';
        scanStatus3 = 'off';

        if (!isDemo) {
            setInterval(function() {
                $("#listResults").load("list.php");
            }, 10000);
        } else {
            $("#listResults").load("list.php");
        }

        //LOOP READER - EXDUL READER
        if (!isDemo) {
            setInterval(function() {

                /*
                $.get("readexdul.php?ip=10.10.1.10", function(data) {
                    scanLoop1 = data;
                    $("#loopscan1").html(scanLoop1);

                    if (scanLoop1 == 'off') {
                        $("#lpr1").css({
                            "border": "2px solid white"
                        });
                        scanStatus1 = 'off';
                    } else {
                        $("#lpr1").css({
                            "border": "2px solid green"
                        });
                        //START LPR CATCHING
                        if (scanStatus1 == 'off') {
                            scanStatus1 = 'scanning';
                            $.get('snaplpr.php?ip=10.10.1.12', function(path) {
                                startLPR(1, path)
                            });
                        }
                    }
                });
                */

                $.get("readexdul.php?ip=10.10.3.10", function(data) {
                    scanLoop2 = data;
                    $("#loopscan2").html(scanLoop2);

                    if (scanLoop2 == 'off') {
                        $("#lpr2").css({
                            "border": "2px solid white"
                        });
                        scanStatus2 = 'off';
                    } else {
                        $("#lpr2").css({
                            "border": "2px solid green"
                        });
                        //START LPR CATCHING
                        if (scanStatus2 == 'off') {
                            scanStatus2 = 'scanning';
                            $.get('snaplpr.php?ip=10.10.2.12', function(path) {
                                startLPR(2, path)
                            });
                        }
                    }
                });

                /*
                $.get("readexdul.php?ip=10.10.2.10", function(data) {
                    scanLoop3 = data;
                    $("#loopscan3").html(scanLoop3);

                    if (scanLoop3 == 'off') {
                        $("#lpr3").css({
                            "border": "2px solid white"
                        });
                        scanStatus3 = 'off';
                    } else {
                        $("#lpr3").css({
                            "border": "2px solid green"
                        });
                        //START LPR CATCHING
                        if (scanStatus3 == 'off') {
                            scanStatus3 = 'scanning';
                            $.get('snaplpr.php?ip=10.10.3.12', function(path) {
                                startLPR(3, path)
                            });
                        }
                    }
                });
                */

            }, 500);
        }

        //GET IMAGE TO BE SENT TO ARH CLOUD SERVICE
        const urlToObject = async (filepath, scannerId) => {
            const response = await fetch(filepath);
            const blob = await response.blob();
            const file = new File([blob], 'image.jpg', {
                type: blob.type
            });
            const dataTransfer = new DataTransfer();
            const fileInputElement = document.getElementById('scanImage' + scannerId);
            dataTransfer.items.add(file);
            fileInputElement.files = dataTransfer.files;
            //console.log(file);
        }

        //SEND QUERY TO AHR COULD SERVICE
        function startLPR(scannerId, path) {
            urlToObject(path, scannerId).then(function() {
                const formData = new FormData();
                formData.append('service', 'anpr,mmr');
                const fileInputElement = document.getElementById('scanImage' + scannerId);
                console.log(fileInputElement.files[0]);
                formData.append('image', fileInputElement.files[0]);
                formData.append('maxreads', 1);
                const options = {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Api-Key': 'kYQhj3VUCC9R3QIDtqjif9kFYMl0LCRG3A1MDVvd',
                    },
                };
                fetch('https://api.cloud.adaptiverecognition.com/vehicle/afr', options)
                    .then(function(response) {
                        //console.log('Response received:', response)
                        return response.json();
                    }).then(function(results) {
                        console.log(results.data.vehicles[0]);
                        vehicle = results.data.vehicles[0];
                        color = "0,0,0";
                        mark = "unknown";
                        category = "unknown";
                        if (vehicle.hasOwnProperty('mmr')) {
                            color = vehicle['mmr']['color']['r'] + "," + vehicle['mmr']['color']['g'] + "," + vehicle['mmr']['color']['b'];
                            mark = vehicle['mmr']['make'];
                            category = vehicle['mmr']['category'];
                        }

                        extendedPlateFrame = vehicle['bounds']['extendedPlateFrame'];
                        bounds = extendedPlateFrame['bottomLeft']['x'] + ',' + extendedPlateFrame['bottomLeft']['y'] + ',' + extendedPlateFrame['bottomRight']['x'] + ',' + extendedPlateFrame['bottomRight']['y'] + ',' + extendedPlateFrame['topLeft']['x'] + ',' + extendedPlateFrame['topLeft']['y'] + ',' + extendedPlateFrame['topRight']['x'] + ',' + extendedPlateFrame['topRight']['y'];
                        $.get("save.php", {
                            'scannerId': scannerId,
                            'plate': vehicle['plate']['separatedText'],
                            'picture': path,
                            'description': vehicle['plate']['country'],
                            'color': color,
                            'category': category,
                            'mark': mark,
                            'bounds': bounds,
                            'width': (extendedPlateFrame['topRight']['x'] - extendedPlateFrame['topLeft']['x']),
                            'height': (extendedPlateFrame['bottomRight']['y'] - extendedPlateFrame['topRight']['y']),
                            x: extendedPlateFrame['topLeft']['x'],
                            y: extendedPlateFrame['topLeft']['y']
                        }, function(data) {
                            console.log(data);
                        })
                    })
                    .catch((err) => console.log('Error:', err));
            });
        }

        //------- UNDER VEHICLES SCANNER ---------//
        function startSCAN(scannerId) {
            const grabTotal = 50;
            const grabTime = 100;
            var i = 0;
            var garbInterval = setInterval(function() {
                if (i < grabTotal) {
                    $.get("snapcam.php?scannerId=" + scannerId + "&id=" + i, function() {
                        $.get("merge.php");
                    });
                } else {
                    clearInterval(garbInterval);
                }

                i++;
                console.log("grabbing : " + i);
            }, grabTime);
        }

        $(document).ready(function() {
            // Submit the form using the space key
            $(document).keydown(function(event) {
                if (event.which == 32) {
                    submitForm();
                }
            });

            $('#adjustmentForm').submit(function(event) {
                event.preventDefault();
                startGrabbing();
            });
        });

        function submitForm() {
            $('#adjustmentForm').submit();
        }

        //GRABBER
        function startGrabbing() {
            console.log("Start Grabbing");
            $.ajax({
                url: 'http://localhost:5000/grab_images',
                method: 'POST',
                data: $('#adjustmentForm').serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        console.log(response.image)
                        //$('#result').html('<img height="400" src="http://localhost:5000/' + response.image + '" alt="Captured Image" />');
                    } else {
                        $('#result').text(response.message);
                    }
                    console.log("Finish");
                },
                error: function() {
                    $('#result').text('An error occurred while processing the request.');
                    console.log("Finish");
                }
            });
        }

        /*setTimeout(function(){
            $.get('prepend.php', function(data){
                table.row.add(JSON.parse(data)).draw().node();
            })
        }, 1000);*/
    </script>
</body>

</html>