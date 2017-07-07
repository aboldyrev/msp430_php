var ctx = document.getElementById("myChart").getContext('2d');

nanoajax.ajax({url: '/get-data'}, function (code, data) {
		data = JSON.parse(data);
		var myLineChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: data.timestamps,
				datasets: [{
					label: 'Температура',
					data: data.temperatures,
					backgroundColor: 'rgba(75, 192, 192, 0.2)',
					borderColor: 'rgba(75, 192, 192, 1)',
					pointBackgroundColor: 'rgba(75, 192, 192, 1)',
					yAxisID: 'temp-y-axis'
				}, {
					label: 'Освещение',
					data: data.lights,
					backgroundColor: 'rgba(255, 99, 132, 0.2)',
					borderColor: 'rgba(255,99,132,1)',
					pointBackgroundColor: 'rgba(255,99,132,1)',
					yAxisID: 'light-y-axis'
				}]
			},
			options: {
				scales: {
					yAxes: [{
						id: 'temp-y-axis',
						type: 'linear'
					}, {
						id: 'light-y-axis',
						position: 'right',
						type: 'linear'
					}],
					xAxes: [{
						type: 'time',
						time: {
							displayFormats: {
								minute: 'H:mm'
							}
						}
					}]
				},
				tooltips: {
					intersect: false,
					mode: 'x',
					callbacks: {
						title: function (tooltipItem, chart) {
							return 'Время: ' + moment(tooltipItem[0].xLabel, 'YYYY-MM-DD HH:mm:SS').format('HH:mm')
						}
					}
				},
				layout: {
					padding: 20
				},
				title: {
					display: true,
					text: 'График изменения температуры и освещённости за последние 4 часа'
				},
				elements: {
					point: {
						radius: 2,
						hoverRadius: 5
					},
					line: {
						borderWidth: 1,
						fill: false
					}
				}
			}
		});
	}
);