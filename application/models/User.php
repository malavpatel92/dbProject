<?php
class User extends CI_Model {

  public $id;
  public $name;
  public $email;
  public $password;
  public $birthday;

  //Returns a list of all users
  public function get_users()
  {
    $q_string = "SELECT * FROM users;"; //Change this to get all but password
    $query = $this->db->query($q_string);
    $rows = $query->result('User'); //Returns results as array of user objects

    return $rows;
  }

  //Returns a user specified by id
  public function get_user($id)
  {
    $q_string = "SELECT * FROM users WHERE id=?;"; //Change this to get all but password
    $query = $this->db->query($q_string, array($id));
    $rows = $query->result('User'); //Returns results as array of user objects

    return $rows;
  }

  //Returns the locations that the user with $id wants to visit
  public function get_user_locations($id)
  {
    $q_string = "CALL Locations_By_User(?);"; //Change this to get all but password
    $query = $this->db->query($q_string, array($id));
    $rows = $query->result(); //Returns results as array of user objects
    $query->next_result();

    return $rows;
  }

  //Returns the locations that the user has ranked
  public function get_user_rankings($id)
  {
    $q_string = "CALL Rankings_By_User(?);"; //Change this to get all but password
    $query = $this->db->query($q_string, array($id));
    $rows = $query->result(); //Returns results as array of user objects
    $query->next_result();

    return $rows;
  }

  //Returns the locations that the user has ranked up
  public function get_user_ranked_up($id)
  {
    $q_string = "CALL Rankings_By_User_Up(?);"; //Change this to get all but password
    $query = $this->db->query($q_string, array($id));
    $rows = $query->result(); //Returns results as array of user objects
    $query->next_result();

    //We just want an array of ids
    $values = [];
    foreach($rows as $row)
    {
      $values[] = $row->location_id;
    }

    return $values;
  }

  //Returns the locations that the user has ranked up
  public function get_user_ranked_down($id)
  {
    $q_string = "CALL Rankings_By_User_Down(?);"; //Change this to get all but password
    $query = $this->db->query($q_string, array($id));
    $rows = $query->result(); //Returns results as array of user objects
    $query->next_result();

    //We just want an array of ids
    $values = [];
    foreach($rows as $row)
    {
      $values[] = $row->location_id;
    }

    return $values;
  }

  //Add a location to a user's list
  public function new_Order($user_id)
  {
    $q_string = "SELECT MAX(ul.order) as max
      FROM user_locations ul
      WHERE ul.user_id = ?
      GROUP BY ul.user_id;"; //Change this to get all but password
    $query = $this->db->query($q_string, array($user_id));

    $rows = $query->result(); //Returns results

    if(isset($rows[0]))
      return ((int) $rows[0]->max) + 1 ; //User has a order. Return this + 1
    else
      return 1;
  }

  //Add a location to a user's list
  public function add_user_location($user_id, $location_id, $order)
  {
    $q_string = "INSERT INTO `locBucket`.`user_locations` (`user_id`, `location_id`, `order`) VALUES (?, ?, ?);"; //Change this to get all but password
    $query = $this->db->query($q_string, array($user_id, $location_id, $order));
  }

  //Returns true if the user/password are correct
  public function login($email, $password)
  {
    $q_string = "SELECT * FROM users WHERE email=? LIMIT 1;";
    $query = $this->db->query($q_string, array($email));

    $rows = $query->result('User'); //Returns results as array of user objects

    if(isset($rows[0]))
      $user = $rows[0]; //User exists

    if(isset($user) && password_verify($password, $user->password))
    {
      //Successful login, setup session and redirect to dashboard
      $array = ['USER_ID' => $user->id, 'USER_NAME' => $user->name,
                'USER_EMAIL' => $user->email, 'USER_BIRTHDAY' => $user->birthday,
                'USER_ROLE' => $user->role_id];
      $this->session->set_userdata($array);
      return true;
    }

    return false;
  }

  //Inserts a user into the database
  public function create_user($userObject)
  {
    //Ensure email isn't taken yet
    $q_string = "SELECT * FROM users WHERE email=? LIMIT 1;";
    $query = $this->db->query($q_string, array($userObject->email));

    $rows = $query->result('User'); //Returns results as array of user objects

    if(isset($rows[0]))
      return false; //Email exists in database

    $q_string = "INSERT INTO `locBucket`.`users` (`name`, `email`, `password`, `role_id`) VALUES (?, ?, ?, ?);";
    $values = [$userObject->name, $userObject->email, password_hash($userObject->password, PASSWORD_DEFAULT), 1];
    $query = $this->db->query($q_string, $values); //Insert

    return true;
  }
}
?>
