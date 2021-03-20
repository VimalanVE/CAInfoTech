<?php

class Users_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Login User based on Email & Password
	 * @param $email
	 * @param $password
	 * @return mixed
	 */
	public function login($email, $password)
	{
		$query = $this->db->get_where('users', array('email' => $email, 'password' => md5($password)));
		return $query->row_array();
	}

	/**
	 * Register User with User details
	 * @param $data
	 * @return mixed
	 */
	public function register($data)
	{
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	/**
	 * Get all Users except Admin
	 * @param $admin_email
	 * @return mixed
	 */
	public function get_all_users($admin_email)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email !=', $admin_email);
		$query = $this->db->get();
		return $query->result_array();
	}

}

