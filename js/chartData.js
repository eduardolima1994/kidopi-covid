var randomScalingFactor = function () { return Math.round(Math.random() * 1000) };
	
	var lineChartData = {
		labels : states,
		datasets : [
			{
				label: "My First dataset",
				fillColor : "rgba(220,220,220,0.2)",
				strokeColor : "rgba(220,220,220,1)",
				pointColor : "rgba(220,220,220,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(220,220,220,1)",
				data : confirmed
			},
			{
				label: "My Second dataset",
				fillColor : "rgba(37, 190, 174, 0.2)",
				strokeColor : "rgba(37, 190, 174, 1)",
				pointColor : "rgba(37, 190, 174, 1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(37, 190, 174, 1)",
				data : death
			}
		]

	}
		
	var barChartData = {
		labels : states,
		datasets : [
			{
				fillColor : "rgba(143, 140, 136,0.5)",
				strokeColor : "rgba(143, 140, 136,0.8)",
				highlightFill: "rgba(143, 140, 136,0.75)",
				highlightStroke: "rgba(143, 140, 136,1)",
				data : confirmed
			},
			{
				fillColor : "rgba(226, 105, 71, 0.2)",
				strokeColor : "rgba(226, 105, 71, 0.8)",
				highlightFill : "rgba(226, 105, 71, 0.75)",
				highlightStroke : "rgba(226, 105, 71, 1)",
				data : death
			}
		]

	} 

