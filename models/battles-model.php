<?php

class Battles
{
  // Battle properties
  public $db;
  public $battles;

  // Assign parameter to argument to $db property
  public function __construct($db)
  {
    $this->db = $db;
  }

  /**
   * Queries for all data in battles table
   * @return [array]
   */
  public function getBattles()
  {
    // Query
    $stmt = $this->db->query("SELECT * FROM Battles");

    // Executes query
    $stmt->execute();

    // Create an empty array for the battles
    $battles = array();

    // loop through $result
    while($row = $stmt->fetch()) {

      // data from battles is added to the array
      $battles[] = $row;

    }
    // Return Array of rows
    return $battles;
  }

  public function getBattleByName($name)
  {
    $stmt = $this->db->query("SELECT * from Battles WHERE Battles.name = :name");

    $stmt->bindParam(':name', $name);
    $stmt->execute();

    $battle = array();

    while($row = $stmt->fetch()) {
      $battle = $row;
    }

    return $battle;

  }

  public function getBattleById($battleId)
  {
    $stmt = $this->db->query("SELECT * from Battles WHERE Battles.id = :id");

    $stmt->bindParam(':id', $battleId);
    $stmt->execute();

    $battle = array();

    while($row = $stmt->fetch()) {
      $battle = $row;
    }

    return $battle;

  }

  public function getFactionsByBattleId($battleId)
  {
    $stmt = $this->db->query("SELECT Factions.factionName, NotablePersons.notablePersonName, NotablePersons.imageURL FROM Battles
      INNER JOIN Battles_has_NotablePersons
      ON Battles.id = Battles_has_NotablePersons.Battles_id
      INNER JOIN NotablePersons
      ON Battles_has_NotablePersons.NotablePersons_id = NotablePersons.id
      INNER JOIN Factions
      ON NotablePersons.Factions_id = Factions.id
      WHERE Battles.id = :id"
    );

    $stmt->bindParam(':id', $battleId);
    $stmt->execute();

    $factions = array();

    while($row = $stmt->fetch()) {
      // Used to see if faction array has already been created
      $doesFactionExist = false;

      foreach ($factions as &$faction) {
        // If faction array has already been created
        // add notable persons to it
        if($row['factionName'] == $faction['factionName']) {
          array_push($faction['notablePersons'],
            array(
              "name" => $row['notablePersonName'],
              "imageURL" => $row['imageURL']
              )
            );
          $doesFactionExist = true;
        }
      }
      // If faction is new, create an array for it
      if($doesFactionExist === false) {
        array_push($factions, array(
          "factionName" => $row['factionName'],
          "notablePersons" => array(
            array(
              "name" => $row['notablePersonName'],
              "imageURL" => $row['imageURL']
              )
            )
          )
        );
      }
    }
  return $factions;
}

  // Insert Battle
  public function insertBattle($name, $date, $location, $lat, $lng, $outcome)
  {
    // Query
    $stmt = $this->db->query("INSERT INTO battles VALUES ( DEFAULT, :name, :date, :location, :latitude, :longitude, :outcome)");

    // Execute query
    $stmt->execute(array(
      ':name'=>$name,
      ':date'=>$date,
      ':location'=>$location,
      ':latitude'=>$lat,
      ':longitude'=>$lng,
      ':outcome'=>$outcome
    ));
  }

  public function getAllDatabaseDetails() {
    $stmt = $this->db->query("SELECT * FROM Battles
        INNER JOIN Battles_has_NotablePersons
        ON Battles.id = Battles_has_NotablePersons.Battles_id
        INNER JOIN NotablePersons
        ON Battles_has_NotablePersons.NotablePersons_id = NotablePersons.id
        INNER JOIN Factions
        ON NotablePersons.Factions_id = Factions.id");

    $stmt->execute();

    $allData = array();

    while($row = $stmt->fetch()) {
      echo "<pre>";
      var_dump($row);

      foreach($allData as $data) {

      }
      array_push($allData, array(
        "battleName" => $row['name'],
        "location" => $row['location'],
        "outcome" => $row['outcome'],
        "battleDescription" => $row['description'],
        "factions" => array(
            "factionName" => $row['factionName'],
            "notablePersons" => array(
              array(
                "name" => $row['notablePersonName'],
                "imageURL" => $row['imageURL']
                )
              )
            )
          )
        );
    }
    return $allData;
  }

  public function getAllData() {
    $stmt = $this->db->query("SELECT * FROM Battles
        INNER JOIN Battles_has_NotablePersons
        ON Battles.id = Battles_has_NotablePersons.Battles_id
        INNER JOIN NotablePersons
        ON Battles_has_NotablePersons.NotablePersons_id = NotablePersons.id
        INNER JOIN Factions
        ON NotablePersons.Factions_id = Factions.id");

    $stmt->execute();

    $allData = array();

    while($row = $stmt->fetch()) {
      $doesBattleExist = false;
      $doesFactionExist = false;

      foreach ($allData as &$data) {
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
          if($row['name'] == $data['battleName']) {
            array_push(
              $data['factions'], array(
                "factionName" => $row['factionName'],
                "notablePersons" => array(
                  array(
                    "name" => $row['notablePersonName'],
                    "imageURL" => $row['imageURL']
                  )
                )
              )
            );
          $doesBattleExist = true;
        }
      }

    //   foreach ($allData as &$data) {
    //     // If faction array has already been created
    //     // add notable persons to it
    //       if($row['factionName'] == $data['factionName']) {
    //       array_push($data['factions'], array (
    //       "notablePersons" =>
    //         array(
    //           "name" => $row['notablePersonName'],
    //           "imageURL" => $row['imageURL']
    //           )
    //
    //         )
    //         );
    //       // $doesBattleExist = true;
    //       $doesFactionExist = true;
    //     }
    //
    // }

      if($doesBattleExist === false) {
        array_push($allData, array(
          "battleName" => $row['name'],
          "location" => $row['location'],
          "outcome" => $row['outcome'],
          "battleDescription" => $row['description'],
          "factions" => array(
              "factionName" => $row['factionName'],
              "notablePersons" => array(
                array(
                  "name" => $row['notablePersonName'],
                  "imageURL" => $row['imageURL']
                  )
                )
              )
            )
          );
        }
    }
    return $allData;
  }

}



// while($row = $stmt->fetch()) {
//   $doesBattleExist = false;
//
//   foreach ($allData as &$data) {
//     if($row['name'] == $data['battleName']) {
//       array_push($data['factions'],
//         array(
//           "factionName" => $row['factionName'],
//           "notablePersons" => array(
//             array(
//               "name" => $row['notablePersonName'],
//               "imageURL" => $row['imageURL']
//               )
//             )
//           )
//         );
//       $doesBattleExist = true;
//     }
//
//     if($doesBattleExist === false) {
//       array_push($allData, array(
//         "battleName" => $row['name'],
//         "factions" => array(
//           array(
//             "factionName" => $row['factionName'],
//             "notablePersons" => array(
//               array(
//                 "name" => $row['notablePersonName'],
//                 "imageURL" => $row['imageURL']
//                 )
//               )
//             )
//           )
//         )
//       );
//     }
//   }
// }
