<?php
declare(strict_types=1);
/**
 * @copyright Copyright (c) 2018, Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\TwoFactorItIsReallyMe\Provider;

use OCA\TwoFactorItIsReallyMe\AppInfo\Application;
use OCA\TwoFactorItIsReallyMe\Settings\Personal;
use OCP\Authentication\TwoFactorAuth\IPersonalProviderSettings;
use OCP\Authentication\TwoFactorAuth\IProvider;
use OCP\Authentication\TwoFactorAuth\IProvidesPersonalSettings;
use OCP\IConfig;
use OCP\IInitialStateService;
use OCP\IL10N;
use OCP\IUser;
use OCP\Template;

class ItIsReallyMeProvider implements IProvider, IProvidesPersonalSettings {

	/** @var IL10N */
	private $l10n;
	/** @var IConfig */
	private $config;
	/** @var IInitialStateService */
	private $initialStateService;

	public function __construct(IL10N $l10n,
								IConfig $config,
								IInitialStateService $initialStateService) {
		$this->l10n = $l10n;
		$this->config = $config;
		$this->initialStateService = $initialStateService;
	}

	/**
	 * Get unique identifier of this 2FA provider
	 *
	 * @return string
	 */
	public function getId(): string {
		return Application::APP_ID;
	}

	/**
	 * Get the display name for selecting the 2FA provider
	 *
	 * @return string
	 */
	public function getDisplayName(): string {
		return $this->l10n->t('It is really me');
	}

	/**
	 * Get the description for selecting the 2FA provider
	 *
	 * @return string
	 */
	public function getDescription(): string {
		return $this->l10n->t('Easy and secure validation of user');
	}

	/**
	 * Get the template for rending the 2FA provider view
	 *
	 * @param IUser $user
	 * @return Template
	 */
	public function getTemplate(IUser $user): Template {
		$tmpl = new Template(Application::APP_ID, 'challenge');
		$tmpl->assign('user', $user->getDisplayName());

		return $tmpl;
	}

	public function verifyChallenge(IUser $user, string $challenge): bool {
		return true;
	}

	public function isTwoFactorAuthEnabledForUser(IUser $user): bool {
		return $this->config->getAppValue(Application::APP_ID, $user->getUID() . '_enabled', '0') === '1';
	}

	public function getPersonalSettings(IUser $user): IPersonalProviderSettings {
		return new Personal(
			$this->config->getAppValue(Application::APP_ID, $user->getUID() . '_enabled', '0') === '1',
			$this->initialStateService
		);
	}


}
