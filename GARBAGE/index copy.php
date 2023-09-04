<!doctype html>
<html lang="en">

<head>
    <?php include "includes/_inc_head.php" ?>
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

                                        <div id="imageGallery" class="table-responsive">

                                            <table id="entrancesTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Scanner</th>
                                                        <th>Plate</th>
                                                        <th>Picture</th>
                                                        <th>Scan</th>
                                                        <th>Country</th>
                                                        <th>Color</th>
                                                        <th>Category</th>
                                                        <th>Mark</th>
                                                        <th>Plate Miniature</th>
                                                        <th>Entry Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data will be filled in here by DataTables -->
                                                </tbody>
                                            </table>

                                        </div>
                                        <input type="button" value="Simulate scan LPR" onclick="SimulateLPR()">
                                        <input type="button" value="Simulate Refresh" onclick="refreshDataTable()">
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
    <link rel="stylesheet" href="assets/css/viewer.min.css">
    <script src="assets/js/viewer.min.js"></script>
    <script>
        isDemo = false;

        scanLoop1 = 'off';
        scanLoop2 = 'off';
        scanLoop3 = 'off';
        scanStatus1 = 'off';
        scanStatus2 = 'off';
        scanStatus3 = 'off';

        //LOAD ENTRANCES
        let entrancesTable;
        $(document).ready(function() {
            entrancesTable = $('#entrancesTable').DataTable({
                "ajax": "fetch_entrances.php",
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "scanner"
                    },
                    {
                        "data": "plate"
                    },
                    {
                        "data": "picture",
                        "render": function(data, type, row) {
                            return '<img src="' + data + '" width="50" height="50" alt="Picture" class="img-thumbnail" onclick="showModalImage(\'' + data + '\')">';
                        }
                    },
                    {
                        "data": "scan",
                        "render": function(data, type, row) {
                            return '<img src="' + data + '" width="50" height="50" alt="Scan" class="img-thumbnail" onclick="showModalImage(\'' + data + '\')">';
                        }
                    },
                    {
                        "data": "description", // assuming country data is within the description
                        "title": "Country",
                        "render": function(data, type, row) {
                            // Transform the description data to get the country value
                            // For the sake of this example, let's say you just return the data as-is
                            return data;
                        }
                    },
                    {
                        "data": "color",
                        "render": function(data, type, row) {
                            // Assuming `data` contains the RGB value in the format 'r,g,b'
                            return '<div style="background-color: rgb(' + data + '); width: 30px; height: 30px;"></div>';
                        }
                    },
                    {
                        "data": "category"
                    },
                    {
                        "data": "mark"
                    },
                    {
                        "data": "picture",
                        "title": "Plate Miniature",
                        "render": function(data, type, row) {
                            const miniaturePath = data ? data + '.png' : null;
                            return '<img src="' + miniaturePath + '" width="50" height="50" alt="Plate Miniature" class="img-thumbnail" onclick="showModalImage(\'' + miniaturePath + '\')">';
                        }
                    },
                    {
                        "data": "entry_date",
                        "render": function(data, type, row) {
                            // Assuming you want to keep it in its raw form. Modify as needed.
                            return data;
                        }
                    }
                ],
                "order": [
                    [10, 'desc']
                ]
            });
        });

        function refreshDataTable() {
            entrancesTable.ajax.reload(); // Use the stored reference to reload the table.
        }

        // Setup the refresh interval here
        setInterval(function() {
            entrancesTable.ajax.reload(null, false); // Update the reference to use the renamed variable.
        }, 2000);


        let viewer;

        function showModalImage(imgSrc) {
            $("#modalImage").attr("src", imgSrc);
            $("#imageModal").modal("show");

            viewer = new Viewer(document.getElementById('modalImage'));
        }

        $("#imageModal").on('hidden.bs.modal', function() {
            if (viewer) {
                viewer.destroy();
            }
        });

        function SimulateLPR() {
            $.get('snaplpr.php?ip=10.10.3.12', function(path) {
                startLPR(2, path)
            });
        }

        //LOOP READER - EXDUL READER
        if (!isDemo) {
            setInterval(function() {

                
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

                            pictureName = "10-10-1-12-" + Date.now() + ".jpg";

                            startGrabbing(pictureName);

                            $.get('snaplpr.php?ip=10.10.1.12&name=' + pictureName, function(path) {
                                startLPR(1, pictureName)
                            });
                        }
                    }
                });
                

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
                            pictureName = "10-10-3-12-" + Date.now() + ".jpg";

                            startGrabbing(pictureName);

                            $.get('snaplpr.php?ip=10.10.3.12&name=' + pictureName, function(path) {
                                startLPR(2, pictureName)
                            });
                            
                        }
                    }
                });

                
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

                            pictureName = "10-10-2-12-" + Date.now() + ".jpg";

                            startGrabbing(pictureName);

                            $.get('snaplpr.php?ip=10.10.2.12&name=' + pictureName, function(path) {
                                startLPR(3, pictureName)
                            });
                        }
                    }
                });
                

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
            urlToObject('assets/lprsnaps/' + path, scannerId).then(function() {
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
                        console.log('Response received:', response)
                        return response.json();
                    }).then(function(results) {
                        console.log(results.data.vehicles[0]);
                        vehicle = results.data.vehicles[0];
                        color = "0,0,0";
                        mark = "unknown";
                        category = "unknown";
                        if (vehicle && vehicle.hasOwnProperty('mmr') && vehicle['mmr'].hasOwnProperty('color')) {
                            color = vehicle['mmr']['color']['r'] + "," + vehicle['mmr']['color']['g'] + "," + vehicle['mmr']['color']['b'];
                            mark = vehicle['mmr']['make'];
                            category = vehicle['mmr']['category'];
                        }

                        bounds = "0";

                        if (vehicle && vehicle.hasOwnProperty('bounds') && vehicle['bounds'].hasOwnProperty('extendedPlateFrame')) {
                            extendedPlateFrame = vehicle['bounds']['extendedPlateFrame'];
                            bounds = extendedPlateFrame['bottomLeft']['x'] + ',' + extendedPlateFrame['bottomLeft']['y'] + ',' + extendedPlateFrame['bottomRight']['x'] + ',' + extendedPlateFrame['bottomRight']['y'] + ',' + extendedPlateFrame['topLeft']['x'] + ',' + extendedPlateFrame['topLeft']['y'] + ',' + extendedPlateFrame['topRight']['x'] + ',' + extendedPlateFrame['topRight']['y'];
                        }

                        let separatedText;
                        let countryText;

                        if (vehicle && vehicle.hasOwnProperty('plate') && vehicle['plate'].hasOwnProperty('separatedText')) {
                            separatedText = vehicle['plate']['separatedText'];
                            vehicle['plate']['country']
                        } else {
                            // Handle the case where the properties are not present
                            // You can either set a default value or handle the error accordingly
                            separatedText = "N/A"; // setting a default value, adjust this as needed
                            countryText = "N/A";
                        }


                        $.get("save.php", {
                            'scannerId': scannerId,
                            'plate': separatedText,
                            'picture': 'assets/lprsnaps/' + path,
                            'description': countryText,
                            'color': color,
                            'scan': 'assets/scans/' + path,
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

        //GRABBER
        function startGrabbing(picturename) {
            console.log("Start Grabbing");
            /*$.get('snaplpr.php?ip=10.10.3.12', function(path) {
                startLPR(2, path)
            });*/
            $.ajax({
                url: 'http://localhost:5000/grab_images',
                type: 'POST',
                data: {
                    'contrast': '1.0',
                    'brightness': '0',
                    'hist_eq_intensity': '0.5',
                    'picturename': picturename
                },
                success: function(response) {
                    if (response.status === "success") {
                        console.log("Images captured and assembled successfully.");
                        // Handle displaying the image or other actions here.
                    } else {
                        console.error(response.message);
                    }
                },
                error: function(error) {
                    console.error("Error during image grabbing:", error);
                }
            });
        }

    </script>
</body>

</html>