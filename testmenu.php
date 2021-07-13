<?php

$servername = "localhost";
$username = "personal";
$password = "C0l0mb14";
$dbname = "mibase";

function makeNested(Array $data, $parent_id = 0) {
    $tree = array();
    foreach ($data as $d) {
        if ($d['parent_id'] == $parent_id) {
            $children = makeNested($data, $d['menu_id']);
            if (!empty($children)) {
                $d['children'] = $children;
            }
            $tree[] = $d;
        }
    }
    return $tree;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT menu_id,title,icon,type,parent_id FROM tb_app_menu where active='S'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $data = array();
  while ($row = $result->fetch_assoc()) {
    $tmp = array();
    $tmp['menu_id'] = $row['menu_id'];
    $tmp['title'] = $row['title'];
    $tmp['icon'] = $row['icon'];
    $tmp['type'] = $row['type'];
    $tmp['parent_id'] = $row['parent_id'];
    $tmp['children'] = array();
    array_push($data, $tmp);
  }

  $results['result'] = makeNested($data);
  print json_encode($results, JSON_NUMERIC_CHECK);
}
else {
  echo "No rows.";
}
$conn->close();
?>