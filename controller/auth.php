<?php

class AuthController extends Controller {
	public function indexAction() {
		// $page = new Page('auth');
		// return new Response($page);

		try {
			$oauth = new OAuth(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
			$requestTokenInfo = $oauth->getRequestToken(REQUEST_TOKEN_URL, $this->getCallbackUrl());
			if ($requestTokenInfo) {
				$_SESSION['requestToken'] = $requestTokenInfo['oauth_token'];
				$_SESSION['requestTokenSecret'] = $requestTokenInfo['oauth_token_secret'];
				return new RedirectResponse($this->getAuthorizationUrl($requestTokenInfo['oauth_token']));
			} else {
				return new FatalErrorResponse('Failed to obtain temporary credentials: ' . $oauth->getLastResponse());
			}
		} catch (OAuthException $e) {
			return new FatalErrorResponse('Error obtaining temporary credentials: ' . $e->getMessage());
		}
	}

	public function callbackAction() {
		if (!Request::get('oauth_verifier')) {
			return new FatalErrorResponse('Content owner did not authorize the temporary credentials');
		}

		try {
			$oauth = new OAuth(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
			$oauth->setToken($_SESSION['requestToken'], $_SESSION['requestTokenSecret']);
			$accessTokenInfo = $oauth->getAccessToken(ACCESS_TOKEN_URL, null, Request::get('oauth_verifier'));
			if ($accessTokenInfo) {
				$_SESSION['accessToken'] = $accessTokenInfo['oauth_token'];
				$_SESSION['accessTokenSecret'] = $accessTokenInfo['oauth_token_secret'];
				$_SESSION['noteStoreUrl'] = $accessTokenInfo['edam_noteStoreUrl'];
				$_SESSION['webApiUrlPrefix'] = $accessTokenInfo['edam_webApiUrlPrefix'];
				$_SESSION['tokenExpires'] = (int)($accessTokenInfo['edam_expires'] / 1000);
				$_SESSION['userId'] = $accessTokenInfo['edam_userId'];
				return new RedirectResponse('/');
			} else {
				return new FatalErrorResponse('Failed to obtain token credentials: ' . $oauth->getLastResponse());
			}
		} catch (OAuthException $e) {
			return new FatalErrorResponse('Error obtaining token credentials: ' . $e->getMessage());
		}  
	}

	protected function getCallbackUrl() {
		$thisUrl = (empty($_SERVER['HTTPS'])) ? "http://" : "https://";
		$thisUrl .= $_SERVER['SERVER_NAME'];
		$thisUrl .= ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? "" : (":".$_SERVER['SERVER_PORT']);
		$thisUrl .= '/auth/callback/';
		return $thisUrl;
	}

	protected function getAuthorizationUrl($requestToken) {
		$url = AUTHORIZATION_URL;
		$url .= '?oauth_token=';
		$url .= urlencode($requestToken);
		return $url;
	}
}

?>