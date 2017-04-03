<?php
class Location extends CI_Model {

  public $id;
  public $name;
  public $coordinates;
  public $description;
  public $type_id;
  public $cost;

  //Returns a list of all locations
  public function get_locations()
  {
     $q_string = "SELECT l.name, l.description FROM Location l;";
     $query = $this->db->query($q_string);
     $rows = $query->result('Location');

     return $rows;

  }

  //Returns a location specified by id
  public function get_location($id)
  {
     $q_string = "SELECT * FROM location WHERE id = ?;";
     $query = $this->db->query($q_string,array($id));
     $rows = $query->result('Location');

     return $rows;
  }

  //Returns a location ranking specified by id
  public function get_location_ranking($id)
  {
     $q_string = "SELECT l.name, l.description FROM location l, ranking r
      WHERE id = ? AND r.location_id =?;";
     $query = $this->db->query($q_string,array($id));
     $rows = $query->result('Location');

     return $rows;

  }

  //Returns the images for the location specified
  public function get_location_images($id)
  {

  }

  //How do we want to implement this?
  public function search_locations()
  {

  }

  //Inserts a location into the database
  public function create_location()
  {

  }
}
?>