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

		$query = $node->buildQueryOrderLimit($query);
		$query = $node->buildQueryTypeAlias($query);
		$query = $node->buildQueryStatus($query);
		$query = $node->buildQueryRole($query);
		$query = $node->buildQueryContain($query);

		// build query like conditions with node-id list from i18n matches

		$term = isset($query['q']) ? $query['q'] : null;
		$term = empty($term) ? '%' : '%' . $term . '%';

		$nodeLikeConditions = $node->nodeLikeConditions($term);

		// If a non empty array with node ids exist, add it to conditions
		if (isset($query['node_ids'])) {
			$node_ids = (array)$query['node_ids'];
			// filter any non integer values
			$node_ids = array_filter($node_ids, function($i) {
				return is_int($i); });
			// Check again if array is not empty after filtering it
			if (!empty($node_ids)) {
				$nodeLikeConditions[$node->escapeField('id')] = $node_ids;
			}
		}

		$q = array(
			'conditions' => array(
				'AND' => array(
					array(
						'OR' => $nodeLikeConditions,
					),
				),
			),
		);

		return $node->_mergeQueries($q, $query);
	}

}
