<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Core\Promotion\Checker;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Promotion\Checker\RuleCheckerInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class ContainsTaxonRuleChecker implements RuleCheckerInterface
{
    const TYPE = 'contains_taxon';

    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @param TaxonRepositoryInterface $taxonRepository
     */
    public function __construct(TaxonRepositoryInterface $taxonRepository)
    {
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration)
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnexpectedTypeException($subject, OrderInterface::class);
        }

        if (!isset($configuration['taxon']) || !isset($configuration['count'])) {
            return false;
        }

        $targetTaxon = $this->taxonRepository->findOneBy(['code' => $configuration['taxon']]);
        if (null === $targetTaxon) {
            return false;
        }

        $validProducts = 0;
        foreach ($subject->getItems() as $item) {
            if (!$item->getProduct()->hasTaxon($targetTaxon)) {
                continue;
            }

            $validProducts += $item->getQuantity();
        }

        return $validProducts >= $configuration['count'];
    }

    /**
     * @return string
     */
    public function getConfigurationFormType()
    {
        // it will be implemented after moving promotions to new backend UI
        return null;
    }
}
