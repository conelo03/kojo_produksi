<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('login') != TRUE)
		{
			set_pesan('Silahkan login terlebih dahulu', false);
			redirect('');
		}
		date_default_timezone_set("Asia/Jakarta");
		$this->load->library('upload');
	}

	public function index()
	{
    $data['title']		= 'Data Order';
		$data['order']		= $this->M_order->get_data(null, null, true)->result_array();
		$this->load->view('order/data', $data);
	}

	public function riwayat()
	{
    $data['title']		= 'Riwayat Order';
		$data['order']		= $this->M_order->get_data_riwayat()->result_array();
		$this->load->view('order/data_riwayat', $data);
	}

	public function tambah()
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Data Order';
			$data['produk']		= $this->M_produk->get_data()->result_array();
			$data['pelanggan']		= $this->M_pelanggan->get_data()->result_array();
			$this->load->view('order/tambah', $data);
		} else {
			$data		= $this->input->post(null, true);
			$file = $this->upload_file('design_order');
			$data_user	= [
				'tgl_order'			=> $data['tgl_order'],
				'id_pelanggan'			=> $data['id_pelanggan'],
				'id_produk'			=> $data['id_produk'],
				'jumlah_ukuran_s'			=> $data['jumlah_ukuran_s'],
				'jumlah_ukuran_m'			=> $data['jumlah_ukuran_m'],
				'jumlah_ukuran_l'			=> $data['jumlah_ukuran_l'],
				'jumlah_ukuran_xl'			=> $data['jumlah_ukuran_xl'],
				'jumlah_ukuran_xxl'			=> $data['jumlah_ukuran_xxl'],
				'design_order'		=> $file,
				'catatan'			=> $data['catatan'],
				'id_pegawai' => $this->session->userdata('id_pegawai'),
				'status_order'			=> 0,
				'created_at' => date('Y-m-d H:i:s')
			];
			if ($this->M_order->insert($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('tambah-order');
			} else {
				$order = $this->db->select('id_order')->from('tb_order')->order_by('id_order', 'DESC')->get()->row_array();
				$this->add_detail_order($order['id_order']);
				$this->session->set_flashdata('msg', 'success');
				redirect('order');
			}
		}
	}

	public function edit($id_order)
	{
		$this->validation();
		if (!$this->form_validation->run()) {
			$data['title']		= 'Data Order';
			$data['order']	= $this->M_order->get_by_id($id_order);
			$data['produk']		= $this->M_produk->get_data()->result_array();
			$data['pelanggan']		= $this->M_pelanggan->get_data()->result_array();
			$this->load->view('order/edit', $data);
		} else {
			$data		= $this->input->post(null, true);
			if (empty($_FILES['design_order']['name'])) {
				$file = $data['design_order_old'];
			}else{
				$file = $this->upload_file('design_order');
			}
			$data_user	= [
				'id_order'		=> $id_order,
				'tgl_order'			=> $data['tgl_order'],
				'id_pelanggan'			=> $data['id_pelanggan'],
				'id_produk'			=> $data['id_produk'],
				'jumlah_ukuran_s'			=> $data['jumlah_ukuran_s'],
				'jumlah_ukuran_m'			=> $data['jumlah_ukuran_m'],
				'jumlah_ukuran_l'			=> $data['jumlah_ukuran_l'],
				'jumlah_ukuran_xl'			=> $data['jumlah_ukuran_xl'],
				'jumlah_ukuran_xxl'			=> $data['jumlah_ukuran_xxl'],
				'design_order'		=> $file,
				'catatan'			=> $data['catatan'],
				'status_order'			=> $data['status_order']
			];
			
			if ($this->M_order->update($data_user)) {
				$this->session->set_flashdata('msg', 'error');
				redirect('edit-order/'.$id_order);
			} else {
				$this->session->set_flashdata('msg', 'edit');
				redirect('order');
			}
		}
	}

	private function validation()
	{
		$this->form_validation->set_rules('tgl_order', 'Tgl Order', 'required|trim');
		$this->form_validation->set_rules('id_produk', 'Produk', 'required|trim');
		$this->form_validation->set_rules('id_pelanggan', 'Pelanggan', 'required|trim');
		$this->form_validation->set_rules('jumlah_ukuran_s', 'Jumlah', 'required|trim');
		$this->form_validation->set_rules('jumlah_ukuran_m', 'Jumlah', 'required|trim');
		$this->form_validation->set_rules('jumlah_ukuran_l', 'Jumlah', 'required|trim');
		$this->form_validation->set_rules('jumlah_ukuran_xl', 'Jumlah', 'required|trim');
		$this->form_validation->set_rules('jumlah_ukuran_xxl', 'Jumlah', 'required|trim');
		$this->form_validation->set_rules('catatan', 'Catatan', 'required|trim');
		
	} 

	private function add_detail_order($id_order)
	{
		$this->db->insert('tb_keuangan', ['id_order' => $id_order]);
		$this->db->insert('tb_purchase', ['id_order' => $id_order]);
		$this->db->insert('tb_cutting', ['id_order' => $id_order]);
		$this->db->insert('tb_bordir', ['id_order' => $id_order]);
		$this->db->insert('tb_jahit', ['id_order' => $id_order]);
		$this->db->insert('tb_qc', ['id_order' => $id_order]);
		$this->db->insert('tb_pengiriman', ['id_order' => $id_order]);

		return true;
	}

	public function hapus($id_order)
	{
		$this->M_order->delete($id_order);
		$this->session->set_flashdata('msg', 'hapus');
		redirect('order');
	}

	public function detail($id_order)
	{
    $data['title']		= 'Data Order';
		$data['order']		= $this->M_order->get_by_id($id_order);
		$data['keuangan']		= $this->db->get_where('tb_keuangan', ['id_order' => $id_order])->row_array();
		$data['purchase']		= $this->db->get_where('tb_purchase', ['id_order' => $id_order])->row_array();
		$data['cutting']		= $this->db->get_where('tb_cutting', ['id_order' => $id_order])->row_array();
		$data['bordir']		= $this->db->get_where('tb_bordir', ['id_order' => $id_order])->row_array();
		$data['jahit']		= $this->db->get_where('tb_jahit', ['id_order' => $id_order])->row_array();
		$data['qc']		= $this->db->get_where('tb_qc', ['id_order' => $id_order])->row_array();
		$data['pengiriman']		= $this->db->get_where('tb_pengiriman', ['id_order' => $id_order])->row_array();
		$data['pegawai']		= $this->M_pegawai->get_data()->result_array();
		$this->db->select('*, tb_pegawai_cutting.id_pegawai as id_pegawai')->from('tb_pegawai_cutting')->join('tb_pegawai', 'tb_pegawai.id_pegawai=tb_pegawai_cutting.id_pegawai')->join('tb_order', 'tb_order.id_order=tb_pegawai_cutting.id_order')->where('tb_pegawai_cutting.id_order', $id_order);
		$data['pegawai_cutting']		= $this->db->get()->result_array();
		$this->db->select('*, tb_pegawai_jahit.id_pegawai as id_pegawai')->from('tb_pegawai_jahit')->join('tb_pegawai', 'tb_pegawai.id_pegawai=tb_pegawai_jahit.id_pegawai')->join('tb_order', 'tb_order.id_order=tb_pegawai_jahit.id_order')->where('tb_pegawai_jahit.id_order', $id_order);
		$data['pegawai_jahit']		= $this->db->get()->result_array();
		$this->db->select('*, tb_pegawai_qc.id_pegawai as id_pegawai')->from('tb_pegawai_qc')->join('tb_pegawai', 'tb_pegawai.id_pegawai=tb_pegawai_qc.id_pegawai')->join('tb_order', 'tb_order.id_order=tb_pegawai_qc.id_order')->where('tb_pegawai_qc.id_order', $id_order);
		$data['pegawai_qc']		= $this->db->get()->result_array();
		$data['jumlah_order'] = $data['order']['jumlah_ukuran_s'] + $data['order']['jumlah_ukuran_m'] + $data['order']['jumlah_ukuran_l'] + $data['order']['jumlah_ukuran_xl'] + $data['order']['jumlah_ukuran_xxl'];
		$upah_cutting = $this->db->get_where('tb_upah_cutting', ['id_upah_cutting' => 1])->row_array();
		$upah_jahit = $this->db->get_where('tb_upah_jahit', ['id_upah_jahit' => 1])->row_array();
		$upah_qc = $this->db->get_where('tb_upah_qc', ['id_upah_qc' => 1])->row_array();
		$data['upah_cutting'] = $upah_cutting['upah_cutting'];
		$data['upah_jahit'] = $upah_jahit['upah_jahit'];
		$data['upah_qc'] = $upah_qc['upah_qc'];
		$this->load->view('order/detail', $data);
	}

	public function cetak($id_order)
	{
		$this->load->library('pdf');
    $data['title']		= 'Data Order';
		$data['order']		= $this->M_order->get_by_id($id_order);
		$this->db->select('*, tb_pegawai_cutting.id_pegawai as id_pegawai')->from('tb_pegawai_cutting')->join('tb_pegawai', 'tb_pegawai.id_pegawai=tb_pegawai_cutting.id_pegawai')->join('tb_order', 'tb_order.id_order=tb_pegawai_cutting.id_order')->where('tb_pegawai_cutting.id_order', $id_order);
		$data['pegawai_cutting']		= $this->db->get()->result_array();
		$this->db->select('*, tb_pegawai_jahit.id_pegawai as id_pegawai')->from('tb_pegawai_jahit')->join('tb_pegawai', 'tb_pegawai.id_pegawai=tb_pegawai_jahit.id_pegawai')->join('tb_order', 'tb_order.id_order=tb_pegawai_jahit.id_order')->where('tb_pegawai_jahit.id_order', $id_order);
		$data['pegawai_jahit']		= $this->db->get()->result_array();
		$this->db->select('*, tb_pegawai_qc.id_pegawai as id_pegawai')->from('tb_pegawai_qc')->join('tb_pegawai', 'tb_pegawai.id_pegawai=tb_pegawai_qc.id_pegawai')->join('tb_order', 'tb_order.id_order=tb_pegawai_qc.id_order')->where('tb_pegawai_qc.id_order', $id_order);
		$data['pegawai_qc']		= $this->db->get()->result_array();
		
		$html_content = $this->load->view('order/cetak', $data, true);
		$filename = 'Laporan Order - '.$data['order']['nama_pelanggan'].'('.$data['order']['no_telepon'].')'.' .pdf';

		$this->pdf->loadHtml($html_content);

		$this->pdf->set_paper('a4','potrait');
		
		$this->pdf->render();
		$this->pdf->stream($filename, ['Attachment' => 1]);
		//$this->load->view('order/cetak', $data);
	}

	public function cetak_bom_list($id_order)
	{
		$this->load->library('pdf');
		$data['title']		= 'Data Order';
		$data['order']		= $this->M_order->get_by_id($id_order);
		$data['produk']		= $this->M_produk->get_by_id($data['order']['id_produk']);
		$order		= $this->M_order->get_by_id($id_order);
		$produk		= $this->M_produk->get_by_id($data['order']['id_produk']);

		$data['jml_kain_s'] = $order['jumlah_ukuran_s'] * $produk['pnj_kain_s'];
		$data['jml_kain_m'] = $order['jumlah_ukuran_m'] * $produk['pnj_kain_m'];
		$data['jml_kain_l'] = $order['jumlah_ukuran_l'] * $produk['pnj_kain_l'];
		$data['jml_kain_xl'] = $order['jumlah_ukuran_xl'] * $produk['pnj_kain_xl'];
		$data['jml_kain_xxl'] = $order['jumlah_ukuran_xxl'] * $produk['pnj_kain_xxl'];
		$data['total_harga_kain'] = ($data['jml_kain_s'] * $produk['harga_kain']) + 
		($data['jml_kain_m'] * $produk['harga_kain']) + 
		($data['jml_kain_l'] * $produk['harga_kain']) + 
		($data['jml_kain_xl'] * $produk['harga_kain']) + 
		($data['jml_kain_xxl'] * $produk['harga_kain']);

		$data['jml_kancing_s'] = $order['jumlah_ukuran_s'] * $produk['jml_kancing_s'];
		$data['jml_kancing_m'] = $order['jumlah_ukuran_m'] * $produk['jml_kancing_m'];
		$data['jml_kancing_l'] = $order['jumlah_ukuran_l'] * $produk['jml_kancing_l'];
		$data['jml_kancing_xl'] = $order['jumlah_ukuran_xl'] * $produk['jml_kancing_xl'];
		$data['jml_kancing_xxl'] = $order['jumlah_ukuran_xxl'] * $produk['jml_kancing_xxl'];
		$data['total_harga_kancing'] = ($data['jml_kancing_s'] * $produk['harga_kancing']) + 
		($data['jml_kancing_m'] * $produk['harga_kancing']) + 
		($data['jml_kancing_l'] * $produk['harga_kancing']) + 
		($data['jml_kancing_xl'] * $produk['harga_kancing']) + 
		($data['jml_kancing_xxl'] * $produk['harga_kancing']);

		$data['jml_resleting_s'] = $order['jumlah_ukuran_s'] * $produk['pnj_resleting_s'];
		$data['jml_resleting_m'] = $order['jumlah_ukuran_m'] * $produk['pnj_resleting_m'];
		$data['jml_resleting_l'] = $order['jumlah_ukuran_l'] * $produk['pnj_resleting_l'];
		$data['jml_resleting_xl'] = $order['jumlah_ukuran_xl'] * $produk['pnj_resleting_xl'];
		$data['jml_resleting_xxl'] = $order['jumlah_ukuran_xxl'] * $produk['pnj_resleting_xxl'];
		$data['total_harga_resleting'] = ($data['jml_resleting_s'] * $produk['harga_resleting']) + 
		($data['jml_resleting_m'] * $produk['harga_resleting']) + 
		($data['jml_resleting_l'] * $produk['harga_resleting']) + 
		($data['jml_resleting_xl'] * $produk['harga_resleting']) + 
		($data['jml_resleting_xxl'] * $produk['harga_resleting']);

		$data['jml_prepet_s'] = $order['jumlah_ukuran_s'] * $produk['jml_prepet_s'];
		$data['jml_prepet_m'] = $order['jumlah_ukuran_m'] * $produk['jml_prepet_m'];
		$data['jml_prepet_l'] = $order['jumlah_ukuran_l'] * $produk['jml_prepet_l'];
		$data['jml_prepet_xl'] = $order['jumlah_ukuran_xl'] * $produk['jml_prepet_xl'];
		$data['jml_prepet_xxl'] = $order['jumlah_ukuran_xxl'] * $produk['jml_prepet_xxl'];
		$data['total_harga_prepet'] = ($data['jml_prepet_s'] * $produk['harga_prepet']) + 
		($data['jml_prepet_m'] * $produk['harga_prepet']) + 
		($data['jml_prepet_l'] * $produk['harga_prepet']) + 
		($data['jml_prepet_xl'] * $produk['harga_prepet']) + 
		($data['jml_prepet_xxl'] * $produk['harga_prepet']);

		$data['jml_rib_s'] = $order['jumlah_ukuran_s'] * $produk['pnj_rib_s'];
		$data['jml_rib_m'] = $order['jumlah_ukuran_m'] * $produk['pnj_rib_m'];
		$data['jml_rib_l'] = $order['jumlah_ukuran_l'] * $produk['pnj_rib_l'];
		$data['jml_rib_xl'] = $order['jumlah_ukuran_xl'] * $produk['pnj_rib_xl'];
		$data['jml_rib_xxl'] = $order['jumlah_ukuran_xxl'] * $produk['pnj_rib_xxl'];
		$data['total_harga_rib'] = ($data['jml_rib_s'] * $produk['harga_rib']) + 
		($data['jml_rib_m'] * $produk['harga_rib']) + 
		($data['jml_rib_l'] * $produk['harga_rib']) + 
		($data['jml_rib_xl'] * $produk['harga_rib']) + 
		($data['jml_rib_xxl'] * $produk['harga_rib']);

		$data['total_biaya'] = $data['total_harga_kain'] + $data['total_harga_kancing'] + $data['total_harga_resleting'] + $data['total_harga_prepet'] + $data['total_harga_rib'];
		
		$html_content = $this->load->view('order/cetak_bom_list', $data, true);
		$filename = 'BOM List .pdf';

		$this->pdf->loadHtml($html_content);

		$this->pdf->set_paper('a4','potrait');
		
		$this->pdf->render();
		$this->pdf->stream($filename, ['Attachment' => 1]);
    
		//$this->load->view('order/cetak', $data);
	}

	private function upload_file($file)
	{
		$config['upload_path'] = './assets/upload/'.$file;
		$config['allowed_types'] = 'jpg|png|jpeg|pdf|docx|xlsx|doc|xls';
		$config['max_size'] = 10000;
		$this->upload->initialize($config);
		$this->load->library('upload', $config);

		if(! $this->upload->do_upload($file))
		{
			return '';
		}

		return $this->upload->data('file_name');
	}

	public function download_file($file)
	{
		$file = explode("7C", $file);
		force_download('/assets/upload/file_keuangan/5533-15688-1-PB.pdf', NULL);
	}

	private function update_progres($id_order, $progress)
	{
		$data_user	= [
			'id_order'		=> $id_order,
			'progres'			=> $progress
		];
		
		$this->M_order->update($data_user);

		return true;
	}

	public function update_keuangan($id_order, $id_keuangan)
	{
		$data		= $this->input->post(null, true);
		if (empty($_FILES['file_keuangan']['name'])) {
			$file = $data['file_keuangan_old'];
		}else{
			$file = $this->upload_file('file_keuangan');
		}

		$data_ = [
			'id_pegawai' => $this->session->userdata('id_pegawai'),
			'file_keuangan' => $file,
			'catatan_keuangan' => $data['catatan_keuangan'],
			'status_keuangan' => $data['status_keuangan']
		];

		$this->db->where('id_keuangan', $id_keuangan);
		$this->db->update('tb_keuangan', $data_);

		$this->update_progres($id_order, 'Keuangan - '.status_text($data['status_keuangan']));

		$this->session->set_flashdata('msg', 'update-status');
		redirect('detail-order/'.$id_order);
	}

	public function update_purchase($id_order, $id_purchase)
	{
		$data		= $this->input->post(null, true);
		if (empty($_FILES['file_purchase']['name'])) {
			$file = $data['file_purchase_old'];
		}else{
			$file = $this->upload_file('file_purchase');
		}

		$data_ = [
			'id_pegawai' => $this->session->userdata('id_pegawai'),
			'file_purchase' => $file,
			'catatan_purchase' => $data['catatan_purchase'],
			'status_purchase' => $data['status_purchase']
		];

		$this->db->where('id_purchase', $id_purchase);
		$this->db->update('tb_purchase', $data_);

		$this->update_progres($id_order, 'Purchase - '.status_text($data['status_purchase']));

		$this->session->set_flashdata('msg', 'update-status');
		redirect('detail-order/'.$id_order);
	}

	public function update_cutting($id_order, $id_cutting)
	{
		$data		= $this->input->post(null, true);
		if (empty($_FILES['file_cutting']['name'])) {
			$file = $data['file_cutting_old'];
		}else{
			$file = $this->upload_file('file_cutting');
		}

		$data_ = [
			'id_pegawai' => $this->session->userdata('id_pegawai'),
			'file_cutting' => $file,
			'catatan_cutting' => $data['catatan_cutting'],
			'status_cutting' => $data['status_cutting']
		];

		$this->db->where('id_cutting', $id_cutting);
		$this->db->update('tb_cutting', $data_);

		$this->update_progres($id_order, 'Cutting - '.status_text($data['status_cutting']));

		$this->session->set_flashdata('msg', 'update-status');
		redirect('detail-order/'.$id_order);
	}

	public function update_bordir($id_order, $id_bordir)
	{
		$data		= $this->input->post(null, true);
		if (empty($_FILES['file_bordir']['name'])) {
			$file = $data['file_bordir_old'];
		}else{
			$file = $this->upload_file('file_bordir');
		}

		$data_ = [
			'id_pegawai' => $this->session->userdata('id_pegawai'),
			'file_bordir' => $file,
			'catatan_bordir' => $data['catatan_bordir'],
			'status_bordir' => $data['status_bordir']
		];

		$this->db->where('id_bordir', $id_bordir);
		$this->db->update('tb_bordir', $data_);

		$this->update_progres($id_order, 'Bordir - '.status_text($data['status_bordir']));

		$this->session->set_flashdata('msg', 'update-status');
		redirect('detail-order/'.$id_order);
	}

	public function update_jahit($id_order, $id_jahit)
	{
		$data		= $this->input->post(null, true);
		if (empty($_FILES['file_jahit']['name'])) {
			$file = $data['file_jahit_old'];
		}else{
			$file = $this->upload_file('file_jahit');
		}

		$data_ = [
			'id_pegawai' => $this->session->userdata('id_pegawai'),
			'file_jahit' => $file,
			'catatan_jahit' => $data['catatan_jahit'],
			'status_jahit' => $data['status_jahit']
		];

		$this->db->where('id_jahit', $id_jahit);
		$this->db->update('tb_jahit', $data_);

		$this->update_progres($id_order, 'Jahit - '.status_text($data['status_jahit']));

		$this->session->set_flashdata('msg', 'update-status');
		redirect('detail-order/'.$id_order);
	}

	public function update_qc($id_order, $id_qc)
	{
		$data		= $this->input->post(null, true);
		if (empty($_FILES['file_qc']['name'])) {
			$file = $data['file_qc_old'];
		}else{
			$file = $this->upload_file('file_qc');
		}

		$data_ = [
			'id_pegawai' => $this->session->userdata('id_pegawai'),
			'file_qc' => $file,
			'catatan_qc' => $data['catatan_qc'],
			'status_qc' => $data['status_qc']
		];

		$this->db->where('id_qc', $id_qc);
		$this->db->update('tb_qc', $data_);

		$this->update_progres($id_order, 'QC & Package - '.status_text($data['status_qc']));

		$this->session->set_flashdata('msg', 'update-status');
		redirect('detail-order/'.$id_order);
	}

	public function update_pengiriman($id_order, $id_pengiriman)
	{
		$data		= $this->input->post(null, true);
		if (empty($_FILES['file_pengiriman']['name'])) {
			$file = $data['file_pengiriman_old'];
		}else{
			$file = $this->upload_file('file_pengiriman');
		}

		$data_ = [
			'id_pegawai' => $this->session->userdata('id_pegawai'),
			'file_pengiriman' => $file,
			'catatan_pengiriman' => $data['catatan_pengiriman'],
			'status_pengiriman' => $data['status_pengiriman']
		];

		$this->db->where('id_pengiriman', $id_pengiriman);
		$this->db->update('tb_pengiriman', $data_);

		$this->update_progres($id_order, 'Pengiriman - '.status_text($data['status_pengiriman']));

		if ($data['status_pengiriman'] == 4) {
			$data_user	= [
				'id_order'		=> $id_order,
				'status_order'			=> $data['status_pengiriman']
			];
			
			$this->M_order->update($data_user);
		}
		

		$this->session->set_flashdata('msg', 'update-status');
		redirect('detail-order/'.$id_order);
	}

	public function tambah_pegawai_cutting($id_order)
	{
		$data		= $this->input->post(null, true);
		$data_ = [
			'id_order'	=> $id_order,
			'id_pegawai' => $data['id_pegawai'],
			'jumlah' => $data['jumlah'],
			'harga' => $data['harga'],
			'kasbon' => $data['kasbon'],
			'tgl_cair' => $data['tgl_cair'],
			'pola_potongan' => $data['pola_potongan'],
			'detail_ukuran' => $data['detail_ukuran'],
			'catatan_potongan' => $data['catatan_potongan'],
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->db->insert('tb_pegawai_cutting', $data_);

		$this->session->set_flashdata('msg', 'success');
		redirect('detail-order/'.$id_order);
	}

	public function edit_pegawai_cutting($id_order, $id_pegawai_cutting)
	{
		$data		= $this->input->post(null, true);
		$data_ = [
			'id_order'	=> $id_order,
			'id_pegawai' => $data['id_pegawai'],
			'jumlah' => $data['jumlah'],
			'harga' => $data['harga'],
			'kasbon' => $data['kasbon'],
			'tgl_cair' => $data['tgl_cair'],
			'pola_potongan' => $data['pola_potongan'],
			'detail_ukuran' => $data['detail_ukuran'],
			'catatan_potongan' => $data['catatan_potongan'],
		];

		$this->db->where('id_pegawai_cutting', $id_pegawai_cutting);
		$this->db->update('tb_pegawai_cutting', $data_);

		$this->session->set_flashdata('msg', 'edit');
		redirect('detail-order/'.$id_order);
	}

	public function hapus_pegawai_cutting($id_order, $id_pegawai_cutting)
	{
		$this->db->where('id_pegawai_cutting', $id_pegawai_cutting);
		$this->db->delete('tb_pegawai_cutting');

		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-order/'.$id_order);
	}

	public function tambah_pegawai_jahit($id_order)
	{
		$data		= $this->input->post(null, true);
		$data_ = [
			'id_order'	=> $id_order,
			'id_pegawai' => $data['id_pegawai'],
			'jumlah' => $data['jumlah'],
			'harga' => $data['harga'],
			'kasbon' => $data['kasbon'],
			'tgl_cair' => $data['tgl_cair'],
			'ukuran_pendek' => $data['ukuran_pendek'],
			'ukuran_panjang' => $data['ukuran_panjang'],
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->db->insert('tb_pegawai_jahit', $data_);

		$this->session->set_flashdata('msg', 'success');
		redirect('detail-order/'.$id_order);
	}

	public function edit_pegawai_jahit($id_order, $id_pegawai_jahit)
	{
		$data		= $this->input->post(null, true);
		$data_ = [
			'id_order'	=> $id_order,
			'id_pegawai' => $data['id_pegawai'],
			'jumlah' => $data['jumlah'],
			'harga' => $data['harga'],
			'kasbon' => $data['kasbon'],
			'tgl_cair' => $data['tgl_cair'],
			'ukuran_pendek' => $data['ukuran_pendek'],
			'ukuran_panjang' => $data['ukuran_panjang'],
		];

		$this->db->where('id_pegawai_jahit', $id_pegawai_jahit);
		$this->db->update('tb_pegawai_jahit', $data_);

		$this->session->set_flashdata('msg', 'edit');
		redirect('detail-order/'.$id_order);
	}

	public function hapus_pegawai_jahit($id_order, $id_pegawai_jahit)
	{
		$this->db->where('id_pegawai_jahit', $id_pegawai_jahit);
		$this->db->delete('tb_pegawai_jahit');

		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-order/'.$id_order);
	}

	public function tambah_pegawai_qc($id_order)
	{
		$data		= $this->input->post(null, true);
		$data_ = [
			'id_order'	=> $id_order,
			'id_pegawai' => $data['id_pegawai'],
			'jumlah' => $data['jumlah'],
			'harga' => $data['harga'],
			'kasbon' => $data['kasbon'],
			'tgl_cair' => $data['tgl_cair'],
			'ukuran_pendek' => $data['ukuran_pendek'],
			'ukuran_panjang' => $data['ukuran_panjang'],
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->db->insert('tb_pegawai_qc', $data_);

		$this->session->set_flashdata('msg', 'success');
		redirect('detail-order/'.$id_order);
	}

	public function edit_pegawai_qc($id_order, $id_pegawai_qc)
	{
		$data		= $this->input->post(null, true);
		$data_ = [
			'id_order'	=> $id_order,
			'id_pegawai' => $data['id_pegawai'],
			'jumlah' => $data['jumlah'],
			'harga' => $data['harga'],
			'kasbon' => $data['kasbon'],
			'tgl_cair' => $data['tgl_cair'],
			'ukuran_pendek' => $data['ukuran_pendek'],
			'ukuran_panjang' => $data['ukuran_panjang'],
		];

		$this->db->where('id_pegawai_qc', $id_pegawai_qc);
		$this->db->update('tb_pegawai_qc', $data_);

		$this->session->set_flashdata('msg', 'edit');
		redirect('detail-order/'.$id_order);
	}

	public function hapus_pegawai_qc($id_order, $id_pegawai_qc)
	{
		$this->db->where('id_pegawai_qc', $id_pegawai_qc);
		$this->db->delete('tb_pegawai_qc');

		$this->session->set_flashdata('msg', 'hapus');
		redirect('detail-order/'.$id_order);
	}
}
