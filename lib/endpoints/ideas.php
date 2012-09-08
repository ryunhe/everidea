<?php

namespace Endpoints;

/**
 * Support Endpoints:
 *
 * GET /ideas/
 * GET /ideas/<guid>
 *
 * POST /ideas/
 * POST /ideas/<guid>/likes
 *
 * DELETE /ideas/<guid>
 *
 */
class Ideas extends \Endpoint {
	public function fetch($identity) {
		if (!is_null($identity)) {
			### GET /ideas/<guid>
			$idea = new \Idea($identity);
			return new \ApiResponse($idea->attris());
		}

		### GET /ideas/
		$result = \Idea::search(
			\Request::get('q'), 0, \Request::get('count', 10), (\Request::get('q') ? \SortOrder::RELEVANCE : \SortOrder::CREATED));
		
		$ideas = array();
		foreach ($result as $idea)
			$ideas[] = $idea->guid;

		return new \ApiResponse($ideas);		
	}

	public function post($identity) {
		if (\Request::segment($identity) == 'likes') {
			### POST /ideas/<guid>/likes
			$idea = new \Idea($identity);
			if (!$idea->likers->exists($this->visitor->username)) {
				$idea->likers->add($this->visitor->username);
				$idea->save();
			}
			return new \ApiResponse($idea->likers->count());
		}

		### POST /ideas/
		if (!\Request::get('summary'))
			return new \ApiMissParameterResponse();

		$idea = new \Idea(\Request::get('guid'));

		$idea->creator = $this->visitor->username;
		$idea->summary = \Request::get('summary');
		$idea->tags->add(array_filter(explode(' ', \Request::get('tags'))));

		$idea->save();

		return new \ApiResponse($idea->guid);
	}

	public function delete($identity) {
		### DELETE /ideas/<guid>
	}
}

?>