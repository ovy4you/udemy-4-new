<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements  GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function priceFilter($value)
    {
        return '$'.number_format($value,2);
    }

    public function getGlobals(){
        return [
            'locale' => $this->locale,
        ];
    }
}
