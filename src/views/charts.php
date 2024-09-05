<?php

// Variabile globale pentru stocarea datelor si configuratiei graficului
$chartData1 = array();
$chartConfig1 = array();
$chartData2 = array();
$chartConfig2 = array();
$chartData3 = array();
$chartConfig3 = array();

function fetchDataFromDatabase1($conn_actors)
{

    $sql = "SELECT
        COUNT(DISTINCT CASE WHEN rank > 0 THEN full_name END) AS Won,
        COUNT(DISTINCT CASE WHEN rank = 0 THEN full_name END) AS Lost
        FROM screen_actor_guild_awards";
    $result = mysqli_query($conn_actors, $sql);
    $data = array();

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $data['Won'] = $row['Won'];
        $data['Lost'] = $row['Lost'];
    }

    $conn_actors->close();

    return $data;
}

function fetchDataFromDatabase2($conn_actors, $limit)
{
    $sql = "SELECT
        sa.full_name,
        COUNT(DISTINCT sa.show) AS ShowCount,
        COUNT(CASE WHEN sa.rank <> 0 THEN 1 END) AS AwardsWon
    FROM
        screen_actor_guild_awards AS sa
    WHERE sa.full_name <> ''
    GROUP BY
        sa.full_name
    ORDER BY
        ShowCount DESC
    LIMIT " . $limit;

    $result = mysqli_query($conn_actors, $sql);
    $data = array();

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $actorName = $row['full_name'];
            $data[$actorName]['ShowCount'] = $row['ShowCount'];
            $data[$actorName]['AwardsWon'] = $row['AwardsWon'];
        }
    }

    $conn_actors->close();

    return $data;
}

function fetchDataFromDatabase3($conn_actors, $startYear, $endYear)
{

    $sql = "SELECT SUBSTRING_INDEX(year, ' - ', 1) AS AwardYear, COUNT(*) AS ShowCount
    FROM screen_actor_guild_awards
    WHERE SUBSTRING_INDEX(year, ' - ', 1) BETWEEN $startYear AND $endYear
    GROUP BY AwardYear
    ORDER BY AwardYear ASC;";

    $result = mysqli_query($conn_actors, $sql);
    $data = array();

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $awardYear = $row['AwardYear'];
            $data[$awardYear] = $row['ShowCount'];
        }
    }

    $conn_actors->close();

    return $data;
}

function generateView1Chart($conn_actors)
{
    global $chartData1, $chartConfig1;

    $chartData1 = fetchDataFromDatabase1($conn_actors);
    $labels = array_keys($chartData1);
    $values = array_values($chartData1);

    $chartConfig1 = array(
        'type' => 'pie',
        'data' => array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'data' => $values,
                    'backgroundColor' => array(
                        '#FF6384',
                        '#36A2EB',
                    )
                )
            )
        )
    );

    $chartScript = 'var ctx = document.getElementById("view1Chart").getContext("2d");';
    $chartScript .= 'new Chart(ctx, ' . json_encode($chartConfig1) . ');';

    echo '<h4> Number of Awards won vs lost for all actors </h4>';
    echo '<div class="view1-chart-container">';
    echo '<canvas id="view1Chart"></canvas>';
    echo '</div>';
    echo '<script>' . $chartScript . '</script>';
}

function generateView2Chart($conn_actors)
{
    global $chartData2, $chartConfig2;

    $limit = $_GET['limit'] ?? 30; // Numarul de actori selectat

    $chartData2 = fetchDataFromDatabase2($conn_actors, $limit);
    $labels = array_keys($chartData2);
    $values = array_values($chartData2);

    $chartConfig2 = array(
        'type' => 'bar',
        'data' => array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => 'Show Count',
                    'data' => array_column($values, 'ShowCount'),
                    'backgroundColor' => '#FF6384'
                ),
                array(
                    'label' => 'Awards Won',
                    'data' => array_column($values, 'AwardsWon'),
                    'backgroundColor' => '#36A2EB'
                )
            )
        ),
        'options' => array(
            'scales' => array(
                'y' => array(
                    'beginAtZero' => true
                )
            )
        )
    );

    $chartScript = 'var ctx = document.getElementById("view2Chart").getContext("2d");';
    $chartScript .= 'new Chart(ctx, ' . json_encode($chartConfig2) . ');';


    echo '<h4>Number of Shows and Awards for the best ranked ' . $limit . ' Actors</h4>';


    echo '<div class="chart-section">';
    //numarul de actori pe care il selectez eu - am nevoie de el tot pe pagina mea, nu in alta parte 
    echo '<form method="GET" action="' . $_SERVER['PHP_SELF'] . '#charts">';
    echo '<label for="limit">Number of Actors:</label>';
    echo '<select name="limit" id="limit">';
    for ($i = 1; $i <= 30; $i++) {
        echo '<option value="' . $i . '"' . ($i == $limit ? ' selected' : '') . '>' . $i . '</option>';
    }
    echo '</select>';
    echo '<input type="hidden" name="action" value="view2">';
    echo '<input type="submit" value="Generate Chart">';
    echo '</form>';
    echo '</div>';

    echo '<div class="chart-container">';
    echo '<canvas id="view2Chart"></canvas>';
    echo '</div>';
    echo '<script>' . $chartScript . '</script>';
}

function generateView3Chart($conn_actors)
{
    global $chartData3, $chartConfig3;

    $startYear = $_GET['startYear'] ?? null;
    $endYear = $_GET['endYear'] ?? null;

    if ($startYear && $endYear) {
        $chartData3 = fetchDataFromDatabase3($conn_actors, $startYear, $endYear);
    } else { //default
        $startYear = 1994;
        $endYear = 2020;
        $chartData3 = fetchDataFromDatabase3($conn_actors, $startYear, $endYear);
    }
    $labels = array_keys($chartData3);
    $values = array_values($chartData3);

    $chartConfig3 = array(
        'type' => 'line',
        'data' => array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => 'Awards Won',
                    'data' => $values,
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF6384',
                    'fill' => true
                )
            )
        ),
        'options' => array(
            'scales' => array(
                'y' => array(
                    'beginAtZero' => true
                )
            )
        )
    );

    $chartScript = 'var ctx = document.getElementById("view3Chart").getContext("2d");';
    $chartScript .= 'new Chart(ctx, ' . json_encode($chartConfig3) . ');';

    echo '<h4>Total Awards Won Over the Years</h4>';
    echo '<div class="chart-section">';
    echo '<form method="GET" action="' . $_SERVER['PHP_SELF'] . '#charts">';
    echo '<label for="startYear">Start Year:</label>';
    echo '<input type="number" name="startYear" id="startYear" required placeholder="' . $startYear . '">';
    echo '<label for="endYear">End Year:</label>';
    echo '<input type="number" name="endYear" id="endYear" required placeholder="' . $endYear . '">';
    echo '<input type="hidden" name="action" value="view3">';
    echo '<input type="submit" value="Generate Chart">';
    echo '</form>';
    echo '</div>';
    echo '<div class="chart-container">';
    echo '<canvas id="view3Chart"></canvas>';
    echo '</div>';
    echo '<script>' . $chartScript . '</script>';
}

function exportToCSV13($data, $filename)
{
    $filePath = '' . $filename . '.csv';

    $output = fopen($filePath, 'w');
    if ($output) {
        foreach ($data as $key => $value) {
            fputcsv($output, array($key, $value));
        }

        fclose($output);
        return $filePath;
    } else {
        return null;
    }
}

function exportToCSV2($data, $filename)
{
    $filePath = '' . $filename . '.csv';

    $output = fopen($filePath, 'w');
    if ($output) {
        fputcsv($output, array('Actor Name', 'Show Count', 'Awards Won'));

        foreach ($data as $actorName => $values) {
            $rowData = array($actorName, $values['ShowCount'], $values['AwardsWon']);
            fputcsv($output, $rowData);
        }

        fclose($output);
        return $filePath;
    } else {
        return null;
    }
}

function exportToWebP($chartConfig, $filename)
{

    $chartData = json_encode($chartConfig);

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="chartCanvas"></canvas>
    <script>
        var chartData = $chartData;
        var ctx = document.getElementById("chartCanvas").getContext("2d");
        new Chart(ctx, chartData);

        var exportCanvas = document.createElement("canvas");
        exportCanvas.width = ctx.canvas.width;
        exportCanvas.height = ctx.canvas.height;
        var exportContext = exportCanvas.getContext("2d");
        exportContext.drawImage(ctx.canvas, 0, 0);
        exportCanvas.toBlob(function(blob) {
            var url = URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url;
            a.download = "$filename.webp";
            a.click();
            URL.revokeObjectURL(url);
        }, "image/webp", 1);
    </script>
</body>
</html>
HTML;

    $filePath = "$filename.html";
    file_put_contents($filePath, $html);

    return $filePath;
}

function exportToSVG($chartConfig, $filename)
{
    $chartData = json_encode($chartConfig);

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dom-to-image"></script>
</head>
<body>
    <div id="chartContainer"></div>
    <script>
        var chartData = JSON.parse('$chartData');
        var canvas = document.createElement("canvas");
        canvas.width = 500; 
        canvas.height = 500;
        document.getElementById("chartContainer").appendChild(canvas);
        
        var ctx = canvas.getContext("2d");
        new Chart(ctx, chartData);
        
        domtoimage.toSvg(canvas)
            .then(function(svgDataUrl) {
                var downloadLink = document.createElement("a");
                downloadLink.href = svgDataUrl;
                downloadLink.download = "$filename.svg";
                downloadLink.click();
            });
    </script>
</body>
</html>
HTML;

    $filePath = "$filename.html";
    file_put_contents($filePath, $html);

    return $filePath;
}


if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'view1':
            generateView1Chart($conn_actors);
            break;
        case 'view2':
            generateView2Chart($conn_actors);
            break;
        case 'view3':
            generateView3Chart($conn_actors);
            break;
        default:
            break;
    }
}
