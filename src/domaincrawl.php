<?php
libxml_use_internal_errors(TRUE);

$domain = $_POST['domain'];
$name = $_POST['name'];

if(strpos($domain, '://') == false) {
  $domain = 'https://'.$domain;
}

$response = file_get_contents($domain);
$dom = new DOMDocument();
$dom->loadHTML($response);

$links = $dom->getElementsByTagName('a');
foreach ($links as $link) {
  $linkhref = $link->getAttribute('href');
  if (preg_match('(javascript|mailto|tel|#)i',$linkhref) == 0 && $linkhref != '/') {
    if(substr( $linkhref, 0, 4 ) == "http"){
      $hrefs[] = $linkhref;
    }
    else {
      $hrefs[] = $domain.$linkhref;
    }
  }
}

foreach ($hrefs as $href) {
  $response = file_get_contents($href);
  $dom = new DOMDocument();
  $dom->loadHTML($response);

  foreach ($links as $link) {
  $linkhref = $link->getAttribute('href');
    if (preg_match('(javascript|mailto|tel|#)i',$linkhref) == 0 && $linkhref != '/') {
      if(substr( $linkhref, 0, 4 ) == "http"){
        $hrefs2[] = $linkhref;
      }
      else {
        $hrefs2[] = $domain.$linkhref;
      }
    }
  }
}

foreach ($hrefs2 as $href) {
  try {
    if(file_exists('cache/'.preg_replace('/http:\/\/|https:\/\/|\/|:/', '.', $href))) {
      $response = file_get_contents('cache/'.preg_replace('/http:\/\/|https:\/\/|\/|:/', '.', $href));
      if(strpos($response, $name) !== false) {
          $foundon[] = $href;
        }
    }
    else {
      $response = @file_get_contents($href);
      if($response == '') {
        throw new Exception("Cannot access '$href' to read contents.");
      }
      else {
        file_put_contents('cache/'.preg_replace('/http:\/\/|https:\/\/|\/|:/', '.', $href), $response);
        if(strpos($response, $name) !== false) {
          $foundon[] = $href;
        }
      }
    }
  } catch (\Exception $ex) {
    $error[] = $href;
  }
}

$data = array('domains' => $foundon, 'pages_crawled' => sizeof($hrefs2), 'error' => $error);
echo json_encode($data);
