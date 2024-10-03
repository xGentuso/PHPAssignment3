<?php 

require_once('database_oo.php');
require_once('technician.php');

class TechnicianDB {
  private $db;

  // constructor
  public function __construct() {
    $this->db = Database::getDB();
  }

  public function getAllTechnicians() {
    $query = 'SELECT * FROM technicians ORDER BY lastName, firstName';
    $statement = $this->db->prepare($query);
    $statement->execute();
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    $technicians = [];
    foreach($rows as $row) {
      $technicians[] = new Technician(
        $row['techID'],
        $row['firstName'],
        $row['lastName'],
        $row['email'],
        $row['phone'],
        $row['password']
      );
    }

    return $technicians;
  }

  public function addTechnician(Technician $technician) {
    try {
      $query = 'INSERT INTO technicians (firstName, lastName, email, phone, password)
                VALUES (:first_name, :last_name, :email, :phone, :password)';
      $statement = $this->db->prepare($query);
      $statement->bindValue(':first_name', $technician->getFirstName(), PDO::PARAM_STR);
      $statement->bindValue(':last_name', $technician->getLastName(), PDO::PARAM_STR);
      $statement->bindValue(':email', $technician->getEmail(), PDO::PARAM_STR);
      $statement->bindValue(':phone', $technician->getPhone(), PDO::PARAM_STR);
      $statement->bindValue(':password', $technician->getPassword(), PDO::PARAM_STR);
      $statement->execute();
      $statement->closeCursor;
    } catch (PDOException $e) {
      error_log("Database Error [addTechnician]: " . $e->getMessage());

      throw new Exception("Unable to add technician.");
    }
  }

  public function deleteTechnician($techID) {
    try {
      $query = 'DELETE FROM technicians WHERE techID = :techID';
      $statement = $this->db->prepare($query);
      $statement->bindValue(':techID', $techID, PDO::PARAM_INT);
      $statement->execute();
      $statement->closeCursor();
    } catch (PDOException $e) {
      
      error_log("Database Error [deleteTechnician]: ") . $e->getMessage();

      throw new Exception("Unable to delete technician.");
    }
  }

  public function getAllTechnicianByID($techID) {
    try {
      $query = 'SELECT * FROM technicians WHERE techID = :techID';
      $statement = $this->db->prepare($query);
      $statement->bindValue(':techID', $techID, PDO::PARAM_INT);
      $statement->execute();
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      $statement->closeCursor();

      if ($row) {
        return new Technician(
          $row['techID'],
          $row['firstName'],
          $row['lastName'],
          $row['email'],
          $row['phone'],
          $row['password']
        );
      } else {
        return null;
      }
    } catch (PDOException $e) {

      error_log("Database Error [getTechnicianByID]: " . $e->getMessage());

      throw new Exception("Unabale to fetch technician.");
    }
  }

  public function updateTechnicians(Technician $technician) {
    try {
      $query = 'UPDATE technicians
                SET firstName = :first_name, lastName = :last_name, email = :email, phone = :phone, password = :password
                WHERE techID = :techID';
      $statement = $this->db->prepare($query);
      $statement->bindValue(':first_name', $technician->getFirstName(), PDO::PARAM_STR);
      $statement->bindValue(':last_name', $technician->getLastName(), PDO::PARAM_STR);
      $statement->bindValue(':email', $technician->getEmail(), PDO::PARAM_STR);
      $statement->bindValue(':phone', $technician->getPhone(), PDO::PARAM_STR);
      $statement->bindValue(':password', $technician->getPassword(), PDO::PARAM_STR);
      $statement->execute();
      $statement->closeCursor();
    } catch (PDOException $e) {

      error_log("Database Error [updateTechnician]: " . $e->getMessage());

      throw new Exception("Unabale to update technician.");
    }
  }
}

?>