<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agent
 *
 * @ORM\Table(name="agent")
 * @ORM\Entity
 */
class Agent
{
    /**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=12, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $matricule;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomPatronymique", type="string", length=40, nullable=true)
     */
    private $nompatronymique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomUsuel", type="string", length=40, nullable=true)
     */
    private $nomusuel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=true)
     */
    private $datenaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeEmploiAffectationHierarchique", type="string", length=10, nullable=true)
     */
    private $codeemploiaffectationhierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongEmploiAffectation", type="string", length=60, nullable=true)
     */
    private $liblongemploiaffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePosteAffectation", type="string", length=10, nullable=true)
     */
    private $codeposteaffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongPosteAffectation", type="string", length=60, nullable=true)
     */
    private $liblongposteaffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeUOAffectationHierarchique", type="string", length=10, nullable=true)
     */
    private $codeuoaffectationhierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongUOAffectationHierarchique", type="string", length=100, nullable=true)
     */
    private $liblonguoaffectationhierarchique;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDebutAffectationHierarchique", type="date", nullable=true)
     */
    private $datedebutaffectationhierarchique;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateFinAffectationHierarchique", type="date", nullable=true)
     */
    private $datefinaffectationhierarchique;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="quotiteAffectationHierarchique", type="boolean", nullable=true)
     */
    private $quotiteaffectationhierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephonePro", type="string", length=20, nullable=true)
     */
    private $telephonepro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="portablePro", type="string", length=20, nullable=true)
     */
    private $portablepro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mailPro", type="string", length=100, nullable=true)
     */
    private $mailpro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numDossierHarpege", type="string", length=12, nullable=true)
     */
    private $numdossierharpege;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
     */
    private $lastupdate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePIP", type="string", length=8, nullable=true)
     */
    private $codepip;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongPIP", type="string", length=45, nullable=true)
     */
    private $liblongpip;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeCorps", type="string", length=4, nullable=true)
     */
    private $codecorps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libCourtCorps", type="string", length=18, nullable=true)
     */
    private $libcourtcorps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongCorps", type="string", length=45, nullable=true)
     */
    private $liblongcorps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeGrade", type="string", length=4, nullable=true)
     */
    private $codegrade;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongGrade", type="string", length=45, nullable=true)
     */
    private $liblonggrade;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeEchelon", type="string", length=2, nullable=true)
     */
    private $codeechelon;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeGroupeHierarchique", type="string", length=2, nullable=true)
     */
    private $codegroupehierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongGroupeHierarchique", type="string", length=45, nullable=true)
     */
    private $liblonggroupehierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="temEnseignantChercheur", type="string", length=1, nullable=true)
     */
    private $temenseignantchercheur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mailPerso", type="string", length=100, nullable=true)
     */
    private $mailperso;

    /**
     * @var string|null
     *
     * @ORM\Column(name="temEtat", type="string", length=1, nullable=true)
     */
    private $temetat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="affectationHierarchique", type="text", length=65535, nullable=true)
     */
    private $affectationhierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="affectationsFonctionnelles", type="text", length=65535, nullable=true)
     */
    private $affectationsfonctionnelles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="affectationsAdresses", type="text", length=65535, nullable=true)
     */
    private $affectationsadresses;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeEmploiAffectation", type="string", length=10, nullable=true)
     */
    private $codeemploiaffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="categorieEmploiPoste", type="string", length=4, nullable=true)
     */
    private $categorieemploiposte;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="civilite", type="boolean", nullable=true)
     */
    private $civilite;

    /**
     * @var int|null
     *
     * @ORM\Column(name="indiceMajore", type="integer", nullable=true)
     */
    private $indicemajore;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeQualiteStatutaire", type="string", length=1, nullable=true)
     */
    private $codequalitestatutaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongQualiteStatutaire", type="string", length=45, nullable=true)
     */
    private $liblongqualitestatutaire;


}
