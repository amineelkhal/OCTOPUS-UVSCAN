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
                                <h1 class="page-title">Statistics</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Statistics</li>
                                </ol>
                            </div>
                            <div class="ms-auto pageheader-btn">
                                <div id="bttn" style="margin-bottom: 20px;"></div>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-4 -->
                        <div class="row">
                            <div class="col-4 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        Vehicles of current year
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="entrancesChart" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-4 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                    Vehicles of current year By Scanner Name / By Months
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="bymonthbyscannername" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-4 col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                    Vehicles of current year By Scanner Name
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="entrancesByScannerNameChart" class="h-275"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let monthlyCounts = [];
        let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        fetch("/fetch_statistics.php?s=all").then(response => response.json()).then(data => {
            months.forEach(month => {
                monthlyCounts.push(data[month] || 0);
            });
            entrancesChart(monthlyCounts);
        });

        function entrancesChart(data) {
            new Chart(document.getElementById('entrancesChart').getContext('2d'), {
                type: 'line', // or 'bar' or other chart type you'd prefer
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Vehicles',
                        data: data,
                        borderColor: "#3e95cd",
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white'
                            }
                        },
                        x: {
                            ticks: {
                                color: 'white'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    }
                }
            });
        }

        var ctx_entrancesByScannerNameChart = document.getElementById('entrancesByScannerNameChart').getContext('2d');
        var chart_entrancesByScannerNameChart;

        $.ajax({
            url: 'fetch_statistics.php?s=byscannername',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                var scannerNames = data.map(function(entry) {
                    return entry.scanner_name;
                });
                var entranceCounts = data.map(function(entry) {
                    return entry.entrance_count;
                });

                chart_entrancesByScannerNameChart = new Chart(ctx_entrancesByScannerNameChart, {
                    type: 'bar',
                    data: {
                        labels: scannerNames,
                        datasets: [{
                            label: 'Number of Vehicles',
                            data: entranceCounts,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'white'
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'white'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white'
                                }
                            }
                        }
                    }
                });
            }
        });

        var ctx_bymonthbyscannername = document.getElementById('bymonthbyscannername').getContext('2d');
        var chart_bymonthbyscannername;

        $.ajax({
            url: 'fetch_statistics.php?s=bymonthbyscannername',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log( data )
                var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                var scannerNames = [...new Set(data.map(entry => entry.name))]; // unique scanner names
                var colors = ["#ff7e00", "#ab7ee3", "#3bc89e"];

                var i = -1;
                var datasets = scannerNames.map(name => {

                    i++;
                    return {
                        label: name,
                        data: months.map((month, index) => {
                            var found = data.find(entry => entry.month === (index + 1) && entry.name === name);
                            return found ? found.count : 0;
                        }),
                        backgroundColor: colors[i]
                    };
                    
                });

                chart_bymonthbyscannername = new Chart(ctx_bymonthbyscannername, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'white'
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'white'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>