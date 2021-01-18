<?php
  header("Content-Type:application/json");
  $input_string = $_GET['string'];
  global $response;
  if(isset($input_string)) {
    if(strpos($input_string, '@') !== false) {
      $response['email'] = $input_string;
      $domain = explode('@', $input_string)[1];
      $response['domain'] = $domain;
      $fullname = explode('@', $input_string)[0];
      $fullname = str_replace('.', ' ', $fullname);
      //getCompany($domain);
      getName($fullname);
      getPersonDetail($fullname, $domain);
    }
    else {
      getName($input_string);
    }
    crawlwebsite($fullname ,$response['more']);
    echo json_encode($response);
  }

  function crawlwebsite($name, $domain) {
    global $response;
    $detailpage = file_get_contents($domain);
    $dom = new DomDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($detailpage);
    $xpath = new DOMXpath($dom);
    $results = $xpath->query("//*[text()[contains( translate(., 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'".$name."')]]");
    if(count($results) != 0) {
      $result = $results[0];
      while($result->parentNode->nodeValue == $result->nodeValue) {
        $result = $result->parentNode;
      }
      $response['webscraping'] = $result->parentNode->nodeValue;
    }
  }

  function getPersonDetail($name, $domain) {
    include 'credentials.php';
    global $response;
    $search = "site:{$domain} \"{$name}\"";
    $search = urlencode($search);
    $about = file_get_contents("https://www.googleapis.com/customsearch/v1?cx=006584671418311382743:jt36an3ix9p&q={$search}&fields=items(link)&key={$googleapikey}");
    $result = json_decode($about);
    if(isset($result->items[0])) {
      $response['more'] = $result->items[0]->link;
    }
    $search = "\"{$name}\" site:{$domain}";
    $search = urlencode($search);
    $image = file_get_contents("https://www.googleapis.com/customsearch/v1?cx=006584671418311382743:jt36an3ix9p&q={$search}&fields=items(link)&searchType=image&key={$googleapikey}");
    $result = json_decode($image);
    if(isset($result->items[0])) {
      $response['image']['src'] = $result->items[0]->link;
      $url = 'https://francecentral.api.cognitive.microsoft.com/face/v1.0/detect?returnFaceAttributes=age,gender';
      $data = array('url' => $result->items[0]->link);
      $data_string = json_encode($data);

      // use key 'http' even if you send the request to https://...

      $options = array(
          'http' => array(
              'header'  => array("Ocp-Apim-Subscription-Key: 53e1eecb6b774a8a8985549f7593e74b", "Content-Type: application/json; charset=utf-8"),
              'method'  => 'POST',
              'content' => $data_string
          )
      );
      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      /*$response['image']['rectangle']['top'] = json_decode($result)[0] -> faceRectangle -> top;
      $response['image']['rectangle']['left'] = json_decode($result)[0] -> faceRectangle -> left;
      $response['image']['rectangle']['width'] = json_decode($result)[0] -> faceRectangle -> width;
      $response['image']['rectangle']['height'] = json_decode($result)[0] -> faceRectangle -> height;*/
      $response['image']['age'] = date("Y") - json_decode($result)[0] -> faceAttributes -> age;
      $response['image']['gender'] = json_decode($result)[0] -> faceAttributes ->gender;
    }
    else{
      $response['image'] = '';
    }
      /*libxml_use_internal_errors(true);
      $dom = new DomDocument;
      $dom->loadHTMLFile($response['more']);
      $xpath = new DomXPath($dom);
      $img = $xpath->query("//*[text()[contains( translate(., 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'sebastian fluri')]]/../img")[0]->nodeValue;
      print_r($img);
      $response['img'] = $img;*/
  }

  function getCompany($domain) {
    include 'credentials.php';
    global $response;
    try {
      $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password);
      $query = $pdo->prepare("select * from company where domain like '%{$domain}%'");
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_ASSOC);
      if(count($results) == 0) {
        libxml_use_internal_errors(true);
        $dom = new DomDocument;
        $dom->loadHTMLFile("https://www.google.com/search?q={$domain}");
        $xpath = new DomXPath($dom);
        $response['company']['name'] = $xpath->query("//*[contains(@style,'max-width:72px;max-height:72px')]/../following-sibling::*")[0]->nodeValue;
        $description = $xpath->query("//*[contains(@style,'max-width:72px;max-height:72px')]/../following-sibling::*")[1]->nodeValue;
        if(preg_match('/\r\n|\r|\n/',$description)) {
          $response['company']['segment'] = preg_split('/\r\n|\r|\n/', $description)[1];
        }
        else{
          $response['company']['segment'] = $description;
        }
        $response['company']['address'] = $xpath->query("//*[contains(text(),'Adresse')]/../following-sibling::*")[0]->nodeValue;
        $response['company']['phone'] = $xpath->query("//*[contains(text(),'Telefonnummer')]/../following-sibling::*")[0]->nodeValue;
        $url = $xpath->query("//*[contains(text(),'Website')]/../@href")[0]->nodeValue;
        if($url != '') {
          $response['company']['domain'] = explode('/',explode('://', $url)[1])[0];
          $query = "REPLACE INTO company (companyname, companyaddress, segment, domain, phone) VALUES ('{$response['company']['name']}', '{$response['company']['address']}', '{$response['company']['segment']}', '{$response['company']['domain']}', '{$response['company']['phone']}')";
          $query = $pdo->prepare($query);
          $query->execute();
        }
      }
      else {
        $response['company']['name'] = $results[0]['companyname'];
        $response['company']['segment'] = $results[0]['segment'];
        $response['company']['phone'] = $results[0]['phone'];
        $response['company']['address'] = $results[0]['companyaddress'];
        $response['company']['domain'] = $results[0]['domain'];
      }
    } catch (\PDOException $e) {
          throw new \PDOException($e->getMessage(), (int)$e->getCode());
      }
  }

  function getName($string) {
    include 'credentials.php';
    global $response;
    try {
        $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password);
        $names = explode(' ', $string);
        $query = "select * from (select gender, name, substring_index(origin, ',', 1) as origin, 'firstname' as type from firstname union select null, name, substring_index(origin, ',', 1) as origin, 'lastname' as type from lastname) as names where ";
        foreach ($names as $name) {
          $query .= "lower(name) = '".strtolower($name)."' or ";
          $name = str_replace("ue", "ü", strtolower($name));
          $name = str_replace("ae", "ä", strtolower($name));
          $name = str_replace("oe", "ö", strtolower($name));
          $query .= "lower(name) = '".$name."' or ";
        }
        $query = substr($query, 0, -4);
        //$query .= " collate utf8_general_ci";
        $query = $pdo->prepare($query);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $result) {
          $tmp[$result['name']]['count'] += 1;
          $tmp[$result['name']]['type'] = $result['type'];
          //$tmp[$result['name']]['origin'] = $result['origin'];
          if($result['type'] == 'firstname') {$tmp[$result['name']]['gender'] = $result['gender'];}
        }
        foreach ($tmp as $key => $value) {
          if($value['count'] == 1) {
            $response[$value['type']]['value'] = $key;
            if($value['type'] == 'firstname') {
              $response[$value['type']]['gender'] = $value['gender'];
              // TODO detect nonunique gender names like Andrea
            }
            /*if(isset($value['origin'])) {
              $response[$value['type']]['origin'] = $value['origin'];
            }*/
          }
        }
        foreach ($tmp as $key => $value) {
          if($value['count'] !== 1) {
            if(isset($response['firstname'])) {
              $response['lastname'] = $key;
              /*if(isset($value['origin'])) {
                $response['lastname']['origin'] = $value['origin'];
                // TODO detect names that can be switched like robert franz
              }*/
            }
            if(isset($response['lastname'])) {
              $response['firstname']['value'] = $key;
              $response['firstname']['gender'] = $value['gender'];
              /*if(isset($value['origin'])) {
                $response['firstname']['origin'] = $value['origin'];
              }*/
            }
            else {
              $response['name'] = $string;
            }
          }
        }
        return $response;
      } catch (\PDOException $e) {
          throw new \PDOException($e->getMessage(), (int)$e->getCode());
      }
  }
