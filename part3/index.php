<?php
    ini_set('max_execution_time', 300);
    function debug_to_console($data) {
        if (is_array($data))
            $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "');</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "');</script>";

        echo $output;
    }

    $link = mysql_connect('localhost', 'root', 'newrootpass') or die('Could not connect: ' . mysql_error());
    //echo "Connected successfully\n";

    /*$sql = "CREATE DATABASE cs179g";
    if (mysql_query($sql, $link)) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: " . mysql_error();
    }*/

    mysql_select_db('cs179g') or die('Could not select database');
    
    /*$sql = "CREATE TABLE tagpol (
        tag VARCHAR(100) PRIMARY KEY, 
        data LONGTEXT NOT NULL
   )";

    if (mysql_query($sql, $link)) {
        echo "Table tagpol created successfully";
    } else {
        echo "Error creating table: " . mysql_error();
    }*/

    /*$sql = "CREATE TABLE statestagpol (
        tag VARCHAR(100) PRIMARY KEY, 
        data LONGTEXT NOT NULL
   )";

    if (mysql_query($sql, $link)) {
        echo " Table statestagpol created successfully";
    } else {
        echo " Error creating table: " . mysql_error();
    }*/

    $i = 0;
    /*$csv_file = fopen("/var/www/html/total_pol.csv", "r");
    debug_to_console("opened file: " . $csv_file);
    if ($csv_file) {
        $row = fgets($csv_file, 4096);
        while (($row = fgets($csv_file, 4096)) !== false) {
            if (strpos($row_data, ";") !== false) {
                $row_data = explode(';', $row);
                //debug_to_console("$row_data[0]: " . strval($row_data[0]));
                //debug_to_console("$row_data[1]: " . strval($row_data[1]));
                $insert_data = str_replace("'", "", $row_data[1]);
                $insert_data = str_replace(", ", "; ", $insert_data);
                $insert_data = trim($insert_data);

                $sql = "INSERT INTO tagpol VALUES ('$row_data[0]','$insert_data')";

                if (mysql_query($sql, $link)) {
                    //echo "Inserted successfully";
                } else {
                    //echo "Error creating table: " . mysql_error();
                }
                $i++;
            }
        }
        fclose($csv_file);
    }*/

    $i = 0;
    /*$csv_file = fopen("/var/www/html/total_states_pol.csv", "r");
    debug_to_console("opened file: " . $csv_file);
    if ($csv_file) {
        $row = "";
        $row_data = "";
        $insert_data = "";
        #$row = fgets($csv_file, 4096);
        while (($row = fgets($csv_file, 4096)) !== false) {
            if (strpos($row, ";") !== false) {
                $row_data = explode(';', $row, 2);
                #debug_to_console("$row_data[0]: " . strval($row_data[0]));
                #debug_to_console("$row_data[1]: " . strval($row_data[1]));
                $insert_data = str_replace("'", "", $row_data[1]);
                $insert_data = str_replace(",", "", $insert_data);
                $insert_data = trim($insert_data);

                $sql = "INSERT INTO statestagpol VALUES ('$row_data[0]','$insert_data')";

                if (mysql_query($sql, $link)) {
                    //echo "Inserted successfully";
                } else {
                    //echo "Error creating table: " . mysql_error();
                }
                $i++;
            }
        }
        fclose($csv_file);
    }

    #debug_to_console("done with file... i:" . strval($i));
    echo " INSERTED: $i";*/

    $map_pos = "";
    $map_neu = "";
    $map_neg = "";
    $pos_i = 0;
    $neu_i = 0;
    $neg_i = 0;
    if(isset($_GET["search"])) {
        $hashtag = $_GET["search"];
        $sql = "SELECT * FROM tagpol WHERE tag ='$hashtag'";
        $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $i = 0;
            foreach ($line as $col_value) {
                if ($i != 0 && $col_value != "") {
                    $col_value = trim($col_value, "[]");
                    $col_arr = explode(";", $col_value);
                    foreach($col_arr as $col_arr_val) {
                        $curr_arr = explode(",", $col_arr_val);

                        if (count($curr_arr) < 3) {
                            break;
                        }

                        $pol = $curr_arr[0];
                        $lat = $curr_arr[1];
                        $lon = $curr_arr[2];

                        if ($pol > 0) {
                            $pos_i++;
                            $map_pos .= "csv_pos.push({location: new google.maps.LatLng($lat, $lon), weight: 150});\n\t\t";
                        } else if ($pol == 0) {
                            $neu_i++;
                            $map_neu .= "csv_neu.push({location: new google.maps.LatLng($lat, $lon), weight: 150});\n\t\t";
                        } else if ($pol < 0) {
                            $neg_i++;
                            $map_neg .= "csv_neg.push({location: new google.maps.LatLng($lat, $lon), weight: 150});\n\t\t";
                        }
                    }
                }
                $i++;
            }
        }
        mysql_free_result($result);

        #statestagpol
        $sql = "SELECT * FROM statestagpol WHERE tag ='$hashtag'";
        $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
        //center of states by lat/lon source: http://dev.maxmind.com/geoip/legacy/codes/state_latlon/
        $states = array(
            "AK" => "61.3850,-152.2683",
            "AL" => "32.7990,-86.8073",
            "AR" => "34.9513,-92.3809",
            "AS" => "14.2417,-170.7197",
            "AZ" => "33.7712,-111.3877",
            "CA" => "36.1700,-119.7462",
            "CO" => "39.0646,-105.3272",
            "CT" => "41.5834,-72.7622",
            "DC" => "38.8964,-77.0262",
            "DE" => "39.3498,-75.5148",
            "FL" => "27.8333,-81.7170",
            "GA" => "32.9866,-83.6487",
            "HI" => "21.1098,-157.5311",
            "IA" => "42.0046,-93.2140",
            "ID" => "44.2394,-114.5103",
            "IL" => "40.3363,-89.0022",
            "IN" => "39.8647,-86.2604",
            "KS" => "38.5111,-96.8005",
            "KY" => "37.6690,-84.6514",
            "LA" => "31.1801,-91.8749",
            "MA" => "42.2373,-71.5314",
            "MD" => "39.0724,-76.7902",
            "ME" => "44.6074,-69.3977",
            "MI" => "43.3504,-84.5603",
            "MN" => "45.7326,-93.9196",
            "MO" => "38.4623,-92.3020",
            "MP" => "14.8058,145.5505",
            "MS" => "32.7673,-89.6812",
            "MT" => "46.9048,-110.3261",
            "NC" => "35.6411,-79.8431",
            "ND" => "47.5362,-99.7930",
            "NE" => "41.1289,-98.2883",
            "NH" => "43.4108,-71.5653",
            "NJ" => "40.3140,-74.5089",
            "NM" => "34.8375,-106.2371",
            "NV" => "38.4199,-117.1219",
            "NY" => "42.1497,-74.9384",
            "OH" => "40.3736,-82.7755",
            "OK" => "35.5376,-96.9247",
            "OR" => "44.5672,-122.1269",
            "PA" => "40.5773,-77.2640",
            "PR" => "18.2766,-66.3350",
            "RI" => "41.6772,-71.5101",
            "SC" => "33.8191,-80.9066",
            "SD" => "44.2853,-99.4632",
            "TN" => "35.7449,-86.7489",
            "TX" => "31.1060,-97.6475",
            "UT" => "40.1135,-111.8535",
            "VA" => "37.7680,-78.2057",
            "VI" => "18.0001,-64.8199",
            "VT" => "44.0407,-72.7093",
            "WA" => "47.3917,-121.5708",
            "WI" => "44.2563,-89.6385",
            "WV" => "38.4680,-80.9696",
            "WY" => "42.7475,-107.2085",
       );
        
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $i = 0;
            foreach ($line as $col_value) {
                $col_value = str_replace($hashtag, "", $col_value);
                $state_arr = explode(";", $col_value);
                foreach($state_arr as $state_arr_val) {
                    $curr_state = explode("_", $state_arr_val);

                    $state = $curr_state[0];
                    if ($state == "") {
                        break;
                    }       

                    $pos_w = $curr_state[1];
                    $neu_w = $curr_state[2];
                    $neg_w = $curr_state[3];
                    $geo = $states[strval($state)];
                    $geo = explode(",", $geo);
                    $lat = $geo[0];
                    $lon = $geo[1];

                    if ($pos_w > 0) {
                        $pos_i += $pos_w;
                        $map_pos .= "csv_pos.push({location: new google.maps.LatLng($lat, $lon), weight: $pos_w});\n\t\t";
                    } 
                    if ($neu_w > 0) {
                        $neu_i += $neu_w;
                        $map_neu .= "csv_neu.push({location: new google.maps.LatLng($lat, $lon), weight: $neu_w});\n\t\t";
                    } 
                    if ($neg_w > 0) {
                        $neg_i += $neg_w;
                        $map_neg .= "csv_neg.push({location: new google.maps.LatLng($lat, $lon), weight: $neg_w});\n\t\t";
                    }
                }
            }
        }
        mysql_free_result($result);
    }

    mysql_close($link);
    $results = array();
    $hashtag = "";
?>
<head>
    <style>
        html, body, #map-canvas {
            font-family: sans-serif;
            height:  100%;
            margin:  0px;
            padding: 0px;
            z-index: 1;
        }
  
        #main-window {
            position: absolute;
            height:  264px;
            width:   264px;
            bottom:  42px;
            left:    42px;
            padding: 20px;
            z-index: 100; 
            background-color: rgba(0, 172, 237, 0.5); 
            border-color: white;
            border-style: solid;
            border-width: 2px;
            box-shadow: -10px 10px 5px #212121;
        }

        #radius-label, #opacity-label, #max-label, #button {
            margin-top: 10px;
        }

        #radius-slider, #opacity-slider {
            width:      250px;
            margin-top: 10px;
        }

        #radius-slider .ui-slider-handle, 
        #opacity-slider .ui-slider-handle {
            cursor:pointer;
        }

        #project {
            font-size:     15pt;
            font-weight:   bold;
            margin-bottom: 10px;
        }
    </style>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-5FElP2uUMKjG6nF5311Rz3IponCjkpU&v=3.exp&libraries=visualization"></script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">

    <script>
        var map, pointarray, heatmap_pos, heatmap_neu, heatmap_neg;
        var csv_pos = [];
        var csv_neu = [];
        var csv_neg = [];
        var first = 0;

        //clears each map array and populates with new data points from php cassandra results
        function updateHeatMaps() {
            csv_pos = [];
            csv_neu = [];
            csv_neg = [];
            <?php echo $map_pos; ?>
            <?php echo $map_neu; ?>
            <?php echo $map_neg; ?>
            loadPosHeatmap(csv_pos, "pos");
            loadNeuHeatmap(csv_neu, "neu");
            loadNegHeatmap(csv_neg, "neg");
        }
        
        //initializes underlying map for first time
        function initMap() {
            var customMapType = new google.maps.StyledMapType(
                [
                    {
                        stylers: [
                            {hue: "#00aced"},
                            {visibility: "simplified"},
                            {gamma: 0.5},
                            {weight: 0.5}
                        ]
                    },
                    {
                        elementType: "labels",
                        stylers: [{visibility: "on"}]
                    },
                    {
                        featureType: "water",
                        stylers: [{color: "#00aced"}]
                    }
                ], 
                {
                    name: "Twitter Blue"
                }
            );
            var customMapTypeId = "custom_style";

            var mapOptions = {
                zoom: 5,
                center: new google.maps.LatLng(38.5, -98.5),
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, customMapTypeId]
                }
            };

            map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
            map.mapTypes.set(customMapTypeId, customMapType);
            map.setMapTypeId(customMapTypeId);
        }
        
        //toggles specific heatmap and display indicator next to button
        function toggleHeatmap(pol) {
            if (pol == "pos"){ 
                heatmap_pos.setMap(heatmap_pos.getMap() ? null : map);
                if (document.getElementById("toggle_status_pos").innerHTML.indexOf("ON") != -1)
                    document.getElementById("toggle_status_pos").innerHTML = " OFF:";
                else
                    document.getElementById("toggle_status_pos").innerHTML = " ON:";
            }
            if (pol == "neu") {
                heatmap_neu.setMap(heatmap_neu.getMap() ? null : map);
                if (document.getElementById("toggle_status_neu").innerHTML.indexOf("ON") != -1)
                    document.getElementById("toggle_status_neu").innerHTML = " OFF:";
                else
                    document.getElementById("toggle_status_neu").innerHTML = " ON:";
            }
            if (pol == "neg") {
                heatmap_neg.setMap(heatmap_neg.getMap() ? null : map);
                if (document.getElementById("toggle_status_neg").innerHTML.indexOf("ON") != -1)
                    document.getElementById("toggle_status_neg").innerHTML = " OFF:";
                else
                    document.getElementById("toggle_status_neg").innerHTML = " ON:";
            }
        }

        //reloads positive heatmap
        function loadPosHeatmap(csv, pol) {
            var pointArray = new google.maps.MVCArray(csv);

            if(heatmap_pos) heatmap_pos.setMap(null);
            heatmap_pos = new google.maps.visualization.HeatmapLayer({
                data: pointArray,
                radius: $("#radius-slider").slider("value"),
                opacity: $("#opacity-slider").slider("value")
            });
            heatmap_pos.setMap(map);
        }

        //reloads neutral heatmap
        function loadNeuHeatmap(csv, pol) {
            var pointArray = new google.maps.MVCArray(csv);

            if(heatmap_neu) heatmap_neu.setMap(null);
            heatmap_neu = new google.maps.visualization.HeatmapLayer({
                data: pointArray,
                radius: $("#radius-slider").slider("value"),
                opacity: $("#opacity-slider").slider("value")
            });
            heatmap_neu.setMap(map);
        }

        //reloads negative heatmap
        function loadNegHeatmap(csv, pol) {
            var pointArray = new google.maps.MVCArray(csv);

            if(heatmap_neg) heatmap_neg.setMap(null);
            heatmap_neg = new google.maps.visualization.HeatmapLayer({
                data: pointArray,
                radius: $("#radius-slider").slider("value"),
                opacity: $("#opacity-slider").slider("value")
            });
            heatmap_neg.setMap(map);
        }
        
        $(document).ready(function(){
            google.maps.event.addDomListener(window, "load", initMap);
            
            //creates main ui window
            $(function() {
                $("#main-window");
            });
            
            //creates radius and opacity sliders in jquery
            $(function() {
                $("#radius-slider").slider({
                    orientation: "horizontal",
                    range: "min",
                    min: 1,
                    max: 50,
                    value: 50,
                    slide: function(event, ui) {
                        $("#radius-label").html("radius: " + ui.value);
                        if(heatmap_pos == null || heatmap_neu == null || heatmap_neg == null) return;
                        heatmap_pos.set("radius", ui.value);
                        heatmap_neu.set("radius", ui.value);
                        heatmap_neg.set("radius", ui.value);
                    }
                });

                $("#opacity-slider").slider({
                    orientation: "horizontal",
                    range: "min",
                    min: 0,
                    max: 100,
                    value: 100,
                    slide: function(event, ui) {
                        $("#opacity-label").html("opacity: " + ui.value/100);
                        if(heatmap_pos == null || heatmap_neu == null || heatmap_neg == null) return;
                        heatmap_pos.set("opacity", ui.value/100);
                        heatmap_neu.set("opacity", ui.value/100);
                        heatmap_neg.set("opacity", ui.value/100);
                    }
                });

                setTimeout(function(){ updateHeatMaps();}, 1500);
            });
        });
    </script>
</head>
<body>
    <div id="map-canvas"></div>
    <div id="main-window">
        <!--if get request is not set, load this default empty window-->
        <?php if (!isset($_GET["search"])) { ?>
            <div id="project">CS179G Twitter Heat Map</div>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
            <input type="text" id="csv-file" name="search" placeholder="mothersday">
            <input type="submit" value="Search">
            </form>

            <div id="radius-label">radius: 50</div>
            <div id="radius-slider"></div>

            <div id="opacity-label">opacity: 1.0</div>
            <div id="opacity-slider"></div>

        <?php } ?>
        <!--if get request is set, load this populated window-->
        <?php if (isset($_GET["search"])) { ?>
            <div id="project">CS179G Twitter Heat Map</div>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
            <input type="text" id="csv-file" name="search" placeholder="mothersday" value="<?php if ($_GET["search"] != "") { echo $_GET["search"];} ?>" style="width: 189px;">
            <input type="submit" value="Search">
            </form>

            <div id="button"><button onclick="toggleHeatmap('pos')" style="text-align: left; width: 167px;">Toggle Positive Heatmap</button><div id="toggle_status_pos" style="display: inline; text-align: left; width: 21px;"> ON:</div> <?php echo (substr_count($map_pos, "\n") + $pos_i) ?></div>
            <div id="button"><button onclick="toggleHeatmap('neu')" style="text-align: left; width: 167px;">Toggle Neutral Heatmap</button><div id="toggle_status_neu" style="display: inline; text-align: left; width: 21px;"> ON:</div> <?php echo (substr_count($map_neu, "\n") + $neu_i) ?></div>
            <div id="button"><button onclick="toggleHeatmap('neg')" style="text-align: left; width: 167px;">Toggle Negative Heatmap</button><div id="toggle_status_neg" style="display: inline; text-align: left; width: 21px;"> ON:</div> <?php echo (substr_count($map_neg, "\n") + $neg_i) ?></div>

            <div id="radius-label">radius: 20</div>
            <div id="radius-slider"></div>

            <div id="opacity-label">opacity: 1.0</div>
            <div id="opacity-slider"></div>
        <?php } ?>
    </div>
</body>