<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Structure
 *
 * @ORM\Table(name="structure")
 * @ORM\Entity
 */
class Structure {

    #region Attributes
    
    /**
     * @var string
     *
     * @ORM\Column(name="codeUO", type="string", length=10, nullable=false)
     * @ORM\Id
     */
    private $codeUO;

    /**
     * @var string
     *
     * @ORM\Column(name="libCourtUO", type="string", length=25, nullable=false)
     */
    private $libCourtUO;

    /**
     * @var string
     *
     * @ORM\Column(name="libLongUO", type="string", length=100, nullable=false)
     */
    private $libLongUO;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeTypeUO", type="string", length=3, nullable=true)
     */
    private $codeTypeUO;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongTypeUO", type="string", length=45, nullable=true)
     */
    private $libLongTypeUO;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeTypeArborescence", type="string", length=3, nullable=true)
     */
    private $codeTypeArborescence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongTypeArborescence", type="string", length=45, nullable=true)
     */
    private $libLongTypeArborescence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="listeAdressesUO", type="text", length=65535, nullable=true)
     */
    private $listeAdressesUO;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeHarpege", type="string", length=5, nullable=true)
     */
    private $codeHarpege;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
     */
    private $lastUpdate;

    #endregion
    
    #region Getters and Setters

    public function getCodeUO(): ?string {
        return $this->codeUO;
    }
    public function setCodeUO(string $codeUO): self {
        $this->codeUO = $codeUO;

        return $this;
    }

    public function getLibCourtUO(): ?string {
        return $this->libCourtUO;
    }
    public function setLibCourtUO(string $libCourtUO): self {
        $this->libCourtUO = $libCourtUO;

        return $this;
    }

    public function getLibLongUO(): ?string {
        return $this->libLongUO;
    }
    public function setLibLongUO(string $libLongUO): self {
        $this->libLongUO = $libLongUO;

        return $this;
    }

    public function getCodeTypeUO(): ?string {
        return $this->codeTypeUO;
    }
    public function setCodeTypeUO(?string $codeTypeUO): self {
        $this->codeTypeUO = $codeTypeUO;

        return $this;
    }

    public function getLibLongTypeUO(): ?string {
        return $this->libLongTypeUO;
    }
    public function setLibLongTypeUO(?string $libLongTypeUO): self {
        $this->libLongTypeUO = $libLongTypeUO;

        return $this;
    }

    public function getCodeTypeArborescence(): ?string {
        return $this->codeTypeArborescence;
    }
    public function setCodeTypeArborescence(?string $codeTypeArborescence): self {
        $this->codeTypeArborescence = $codeTypeArborescence;

        return $this;
    }

    public function getLibLongTypeArborescence(): ?string {
        return $this->libLongTypeArborescence;
    }
    public function setLibLongTypeArborescence(?string $libLongTypeArborescence): self {
        $this->libLongTypeArborescence = $libLongTypeArborescence;

        return $this;
    }

    public function getListeAdressesUO(): ?string {
        return $this->listeAdressesUO;
    }
    public function setListeAdressesUO(?string $listeAdressesUO): self {
        $this->listeAdressesUO = $listeAdressesUO;

        return $this;
    }

    public function getCodeHarpege(): ?string {
        return $this->codeHarpege;
    }
    public function setCodeHarpege(?string $codeHarpege): self {
        $this->codeHarpege = $codeHarpege;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface {
        return $this->lastUpdate;
    }
    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    #endregion

}
