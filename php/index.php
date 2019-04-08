<?php
$j = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'graph_1.json' ); 
$nodes = json_decode($j, TRUE);
echo "Граф с вершинами:";
$string = "";
foreach($nodes as $key => $value)
{
    $string .= $key . ", ";
}
$string = substr($string, 0, -2);
echo $string;
echo "<br>";
echo "<br>";?>
<label>"Выберите начальную вершину:</label><br>
<form action="index.php" method="POST">
<select name="select_start" id="select_start">
<?php
foreach($nodes as $key => $value)
{
    echo "<option value = $key>$key</option>";
}?>
</select><br>
<label>"Выберите конечную вершину:</label><br>
<select name="select_finish" id="select_finish">
<?php
foreach($nodes as $key => $value)
{
    echo "<option value = $key>$key</option>";
}?>
</select><br>
<input type="submit" name="send" value="Отправить" />
</form>
<?php
$start = $_POST['select_start'];
$finish = $_POST['select_finish'];

$marks = [];
$visited_nodes = [];
$unvisited_nodes = [];
$negative = FALSE;

foreach ($nodes as $node => $neighbors) {
    foreach ($neighbors as $neighbor => $value) {
        if ($value < 0) {
            $negative = TRUE;
        }
    }
    if ($node == $start) {
        $mark = 0;
    } else {
        $mark = 999999;
    }
    $marks[$node] = $mark;
    $unvisited_nodes[$node] = $mark;
}

if ($negative) {
    exit("К сожалению, алгоритм Дейкстры не работает с рёбрами отрицательного веса.");
}

echo $current_node;
while ($unvisited_nodes != []) {
    $current_node = min(array_keys($unvisited_nodes, min($unvisited_nodes)));
    foreach ($nodes[$current_node] as $next_node => $distance) {
        if (in_array($next_node, $visited_nodes)) continue(1);
        $new_mark = $marks[$current_node] + $distance;
        if ($new_mark < $marks[$next_node]) {
            $marks[$next_node] = $new_mark;
            $unvisited_nodes[$next_node] = $new_mark;
        }
    }
    $visited_nodes[] = $current_node;
    unset($unvisited_nodes[$current_node]);
}

$current_node = $finish;
$short_path[] = $finish;
while ($current_node != $start) {
    foreach ($nodes[$current_node] as $next_node => $distance) {
        if ($marks[$current_node] - $distance == $marks[$next_node]) {
            $short_path[] = $next_node;
            $current_node = $next_node;
            break(1);
        }
    }
}

$short_path = array_reverse($short_path);
echo "Кратчайший путь из вершины ", $start, " в вершину ", $finish, ": ";
foreach ($short_path as $key => $value) {
    echo $value . PHP_EOL;
}
echo "<br>";
echo "Общий вес маршрута: " . $marks[$finish];
?>