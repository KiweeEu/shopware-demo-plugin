<?php
namespace Kiwee\KiweeDemo\Test\Storefront\Pagelet\Header;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\CartRuleLoader;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Api\Util\AccessKeyHelper;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\SalesChannelCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Test\TestDefaults;
use Shopware\Storefront\Page\GenericPageLoader;
use Symfony\Component\HttpFoundation\Request;

class HeaderPageletEventSubscriberTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testAddTopbarTextDefaultValue()
    {
        $context = $this->createSalesChannelContext();
        $request = new Request();
        $page = $this->getPageLoader()->load($request, $context);

        $this->assertEquals('This is a demo shop.',
            $page->getHeader()->getExtensions()['topbar']->getTopbarText()
        );
    }

    /**
     * @return void
     * @depends testAddTopbarTextDefaultValue
     */
    public function testAddTopbarTextModifiedValue()
    {
        $context = $this->createSalesChannelContext();
        $request = new Request();
        $systemConfigService = $this->getContainer()->get(SystemConfigService::class);
        $systemConfigService->set('KiweeDemo.config.topbar', 'This is not a demo shop.');
        $page = $this->getPageLoader()->load($request, $context);

        $this->assertEquals('This is not a demo shop.',
            $page->getHeader()->getExtensions()['topbar']->getTopbarText()
        );
    }

    protected function createSalesChannel(array $salesChannelOverride = []): array
    {
        /** @var EntityRepository<SalesChannelCollection> $salesChannelRepository */
        $salesChannelRepository = static::getContainer()->get('sales_channel.repository');
        $paymentMethod = $this->getAvailablePaymentMethod();

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('domains.url', 'http://localhost'));
        $salesChannelIds = $salesChannelRepository->searchIds($criteria, Context::createDefaultContext());

        if (!isset($salesChannelOverride['domains']) && $salesChannelIds->firstId() !== null) {
            $salesChannelRepository->delete([['id' => $salesChannelIds->firstId()]], Context::createDefaultContext());
        }

        $salesChannel = array_replace_recursive([
            'id' => $salesChannelOverride['id'] ?? Uuid::randomHex(),
            'typeId' => Defaults::SALES_CHANNEL_TYPE_STOREFRONT,
            'name' => 'API Test case sales channel',
            'accessKey' => AccessKeyHelper::generateAccessKey('sales-channel'),
            'languageId' => Defaults::LANGUAGE_SYSTEM,
            'snippetSetId' => $this->getSnippetSetIdForLocale('en-GB'),
            'currencyId' => Defaults::CURRENCY,
            'paymentMethodId' => $paymentMethod->getId(),
            'paymentMethods' => [['id' => $paymentMethod->getId()]],
            'shippingMethodId' => $this->getAvailableShippingMethod()->getId(),
            'navigationCategoryId' => $this->getValidCategoryId(),
            'countryId' => $this->getValidCountryId(null),
            'currencies' => [['id' => Defaults::CURRENCY]],
            'languages' => $salesChannelOverride['languages'] ?? [['id' => Defaults::LANGUAGE_SYSTEM]],
            'customerGroupId' => TestDefaults::FALLBACK_CUSTOMER_GROUP,
            'domains' => [
                [
                    'languageId' => Defaults::LANGUAGE_SYSTEM,
                    'currencyId' => Defaults::CURRENCY,
                    'snippetSetId' => $this->getSnippetSetIdForLocale('en-GB'),
                    'url' => 'http://localhost',
                ],
            ],
            'countries' => [['id' => $this->getValidCountryId(null)]],
        ], $salesChannelOverride);

        $salesChannelRepository->upsert([$salesChannel], Context::createDefaultContext());

        return $salesChannel;
    }

    protected function getPageLoader(): GenericPageLoader
    {
        return $this->getContainer()->get(GenericPageLoader::class);
    }

    private function createSalesChannelContext(): SalesChannelContext
    {
        $salesChannel = $this->createSalesChannel();

        return $this->createContext($salesChannel);
    }

    private function createContext(array $salesChannel): SalesChannelContext
    {
        $context = static::getContainer()->get(SalesChannelContextFactory::class)
            ->create(Uuid::randomHex(), $salesChannel['id']);

        $ruleLoader = static::getContainer()->get(CartRuleLoader::class);
        $ruleLoader->loadByToken($context, $context->getToken());

        return $context;
    }
}
