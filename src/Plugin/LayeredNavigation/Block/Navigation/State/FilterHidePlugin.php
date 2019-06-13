<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2019.
 */

namespace Emico\AttributeLanding\Plugin\LayeredNavigation\Block\Navigation\State;

use Emico\AttributeLanding\Model\FilterHider\FilterHiderInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\LayeredNavigation\Block\Navigation\State;

class FilterHidePlugin
{
    /**
     * @var FilterHiderInterface
     */
    private $filterHider;

    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * Plugin constructor.
     * @param LandingPageContext $landingPageContext
     * @param FilterHiderInterface $filterHider
     */
    public function __construct(
        LandingPageContext $landingPageContext,
        FilterHiderInterface $filterHider
    ) {
        $this->filterHider = $filterHider;
        $this->landingPageContext = $landingPageContext;
    }

    /**
     * @param State $subject
     * @param $filters
     * @return mixed
     */
    public function afterGetActiveFilters(State $subject, array $filterItems)
    {
        $landingPage = $this->landingPageContext->getLandingPage();
        if (!$landingPage || !$landingPage->getHideSelectedFilters()) {
            return $filterItems;
        }
        /**
         * @var int $index
         * @var FilterInterface $filterItem
         */
        foreach ($filterItems as $index => $filterItem) {
            if ($this->filterHider
                ->shouldHideFilter($landingPage, $filterItem->getFilter())
            ) {
                unset($filterItems[$index]);
            }
        }

        return $filterItems;
    }
}