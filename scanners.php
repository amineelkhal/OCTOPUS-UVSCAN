<!doctype html>
<html lang="en">

<head>
    <?php include "includes/_inc_head.php" ?>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "uviscan";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM scanner";
    $result = $conn->query($sql);

    $scanners = [];
    while ($row = $result->fetch_assoc()) {
        $scanners[] = $row;
    }
    ?>

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
                                <h1 class="page-title">Scanners</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Scanners</li>
                                </ol>
                            </div>
                            <div class="ms-auto pageheader-btn">
                                <div id="bttn" style="margin-bottom: 20px;"></div>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-4 -->
                        <div class="row">

                            <?php foreach ($scanners as $scanner) : ?>
                                <div class="col-md-3 mb-4">
                                    <div class="card">
                                        <img src="assets/images/scanners/<?php echo $scanner['scannerid']; ?>.jpg" class="card-img-top" alt="Scanner Image">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $scanner['name']; ?></h5>
                                            <p><?php echo $scanner['description']; ?></p>
                                            <form action="update_scanner.php" method="POST">
                                                <input type="hidden" name="scannerid" value="<?php echo $scanner['scannerid']; ?>">
                                                <div class="mb-3">
                                                    <label for="contrast">Contrast</label>
                                                    <input type="number" step="0.01" class="form-control" name="contrast" value="<?php echo $scanner['contrast']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="brightness">Brightness</label>
                                                    <input type="number" class="form-control" name="brightness" value="<?php echo $scanner['brightness']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="hist_eq_intensity">Histogram Equalization Intensity</label>
                                                    <input type="number" step="0.01" class="form-control" name="hist_eq_intensity" value="<?php echo $scanner['hist_eq_intensity']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ip_address">IP Address</label>
                                                    <input type="text" class="form-control" name="ip_address" value="<?php echo $scanner['ip_address']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="capture_duration">Capture Duration</label>
                                                    <input type="number" class="form-control" name="capture_duration" value="<?php echo $scanner['duration']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="crop_pixels">Crop Pixels</label>
                                                    <input type="number" class="form-control" name="crop_pixels" value="<?php echo $scanner['crop']; ?>">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

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

    <script>

    </script>
</body>

</html>