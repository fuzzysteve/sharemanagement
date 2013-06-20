<?
header('Content-type: application/json');
$dbh = new PDO('mysql:host=localhost;dbname=eve', 'eve', 'eve');


$sql="select * from balance order by id desc";
$stmt = $dbh->prepare($sql);
$stmt->execute();
echo '{"cols":[{"id":"","label":"Date","type":"datetime"},{"id":"","label":"Value","type":"number"}],"rows":[';

$x=1;
while ($row=$stmt->fetchObject())
{
if ($x!=1)
{
echo ",";
}
$x=2;
$date=localtime(strtotime($row->pointtime));
$jsonformatdate="Date( ".($date[5]+1900).", ".($date[4]+1).", ".($date[3]).", ".($date[2]).", 0 , 0)";
echo '{"c":[{"v":"'.$jsonformatdate.'"},{"v":'.$row->balance/$row->shares."}]}";
}
echo "]}";

?>
