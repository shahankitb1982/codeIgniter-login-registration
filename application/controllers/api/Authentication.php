<?php

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * Class Process
 */
class Authentication extends REST_Controller
{

	/**
	 * Authentication constructor.
	 */

	public function __construct()
	{

		parent::__construct();

		$this->load->database();
		$this->load->model('auth_model');
		$this->auth_model = new Auth_model;

	}

	/**
	 * API for login.
	 *
	 */
	public function login_post()
	{

		$email = $this->post('email');
		$password = $this->post('password');

		// Validate the post data
		if (!empty($email) && !empty($password)) {

			$query = $this->auth_model->checkLogin($email, $password);

			if ($query->num_rows() == 1) {
				$row = $query->row();
				if ($row->is_active != 1) {
					$this->response("User account is disabled.", REST_Controller::HTTP_BAD_REQUEST);
				} else {
					$user_data = array(
						'users_id' => $row->users_id,
						'first_name' => $row->first_name,
						'last_name' => $row->last_name,
						'email' => $row->email
					);
					$this->session->set_userdata('logged_in', $user_data);
					$this->auth_model->update_last_login($row->users_id);

					$this->response(array(
						'status' => TRUE,
						'message' => 'User login successful.',
						'data' => $user_data
					), REST_Controller::HTTP_OK);
				}
			} else {
				$this->response("Wrong Email or password.", REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response("Email and password required.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * API for login.
	 *
	 */
	public function registration_post()
	{
		$first_name = $this->post('first_name');
		$last_name = $this->post('last_name');
		$email = $this->post('email');
		$password = $this->post('password');

		// Validate the post data
		if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password)) {

			$result = $this->auth_model->check_email($email);

			if (count($result) > 0) {
				$this->response("Email already exists.", REST_Controller::HTTP_BAD_REQUEST);
			}
			else {
				$data = array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'email' => $email,
					'password' => Utils::hash('sha1', $password, AUTH_SALT),
					'is_active' => 1
				);

				$this->db->insert('users', $data);
				$users_id = $this->db->insert_id();
				if ($this->db->affected_rows() > 0) {
					$this->response(array(
						'status' => TRUE,
						'message' => 'User register successfully.',
						'data' => array("user_id" => $users_id)
					), REST_Controller::HTTP_OK);
				} else {
					$this->response("Something wrong.", REST_Controller::HTTP_BAD_REQUEST);
				}
			}
		} else {
			$this->response("First name, Last name, Email and password required.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
