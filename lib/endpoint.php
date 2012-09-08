<?php

class Endpoint {
	public function call($identity) {
		if (!Visitor::isAuth())
			return new ApiAccessDeniedResponse();

		$this->visitor = new Visitor();

		switch (Request::method()) {
			case 'POST' :
				return $this->post($identity);
				break;
			case 'GET' :
				return $this->fetch($identity);
				break;
			case 'DELETE' :
				return $this->delete($identity);
				break;
		}

		return new ApiResponse('Invalid request..', ApiResponse::STATUS_NOT_FOUND);
	}
}

?>