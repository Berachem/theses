<?php 
session_start();
$theses = $_SESSION["theses"];

$FRlanguageNumber = 0;
$ENlanguageNumber = 0;
$ENFRlanguageNumber = 0;
$otherLanguageNumber = 0;


foreach($theses as $these){
    if($these->getLangue() == "fr"){
        $FRlanguageNumber++;
    }
    elseif($these->getLangue() == "en"){
        $ENlanguageNumber++;
    }
    elseif($these->getLangue() == "enfr"){
        $ENFRlanguageNumber++;
    }else{
        $otherLanguageNumber++;
    }
}


?>


<script>
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["fr", "en", "enfr", "autres"],
    datasets: [{
      data: [parseInt(<?php echo $FRlanguageNumber; ?>), parseInt(<?php echo $ENlanguageNumber; ?>), parseInt(<?php echo $ENFRlanguageNumber; ?>), parseInt(<?php echo $otherLanguageNumber; ?>)],
      backgroundColor: ['#4e73df', '#C8331C', '#13D253', '#A808A0'],
      hoverBackgroundColor: ['#2e59d9', '#A62817', '#2CAF35', '#D21399'],
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