<!doctype html>
<html lang="en">

<head>
    <?php include "includes/_inc_head.php" ?>
    <style>
        .plate {
            background-color: #fff;
            color: #000;
            font-weight: bold;
            padding: 2px;
            text-align: center;
        }

        #historyTable {
            width: 100% !important;
        }
    </style>
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
                                                        <th>Plate Miniature</th>
                                                        <th>Details</th>
                                                        <th>Entry Date</th>
                                                        <th>Action</th>
                                                        <th>Scanning Results</th>
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
        isDemo = true;

        scanLoop1 = 'off';
        scanLoop2 = 'off';
        scanLoop3 = 'off';
        scanStatus1 = 'off';
        scanStatus2 = 'off';
        scanStatus3 = 'off';

        //FETCH STATISTICS ON LOAD
        fetchStatistics();

        //LOAD ENTRANCES
        let entrancesTable;
        $(document).ready(function() {
            entrancesTable = $('#entrancesTable').DataTable({
                "ajax": "fetch_entrances.php",
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name",
                        "render": function(data, type, row) {
                            return '<span>' + data + '</span><hr><span>' + row.scannerdescription + '</span>';
                        }
                    },
                    {
                        "data": "plate",
                        "render": function(data, type, row) {
                            return '<div class="plate">' + replaceArabicWithFrench(data) + '</div>';
                        }
                    },
                    {
                        "data": "picture",
                        "render": function(data, type, row) {
                            return '<img src="' + data + '" width="150" alt="Picture" class="img-thumbnail" onclick="showModalImage(\'' + data + '\')">';
                        }
                    },
                    {
                        "data": "scan",
                        "render": function(data, type, row) {
                            return '<img src="' + data + '" width="150" alt="Scan" class="img-thumbnail" onclick="showModalImage(\'' + data + '\')">';
                        }
                    },
                    {
                        "data": "picture",
                        "title": "Plate Miniature",
                        "render": function(data, type, row) {
                            const miniaturePath = data ? data + '.png' : null;
                            return '<img src="' + miniaturePath + '" width="120" alt="Plate Miniature" class="img-thumbnail" onclick="showModalImage(\'' + miniaturePath + '\')">';
                        }
                    },
                    {
                        "title": "Details",
                        "data": null,
                        "render": function(data, type, row) {
                            // For the color, we'll use a small square next to the color text
                            let colorBox = '<div style="display: inline-block; background-color: rgb(' + row.color + '); width: 15px; height: 15px; margin-right: 5px;"></div>';

                            return "Country : " + row.description + '<hr>Color : ' + colorBox + '<hr>Category : ' + row.category + '<hr>Mark : ' + row.mark;
                        }
                    },
                    {
                        "data": "entry_date",
                        "render": function(data, type, row) {
                            // Assuming you want to keep it in its raw form. Modify as needed.
                            return data;
                        }
                    },
                    {
                        "title": "History",
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                            <button class="btn btn-primary" onclick="showHistory('${row.plate}')">Show History</button><hr>
                            <button class="btn btn-secondary" onclick="editPlate('${row.id}', '${row.plate}', '${row.picture}')">Edit Plate</button>`;
                        },
                        "orderable": false, // Disable sorting for this column
                        "searchable": false // Disable searching for this column
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            // Combine all images in a container for that row
                            return `
            <div class="image-slideshow-container" onclick="startSlideshow(this)">
                <img src="${row.picture}" alt="Picture" class="img-thumbnail">
                <hr>
                <img src="${row.scan}" alt="Scan" class="img-thumbnail">
                <!-- Add other images similarly -->
            </div>
        `;
                        }
                    }

                ],
                "order": [
                    [7, 'desc']
                ]
            });

        });

        let historyDataTable; // This will hold the reference to the DataTable instance

        function showHistory(plate) {
            $.ajax({
                url: "fetch_history.php",
                type: "GET",
                data: {
                    plate: plate
                },
                success: function(response) {
                    let historyData = JSON.parse(response);

                    // If the table is already initialized, destroy it
                    if ($.fn.DataTable.isDataTable('#historyTable')) {
                        $('#historyTable').DataTable().destroy();
                    }

                    // Empty the table before re-populating
                    $('#historyTable tbody').empty();

                    // Populate the table using DataTables
                    historyDataTable = $('#historyTable').DataTable({
                        data: historyData,
                        "autoWidth": false,
                        pageLength: 3,
                        columns: [{
                                "data": "id"
                            },
                            {
                                "data": "scanner"
                            },
                            {
                                "data": "plate",
                                "render": function(data, type, row) {
                                    return '<div class="plate">' + replaceArabicWithFrench(data) + '</div>';
                                }
                            },
                            {
                                "data": "picture",
                                "render": function(data, type, row) {
                                    return '<img src="' + data + '" width="120" alt="Picture" class="img-thumbnail">';
                                }
                            },
                            {
                                "data": "scan",
                                "render": function(data, type, row, meta) {
                                    return '<img src="' + data + '" width="120" alt="Scan" onclick="showSlideshow(\'' + data + '\')" class="img-thumbnail">';
                                }
                            },
                            {
                                "data": "entry_date"
                            }
                        ]
                    });

                    // Open the modal
                    $("#historyModal").modal('show');

                },
                error: function(error) {
                    console.error("Error fetching history:", error);
                }
            });
        }

        function startSlideshow(container) {
            // Initialize viewer.js on the container
            const viewer = new Viewer(container, {
                // viewer.js options here...
            });

            // Start the viewer
            viewer.show();

            // As viewer.js will auto-destroy on close, no need for explicit destroy
        }


        function showSlideshow(scanImageUrl) {
            // Fetch the entire dataset from historyDataTable (You might want to adjust this depending on how you fetch data)
            let allData = historyDataTable.rows().data();

            let slidesHtml = '';

            allData.each(function(rowData) {
                slidesHtml += `<div class="slide">
                          <img src="${rowData.scan}" alt="${rowData.entry_date}" class="slide-image">
                          <div class="slide-description" style="position:relative; margin-top:-30px;">Entry Date : ${rowData.entry_date}</div>
                       </div>`;
            });

            $('#slideContainer').html(slidesHtml);

            // Display the modal
            $('#slideshowModal').modal('show');
        }

        function refreshDataTable() {
            entrancesTable.ajax.reload(); // Use the stored reference to reload the table.
        }

        // Setup the refresh interval here
        setInterval(function() {
            //entrancesTable.ajax.reload(null, false); // Update the reference to use the renamed variable.
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

        function updateLoopScan(scannerId, ip) {
            const scanLoopVar = 'scanLoop' + scannerId;
            const scanStatusVar = 'scanStatus' + scannerId;

            $.get(`readexdul.php?ip=${ip}`, function(data) {
                window[scanLoopVar] = data;
                $(`#loopscan${scannerId}`).html(data);

                const borderColor = (data == 'off') ? "2px solid white" : "2px solid green";
                $(`#lpr${scannerId}`).css({
                    "border": borderColor
                });

                if (data == 'off') {
                    window[scanStatusVar] = 'off';
                } else if (window[scanStatusVar] == 'off') {
                    window[scanStatusVar] = 'scanning';
                    const pictureName = `10-10-${scannerId}-12-${Date.now()}.jpg`;

                    startGrabbing(pictureName);
                    $.get(`snaplpr.php?ip=${ip}&name=${pictureName}`, function(path) {
                        startLPR(scannerId, pictureName);
                    });
                }
            });
        }

        //LOOP READER - EXDUL READER
        if (!isDemo) {
            setInterval(function() {
                console.log("Not Demo")
                updateLoopScan(2, "10.10.1.10");
                updateLoopScan(1, "10.10.3.10");
                updateLoopScan(3, "10.10.2.10");
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

                        fetchStatistics();

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

        //CHANGING LETTER TO FRENCH
        function replaceArabicWithFrench(plate) {
            const arabicToFrenchMap = {
                'ا': 'A',
                'ب': 'B',
                'د': 'D',
                'و': 'E',
                'ط': 'T'
                // ... add other mappings
            };

            for (let key in arabicToFrenchMap) {
                if (plate.includes(key)) {
                    plate = plate.replace(key, arabicToFrenchMap[key]);
                    break; // assuming only one Arabic letter in the plate string
                }
            }
            return plate;
        }

        function editPlate(id, plate, picture) {
            // Set the modal values
            document.getElementById('editPlateId').value = id;
            document.getElementById('editPlateValue').value = plate;
            document.getElementById('editPlatePicture').src = picture + '.png';
            // Show the modal
            $('#editPlateModal').modal('show');
        }

        function savePlate() {
            const isChecked = document.getElementById('isMoroccan').checked;
            let plateValue;

            if (isChecked) {
                const part1 = document.getElementById('part1').value;
                const arabicLetter = document.getElementById('arabicLetters').value;
                const part3 = document.getElementById('part3').value;

                plateValue = `${part1}|${arabicLetter}|${part3}`;
            } else {
                plateValue = document.getElementById('nonMoroccanPlate').value;
            }

            // Send `plateValue` to your server using AJAX or any other method.
            console.log(plateValue); // Just for demonstration.
            const plateId = document.getElementById('editPlateId').value;

            $.ajax({
                url: 'update_plate.php',
                type: 'POST',
                data: {
                    id: plateId,
                    plate: plateValue
                },
                success: function(response) {
                    console.log(response);
                    alert("Update done");
                    $('#editPlateModal').modal('hide');
                },
                error: function(error) {
                    // Handle any AJAX errors
                    alert("Failed to update plate.");
                }
            });
        }

        function updatePlate() {
            const plateId = document.getElementById('editPlateId').value;
            const newPlateValue = document.getElementById('editPlateValue').value;

            $.ajax({
                url: 'update_plate.php',
                type: 'POST',
                data: {
                    id: plateId,
                    plate: newPlateValue
                },
                success: function(response) {
                    if (response.success) {
                        // If successfully updated, you can reload the table or update the specific row
                        entrancesTable.ajax.reload();
                        $('#editPlateModal').modal('hide');
                    } else {
                        // Handle any errors from the server
                        alert(response.error);
                    }
                },
                error: function(error) {
                    // Handle any AJAX errors
                    alert("Failed to update plate.");
                }
            });
        }

        function fetchStatistics() {
            $.ajax({
                url: 'fetch_counts.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Update the HTML with the fetched counts
                    $('#today_count').text(data.today_entries);
                    $('#month_count').text(data.month_entries);
                },
                error: function(error) {
                    console.log('Failed to fetch counts:', error);
                }
            });
        }
    </script>
</body>

</html>