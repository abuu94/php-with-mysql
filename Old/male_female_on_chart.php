<!-- index.php
************************** -->
<!DOCTYPE html>
<html>
<head>
<title>Creating Dynamic Data Graph using PHP and Chart.js</title>
<style type="text/css">
BODY {
    width: 550PX;
}

#chart-container {
    width: 100%;
    height: auto;
}
</style>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/Chart.min.js"></script>


</head>
<body>
    <div id="chart-container">
        <canvas id="graphCanvas"></canvas>
    </div>

    <script>
        $(document).ready(function () {
            showGraph();
        });


        function showGraph()
        {
            {
                $.post("data.php",
                function (data)
                {
                    console.log(data);
                    var name = [];
                    var marks = [];

                    for (var i in data) {
                        name.push(data[i].emp_gender);
                        marks.push(data[i].total);
                    }

                    var chartdata = {
                        labels: name,
                        datasets: [
                            {
                                label: 'Student Marks',
                                backgroundColor: '#49e2ff',
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: marks
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
						///////
						options: {
							scales: {
								
								xAxes: [{
									  ticks:{
										  stepSize : 10,

									  },
									  stacked: true,
									gridLines: {
											lineWidth: 0,
											color: "rgba(255,255,255,0)"
										}
								  }],
								  yAxes: [{

									  stacked: true,
									   ticks: {
										  min: 0,
										  stepSize: 1,
									  }

								  }]
								
								
								
								
								
								
								
								
							}
						},
						
						
						
						////////
                        data: chartdata
                    });
                });
            }
        }
        </script>

</body>
</html>
<!-- ***************************************
data.php
###################################### -->
<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost","root","","test_db");

$sqlQuery =' SELECT  emp_gender, count(emp_gender) as total FROM employee GROUP BY emp_gender';

$result = mysqli_query($conn,$sqlQuery);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

mysqli_close($conn);

echo json_encode($data);
?>
