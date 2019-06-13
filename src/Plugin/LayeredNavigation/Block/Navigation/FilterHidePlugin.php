<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2019.
 */

namespace Emico\AttributeLanding\Plugin\LayeredNavigation\Block\Navigation;

use Emico\AttributeLanding\Model\FilterHider\FilterHiderInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\LayeredNavigation\Block\Navigation;

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
     * @param Navigation $subject
     * @param $filters
     * @return mixed
     */
    public function afterGetFilters(Navigation $subject, array $filters)
    {
        $landingPage = $this->landingPageContext->getLandingPage();
        if (!$landingPage || !$landingPage->getHideSelectedFilters()) {
            return $filters;
        }

        foreach ($filters as $index => $filter) {
            if ($this->filterHider->shouldHideFilter($landingPage, $filter)) {
                unset($filters[$index]);
            }
        }

        return $filters;
    }
}