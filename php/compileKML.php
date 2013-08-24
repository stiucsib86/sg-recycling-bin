<?php

$WASTE_VENDORS = array(
    array(
        'key' => '800 Super Waste',
        'contractor' => '800 Super Waste Management Pte Ltd',
        'contact_no' => '63663800',
        'style_id' => '800superwaste'
    ),
    array(
        'key' => 'Veolia Environmental',
        'contractor' => 'Veolia Environmental Services Pte Ltd',
        'contact_no' => '64883408',
        'style_id' => 'veoliaenvironmental'
    ),
    array(
        'key' => 'SembWaste',
        'contractor' => 'SembWaste Pte Ltd',
        'contact_no' => '1800-2786135',
        'style_id' => 'sembwaste'
    ),
    array(
        'key' => 'Colex Holdings',
        'contractor' => 'Colex Holdings Ltd',
        'contact_no' => '62684775',
        'style_id' => 'colexholdings'
    )
);

//<Style id="style157">
//<IconStyle>
//<Icon>
//<href>http://maps.gstatic.com/intl/en_ALL/mapfiles/ms/micons/blue-dot.png</href>
//</Icon>
//</IconStyle>
//</Style >
// Creates an array of strings to hold the lines of the KML file.
$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = '<kml xmlns="http://earth.google.com/kml/2.1">';
$kml[] = ' <Document>';
$kml[] = ' <Style id="800superwaste">';
$kml[] = ' <IconStyle>';
$kml[] = ' <Icon>';
$kml[] = ' <href>http://maps.gstatic.com/intl/en_ALL/mapfiles/ms/micons/blue-dot.png</href>';
$kml[] = ' </Icon>';
$kml[] = ' </IconStyle>';
$kml[] = ' </Style>';
$kml[] = ' <Style id="veoliaenvironmental">';
$kml[] = ' <IconStyle id="barIcon">';
$kml[] = ' <Icon>';
$kml[] = ' <href>http://maps.gstatic.com/intl/en_ALL/mapfiles/ms/micons/red-dot.png</href>';
$kml[] = ' </Icon>';
$kml[] = ' </IconStyle>';
$kml[] = ' </Style>';
$kml[] = ' <Style id="sembwaste">';
$kml[] = ' <IconStyle id="barIcon">';
$kml[] = ' <Icon>';
$kml[] = ' <href>http://maps.gstatic.com/intl/en_ALL/mapfiles/ms/micons/green-dot.png</href>';
$kml[] = ' </Icon>';
$kml[] = ' </IconStyle>';
$kml[] = ' </Style>';
$kml[] = ' <Style id="colexholdings">';
$kml[] = ' <IconStyle id="barIcon">';
$kml[] = ' <Icon>';
$kml[] = ' <href>http://maps.gstatic.com/intl/en_ALL/mapfiles/ms/micons/yellow-dot.png</href>';
$kml[] = ' </Icon>';
$kml[] = ' </IconStyle>';
$kml[] = ' </Style>';


$string = file_get_contents("../app/data/binsLocation.json");
$bin_locations = json_decode($string, true);

//<Placemark>
//      <name>Boon Keng Road</name>
//      <description><![CDATA[]]></description>
//      <styleUrl>#style5</styleUrl>
//      <Point>
//        <coordinates>103.862236,1.317128,0.000000</coordinates>
//      </Point>
//    </Placemark>

foreach ($bin_locations as $location) {
  if ($location['lng']) {
    // Iterates through the rows, printing a node for each row.
    $kml[] = ' <Placemark>';
    $kml[] = ' <name>' . htmlentities($location['block'] . $location['street']) . '</name>';
    $kml[] = ' <description>' . ('<![CDATA[]]>') . '</description>';
    $_vendor_id = '';
    foreach ($WASTE_VENDORS as $vendor) {
      if ($vendor['contractor'] == $location['contractor']) {
        $_vendor_id = $vendor['style_id'];
      }
    }
    if (!$_vendor_id) {
      $_vendor_id = $WASTE_VENDORS[0]['style_id'];
    }
    $kml[] = ' <styleUrl>#' . ($_vendor_id) . '</styleUrl>';
    $kml[] = ' <Point>';
    $kml[] = ' <coordinates>' . $location['lng'] . ',' . $location['lat'] . '</coordinates>';
    $kml[] = ' </Point>';
    $kml[] = ' </Placemark>';
  }
}

// End XML file
$kml[] = ' </Document>';
$kml[] = '</kml>';
$kmlOutput = join("\n", $kml);
//header('Content-type: application/vnd.google-earth.kml+xml');
echo $kmlOutput;
?>