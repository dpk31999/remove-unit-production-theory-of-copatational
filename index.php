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
echo "<h1>Problem 1</h1>";
$lang_row_arr = get_problem_arr("/example/problem1.txt");
simplification_of_CFG($lang_row_arr);
echo "<hr>";
//solve_problem 2
$lang_row_arr = get_problem_arr("/example/problem2.txt");
echo "<h1>Problem 2</h1>";
simplification_of_CFG($lang_row_arr);
echo "<hr>";


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
  echo "<h3>Original Problem</h3>";
  display_lang($lang_row_arr);

  //remove all lambda values
  echo "<h3>Removing Lambda</h3>";
  //replace data of array
  $lang_row_arr = check_for_lambda($lang_row_arr);
  display_lang($lang_row_arr);

  echo "<h3>Remove Unit Productions</h3>";
  $lang_row_arr = check_for_unit_production($lang_row_arr);
  display_lang($lang_row_arr);

  echo "<h3>Remove useless production</h3>";
  $lang_row_arr = check_for_useless_production($lang_row_arr);
  display_lang($lang_row_arr);
}

//removes the useless productions of a language
function check_for_useless_production($arr)
{
  //keep track of remove useless production
  $remove_main = array();
  foreach ($arr as $key_top => $value) {

    //check if there is a lower case value only
    $okay_row = false;
    foreach ($value['values'] as $key_low => $r) {
      if(ctype_lower($r)){
        $okay_row = true;
      }
      for ($i=0; $i <sizeof($remove_main) ; $i++) {
        //if match, then unset it from main arr
        $check_string = stripos($r, $remove_main[$i]);
        if($check_string !== false){
          $okay_row = false;
        };
      }
    }
    //if row should remove, added to the remove list
    if(!$okay_row){
      array_push($remove_main, $value['main']);
    }
  }

  //remove the value from array
  foreach ($arr as $key => $row) {
    if(in_array($row['main'], $remove_main)){
      unset($arr[$key]);
    }
  }
  //remove the nonterminals from deleted row
  foreach ($arr as $key => $value) {
    foreach ($value["values"] as $k => $r) {
      //check the remove nonTerminal values in the ['values'] array for each remaining one
      for ($i=0; $i <sizeof($remove_main) ; $i++) {
        //if match, then unset it from main arr
        if(stripos($r, $remove_main[$i])){
          unset($arr[$key]["values"][$k]);
        };
      }

    }
  }
  return $arr;
}

//replaces value that are single Non Terminals
function check_for_unit_production($arr){
  $remove_token_index = array(); //keeps track of id for userless productions
  for ($i=0; $i < sizeof($arr); $i++) {
    //loop throught the rows
    if(sizeof($arr[$i]["values"]) == 1 && strlen($arr[$i]["values"][0]) == 1){
      //replace value on non terminal
      $search_txt = $arr[$i]["values"][0];
      $arr[$i]["values"] = find_arr_value_nonterminal("$search_txt", $arr);
    }
  }
  //return the corrected array

  return $arr;
}

//finds all lambda and corrects it
function check_for_lambda($arr)
{
  //add terminal to the first row
  $value_holder = array();
  foreach ($arr[0]["values"] as $key => $value) {
    //check if there is only one nonTerminal and add a terminal
    if(count_capitals($value) == 1 && strlen($value) > 1){
      $newValue = remove_captitals($value);
      //add new value to first row
      array_push($value_holder, $newValue);
    }else if(count_capitals($value) == 1){
      //remove non terminals and add their values
      $arr_value = find_arr_value_nonterminal($value , $arr);
      foreach ($arr_value as $k => $v) {
        //push the value of the non ternminal
        array_push($value_holder, $v);
      }
      //unset replace value
      unset($arr[0]["values"][$key]);
    }
  }
  //merge arrays, and update the first row of the grammmar
  $arr[0]["values"] = array_merge($arr[0]["values"], $value_holder);
  //check for lambda values
  for ($i=0; $i < sizeof($arr); $i++) {
    //loop throuth the rows
    for ($j=0; $j < sizeof($arr[$i]['values']); $j++) {
      //if lambda if found then update the row
      if($arr[$i]['values'][$j] == "lambda"){
        $arr[$i]['values'] = remove_lambda($arr[$i]['values']);
      }
    }
  }
  //return the corrected array
  return $arr;
}
//remove the lamda from individual array, then returns it
function remove_lambda($arr){
  //find a terminal with only one capital letter, replace capital letter,
  //then replace that value for lambda
  $lambda_pos = -1;
  $replace_value = '';
  for ($i=0; $i < sizeof($arr); $i++) {
    if(count_capitals($arr[$i]) == 1){
      //remove main value,[remove capilate letters]
      $replace_value = preg_replace("/(?![a-z])./", "",$arr[$i]);
    }
    if($arr[$i] == 'lambda'){
      $lambda_pos = $i;
    }
  }
  //replace the value in the array

  $arr[$lambda_pos] = $replace_value;

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
  $values = explode("|",$split_string[1]);
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
      echo "<span>{$row['values'][$i]}</span>";

      if($i  < (sizeof($row['values']) - 1)){
        echo "<strong class='pipe'>|</strong>";
      }
    }
    echo "</p>";
  }
}

//helper functions
function count_capitals($s) {
  return strlen(preg_replace('![^A-Z]+!', '', $s));
}
function count_lowercase($s) {
  return strlen(preg_replace('![^a-z]+!', '', $s));
}

//remove NonTerminals and remov them
function remove_captitals($s){
  return preg_replace('![^a-z]+!', '', $s);
}

//find a nonterminal array value
//return array of value or empty array
function find_arr_value_nonterminal($search_txt, $arr){
  foreach ($arr as $key => $value) {
    if($value['main'] == $search_txt){
      return $value['values'];
    }
  }
  return array();
}
 ?>


</body>
</html>
