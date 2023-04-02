<?php
// top 10 établissements qui ont le plus de thèses
$etablissements = array(); // array of names of the institutions
$thesesNumberPerEtablissement = array(); // array of number of theses per institution
foreach($theses as $these){
    if(!in_array($these->getEtablissement(), $etablissements)){
        array_push($etablissements, $these->getEtablissement()); // add the name of the institution to the array
        $thesesNumberPerEtablissement[$these->getEtablissement()] = 1; // add the number of theses (1) to the array
    }else{
        $thesesNumberPerEtablissement[$these->getEtablissement()]++; // increment the number of theses
    }
}
// keep only the top 10 and put the rest in "autres"
$otherEtablissementNumber = 0;
for($i = 9; $i < count($thesesNumberPerEtablissement); $i++){
    $otherEtablissementNumber += $thesesNumberPerEtablissement[$i];
}
$thesesNumberPerEtablissement = array_slice($thesesNumberPerEtablissement, 0, 9);
$etablissements = array_slice($etablissements, 0, 9);

// remove for all $etablissements all text between "(" and ")"
for($i = 0; $i < count($etablissements); $i++){
    $etablissements[$i] = preg_replace('/\([^)]+\)/', '', $etablissements[$i]);
}

$_SESSION["etablissements"] = $etablissements;
$_SESSION["etablissements-colors"] = array('#4e73df', '#1cc88a', '#36b9cc', '#C8331C', '#13D253', '#A808A0', '#D21399', '#D2D213', '#D213D2', '#D21313');



?>



<script>

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("pie-chart-etablissements");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["<?php echo implode('", "', array_keys($thesesNumberPerEtablissement)); ?>"],
    datasets: [{
      data: [<?php echo implode(', ', $thesesNumberPerEtablissement); ?>],
      backgroundColor: [<?php foreach($_SESSION["etablissements-colors"] as $color){ echo "'".$color."', "; } ?>],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#A62817', '#2CAF35', '#D21399', '#D213D2', '#D21313', '#D2D2D2', '#D21313', '#D2D2D2'],
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
      display: true,
      position: 'bottom',
      itemMarginTop: 10,
    },
    cutoutPercentage: 80,
  },
});
</script>