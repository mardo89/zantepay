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
        $currentDate = time();

        if ($this->icoPartOne->isActive($currentDate)) {
            return $this->icoPartOne;
        }

        if ($this->icoPartTwo->isActive($currentDate)) {
            return $this->icoPartTwo;
        }

        if ($this->icoPartThree->isActive($currentDate)) {
            return $this->icoPartThree;
        }

        if ($this->icoPartFour->isActive($currentDate)) {
            return $this->icoPartFour;
        }

        return null;
    }

    /**
     * Get ICO part by date
     *
     * @param string $operationDate
     *
     * @return mixed
     */
    public function getPart($operationDate) {

        if ($this->icoPartOne->isActive($operationDate)) {
            return $this->icoPartOne;
        }

        if ($this->icoPartTwo->isActive($operationDate)) {
            return $this->icoPartTwo;
        }

        if ($this->icoPartThree->isActive($operationDate)) {
            return $this->icoPartThree;
        }

        if ($this->icoPartFour->isActive($operationDate)) {
            return $this->icoPartFour;
        }

        return null;
    }

    /**
     * Gett array of availabe ICO parts
     * @return array
     */
    public function getParts() {
        return [
            $this->getIcoPartOne(),
            $this->getIcoPartTwo(),
            $this->getIcoPartThree(),
            $this->getIcoPartFour()
        ];
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
