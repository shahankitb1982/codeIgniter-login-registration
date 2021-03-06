<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}


	public function Authentication()
	{
		$notif = array();

		$query = $this->checkLogin($this->input->post('email'), $this->input->post('password'));

		if ($query->num_rows() == 1) {
			$row = $query->row();
			if ($row->is_active != 1) {
				$notif['message'] = 'Your account is disabled !';
				$notif['type'] = 'warning';
			} else {
				$sess_data = array(
					'users_id' => $row->users_id,
					'first_name' => $row->first_name,
					'last_name' => $row->last_name,
					'email' => $row->email
				);
				$this->session->set_userdata('logged_in', $sess_data);
				$this->update_last_login($row->users_id);
			}
		} else {
			$notif['message'] = 'Username or password incorrect !';
			$notif['type'] = 'danger';
		}

		return $notif;
	}

	public function checkLogin($param_email, $param_password)
	{
		$email = $param_email;
		$password = Utils::hash('sha1', $param_password, AUTH_SALT);

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('password', $password);
		$this->db->limit(1);

		$query = $this->db->get();
		return $query;
	}


	public function update_last_login($users_id)
	{
		$sql = "UPDATE users SET last_login = NOW() WHERE users_id=" . $this->db->escape($users_id);
		$this->db->query($sql);
	}


	public function register()
	{
		$notif = array();

		$result = $this->check_email($this->input->post('email'));

		if (count($result) > 0) {
			$notif['message'] = 'Email already exists.';
			$notif['type'] = 'warning';
		} else {

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'password' => Utils::hash('sha1', $this->input->post('password'), AUTH_SALT),
				'is_active' => 1
			);

			$this->db->insert('users', $data);
			$users_id = $this->db->insert_id();
			if ($this->db->affected_rows() > 0) {
				$notif['message'] = 'Register successfully';
				$notif['type'] = 'success';
				unset($_POST);
			} else {
				$notif['message'] = 'Something wrong !';
				$notif['type'] = 'danger';
			}
		}
		return $notif;
	}

	public function check_email($email)
	{
		$sql = "SELECT * FROM users WHERE email = " . $this->db->escape($email);
		$res = $this->db->query($sql);
		if ($res->num_rows() > 0) {
			$row = $res->row();
			return $row;
		}
		return null;
	}

}
