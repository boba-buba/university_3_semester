<?php

class EditController extends BaseController
{
	private $model;

	public function __construct(IView $view, IRouter $router, INotesModel $model)
	{
		parent::__construct($view, $router);
		$this->model = $model;
	}

	public function getAction($get)
	{
		if (isset($get['id']) && strlen($get['id']) > 0) {
			$id = (int)$get['id'];
			$this->view->setArg('id', $id);
			$this->view->setArg('text', $this->model->get($id));
		}
		$this->view->render();
	}

	public function postEditAction($post, $get)
	{
		if (!empty($post['text'])) {
			$id = (isset($get['id']) && strlen($get['id']) > 0) ? (int)$get['id'] : null;
			if ($id === null)
				$this->model->add($post['text']);
			else
				$this->model->set($id, $post['text']);
		}
		$this->redirect('Show');
	}
}
