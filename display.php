<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {
      var jsonData = $.ajax({
          url: "getData.php",
          dataType:"json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, {width: 400, height: 240});
    }

    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>

<div id="shareholders">
<table>

<thead>
<tr><th>Name</th><th>Shares</th><th>Time of last entry</th></tr>
</thead><tbody>
<?

$dbh = new PDO('mysql:host=localhost;dbname=eve', 'eve', 'eve');
# current shareholders only
$sql="select name,eveid,shares,lastupdate from shareholders where lastupdate=(select max(lastupdate) from  evesupport.shareholders)";

#all shareholders in the last 5 months, along with the time they were last a shareholder, with the shares they held at that time.
#$sql="select name,eveid,shares,max(lastupdate) from shareholders where lastupdate>date_sub(now(),INTERVAL 5 MONTH) group by name,eveid,shares";


$stmt = $dbh->prepare($sql);

$stmt->execute();
while ($row = $stmt->fetchObject()){
echo "<tr><td>".$row->name."</td><td>".$row->shares."</td><td>".$row->lastupdate."</td></tr>";
}


?>
</tbody>
</table>
</div>
<div class="CurrentStockValue">
Stock value at current time:
<?

$sql="select * from balance order by id desc limit 1";
$stmt = $dbh->prepare($sql);

$stmt->execute();
$row = $stmt->fetchObject();
echo $row->balance/$row->shares;



?>

</div>
</body>
</html>
