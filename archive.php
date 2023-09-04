<!doctype html>
<html lang="en">

<head>
    <?php include "includes/_inc_head.php" ?>
    <style>
        .license-plate {
            font-family: 'Arial', sans-serif;
            display: inline-block;
            background-color: #fff;
            border: 4px solid #000;
            border-radius: 8px;
            padding: 10px 20px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            letter-spacing: 2px;
            font-size: 30px;
            text-transform: uppercase;
            color: #000;
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
                                <h1 class="page-title">Archive</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Archive</li>
                                </ol>
                            </div>
                            <div class="ms-auto pageheader-btn">
                                <div id="bttn" style="margin-bottom: 20px;"></div>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-4 -->
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card ">
                                    <div class="card-header">
                                        <h3 class="card-title mb-0">Entrances</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="entrancesTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Scanner</th>
                                                    <th>Scanning Results</th>
                                                    <th>Plate</th>
                                                    <!--<th>Picture</th>-->
                                                    <!--<th>Scan</th>-->
                                                    <!--<th>Plate Miniature</th>-->
                                                    <th>Details</th>
                                                    <th>Entry Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be filled in here by DataTables -->
                                            </tbody>
                                        </table>
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

        //LOAD ENTRANCES
        let entrancesTable;
        $(document).ready(function() {
            entrancesTable = $('#entrancesTable').DataTable({
                "ajax": "fetch_entrances_archive.php",
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5]
                        }
                    }
                ],
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
                        "data": null,
                        "render": function(data, type, row) {
                            const miniaturePath = row.picture ? row.picture + '.png' : null;
                            // Combine all images in a container for that row
                            return `<div class="image-slideshow-container" onclick="startSlideshow(this)">
                <img src="${row.picture}" alt="Picture" class="img-thumbnail">
                <hr>
                <img src="${row.scan}" alt="Scan" class="img-thumbnail">
                <hr>
                <img src="${miniaturePath}" alt="Scan" class="img-thumbnail">
            </div>`;
                        }
                    },
                    {
                        "data": "plate",
                        "render": function(data, type, row) {
                            return '<div class="license-plate">' + replaceArabicWithFrench(data) + '</div>';
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
                            <button class="btn btn-danger" onclick="restoreLine(${row.id})">Restore</button>`;
                        },
                        "orderable": false, // Disable sorting for this column
                        "searchable": false // Disable searching for this column
                    }

                ],
                "order": [
                    [5, 'desc']
                ]
            });

        });

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

        function restoreLine(id) {
            swal({
                    title: "Attention",
                    text: "Do you really want to retore ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.get("restore.php?id=" + id, function() {
                            //table.row($("#entry-" + id)).remove().draw(false);
                            //refreshDataTable();
                            swal("Entrance deleted", {
                                icon: "success",
                            });
                        });
                    }
                });
        }

        //REFRESH DATATABLE ON CHANGE DETECTED ON DATABASE
        let lastKnownUpdate;

        function refreshDataTable() {
            entrancesTable.ajax.reload(); // Use the stored reference to reload the table.
        }

        function checkForUpdates() {
            $.getJSON('check_updates.php', function(data) {
                if (!lastKnownUpdate) {
                    lastKnownUpdate = data.last_updated;
                } else if (lastKnownUpdate !== data.last_updated) {
                    lastKnownUpdate = data.last_updated;
                    refreshDataTable();
                    console.log('Refreshing data...');
                }
            });
        }

        // Poll every 30 seconds (adjust as necessary).
        setInterval(checkForUpdates, 3000);
    </script>
</body>

</html>