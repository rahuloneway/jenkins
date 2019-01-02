<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php 
		//echo '<pre>';print_r($statistics);echo '</pre>';
		$ip = $statistics['invoices']['monthly_paid_invoices'];
		$ti = $statistics['invoices']['monthly_due_invoices'];
		$im = $statistics['invoices']['chart_months'];
		
		$ep = $statistics['expenses']['monthly_paid_expenses'];
		$te = $statistics['expenses']['monthly_total_expenses'];
		$em = $statistics['invoices']['chart_months'];
		
		$months	=	array(
			'01'		=>	'Jan',
			'02'		=>	'Feb',
			'03'		=>	'Mar',
			'04'		=>	'Apr',
			'05'		=>	'May',
			'06'		=>	'June',
			'07'		=>	'July',
			'08'		=>	'Aug',
			'09'		=>	'Sep',
			'10'		=>	'Oct',
			'11'		=>	'Nov',
			'12'		=>	'Dec'
		);
?>
<script>

var ctx = document.getElementById("myChart").getContext("2d");
var data = {
    labels: ["<?php echo $months[$im[1]];?>","<?php echo $months[$im[2]];?>", "<?php echo $months[$im[3]];?>", "<?php echo $months[$im[4]];?>", "<?php echo $months[$im[5]];?>", "<?php echo $months[$im[6]];?>", "<?php echo $months[$im[7]];?>","<?php echo $months[$im[8]];?>","<?php echo $months[$im[9]];?>","<?php echo $months[$im[10]];?>","<?php echo $months[$im[11]];?>","<?php echo $months[$im[12]];?>"],
    datasets: [
        {
			label: "My Second dataset",
            fillColor: "#acd34a",
            strokeColor: "rgba(151,187,205,0.8)",
            //highlightFill: "#78b5f0",
            highlightFill: "#a6b557",
            data: [<?php echo $ip[$im[1]];?>, <?php echo $ip[$im[2]];?>, <?php echo $ip[$im[3]];?>, <?php echo $ip[$im[4]];?>, <?php echo $ip[$im[5]];?>, <?php echo $ip[$im[6]];?>, <?php echo $ip[$im[7]];?>, <?php echo $ip[$im[8]];?>, <?php echo $ip[$im[9]];?>, <?php echo $ip[$im[10]];?>, <?php echo $ip[$im[11]];?>, <?php echo $ip[$im[12]];?>]
			
        },
        {
			label: "Invoices for 2015",
            fillColor: "#0069ba",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "#0069ba",
            data: [<?php echo $ti[$im[1]];?>, <?php echo $ti[$im[2]];?>, <?php echo $ti[$im[3]];?>, <?php echo $ti[$im[4]];?>, <?php echo $ti[$im[5]];?>, <?php echo $ti[$im[6]];?>, <?php echo $ti[$im[7]];?>, <?php echo $ti[$im[8]];?>, <?php echo $ti[$im[9]];?>, <?php echo $ti[$im[10]];?>, <?php echo $ti[$im[11]];?>, <?php echo $ti[$im[12]];?>]
        }
    ],
	
};
var myBarChart = new Chart(ctx).Bar(data, {
    barShowStroke: false,
	legendTemplate : '<span><i class="fa fa-square s_blue"></i><?php echo $this->lang->line('CHART_INVOICE_LABLE_PAID');?></span> <span><i class="fa fa-square green"></i><?php echo $this->lang->line('CHART_INVOICE_LABLE_UNPAID');?></span>'
});

  var legend = myBarChart.generateLegend();

  //and append it to your page somewhere
  $('#invoice_legendDiv').html(legend);
  
  
var ctx = document.getElementById("chart_expenses").getContext("2d");
var data = {
    labels: ["<?php echo $months[$em[1]];?>","<?php echo $months[$em[2]];?>", "<?php echo $months[$em[3]];?>", "<?php echo $months[$em[4]];?>", "<?php echo $months[$em[5]];?>", "<?php echo $months[$em[6]];?>", "<?php echo $months[$em[7]];?>","<?php echo $months[$em[8]];?>","<?php echo $months[$em[9]];?>","<?php echo $months[$em[10]];?>","<?php echo $months[$em[11]];?>","<?php echo $months[$em[12]];?>"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "#0069ba",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "#0069ba",
			data: [<?php echo $te[$em[1]];?>, <?php echo $te[$em[2]];?>, <?php echo $te[$em[3]];?>, <?php echo $te[$em[4]];?>, <?php echo $te[$em[5]];?>, <?php echo $te[$em[6]];?>, <?php echo $te[$em[7]];?>, <?php echo $te[$em[8]];?>, <?php echo $te[$em[9]];?>, <?php echo $te[$em[10]];?>, <?php echo $te[$em[11]];?>, <?php echo $te[$em[12]];?>]			
        },
        {
            label: "Expenses for 2015",
            fillColor: "#acd34a",
            strokeColor: "rgba(151,187,205,0.8)",
            //highlightFill: "#78b5f0",
            highlightFill: "#a6b557",
            data: [<?php echo $ep[$em[1]];?>, <?php echo $ep[$em[2]];?>, <?php echo $ep[$em[3]];?>, <?php echo $ep[$em[4]];?>, <?php echo $ep[$em[5]];?>, <?php echo $ep[$em[6]];?>, <?php echo $ep[$em[7]];?>, <?php echo $ep[$em[8]];?>, <?php echo $ep[$em[9]];?>, <?php echo $ep[$em[10]];?>, <?php echo $ep[$em[11]];?>, <?php echo $ep[$em[12]];?>]
        }
    ]
};
var myBarChart = new Chart(ctx).Bar(data, {
    barShowStroke: false,
	legendTemplate : '<span><i class="fa fa-square s_blue"></i><?php echo $this->lang->line('CHART_EXPENSE_LABLE_PAID');?></span> <span><i class="fa fa-square green"></i><?php echo $this->lang->line('CHART_EXPENSE_LABLE_UNPAID');?></span>'
});
 var legend = myBarChart.generateLegend();

  //and append it to your page somewhere
  $('#expense_legendDiv').html(legend);
</script>