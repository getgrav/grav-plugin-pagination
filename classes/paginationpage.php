<?php
namespace Grav\Plugin;

use \Grav\Common\GravTrait;

class PaginationPage
{
    use GravTrait;

    /**
     * @var int
     */
    public $number;

    /**
     * @var string
     */
    public $url;

    /**
     * Constructor
     *
     * @param int $number
     * @param string $url
     */
    public function __construct($number, $url)
    {
        $this->number = $number;
        $this->url = $url;
    }

    /**
     * Returns true if the page is the current one.
     *
     * @return bool
     */
    public function isCurrent()
    {
        if (self::$grav['uri']->currentPage() == $this->number) {
            return true;
        } else {
            return false;
        }
    }
}
