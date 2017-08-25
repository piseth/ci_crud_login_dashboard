<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->model('news_model');
        $this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->helper('url');
		
        // check login for controller
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect('login');
        }
		
    }
 
    public function index()
    {
        $data['news'] = $this->news_model->get_news();
        $data['title'] = 'News archive';
 
        $this->load->view('templates/header', $data);
        $this->load->view('news/index', $data);
        $this->load->view('templates/footer');
    }
 
    public function view($slug = NULL)
    {
        $data['news_item'] = $this->news_model->get_news($slug);
        
        if (empty($data['news_item']))
        {
            show_404();
        }
 
        $data['title'] = $data['news_item']['title'];
 
        $this->load->view('templates/header', $data);
        $this->load->view('news/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function create()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
 
        $title['title'] = 'Create a news item';
 
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');
 
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $title);
            $this->load->view('news/create');
            $this->load->view('templates/footer');
 
        }
        else
        {
			
			$imageName = $_FILES['userfile']['name'];
			$new_name = time()."-".$imageName;
			$config['file_name'] = $new_name;
			$config['upload_path']   = './uploads/'; 
			$config['allowed_types'] = 'jpg|png'; 
			$config['max_size']      = 10000; 
			$config['max_width']     = 10240; 
			$config['max_height']    = 7680;
			$this->load->library('upload', $config);
			
			if ($this->upload->do_upload('userfile')) {
				
				$slug = url_title($this->input->post('title'), 'dash', TRUE);
				$data = array(
					'title' => $this->input->post('title'),
					'slug' => $slug,
					'text' => $this->input->post('text'),
					'news_image' => $new_name
				);
				$this->news_model->set_news($data);
				$this->load->view('templates/header', $title);
				$this->load->view('news/success');
				$this->load->view('templates/footer');
			}
        }
    }
    
    public function edit()
    {
        $id = $this->uri->segment(3);
        
        if (empty($id))
        {
            show_404();
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $headerData['title'] = 'Edit a news item';        
        $headerData['news_item'] = $this->news_model->get_news_by_id($id);
        
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');
 
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $headerData);
            $this->load->view('news/edit', $headerData);
            $this->load->view('templates/footer');
 
        }
        else
        {
			$imageName = $_FILES['userfile']['name'];
			$new_name = time()."-".$imageName;
			$config['file_name'] = $new_name;
			$config['upload_path']   = './uploads/'; 
			$config['allowed_types'] = 'jpg|png'; 
			$config['max_size']      = 10000; 
			$config['max_width']     = 10240; 
			$config['max_height']    = 7680;
			$this->load->library('upload', $config);
			
			$slug = url_title($this->input->post('title'), 'dash', TRUE);
			$data = array();
			if (!$this->upload->do_upload('userfile')) {
				$data = array(
					'id' => $id,
					'title' => $this->input->post('title'),
					'slug' => $slug,
					'text' => $this->input->post('text')
				);
			} else {
				$data = array(
					'id' => $id,
					'title' => $this->input->post('title'),
					'slug' => $slug,
					'text' => $this->input->post('text'),
					'news_image' => $new_name
				);
				$news_item = $this->news_model->get_news_by_id($id);
				unlink("./uploads/".$news_item['news_image']);
			}
			$this->news_model->update_news($data);
			redirect( base_url() . 'index.php/news');
        }
    }
    
    public function delete()
    {
        $id = $this->uri->segment(3);

        if (empty($id))
        {
            show_404();
        }
                
        $news_item = $this->news_model->get_news_by_id($id);
        $this->news_model->delete_news($id);
		unlink("./uploads/".$news_item['news_image']);
        redirect( base_url() . 'index.php/news');        
    }

}