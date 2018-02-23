<?php

namespace App\Models\Wallet;


use App\Models\Wallet\ICO\IcoPartFour;
use App\Models\Wallet\ICO\IcoPartOne;
use App\Models\Wallet\ICO\IcoPartThree;
use App\Models\Wallet\ICO\IcoPartTwo;

class Ico
{
    protected $icoPartOne;
    protected $icoPartTwo;
    protected $icoPartThree;
    protected $icoPartFour;

    public function __construct()
    {
        $this->icoPartOne = new IcoPartOne();
        $this->icoPartTwo = new IcoPartTwo();
        $this->icoPartThree = new IcoPartThree();
        $this->icoPartFour = new IcoPartFour();
    }

    /**
     * Get current ICO part
     *
     * @return mixed
     */
    public function getActivePart() {
        if ($this->icoPartOne->isActive()) {
            return $this->icoPartOne;
        }

        if ($this->icoPartTwo->isActive()) {
            return $this->icoPartTwo;
        }

        if ($this->icoPartThree->isActive()) {
            return $this->icoPartTree;
        }

        if ($this->icoPartFour->isActive()) {
            return $this->icoPartFour;
        }

        return null;
    }

    /**
     * Return ICO part one
     *
     * @return IcoPartOne
     */
    public function getIcoPartOne()
    {
        return $this->icoPartOne;
    }

    /**
     * Return ICO part two
     *
     * @return IcoPartTwo
     */
    public function getIcoPartTwo()
    {
        return $this->icoPartTwo;
    }

    /**
     * Return ICO part three
     *
     * @return IcoPartThree
     */
    public function getIcoPartThree()
    {
        return $this->icoPartThree;
    }

    /**
     * Return ICO part four
     *
     * @return IcoPartFour
     */
    public function getIcoPartFour()
    {
        return $this->icoPartFour;
    }
}
