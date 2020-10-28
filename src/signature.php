<?php
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
          $tmp[$result['name']]['origin'] = $result['origin'];
          if($result['type'] == 'firstname') {$tmp[$result['name']]['gender'] = $result['gender'];}
        }
        if(is_array($tmp)) {
          foreach ($tmp as $key => $value) {
            if($value['count'] == 1) {
              $response[$value['type']] = [];
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
                $response['lastname']['value'] = $key;
                if(isset($value['origin'])) {
                  $response['lastname']['origin'] = $value['origin'];
                  // TODO detect names that can be switched like robert franz
                }
              }
              if(isset($response['lastname']['value'])) {
                $response['firstname']['value'] = $key;
                $response['firstname']['gender'] = $value['gender'];
                if(isset($value['origin'])) {
                  $response['firstname']['origin'] = $value['origin'];
                }
              }
              else {
                $response['name'] = $string;
              }
            }
          }
        }
        return $response;
      } catch (\PDOException $e) {
          throw new \PDOException($e->getMessage(), (int)$e->getCode());
      }
  }
?>



<?php
  $body = ($_POST['body']);
  $signature = preg_split("/\r\n|\n|\r/", $body);
  $sig_string='';
  $phone = [];
  $i = sizeof($signature)-1;
  while(
    preg_match('(lg|Grüsse|Gruss|Gruesse|--)i', $signature[$i]) == 0 &&
    #(strlen($signature[$i]) < 100 && sizeof($signature)-4 > $i) &&
    $i != 0
  ) {
    if($signature[$i]!='' && $signature[$i]!=' ' && strlen($signature[$i]) < 80){
      $sig_string=$signature[$i]."\n".$sig_string;
    }
    $i--;
  }
  foreach(preg_split("/((\r?\n)|(\r\n?))/", $sig_string) as $line){
      if (preg_match_all('/[0-9]/', $line) > 5) {
        array_push($phone, $line);
      }
      else if(preg_match_all('/[0-9]/', $line) > 3 && preg_match_all('/[0-9]/', $line) < 6) {
        $place = $line;
      }
      else if(preg_match_all('/[0-9]/', $line) < 4 && preg_match_all('/[0-9]/', $line) > 0) {
        $street = $line;
      }
      $name = getName($line);
      $firstname = $name['firstname']['value'] != null && $firstname == null ? $name['firstname']['value'] : $firstname;
      $lastname = $name['lastname']['value'] != null && $lastname == null ? $name['lastname']['value'] : $lastname;
      $gender = $name['firstname']['gender'] != null && $gender == null ? $name['firstname']['gender'] : $gender;
  }
  $data = array('firstname' => $firstname, 'lastname' => $lastname, 'gender' => $gender, 'phone' => $phone, 'street' => $street, 'place' => $place);
  echo json_encode($data);
?>