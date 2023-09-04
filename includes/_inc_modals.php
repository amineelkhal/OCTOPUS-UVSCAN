<!-- Modal Structure -->
<div id="historyModal" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Entrance History</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="historyTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Scanner</th>
                                <th>Plate</th>
                                <th>Picture</th>
                                <th>Scan</th>
                                <th>Entry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be filled in here by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="slideshowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="slideshowLabel">Scan History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="slideContainer" style="max-height: 450px; overflow:auto;">
                    <!-- Slides will be inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-primary compare-button" value="Compare">
            </div>
        </div>
    </div>
</div>

<style>
    #slideContainer .slide {
        display: block;
        /* By default, all slides are hidden */
        text-align: center;
    }

    .active-slide {
        display: block;
    }

    #slideContainer .slide-image {
        max-width: 100%;
        max-height: 400px;
        /* You can adjust this */
    }

    #slideContainer .slide-description {
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
    }
</style>

<script>
    document.querySelector('.compare-button').addEventListener('click', function() {
        // Implement your image comparison logic here
        console.log('Comparing images...');
    });
</script>


<!-- Modal Structure -->

<div class="modal fade" id="editPlateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="slideshowLabel">Edit plate</h5>
            </div>
            <div class="modal-body">
                <img src="" id="editPlatePicture" width="100%" alt="">
                <input type="hidden" id="editPlateId" />
                <input type="text" id="editPlateValue" class="form-control" placeholder="New Plate Value" />

                <hr>
                <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" checked id="isMoroccan" onchange="togglePlateForm()">
                    <span class="custom-control-label">Is it a Moroccan plate ?</span>
                </label>

                <!-- Moroccan Plate Form -->
                <div id="moroccanPlateForm">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="part1" placeholder="XXX">
                        </div>
                        <div class="col-sm-2">
                            <select id="arabicLetters" class="form-control">
                                <!-- Add Arabic letters options -->
                                <option value="ا">ا</option>
                                <option value="ب">ب</option>
                                <!-- ... other letters ... -->
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="part3" placeholder="XXX">
                        </div>
                    </div>
                </div>

                <!-- Non-Moroccan Plate Form -->
                <div id="nonMoroccanPlateForm" style="display:none;">
                    <input type="text" class="form-control" id="nonMoroccanPlate" placeholder="Plate Number">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="savePlate()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePlateForm() {
        const isChecked = document.getElementById('isMoroccan').checked;
        if (isChecked) {
            document.getElementById('moroccanPlateForm').style.display = 'block';
            document.getElementById('nonMoroccanPlateForm').style.display = 'none';
        } else {
            document.getElementById('moroccanPlateForm').style.display = 'none';
            document.getElementById('nonMoroccanPlateForm').style.display = 'block';
        }
    }
</script>

<!--
<div id="editPlateModal" class="modal fade">
    <div class="modal-header">
        Edit Plate
    </div>
    <div class="modal-body">
        <input type="hidden" id="editPlateId" />
        <input type="text" id="editPlateValue" class="form-control" placeholder="New Plate Value" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="updatePlate()">Save</button>
    </div>
</div>

-->