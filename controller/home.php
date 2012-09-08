<?php

class HomeController extends Controller {
	public function indexAction() {
		$page = new Page('home:withNavbar');
		$page->visitor = new Visitor();
		$page->query = Request::get('q');
		$page->ideas = Idea::search(Request::get('q'));
		return new Response($page);
	}
}

?>