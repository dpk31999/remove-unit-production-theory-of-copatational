<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Solving Problem</title>
    <style media="screen">
      body{
        font-family: Arial;
      }
      p{
        margin: 0 0 10px 0;
      }
      h3{
        font-size: 20px;
        margin: 0;
      }
      .pipe{
        font-size: 20px;
        color:red;
        margin: 0 5px;
      }
    </style>
  </head>
  <body>

<?php
//read problem file

//solve problem 1
echo "<h1>Remove Unit Productions</h1>";
$lang_row_arr = get_problem_arr("/example/problem1.txt");
simplification_of_CFG($lang_row_arr);

//read text and transform to array
function get_problem_arr($relative_path)
{
  $problem = file_get_contents(dirname(__FILE__).$relative_path);
  //explode string and seperate them in to arrays
  $text_rows = explode("\n",$problem);
  //explode each row into an associate array

  $lang_row_arr = array();
  foreach ($text_rows as $key => $text_row) {
    $lang_row = parse_text_row($text_row);
    //check if row is not empty
    if($text_row != ""){
        array_push($lang_row_arr, $lang_row);
    }
  }

  return $lang_row_arr;
}


//CALLS AND DISPLAYS THE ARRAY TO SOLVE THE PROBLEM
function simplification_of_CFG($lang_row_arr){
  //print nicely
  echo '<pre>'; print_r($lang_row_arr); echo '</pre>';

  echo "<h3>Văn Phạm Ban Đầu</h3>";
  display_lang($lang_row_arr);

  echo "<h3>Ta có sản xuất ko đơn vị</h3>";
  display_non_unit($lang_row_arr);

  echo "<h3>Khử sản xuất đơn vị</h3>";
  $lang_row_arr = check_for_unit_production($lang_row_arr);
  display_lang($lang_row_arr);

}

function remove_unit($arr)
{
    $arr_main = get_array_main($arr);
  for ($i=0; $i < sizeof($arr); $i++) {
    for ($k = 0; $k < sizeof($arr[$i]['values']); $k ++){
      // echo $arr[$i]['values'][$k];
      if(in_array(trim($arr[$i]['values'][$k]),$arr_main) == 1) {
        // echo $arr[$i]['values'][$k];
        array_splice($arr[$i]['values'],$k,1);
        $k--;
      }
    }
  }
  return $arr;
}

function display_non_unit($arr)
{ 

  foreach (remove_unit($arr) as $key => $row) {
    echo "<p>";
    echo "<strong>{$row['main']}</strong> ->";
    for ($i=0; $i <= sizeof($row['values']); $i++) {
      if(isset($row['values'][$i]))
      {
        echo "<span>{$row['values'][$i]}</span>";
      }
      if($i  < (sizeof($row['values']) - 1)){
        echo "<strong class='pipe'>|</strong>";
      }
    }
    echo "</p>";
  }
}

function get_array_main($arr)
{
  $arr_main = [];
  for ($i=0; $i < sizeof($arr); $i++) {
    array_push($arr_main,$arr[$i]['main']);
  }

  return $arr_main;
}

//replaces value that are single Non Terminals
function check_for_unit_production($arr){
  $arr_remove_unit = remove_unit($arr);
  $arr_main = get_array_main($arr);
  for($t = 0; $t < 2 ; $t++)
  {
    for ($i=sizeof($arr)-1; $i >= 0; $i--) {
      for ($j = 0; $j < sizeof($arr[$i]['values']); $j++)
      {
        if(in_array(trim($arr[$i]['values'][$j]), $arr_main))
        {
          foreach($arr as $key => $value)
          {
            if($arr[$key]['main'] == trim($arr[$i]['values'][$j]))
            {
              // array_splice($arr[$i]['values'],$j,1);
              foreach($arr[$key]['values'] as $key1 => $value1)
              {
                if(!in_array($arr[$key]['values'][$key1], $arr[$i]['values']) && !in_array($arr[$key]['values'][$key1],$arr_main))
                {
                  array_push($arr[$i]['values'],$arr[$key]['values'][$key1]);
                }
              }
            }
          }
        }
      }
    }
  }
  //return the corrected array

  $arr_main = get_array_main($arr);
  for ($i=0; $i < 1; $i++) {
    for ($k = 0; $k < sizeof($arr[$i]['values']); $k ++){
      if(in_array(trim($arr[$i]['values'][$k]),$arr_main) == 1) {
        array_splice($arr[$i]['values'],$k,1);
        $k--;
      }
    }
  }

  return $arr;
}

/*
Parse the text row into an associate array
returns array
*/
function parse_text_row($row)
{
  //get main value
  $split_string = explode('->', $row);
  $main = $split_string[0];
  if(isset($split_string[1]))
  {
    $values = explode("|",$split_string[1]);
  }
  else{
    $values = null;
  }
  //add info to an array
  $info = array(
    "main" => $main,
    "values" => $values
  );
  //return associate array
  return $info;

}
/*
Display the $language array nicely
*/
function display_lang($arr)
{
  foreach ($arr as $key => $row) {
    echo "<p>";
    echo "<strong>{$row['main']}</strong> ->";
    for ($i=0; $i <= sizeof($row['values']); $i++) {
      if(isset($row['values'][$i]))
      {
        echo "<span>{$row['values'][$i]}</span>";
      }
      if($i  < (sizeof($row['values']) - 1)){
        echo "<strong class='pipe'>|</strong>";
      }
    }
    echo "</p>";
  }
}
?>


</body>
</html>
