<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CurrencyBundle\Twig;

use Sylius\Bundle\CurrencyBundle\Templating\Helper\CurrencyHelperInterface;

/**
 * Sylius currency Twig helper.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CurrencyExtension extends \Twig_Extension
{
    /**
     * @var CurrencyHelperInterface
     */
    protected $helper;

    /**
     * @param CurrencyHelperInterface $helper
     */
    public function __construct(CurrencyHelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('sylius_currency', [$this, 'convertAmount']),
            new \Twig_SimpleFilter('sylius_price', [$this, 'convertAndFormatAmount']),
        ];
    }

    /**
     * Convert amount to target currency.
     *
     * @param int     $amount
     * @param string|null $currency
     *
     * @return string
     */
    public function convertAmount($amount, $currency = null)
    {
        return $this->helper->convertAmount($amount, $currency);
    }

    /**
     * Convert and format amount.
     *
     * @param int     $amount
     * @param string|null $currency
     *
     * @return string
     */
    public function convertAndFormatAmount($amount, $currency = null)
    {
        return $this->helper->convertAndFormatAmount($amount, $currency);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_currency';
    }
}
