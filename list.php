<?php

include "includes/_inc_conn.php";

$sql = "SELECT entrances.*, scanner.name FROM entrances, scanner WHERE scanner.scannerid = entrances.scanner AND entrances.deleted = 0 ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {

        if ( $row["plate"] != "" ){
            $explode = explode("|", $row["plate"]);
            $row["plate"] = $explode[0];
            if ( array_key_exists(1, $explode) ){
                switch($explode[1]) {
                    case "ب" : $row["plate"] .= " | B "; break;
                    case "ه" : $row["plate"] .= " | H "; break;
                    case "ا" : $row["plate"] .= " | A "; break;
                    case "د" : $row["plate"] .= " | D "; break;
                    case "و" : $row["plate"] .= " | E "; break;
                    case "ط" : $row["plate"] .= " | T "; break;
                    default : $row["plate"] .= " | ". $explode[1];
                }
            }
            if ( array_key_exists(2, $explode) ){
                $row["plate"] .= " | ". $explode[2];
            }
            
        }
        

    echo '<tr class="border-bottom" id="entry-' . $row["id"]. '">
    <td class="text-center">' . $row["id"]. '</td>
    <td class="text-center"><span class="badge rounded-pill bg-info-gradient me-1 mb-1 mt-1">' . $row["name"]. '</span></td>
    <td>
    <img onclick="showImage(\'' . $row["picture"]. '\', \'' . $row["plate"]. '\', \'' . $row["mark"]. '\')" src="' . $row["picture"]. '" width="150" class="example-image" style="border:2px solid white" />
    <td>
    <img onclick="showImage(\'' . $row["picture"]. '.png\', \'' . $row["plate"]. '\', \'' . $row["mark"]. '\')" src="' . $row["picture"]. '.png" width="150" class="example-image" style="border:2px solid white" />
    </td>
    <td class="text-muted fs-20 fw-semibold">
    <img onclick="showImage(\'' . $row["scan"]. '\', \'' . $row["plate"]. '\', \'' . $row["mark"]. '\')" src="' . $row["scan"]. '" width="150" class="example-image" style="border:2px solid white" />
    </td>';

    if ( $row["plate"] != ""){
        echo  '<td class="text-muted fs-20 fw-semibold"><span class="badge rounded-pill bg-success me-1 mb-1 mt-1" style="letter-spacing: 2px;">' . $row["plate"]. '</span></td>';
    }else {
        echo  '<td class="text-muted fs-20 fw-semibold"><span class="badge rounded-pill bg-danger me-1 mb-1 mt-1">No plate</span></td>';
    }
    
    echo '<td class="fs-15 fw-semibold"><span class="badge rounded-pill bg-primary">' . $row["entry_date"]. '</span></td>
    <td class="fs-15 fw-semibold">
    <span class="badge rounded-pill bg-success">Brand : ' . $row["mark"]. '</span><br>
    <span class="badge rounded-pill bg-default">Category : ' . $row["category"]. '</span><br>
    <span class="badge rounded-pill bg-default">Country : ' . $row["description"]. '</span><br><hr>
        Color <div style="width:20px; height:20px; display:inline; float:right;background:rgb(' . $row["color"]. ')"></div>
    </td>
    <td class="">
        <a class="btn btn-primary btn-sm rounded-11 me-2" data-bs-toggle="tooltip" data-bs-original-title="Edit"><i><svg class="table-edit" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="16">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM5.92 19H5v-.92l9.06-9.06.92.92L5.92 19zM20.71 5.63l-2.34-2.34c-.2-.2-.45-.29-.71-.29s-.51.1-.7.29l-1.83 1.83 3.75 3.75 1.83-1.83c.39-.39.39-1.02 0-1.41z" />
                </svg></i></a>
        <a href="javascript:deleteLine(\''. $row['id'] .'\')" class="btn btn-danger btn-sm rounded-11" data-bs-toggle="tooltip" data-bs-original-title="Delete"><i><svg class="table-delete" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="16">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4h-3.5z" />
                </svg></i></a>
    </td></tr>';

    }
}

mysqli_close($conn);

?>
