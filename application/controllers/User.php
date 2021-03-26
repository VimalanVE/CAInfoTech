<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('users_model');
		$this->load->library('form_validation');
		$this->load->library('encryption');
		$this->load->library('session');
	}

	public function index()
	{
		//restrict users to go back to login if session has been set
		if ($this->session->userdata('user')) {
			redirect('home');
		} else {
			$this->load->view('login_page');
		}
	}

	public function login()
	{
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run()) {
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$data = $this->users_model->login($email, $password);
			if ($data) {
				$this->session->set_userdata('user', $data);
				redirect('home');
			}
		}
		header('location:' . base_url() . $this->index());
		$this->session->set_flashdata('error', 'Invalid login. User not found');
	}

	public function home()
	{
		//restrict users to go to home if not logged in
		if ($this->session->userdata('user')) {
			$user = $this->session->userdata('user');
			$get_user_email = $user['email'];
			if ($get_user_email == ADMIN_EMAIL) {
				//If Logged-In user is Admin, load admin map view page
				$filter_type = $this->input->get('filter_type');
				$radius = $this->input->get('radius');
				if (isset($filter_type) && isset($radius) && !empty($radius)) {
					$manual_location = new \stdClass();
					$manual_location->latitude = $this->input->get('manual_latitude');
					$manual_location->longitude = $this->input->get('manual_longitude');
					//If Filter is passed in query string, do filter using this function
					$data['get_all_users'] = $this->usersWithinRadius($filter_type, $radius,$manual_location);
				} else {
					//Get all users except Admin user
					$data['get_all_users'] = $this->users_model->get_all_users($get_user_email);
				}
				$data['location'] = null;
				if (count($data['get_all_users']) > 0) {
					foreach ($data['get_all_users'] as $key => $user) {
						$locations[$key]['name'] = $user['name'];
						$locations[$key]['address'] = json_decode($user['location_coordinates']);
					}
					$data['location'] = json_encode($locations);
				}
				$this->load->view('maps/admin_map', $data);
			} else {
				$data['user_location'] = json_decode($user['location_coordinates']);
				$this->load->view('maps/user_map', $data);
			}
		} else {
			redirect('/');
		}

	}

	public function register()
	{
		$json = file_get_contents('https://geolocation-db.com/json');
		$data['location'] = json_decode($json);
		$this->load->view('registration_page', $data);
	}

	public function register_user()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('phone_no', 'Phone No', 'required');
		$this->form_validation->set_rules('latitude', 'Latitude', 'required');
		$this->form_validation->set_rules('longitude', 'Longitude', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		if ($this->form_validation->run()) {
			$location = array(
				'address' => $this->input->post('address'),
				'latitude' => $this->input->post('latitude'),
				'longitude' => $this->input->post('longitude'),
			);
			$encrypted_password = md5($this->input->post('password'));
			$data = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'password' => $encrypted_password,
				'phone' => $this->input->post('phone_no'),
				'location_coordinates' => json_encode($location)
			);
			$id = $this->users_model->register($data);
			if($id){
				$json = file_get_contents('https://geolocation-db.com/json');
				$data['location'] = json_decode($json);
				$this->session->set_flashdata('success', 'Successfully Registered ! Login to proceed further.');
				$this->load->view('registration_page', $data);
			}
		} else {
			header('location:' . base_url() . 'index.php/user/register');
			$this->session->set_flashdata('error', 'Validation Error');
		}

	}

	public function logout()
	{
		$this->session->unset_userdata('user');
		redirect('/');
	}

	public function usersWithinRadius($filter_type, $radius,$manual_location=null)
	{
		if(isset($manual_location) && empty($manual_location)){
			//Get logged-in user current location
			$json = file_get_contents('https://geolocation-db.com/json');
			$location = json_decode($json);
		}else{
			$location = $manual_location;
		}
		if ($filter_type == 'miles') {
			$filter_by_key = 3959;
		} else {
			$filter_by_key = 6371;
		}
		//Haversine Formula concept to get nearby user from certain radius of current location
		$sql = "SELECT *, ( $filter_by_key * acos( cos( radians($location->latitude) ) * cos( radians( JSON_EXTRACT(location_coordinates, '$.latitude') ) ) 
		* cos( radians( JSON_EXTRACT(location_coordinates, '$.longitude') ) - radians($location->longitude) ) + sin( radians($location->latitude) ) * sin(radians(JSON_EXTRACT(location_coordinates, '$.latitude') )) ) ) AS distance 
		FROM users 
		WHERE email != 'admin@cainfotech.com'
		HAVING distance < $radius 
		ORDER BY distance 
		LIMIT 0 , 20";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
