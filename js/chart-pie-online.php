<?php 
session_start();
$theses = $_SESSION["theses"];

$thesesEnLigne = 0;
$thesesNonEnLigne = 0;


foreach($theses as $these){
    if ($these->getEnligne()=="oui"){
        $thesesEnLigne++;
    }else{
        $thesesNonEnLigne++;
    }
    
    
}


?>


<script>
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("pie-chart-online");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["En ligne", "Non en ligne"],
    datasets: [{
      //data: [<?php echo $FRlanguageNumber; ?>, parseInt(<?php echo $ENlanguageNumber; ?>), parseInt(<?php echo $ENFRlanguageNumber; ?>), parseInt(<?php echo $otherLanguageNumber; ?>)],
      data : [<?php echo $thesesEnLigne; ?>, <?php echo $thesesNonEnLigne; ?>],
      backgroundColor: ['#4e73df', '#1cc88a'],
      hoverBackgroundColor: ['#2e59d9', '#17a673'],
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