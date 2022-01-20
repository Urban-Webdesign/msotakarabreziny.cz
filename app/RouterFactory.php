<?php declare(strict_types = 1);

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;

class RouterFactory
{

	use Nette\StaticClass;

	public static function createRouter(): Nette\Routing\Router
	{
		$router = new RouteList();

		$router->withModule('Admin')
			->addRoute('admin/<presenter>/<action>[/<id>]', 'Homepage:default');

		$router->withModule('Front')
			->addRoute('[<lang=cs (cs)>/]', 'Homepage:default')
			->addRoute('[<lang=cs (cs)>/]slunicka', 'Homepage:slunicka')
			->addRoute('[<lang=cs (cs)>/]rybicky', 'Homepage:rybicky')
			->addRoute('[<lang=cs (cs)>/]veverky', 'Homepage:veverky')
			->addRoute('[<lang=cs (cs)>/]broucci', 'Homepage:broucci')
			->addRoute('[<lang=cs (cs)>/]o-nas', 'Homepage:about')
			->addRoute('[<lang=cs (cs)>/]rozpocet', 'Homepage:budget')
			->addRoute('[<lang=cs (cs)>/]projekty-a-spoluprace', 'Homepage:projects')
			->addRoute('[<lang=cs (cs)>/]akce', 'Homepage:events')
			->addRoute('[<lang=cs (cs)>/]kalendar-akci', 'Homepage:calendar')
			->addRoute('[<lang=cs (cs)>/]provoz-ms', 'Homepage:operation')
			->addRoute('[<lang=cs (cs)>/]krouzky', 'Homepage:clubs')
			->addRoute('[<lang=cs (cs)>/]stravovani', 'Homepage:eating')
			->addRoute('[<lang=cs (cs)>/]jidelnicek', 'Homepage:menu')
			->addRoute('[<lang=cs (cs)>/]zamestnanci', 'Homepage:staff')
			->addRoute('[<lang=cs (cs)>/]fotogalerie', 'Gallery:default')
			->addRoute('[<lang=cs (cs)>/]dokumenty', 'Homepage:documents')
			->addRoute('[<lang=cs (cs)>/]dokumenty/svp', 'Homepage:documentsProgram')
			->addRoute('[<lang=cs (cs)>/]dokumenty/skolni-rad', 'Homepage:documentsRules')
			->addRoute('[<lang=cs (cs)>/]dokumenty/vnitrni-rad-skolni-jidelny', 'Homepage:documentsEateryRules')
			->addRoute('[<lang=cs (cs)>/]dokumenty/doporuceni-rodicum', 'Homepage:documentsAdvice')
			->addRoute('[<lang=cs (cs)>/]gdpr', 'Homepage:gdpr')
			->addRoute('[<lang=cs (cs)>/]kontakty', 'Homepage:contact')
			->addRoute('[<lang=cs (cs)>/]<class>/<slug>', 'Homepage:show')
			->addRoute('[<lang=cs (cs)>/]fotogalerie/<galleryId>', 'Gallery:show')
			->addRoute('[<lang=cs (cs)>/]<presenter>/<action>', 'Error:404');

		return $router;
	}

}
