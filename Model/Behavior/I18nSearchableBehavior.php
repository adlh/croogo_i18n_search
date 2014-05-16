<?php

App::uses('ModelBehavior', 'Model');

class I18nSearchableBehavior extends ModelBehavior {

	var $mapMethods = array('/\b_findTranslated\b/' => '_findTranslated');

	function setup(Model $model, $config = array()) {
		$model->findMethods['translated'] = true;
	}

	function _findTranslated(Model $model, $functionCall, $state, $query, $results = array()) {
		if ($state == 'after') {
			return $results;
		}

		// TODO: maybe check first that model is a Node
		$node = $model;

		$q = isset($query['q']) ? '%' . $query['q'] . '%' : null;
		$roleId = isset($query['roleId']) ? $query['roleId'] : null;
		$typeAlias = isset($query['typeAlias']) ? $query['typeAlias'] : null;
		$visibilityRolesField = $node->escapeField('visibility_roles');

		$nodeOrConditions = array(
			$node->escapeField('title') . ' LIKE' => $q,
			$node->escapeField('excerpt') . ' LIKE' => $q,
			$node->escapeField('body') . ' LIKE' => $q,
			$node->escapeField('terms') . ' LIKE' => $q,
		);

		// If a non empty array with node ids exist, add it to conditions
		if (isset($query['node_ids'])) {
			$node_ids = (array)$query['node_ids'];
			// filter any non integer values
			$node_ids = array_filter($node_ids, function($i) {
				return is_int($i); });
			// Check again if array is not empty after filtering it
			if (!empty($node_ids)) {
				$nodeOrConditions[$node->escapeField('id')] = $node_ids;
			}
		}

		$defaults = array(
			'order' => $node->escapeField('created') . ' DESC',
			'limit' => Configure::read('Reading.nodes_per_page'),
			'conditions' => array(
				$node->escapeField('status') => $node->status(),
				'AND' => array(
					array(
						'OR' => $nodeOrConditions,
					),
					array(
						'OR' => array(
							$visibilityRolesField => '',
							$visibilityRolesField . ' LIKE' => '%"' . $roleId . '"%',
						),
					),
				),
			),
			'contain' => array(
				'Meta',
				'Taxonomy' => array(
					'Term',
					'Vocabulary',
				),
				'User',
			),
		);
		if (isset($typeAlias)) {
			$defaults['conditions'][$node->escapeField('type')] = $typeAlias;
		}
		$query = Hash::merge($query, $defaults);

		return $query;
	}

}
