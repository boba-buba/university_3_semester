<?php

class ShowController extends BaseController
{
	private $model;

	public function __construct(IView $view, IRouter $router, INotesModel $model)
	{
		parent::__construct($view, $router);
		$this->model = $model;
	}

	public function getAction()
	{
		$this->view->setArg('notes', $this->model->getAll());
		$this->view->render();
	}

	public function postDeleteAction($post, $get)
	{
		if (isset($get['id'])) {
			$this->model->delete($get['id']);
		}
		$this->redirect();
	}
}
