<?php

App::uses('I18nSearchAppController', 'I18nSearch.Controller');
App::uses('Croogo', 'Lib');
//App::uses('NodesController');

class SearchController extends I18nSearchAppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Search';

/**
 * Components
 *
 * @var array
 * @access public
 */
	public $components = array(
		'Search.Prg' => array(
			'presetForm' => array(
				'paramType' => 'querystring',
			),
			'commonProcess' => array(
				'paramType' => 'querystring',
				'filterEmpty' => true,
			),
		),
	);


/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
	public $uses = array('Nodes.Node');


/**
 * Show
 *
 * @return void
 * @access public
 */
	public function show() {
		$this->Prg->commonProcess();

		$this->paginate = array(
			'published',
			'roleId' => $this->Croogo->roleId(),
		);

		if (!isset($this->request->query['q'])) {
			$nodes = array();
			$q = null;
			$this->set(compact('q', 'nodes'));
		} else {
			$q = $this->request->query['q'];

			// Setup paginate
			$this->paginate['q'] = $q;
			$this->paginate['limit'] = Configure::read(
				'Reading.nodes_per_page');
			$this->paginate['contain'] = array(
				'Meta',
				'User',
			);

			$t_model = $this->Node->translateModel();
			$t_alias = $t_model->alias;
			$n_alias = $this->Node->alias;

			$translations = $t_model->find('all', array(
				'fields' => 'DISTINCT ' . $t_alias . '.foreign_key',
				'conditions' => array(
					$t_alias . '.model' => $n_alias,
					'AND' => array(
						$t_alias . '.content LIKE' => '%' . $q . '%',
					),
				),
			));
			$node_ids = array();
			foreach($translations as $res) {
				$node_ids[] = intval($res[$t_alias]['foreign_key']);
			}

			// Now find all published, visible nodes which match the query or
			// are in the list found on i18n
			$this->paginate['node_ids'] = $node_ids;

			// Uncomment and modify the lines below if the results should be
			// narrowed to just some types of nodes (e.g. page)
			//$node_types = array('page');
			//$this->paginate['typeAlias'] = $node_types;

			$criteria = $this->Node->parseCriteria($this->Prg->parsedParams());
			$nodes = $this->paginate($criteria);

			$this->set(compact('q', 'nodes'));
		}
	}
}
