<?php

error_reporting(E_ALL);

function get_array_id($blk_no = '', $address1 = '') {
  $_id = '';
  $_id .= $blk_no;
  $_id .= $address1;
  $_id = trim(strtolower($_id));
  return $_id = substr($_id, 0, 18);
}

function format_output_item($location) {
  $keys = array('region', 'block', 'street', 'postal', 'lat', 'lng', 'no_of_bin', 'description', 'collection_day', 'contractor', 'contact_no',);

  if (strpos($location['collection_day'], 'For the collection of newspaper') !== FALSE) {
    $location['collection_day'] = "";
  }
  
  foreach ($keys as $key) {
    $location[$key] = trim($location[$key]);
  }

  return $location;
}

$WASTE_VENDORS = array(
    array(
        'key' => '800 Super Waste',
        'contractor' => '800 Super Waste Management Pte Ltd',
        'contact_no' => '63663800'
    ),
    array(
        'key' => 'Veolia Environmental',
        'contractor' => 'Veolia Environmental Services Pte Ltd',
        'contact_no' => '64883408'
    ),
    array(
        'key' => 'SembWaste',
        'contractor' => 'SembWaste Pte Ltd',
        'contact_no' => '1800-2786135'
    ),
    array(
        'key' => 'Colex Holdings',
        'contractor' => 'Colex Holdings Ltd',
        'contact_no' => '62684775'
    )
);

require_once 'simple_html_dom.php';

if (($handle = fopen("locationBin.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    if (get_array_id($data[1], $data[2])) {
      $_data['region'] = $data[0];
      $_data['block'] = $data[1];
      $_data['street'] = $data[2];
      $_data['postal'] = $data[3];
      $_data['no_of_bin'] = $data[4];
      $_data['description'] = $data[5];
      $_data['collection_day'] = $data[5];
      $bins_arr1[get_array_id($data[1], $data[2])] = ($_data);
    }
  }
  fclose($handle);
}

$html = file_get_html('locations.html');
$bins_arr2 = array();
foreach ($html->find('location') as $element) {
  $_data = array();
  $_title_arr = split(' ', $element->title);
  $_data['block'] = (array_shift($_title_arr));
  $_data['street'] = join(' ', $_title_arr);
  $_data['lat'] = $element->lat;
  $_data['lng'] = $element->lng;

  foreach ($WASTE_VENDORS as $vendor) {
    if (strpos($element->description, $vendor['key']) !== FALSE) {
      $_data['contractor'] = $vendor['contractor'];
      $_data['contact_no'] = $vendor['contact_no'];
    }
  }

  if (get_array_id($element->{'title'})) {
    $bins_arr2[get_array_id($element->{'title'})] = $_data;
  }
}

ksort($bins_arr1);
ksort($bins_arr2);
$_data = array();

$count = 1;
foreach ($bins_arr1 as $key => $location) {
  if (isset($bins_arr2[$key])) {
    // Merge the data
    $location = array_merge($bins_arr2[$key], $location);
    unset($bins_arr2[$key]);
  }
  $location = format_output_item($location);
  $final_arr[] = $location;
}

foreach ($bins_arr2 as $key => $location) {
  $final_arr[] = format_output_item($location);
  unset($bins_arr2[$key]);
}

echo json_encode($final_arr);

//var_dump($final_arr);
//var_dump($bins_arr1);
//var_dump($bins_arr2);

die();
?>