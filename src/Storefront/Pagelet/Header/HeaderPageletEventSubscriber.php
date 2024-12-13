<?php
declare(strict_types=1);
namespace Kiwee\KiweeDemo\Storefront\Pagelet\Header;

use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class HeaderPageletEventSubscriber implements EventSubscriberInterface
{

    public function __construct(private SystemConfigService $systemConfigService)
    {
    }

    public function addTopbarText(HeaderPageletLoadedEvent $event): void
    {
        $value = $this->systemConfigService->get('KiweeDemo.config.topbar');
        $topbarConfig = new TopbarConfig();
        $topbarConfig->setTopbarText($value);
        $event->getPagelet()->addExtension('topbar', $topbarConfig);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HeaderPageletLoadedEvent::class => 'addTopbarText'
        ];
    }
}
