<?php

namespace App\Controllers\Index;

use TheMoiza\MvcCore\Core\View;

use TheMoiza\MvcCore\Core\Response;

use TheMoiza\Crud\Grid\Grid;

use TheMoiza\Crud\Connection\Pgsql;

class IndexController{

	public $connection;

	public $gmt = 'UTC-03:00';

	public $controller = '/';

	public function __construct(){

		$Pgsql = new Pgsql;
		$this->connection = $Pgsql->connect(CONNECTION);
	}

	public function index(){

		Response::send(View::load('layout', 'index', []));
	}

	public function grid(){

		$GridConfig = new GridConfig;

		$grid = new Grid($GridConfig, $this->connection->pdo);
		$grid->setGmt($this->gmt);

		Response::json([
			'titles' => $grid->getTitles(),
			'thead' => $grid->makeThead(),
			'controllers' => $grid->getControllers(),
			'filters' => $grid->getFilters()
		], 200);
	}

	public function list(){

		$GridConfig = new GridConfig;

		$grid = new Grid($GridConfig, $this->connection->pdo);
		$grid->setGmt($this->gmt);
		$grid->get();

		Response::json($grid->getArray(), 200);
	}

	public function export(){

		$GridConfig = new GridConfig;

		$grid = new Grid($GridConfig, $this->connection);
		$grid->setGmt($this->gmt);
		$grid->get();

		if(isset($_GET['csv'])){

			$grid->getCsv();
		}

		$grid->getPdf();
	}

	public function form(){

		$FormConfig = new FormConfig;

		$form = new Form($FormConfig, $this->connection);

		if($form->exists()){

			Response::json([
				'titles' => $form->getTitles(),
				'controllers' => $form->getControllers(),
				'fields' => $form->createFields()
			]);

		}else{

			Response::json([
				'status' => false,
				'message' => 'Not fonded'
			], 404);
		}
	}

	public function saveFilters(){


		$GridConfig = new GridConfig;

		$grid = new Grid($GridConfig, $this->connection);

		$saveFilters = $_POST['saveFilters'] ?? '';

		Response::json($grid->saveFilters($this->use_id, $GridConfig->controllers['controller'], $saveFilters), 200);
	}

	public function save(){

		$FormConfig = new FormConfig;

		$form = new Form($FormConfig, $this->connection);
		Response::json($form->save(), 200);
	}

	public function remove(){

		$FormConfig = new FormConfig;

		$form = new Form($FormConfig, $this->connection);
		Response::json($form->remove(), 200);
	}
}