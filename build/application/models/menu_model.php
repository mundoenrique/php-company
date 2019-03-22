<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Menu_Model extends CI_Model {



	public function __construct()

	{

		parent::__construct();



	}



/*

function login_user

This function check is user is valid

*/

	function login_user($username, $password)

	{

	   $this -> db -> select('users.id_user, users.login, users.password, users.nickname, users_system.id_system,roles.id_rol,roles.hierarchy');

	   $this -> db -> from('users');

	   $this->db->join('users_system', 'users_system.id_user = users.id_user');

	   $this->db->join('users_roles', 'users_roles.id_user = users.id_user');

	   $this->db->join('roles', 'roles.id_rol = users_roles.id_rol');

	   $this->db->where('users.status',1); //CHECK IS USER STATUS IS ENABLE

	   $this->db->where('users.login', $username);

	   $this->db->where('users.password', MD5($password));

	   $this->db->limit(1);

	   $query = $this -> db -> get();

		   if($query -> num_rows() == 1)

			   {

			     return $query->result();

			   }

		   else

			   {

			     return false;

			   }

	}

}



/* End of file  */

/* Location: ./application/models/ */
