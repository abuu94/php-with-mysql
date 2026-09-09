index.php
**********************
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
   

				<canvas id="myChart" width="400" height="400"></canvas>
			<script>
			
			$(document).ready(function () {
					showGraph();
				});
				
			function showGraph()
			{
				{
					$.post("piechart_data.php", function(data){
						console.log(data);
						var name = [];
						var marks = [];
						
						for (var i in data) {
                        name.push(data[i].region);
                        marks.push(data[i].total);
							}
						var ctx = document.getElementById('myChart').getContext('2d');
						
						//start
						// For a pie chart
							var myPieChart = new Chart(ctx, {
								type: 'pie',
								data: {
									
									
									
									
									labels: name/*['Red', 'Yellow', 'Green', 'Purple', 'Orange']*/,
									datasets: [{
										label: '# of Votes',
										data: marks/*[12, 19, 3, 5, 2]*/,
										backgroundColor: [
											'rgba(255, 99, 132, 0.2)',
										
											'rgba(255, 206, 86, 0.2)',
											'rgba(75, 192, 192, 0.2)',
											'rgba(153, 102, 255, 0.2)',
											'rgba(255, 159, 64, 0.2)'
										],
										borderColor: [
											'rgba(255, 99, 132, 1)',
										
											'rgba(255, 206, 86, 1)',
											'rgba(75, 192, 192, 1)',
											'rgba(153, 102, 255, 1)',
											'rgba(255, 159, 64, 1)'
										],
										borderWidth: 1
									}]
								},
								options: {
									scales: {
										yAxes: [{
											ticks: {
												beginAtZero: true
											}
										}]
									}
								}
							});
										
						//end
					});
					
				}
			
			}
			</script>
</body>
</html>

piechart_data.php

<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost","root","","coronadb");

$sqlQuery ='SELECT  region, count(region) as total FROM tbl_victim GROUP BY region';

$result = mysqli_query($conn,$sqlQuery);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

mysqli_close($conn);

echo json_encode($data);
?>




