<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>
        <canvas id="bentukChart" style="height: 50vh; max-width: 100%;"></canvas>
    
    <!-- <script src="package/jquery/jquery.min.js"></script> -->
    <!-- <script src="package/chart.js/Chart.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// BentukChart
var bentukChartData = {
	labels  : ["Oracle","MySQL"],
	datasets: [{
		label: "MANUAL",
		backgroundColor: "#00BFFF",
		data: [50,100],
	},{
		label: "MITREKA",
		backgroundColor: "#98FB98",
		data: [100,200],
	},{
		label: "POLITEKNIK",
		backgroundColor: "#F0E68C",
		data: [200,200],
	},{
		label: "NIB",
		backgroundColor: "#FFD1DC",
		data: [150,50],
	}]
};

var bentukChartOptions = {
	responsive: true,
	maintainAspectRatio: false,
	datasetFill: true,
	title: {
		display: true,
		fontSize: 16,
		text : "Grafik Uji Coba"
	},
	// legend: {
	// 	display: true, 
	// 	position: 'bottom',
	// 	labels: {
	// 		fontSize: 14
	// 	}
	// },
	// tooltips: {
	// 	enabled: true,
	// 	titleFontSize: 16,
	// 	bodyFontSize: 14,
	// 	callbacks: {
	// 		title: (ctx) => {
	// 			return $("#bentukChart").data('subtitle')
	// 		}
	// 	}
	// },
	// scales: {
	// 	yAxes: [{
	// 		ticks: {
	// 			beginAtZero: true,
	// 			stacked: true,
	// 			fontSize: 12
	// 		},
	// 	}]
	// },
}

// var bentukChart = new Chart('bentukChart', {
// 	type: 'bar', 
// 	data: bentukChartData,
// 	options: bentukChartOptions
// })

const ctx = document.getElementById('bentukChart');

new Chart(ctx, {
type: 'pie', 
data: bentukChartData,
options: bentukChartOptions
});
</script>

</body>
</html>