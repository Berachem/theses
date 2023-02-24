<?php 
session_start();
$theses = $_SESSION["theses"];

$thesesSousEmbargo = 0;
$thesesNonSousEmbargo = 0;


foreach($theses as $these){
    $embargo = $these->getEmbargo();

    if($embargo != null && $embargo < date("Y-m-d")){
        $thesesSousEmbargo++;
    }
    else{
        $thesesNonSousEmbargo++;
    }
    
    
}


?>


<script>
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("pie-chart-embargo");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Sous embargo", "Non sous embargo"],
    datasets: [{
      //data: [<?php echo $FRlanguageNumber; ?>, parseInt(<?php echo $ENlanguageNumber; ?>), parseInt(<?php echo $ENFRlanguageNumber; ?>), parseInt(<?php echo $otherLanguageNumber; ?>)],
      data : [<?php echo $thesesSousEmbargo; ?>, <?php echo $thesesNonSousEmbargo; ?>],

      backgroundColor: ['#6f42c1', '#ffc107'],
      hoverBackgroundColor: ['#5a32a3', '#e0a800'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});
</script>