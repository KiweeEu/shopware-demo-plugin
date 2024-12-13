<?php
declare(strict_types=1);

namespace Kiwee\KiweeDemo\Storefront\Pagelet\Header;
use Shopware\Core\Framework\Struct\Struct;

class TopbarConfig extends Struct
{
    protected string $topbarText;

    /**
     * @return string
     */
    public function getTopbarText(): string
    {
        return $this->topbarText;
    }

    /**
     * @param string $topbarText
     */
    public function setTopbarText(string $topbarText): void
    {
        $this->topbarText = $topbarText;
    }
}
