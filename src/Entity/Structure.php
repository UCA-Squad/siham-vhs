<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Structure
 *
 * @ORM\Table(name="structure", indexes={@ORM\Index(name="index_codeTypeArborescence", columns={"codeTypeArborescence"}), @ORM\Index(name="index_codeTypeUO", columns={"codeTypeUO"})})
 * @ORM\Entity
 */
class Structure
{
    /**
     * @var string
     *
     * @ORM\Column(name="codeUO", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codeuo;

    /**
     * @var string
     *
     * @ORM\Column(name="libCourtUO", type="string", length=25, nullable=false)
     */
    private $libcourtuo;

    /**
     * @var string
     *
     * @ORM\Column(name="libLongUO", type="string", length=100, nullable=false)
     */
    private $liblonguo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeTypeUO", type="string", length=3, nullable=true)
     */
    private $codetypeuo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongTypeUO", type="string", length=45, nullable=true)
     */
    private $liblongtypeuo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeTypeArborescence", type="string", length=3, nullable=true)
     */
    private $codetypearborescence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongTypeArborescence", type="string", length=45, nullable=true)
     */
    private $liblongtypearborescence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="listeAdressesUO", type="text", length=65535, nullable=true)
     */
    private $listeadressesuo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeHarpege", type="string", length=5, nullable=true)
     */
    private $codeharpege;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
     */
    private $lastupdate;


}
