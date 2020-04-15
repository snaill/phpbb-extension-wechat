<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace phpbb\auth\provider\oauth\service;

/**
 * Wechat OAuth service
 * 
 * 1. add /config/default/container/services_auth.yml
 * 
 *     auth.provider.oauth.service.wechat:
 *       class: phpbb\auth\provider\oauth\service\wechat
 *       arguments:
 *           - '@config'
 *           - '@request'
 *       tags:
 *           - { name: auth.provider.oauth.service } 
 * 2. add WechatService.php to /phpbb/auth/provider/oauth/
 * 3. add wechat.php to /phpbb/auth/provider/oauth/service/
 * 4. add /language/en/common.php
 * 		'AUTH_PROVIDER_OAUTH_SERVICE_WECHAT'					=> 'Wechat',
 * 5. add /language/zh_cmn_hans/common.php
 * 		'AUTH_PROVIDER_OAUTH_SERVICE_WECHAT'					=> '公众号登录',
 */
class wechat extends base
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\config\config				$config		Config object
	 * @param \phpbb\request\request_interface	$request	Request object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\request\request_interface $request)
	{
		$this->config	= $config;
		$this->request	= $request;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_auth_scope()
	{
		return [
			'snsapi_base'
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_service_credentials()
	{
		return [
			'key'		=> $this->config['auth_oauth_wechat_key'],
			'secret'	=> $this->config['auth_oauth_wechat_secret'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function perform_auth_login()
	{
		if (!($this->service_provider instanceof \phpbb\auth\provider\oauth\WechatService))
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_INVALID_SERVICE_TYPE');
		}

		try
		{
			// This was a callback request, get the token
			$token = $this->service_provider->requestAccessToken($this->request->variable('code', ''));
		}
		catch (\OAuth\Common\Http\Exception\TokenResponseException $e)
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_REQUEST');
		}

		// Return the unique identifier
		return $token->getExtraParams()['openid'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function perform_token_auth()
	{
		if (!($this->service_provider instanceof \phpbb\auth\provider\oauth\WechatService))
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_INVALID_SERVICE_TYPE');
		}

		try
		{
			// Send a request with it
			$token = $this->service_provider->storage->retrieveAccessToken('wechat');
		}
		catch (\OAuth\Common\Storage\Exception\TokenNotFoundException $e)
		{
			throw new exception('AUTH_PROVIDER_OAUTH_ERROR_REQUEST');
		}

		// Return the unique identifier
		return $token->getExtraParams()['openid'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_external_service_class()
	{
		return 'phpbb\auth\provider\oauth\WechatService';
	}
}
