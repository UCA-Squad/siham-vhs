<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agent
 *
 * @ORM\Table(name="agent", 
 *      uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})}, 
 *      indexes={@ORM\Index(columns={"aurion"})}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AgentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Agent {

    #region Attributes

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="matricule", type="string", length=12)
     */
    private $matricule;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=12, nullable=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="badge", type="string", length=45, nullable=true)
     */
    private $badge;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomPatronymique", type="string", length=40, nullable=true)
     */
    private $nomPatronymique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomUsuel", type="string", length=40, nullable=true)
     */
    private $nomUsuel;

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
    private $dateNaissance;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDeces", type="date", nullable=true)
     */
    private $dateDeces;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="civilite", type="string", length=1, nullable=true)
     */
    private $civilite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numDossierHarpege", type="integer", nullable=true)
     */
    private $numDossierHarpege;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numen", type="string", length=13, nullable=true)
     */
    private $numen;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
     */
    private $lastUpdate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephonePro", type="string", length=20, nullable=true)
     */
    private $telephonePro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="portablePro", type="string", length=20, nullable=true)
     */
    private $portablePro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="portablePerso", type="string", length=20, nullable=true)
     */
    private $portablePerso;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mailPro", type="string", length=100, nullable=true)
     */
    private $mailPro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mailPerso", type="string", length=100, nullable=true)
     */
    private $mailPerso;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePIP", type="string", length=8, nullable=true)
     */
    private $codePIP;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeQualiteStatutaire", type="string", length=1, nullable=true)
     */
    private $codeQualiteStatutaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePopulationType", type="string", length=5, nullable=true)
     */
    private $codePopulationType;
    /**
     * @var string|null
     *
     * @ORM\Column(name="codeCategoryPopulationType", type="string", length=1, nullable=true)
     */
    private $codeCategoryPopulationType;
    /**
     * @var string|null
     *
     * @ORM\Column(name="codeSubCategoryPopulationType", type="string", length=1, nullable=true)
     */
    private $codeSubCategoryPopulationType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeGroupeHierarchique", type="string", length=2, nullable=true)
     */

    private $codeGroupeHierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeCategory", type="string", length=18, nullable=true)
     */
    private $codeCategory;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeEchelon", type="string", length=2, nullable=true)
     */
    private $codeEchelon;

    /**
     * @var int|null
     *
     * @ORM\Column(name="indiceMajore", type="integer", nullable=true)
     */
    private $indiceMajore;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeCorps", type="string", length=4, nullable=true)
     */
    private $codeCorps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeGrade", type="string", length=4, nullable=true)
     */
    private $codeGrade;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongGrade", type="string", length=45, nullable=true)
     */
    private $libLongGrade;

    /**
     * @var string|null
     *
     * @ORM\Column(name="temEnseignantChercheur", type="string", length=1, nullable=true)
     */
    private $temEnseignantChercheur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="organismePrincipal", type="string", length=45, nullable=true)
     */
    private $organismePrincipal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeEmploiAffectation", type="string", length=10, nullable=true)
     */
    private $codeEmploiAffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongEmploiAffectation", type="string", length=60, nullable=true)
     */
    private $libLongEmploiAffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePosteAffectation", type="string", length=10, nullable=true)
     */
    private $codePosteAffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongPosteAffectation", type="string", length=60, nullable=true)
     */
    private $libLongPosteAffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="categorieEmploiPoste", type="string", length=4, nullable=true)
     */
    private $categorieEmploiPoste;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeTypeModaliteService", type="string", length=15, nullable=true)
     */
    private $codeTypeModaliteService;
    /**
     * @var string|null
     *
     * @ORM\Column(name="ratioModaliteService", type="decimal", scale=2, nullable=true)
     */
    private $ratioModaliteService;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDebutModaliteService", type="date", nullable=true)
     */
    private $dateDebutModaliteService;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateFinModaliteService", type="date", nullable=true)
     */
    private $dateFinModaliteService;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeUOAffectationsHIE", type="string", nullable=true)
     */
    private $codeUOAffectationsHIE;
    /**
     * @var string|null
     *
     * @ORM\Column(name="nameAffectationsHIE", type="text", nullable=true)
     */
    private $nameAffectationsHIE;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDebutAffectationsHIE", type="date", nullable=true)
     */
    private $dateDebutAffectationsHIE;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateFinAffectationsHIE", type="date", nullable=true)
     */
    private $dateFinAffectationsHIE;
    /**
     * @var string|null
     *
     * @ORM\Column(name="quotiteAffectationsHIE", type="string", nullable=true)
     */
    private $quotiteAffectationsHIE;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeUOAffectationsFUN", type="string", nullable=true)
     */
    private $codeUOAffectationsFUN;
    /**
     * @var string|null
     *
     * @ORM\Column(name="nameAffectationsFUN", type="text", nullable=true)
     */
    private $nameAffectationsFUN;
    /**
     * @var string|null
     *
     * @ORM\Column(name="quotiteAffectationsFUN", type="string", nullable=true)
     */
    private $quotiteAffectationsFUN;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeUOAffectationsADR", type="string", nullable=true)
     */
    private $codeUOAffectationsADR;
    /**
     * @var string|null
     *
     * @ORM\Column(name="nameAffectationsADR", type="text", nullable=true)
     */
    private $nameAffectationsADR;
    /**
     * @var string|null
     *
     * @ORM\Column(name="quotiteAffectationsADR", type="string", nullable=true)
     */
    private $quotiteAffectationsADR;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeUOAffectationsAGR", type="string", nullable=true)
     */
    private $codeUOAffectationsAGR;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDebutAffectationsAGR", type="date", nullable=true)
     */
    private $dateDebutAffectationsAGR;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateFinAffectationsAGR", type="date", nullable=true)
     */
    private $dateFinAffectationsAGR;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeFunctions", type="string", nullable=true)
     */
    private $codeFunctions;
    /**
     * @var string|null
     *
     * @ORM\Column(name="nameFunctions", type="text", nullable=true)
     */
    private $nameFunctions;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePositionStatutaire", type="string", nullable=true)
     */
    private $codePositionStatutaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codePositionAdministrative", type="string", nullable=true)
     */
    private $codePositionAdministrative;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDebutPositionAdministrative", type="date", nullable=true)
     */
    private $dateDebutPositionAdministrative;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateFinPositionAdministrative", type="date", nullable=true)
     */
    private $dateFinPositionAdministrative;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="codeAbsence", type="string", nullable=true)
     */
    private $codeAbsence;
    /**
     * @var string|null
     *
     * @ORM\Column(name="nameAbsence", type="text", nullable=true)
     */
    private $nameAbsence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="aurion", type="string", length=12, nullable=true)
     */
    private $aurion;

    #endregion

    #region Getters and Setters

    public function getMatricule(): ?string {
        return $this->matricule;
    }
    public function setMatricule(?string $matricule): self {
        $this->matricule = $matricule;

        return $this;
    }

    public function getUsername(): ?string {
        return $this->username;
    }
    public function setUsername(?string $username): self {
        $this->username = $username;

        return $this;
    }

    public function getBadge(): ?string {
        return $this->badge;
    }
    public function setBadge(?string $badge): self {
        $this->badge = $badge;

        return $this;
    }

    public function getNomPatronymique(): ?string {
        return $this->nomPatronymique;
    }
    public function setNomPatronymique(?string $nomPatronymique): self {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }
    public function getNomUsuel(): ?string {
        return $this->nomUsuel;
    }
    public function setNomUsuel(?string $nomUsuel): self {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }
    public function getPrenom(): ?string {
        return $this->prenom;
    }
    public function setPrenom(?string $prenom): self {
        $this->prenom = $prenom;

        return $this;
    }
    public function getDateNaissance(): ?\DateTimeInterface {
        return $this->dateNaissance;
    }
    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }
    public function getDateDeces(): ?\DateTimeInterface {
        return $this->dateDeces;
    }
    public function setDateDeces(?\DateTimeInterface $dateDeces): self {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getCivilite(): ?string {
        return $this->civilite;
    }
    public function setCivilite(?string $civilite): self {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNumDossierHarpege(): ?int {
        return $this->numDossierHarpege;
    }
    public function setNumDossierHarpege(?int $numDossierHarpege): self {
        $this->numDossierHarpege = $numDossierHarpege;

        return $this;
    }
    public function getNumen(): ?string {
        return $this->numen;
    }
    public function setNumen(?string $numen): self {
        $this->numen = $numen;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface {
        return $this->lastUpdate;
    }
    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getTelephonePro(): ?string {
        return $this->telephonePro;
    }
    public function setTelephonePro(?string $telephonePro): self {
        $this->telephonePro = $telephonePro;

        return $this;
    }
    public function getPortablePro(): ?string {
        return $this->portablePro;
    }
    public function setPortablePro(?string $portablePro): self {
        $this->portablePro = $portablePro;

        return $this;
    }
    public function getPortablePerso(): ?string {
        return $this->portablePerso;
    }
    public function setPortablePerso(?string $portablePerso): self {
        $this->portablePerso = $portablePerso;

        return $this;
    }
    public function getMailPro(): ?string {
        return $this->mailPro;
    }
    public function setMailPro(?string $mailPro): self {
        $this->mailPro = $mailPro;

        return $this;
    }
    public function getMailPerso(): ?string {
        return $this->mailPerso;
    }
    public function setMailPerso(?string $mailPerso): self {
        $this->mailPerso = $mailPerso;

        return $this;
    }

    public function getCodePIP(): ?string {
        return $this->codePIP;
    }
    public function setCodePIP(?string $codePIP): self {
        $this->codePIP = $codePIP;

        return $this;
    }

    public function getCodeQualiteStatutaire(): ?string {
        return $this->codeQualiteStatutaire;
    }
    public function setCodeQualiteStatutaire(?string $codeQualiteStatutaire): self {
        $this->codeQualiteStatutaire = $codeQualiteStatutaire;

        return $this;
    }
    public function getCodePopulationType(): ?string {
        return $this->codePopulationType;
    }
    public function setCodePopulationType(?string $codePopulationType): self {
        $this->codePopulationType = $codePopulationType;

        return $this;
    }
    public function getCodeCategoryPopulationType(): ?string {
        return $this->codeCategoryPopulationType;
    }
    public function setCodeCategoryPopulationType(?string $codeCategoryPopulationType): self {
        $this->codeCategoryPopulationType = $codeCategoryPopulationType;

        return $this;
    }
    public function getCodeSubCategoryPopulationType(): ?string {
        return $this->codeSubCategoryPopulationType;
    }
    public function setCodeSubCategoryPopulationType(?string $codeSubCategoryPopulationType): self {
        $this->codeSubCategoryPopulationType = $codeSubCategoryPopulationType;

        return $this;
    }

    public function getCodeGroupeHierarchique(): ?string {
        return $this->codeGroupeHierarchique;
    }
    public function setCodeGroupeHierarchique(?string $codeGroupeHierarchique): self {
        $this->codeGroupeHierarchique = $codeGroupeHierarchique;

        return $this;
    }
    public function getCodeCategory(): ?string {
        return $this->codeCategory;
    }
    public function setCodeCategory(?string $codeCategory): self {
        $this->codeCategory = $codeCategory;

        return $this;
    }
    public function getCodeEchelon(): ?string {
        return $this->codeEchelon;
    }
    public function setCodeEchelon(?string $codeEchelon): self {
        $this->codeEchelon = $codeEchelon;

        return $this;
    }
    public function getIndiceMajore(): ?int {
        return $this->indiceMajore;
    }
    public function setIndiceMajore(?int $indiceMajore): self {
        $this->indiceMajore = $indiceMajore;

        return $this;
    }
    public function getCodeCorps(): ?string {
        return $this->codeCorps;
    }
    public function setCodeCorps(?string $codeCorps): self {
        $this->codeCorps = $codeCorps;

        return $this;
    }
    public function getCodeGrade(): ?string {
        return $this->codeGrade;
    }
    public function setCodeGrade(?string $codeGrade): self {
        $this->codeGrade = $codeGrade;

        return $this;
    }
    public function getLibLongGrade(): ?string {
        return $this->libLongGrade;
    }
    public function setLibLongGrade(?string $libLongGrade): self {
        $this->libLongGrade = $libLongGrade;

        return $this;
    }

    public function getTemEnseignantChercheur(): ?string {
        return $this->temEnseignantChercheur;
    }
    public function setTemEnseignantChercheur(?string $temEnseignantChercheur): self {
        $this->temEnseignantChercheur = $temEnseignantChercheur;

        return $this;
    }

    public function getOrganismePrincipal(): ?string {
        return $this->organismePrincipal;
    }
    public function setOrganismePrincipal(?string $organismePrincipal): self {
        $this->organismePrincipal = $organismePrincipal;

        return $this;
    }

    public function getCodeEmploiAffectation(): ?string {
        return $this->codeEmploiAffectation;
    }
    public function setCodeEmploiAffectation(?string $codeEmploiAffectation): self {
        $this->codeEmploiAffectation = $codeEmploiAffectation;

        return $this;
    }
    public function getLibLongEmploiAffectation(): ?string {
        return $this->libLongEmploiAffectation;
    }
    public function setLibLongEmploiAffectation(?string $libLongEmploiAffectation): self {
        $this->libLongEmploiAffectation = $libLongEmploiAffectation;

        return $this;
    }
    public function getCodePosteAffectation(): ?string {
        return $this->codePosteAffectation;
    }
    public function setCodePosteAffectation(?string $codePosteAffectation): self {
        $this->codePosteAffectation = $codePosteAffectation;

        return $this;
    }
    public function getLibLongPosteAffectation(): ?string {
        return $this->libLongPosteAffectation;
    }
    public function setLibLongPosteAffectation(?string $libLongPosteAffectation): self {
        $this->libLongPosteAffectation = $libLongPosteAffectation;

        return $this;
    }
    public function getCategorieEmploiPoste(): ?string {
        return $this->categorieEmploiPoste;
    }
    public function setCategorieEmploiPoste(?string $categorieEmploiPoste): self {
        $this->categorieEmploiPoste = $categorieEmploiPoste;

        return $this;
    }

    public function getCodeTypeModaliteService(): ?string {
        return $this->codeTypeModaliteService;
    }
    public function setCodeTypeModaliteService(?string $codeTypeModaliteService): self {
        $this->codeTypeModaliteService = $codeTypeModaliteService;

        return $this;
    }
    public function getRatioModaliteService(): ?double {
        return $this->ratioModaliteService;
    }
    public function setRatioModaliteService(?double $ratioModaliteService): self {
        $this->ratioModaliteService = $ratioModaliteService;

        return $this;
    }
    public function getDateDebutModaliteService(): ?\DateTimeInterface {
        return $this->dateDebutModaliteService;
    }
    public function setDateDebutModaliteService(?\DateTimeInterface $dateDebutModaliteService): self {
        $this->dateDebutModaliteService = $dateDebutModaliteService;

        return $this;
    }
    public function getDateFinModaliteService(): ?\DateTimeInterface {
        return $this->dateFinModaliteService;
    }
    public function setDateFinModaliteService(?\DateTimeInterface $dateFinModaliteService): self {
        $this->dateFinModaliteService = $dateFinModaliteService;

        return $this;
    }

    public function getCodeUOAffectationsHIE(): ?string {
        return $this->codeUOAffectationsHIE;
    }
    public function setCodeUOAffectationsHIE(?string $codeUOAffectationsHIE): self {
        $this->codeUOAffectationsHIE = $codeUOAffectationsHIE;

        return $this;
    }
    public function getNameAffectationsHIE(): ?string {
        return $this->nameAffectationsHIE;
    }
    public function setNameAffectationsHIE(?string $nameAffectationsHIE): self {
        $this->nameAffectationsHIE = $nameAffectationsHIE;

        return $this;
    }
    public function getDateDebutAffectationsHIE(): ?\DateTimeInterface {
        return $this->dateDebutAffectationsHIE;
    }
    public function setDateDebutAffectationsHIE(?\DateTimeInterface $dateDebutAffectationsHIE): self {
        $this->dateDebutAffectationsHIE = $dateDebutAffectationsHIE;

        return $this;
    }
    public function getDateFinAffectationsHIE(): ?\DateTimeInterface {
        return $this->dateFinAffectationsHIE;
    }
    public function setDateFinAffectationsHIE(?\DateTimeInterface $dateFinAffectationsHIE): self {
        $this->dateFinAffectationsHIE = $dateFinAffectationsHIE;

        return $this;
    }
    public function getQuotiteAffectationsHIE(): ?int {
        return $this->quotiteAffectationsHIE;
    }
    public function setQuotiteAffectationsHIE(?int $quotiteAffectationsHIE): self {
        $this->quotiteAffectationsHIE = $quotiteAffectationsHIE;

        return $this;
    }

    public function getCodeUOAffectationsFUN(): ?string {
        return $this->codeUOAffectationsFUN;
    }
    public function setCodeUOAffectationsFUN(?string $codeUOAffectationsFUN): self {
        $this->codeUOAffectationsFUN = $codeUOAffectationsFUN;

        return $this;
    }
    public function getNameAffectationsFUN(): ?string {
        return $this->nameAffectationsFUN;
    }
    public function setNameAffectationsFUN(?string $nameAffectationsFUN): self {
        $this->nameAffectationsFUN = $nameAffectationsFUN;

        return $this;
    }
    public function getQuotiteAffectationsFUN(): ?int {
        return $this->quotiteAffectationsFUN;
    }
    public function setQuotiteAffectationsFUN(?int $quotiteAffectationsFUN): self {
        $this->quotiteAffectationsFUN = $quotiteAffectationsFUN;

        return $this;
    }

    public function getCodeUOAffectationsADR(): ?string {
        return $this->codeUOAffectationsADR;
    }
    public function setCodeUOAffectationsADR(?string $codeUOAffectationsADR): self {
        $this->codeUOAffectationsADR = $codeUOAffectationsADR;

        return $this;
    }
    public function getNameAffectationsADR(): ?string {
        return $this->nameAffectationsADR;
    }
    public function setNameAffectationsADR(?string $nameAffectationsADR): self {
        $this->nameAffectationsADR = $nameAffectationsADR;

        return $this;
    }
    public function getQuotiteAffectationsADR(): ?int {
        return $this->quotiteAffectationsADR;
    }
    public function setQuotiteAffectationsADR(?int $quotiteAffectationsADR): self {
        $this->quotiteAffectationsADR = $quotiteAffectationsADR;

        return $this;
    }

    public function getCodeUOAffectationsAGR(): ?string {
        return $this->codeUOAffectationsAGR;
    }
    public function setCodeUOAffectationsAGR(?string $codeUOAffectationsAGR): self {
        $this->codeUOAffectationsAGR = $codeUOAffectationsAGR;

        return $this;
    }
    public function getDateDebutAffectationsAGR(): ?\DateTimeInterface {
        return $this->dateDebutAffectationsAGR;
    }
    public function setDateDebutAffectationsAGR(?\DateTimeInterface $dateDebutAffectationsAGR): self {
        $this->dateDebutAffectationsAGR = $dateDebutAffectationsAGR;

        return $this;
    }
    public function getDateFinAffectationsAGR(): ?\DateTimeInterface {
        return $this->dateFinAffectationsAGR;
    }
    public function setDateFinAffectationsAGR(?\DateTimeInterface $dateFinAffectationsAGR): self {
        $this->dateFinAffectationsAGR = $dateFinAffectationsAGR;

        return $this;
    }

    public function getCodeFunctions(): ?string {
        return $this->codeFunctions;
    }
    public function setCodeFunctions(?string $codeFunctions): self {
        $this->codeFunctions = $codeFunctions;

        return $this;
    }
    public function getNameFunctions(): ?string {
        return $this->nameFunctions;
    }
    public function setNameFunctions(?string $nameFunctions): self {
        $this->nameFunctions = $nameFunctions;

        return $this;
    }

    public function getCodePositionStatutaire(): ?string {
        return $this->codePositionStatutaire;
    }
    public function setCodePositionStatutaire(?string $codePositionStatutaire): self {
        $this->codePositionStatutaire = $codePositionStatutaire;

        return $this;
    }
    public function getCodePositionAdministrative(): ?string {
        return $this->codePositionAdministrative;
    }
    public function setCodePositionAdministrative(?string $codePositionAdministrative): self {
        $this->codePositionAdministrative = $codePositionAdministrative;

        return $this;
    }
    public function getDateDebutPositionAdministrative(): ?\DateTimeInterface {
        return $this->dateDebutPositionAdministrative;
    }
    public function setDateDebutPositionAdministrative(?\DateTimeInterface $dateDebutPositionAdministrative): self {
        $this->dateDebutPositionAdministrative = $dateDebutPositionAdministrative;

        return $this;
    }
    public function getDateFinPositionAdministrative(): ?\DateTimeInterface {
        return $this->dateFinPositionAdministrative;
    }
    public function setDateFinPositionAdministrative(?\DateTimeInterface $dateFinPositionAdministrative): self {
        $this->dateFinPositionAdministrative = $dateFinPositionAdministrative;

        return $this;
    }

    public function getCodeAbsence(): ?string {
        return $this->codeAbsence;
    }
    public function setCodeAbsence(?string $codeAbsence): self {
        $this->codeAbsence = $codeAbsence;

        return $this;
    }
    public function getNameAbsence(): ?string {
        return $this->nameAbsence;
    }
    public function setNameAbsence(?string $nameAbsence): self {
        $this->nameAbsence = $nameAbsence;

        return $this;
    }

    public function getAurion(): ?string {
        return $this->aurion;
    }
    public function setAurion(?string $aurion): self {
        $this->aurion = $aurion;

        return $this;
    }

    #endregion

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersistPreUpdate()
    {
        $this->lastUpdate = new \DateTime();
    }

    /**
     * Set attributes from response of webservice dossierAgent
     * @param personalData object response of webservice 
     */
    public function addPersonalData($personalData) {
        
        // Set the data with the same name
        if (isset($personalData->donneesPersonnelles)) {
            foreach($personalData->donneesPersonnelles as $attribute => $value) {
                if (\property_exists($this, $attribute)) {
                    if (strpos($attribute, 'date') !== false) {
                        $this->$attribute = new \DateTime(\substr($value,0,10));
                    } else {
                        $this->$attribute = $value;
                    }
                }
            }
        }

        // Interpret phones and emails fields
        $typesNumerosMails = [
            'TPR' => 'telephonePro', 
            'PPR' => 'portablePro', 
            'PPE' => 'portablePerso', 
            'MPR' => 'mailPro', 
            'MPE' => 'mailPerso',
        ];
        // Reset them before
        foreach ($typesNumerosMails as $typeNumerosMails => $attributeNumerosMails) {
            $this->$attributeNumerosMails = null;
        }
        // Set them if exist
        if (isset($personalData->listeNumerosMails)) {
            $listeNumerosMails = \is_object($personalData->listeNumerosMails) ? [$personalData->listeNumerosMails] : $personalData->listeNumerosMails;
            foreach($listeNumerosMails as $numeroMail) {
                if (isset($typesNumerosMails[$numeroMail->codeTypologieNumeroMail])) {
                    $attributeNumerosMails = $typesNumerosMails[$numeroMail->codeTypologieNumeroMail];
                    $this->$attributeNumerosMails = $numeroMail->numeroMail;
                }
            }
        }

        // Concatenate agent functions
        $codeFunctions = [];
        $nameFunctions = [];
        if (isset($personalData->listeFonctions)) {
            $listeFonctions = \is_object($personalData->listeFonctions) ? [$personalData->listeFonctions] : $personalData->listeFonctions;
            foreach($listeFonctions as $listeFonction) {
                $codeFunctions[] = $listeFonction->codeFonction;
                $nameFunctions[] = $listeFonction->libLongFonction;
            }
        }
        $this->codeFunctions = \implode('|', $codeFunctions);
        $this->nameFunctions = \implode('|', $nameFunctions);
    }

    /**
     * Set attributes from response of webservice dossierAgent
     * @param administrativeData object response of webservice 
     */
    public function addAdministrativeData($administrativeData, $startObservationDate = null, $endObservationDate = null) {
        if (empty($startObservationDate))
            $startObservationDate = new \DateTime();
        if (empty($endObservationDate))
            $endObservationDate = new \DateTime();
        $maxSihamDate = new \DateTime($_ENV['SIHAM_WS_DATE_MAX']);
        $endSihamDate = new \DateTime($_ENV['SIHAM_WS_DATE_NULL']);

        #region AFFECTATION
        // Simplify and common data in order to reduce the tests
        $initType = ['ADR' => [], 'FUN' => [], 'HIE' => []];
        $codeUOAffectations = $initType; $nameAffectations = $initType; $quotiteAffectations = $initType; $dateDebutAffectations = $initType; $dateFinAffectations = $initType;
        $codeEmploiAffectation = $initType; $libLongEmploiAffectation = $initType; $codePosteAffectation = $initType; $libLongPosteAffectation = $initType; $categorieEmploiPoste = $initType;
        if (isset($administrativeData->listeAffectations)) {
            $listeAffectations = \is_object($administrativeData->listeAffectations) ? [$administrativeData->listeAffectations] : $administrativeData->listeAffectations;
            foreach($listeAffectations as $listeAffectation) {
                // Convert string to DateTime
                $dateDebutAffectationsCurrent = new \DateTime(\substr($listeAffectation->dateDebutAffectation, 0, 10));
                $dateFinAffectationsCurrent   = new \DateTime(\substr($listeAffectation->dateFinAffectation, 0, 10));
                // Distinct the affectations between the observation dates
                $when = $dateDebutAffectationsCurrent <= $startObservationDate ? 'current' : ($dateDebutAffectationsCurrent <= $endObservationDate ? 'next' : null);
                $type = $listeAffectation->codeTypeRattachement;
                if (!empty($when)) {
                
                    if (!isset($codeUOAffectations[$type][$when]) || !in_array($listeAffectation->codeUOAffectation, $codeUOAffectations[$type][$when])) $codeUOAffectations[$type][$when][] = $listeAffectation->codeUOAffectation;
                    if (!isset($nameAffectations[$type][$when]) || !in_array($listeAffectation->libLongCodeUOAffectation, $nameAffectations[$type][$when])) $nameAffectations[$type][$when][] = $listeAffectation->libLongCodeUOAffectation;
                    if (!isset($quotiteAffectations[$type][$when]) || \array_sum($quotiteAffectations[$type][$when]) < 100) $quotiteAffectations[$type][$when][] = $listeAffectation->quotiteAffectation;
                    
                    $dateDebutAffectations[$type][$when][] = $dateDebutAffectationsCurrent;
                    
                    $codeEmploiAffectation   [$type][$when] = isset($listeAffectation->codeEmploiAffectation)    ? $listeAffectation->codeEmploiAffectation    : null;
                    $libLongEmploiAffectation[$type][$when] = isset($listeAffectation->libLongEmploiAffectation) ? $listeAffectation->libLongEmploiAffectation : null;
                    $codePosteAffectation    [$type][$when] = isset($listeAffectation->codePosteAffectation)     ? $listeAffectation->codePosteAffectation     : null;
                    $libLongPosteAffectation [$type][$when] = isset($listeAffectation->libLongPoste)             ? $listeAffectation->libLongPoste             : null;
                    $categorieEmploiPoste    [$type][$when] = isset($listeAffectation->categorieEmploiPoste)     ? $listeAffectation->categorieEmploiPoste     : null;
                }
                // To have all dates and keep the biggest
                $dateFinAffectations  [$type][!empty($when) ? $when : 'next'][] = $dateFinAffectationsCurrent;
            }
        }
        $this->codeUOAffectationsADR = \implode('|', isset($codeUOAffectations  ['ADR']['current']) ? $codeUOAffectations   ['ADR']['current'] : (isset($codeUOAffectations   ['ADR']['next']) ? $codeUOAffectations   ['ADR']['next'] : []));
        $this->nameAffectationsADR   = \implode('|', isset($nameAffectations    ['ADR']['current']) ? $nameAffectations     ['ADR']['current'] : (isset($nameAffectations     ['ADR']['next']) ? $nameAffectations     ['ADR']['next'] : []));
        $this->quotiteAffectationsADR= \implode('|', isset($quotiteAffectations ['ADR']['current']) ? $quotiteAffectations  ['ADR']['current'] : (isset($quotiteAffectations  ['ADR']['next']) ? $quotiteAffectations  ['ADR']['next'] : []));
        $this->codeUOAffectationsFUN = \implode('|', isset($codeUOAffectations  ['FUN']['current']) ? $codeUOAffectations   ['FUN']['current'] : (isset($codeUOAffectations   ['FUN']['next']) ? $codeUOAffectations   ['FUN']['next'] : []));
        $this->nameAffectationsFUN   = \implode('|', isset($nameAffectations    ['FUN']['current']) ? $nameAffectations     ['FUN']['current'] : (isset($nameAffectations     ['FUN']['next']) ? $nameAffectations     ['FUN']['next'] : []));
        $this->quotiteAffectationsFUN= \implode('|', isset($quotiteAffectations ['FUN']['current']) ? $quotiteAffectations  ['FUN']['current'] : (isset($quotiteAffectations  ['FUN']['next']) ? $quotiteAffectations  ['FUN']['next'] : []));
        $this->codeUOAffectationsHIE = \implode('|', isset($codeUOAffectations  ['HIE']['current']) ? $codeUOAffectations   ['HIE']['current'] : (isset($codeUOAffectations   ['HIE']['next']) ? $codeUOAffectations   ['HIE']['next'] : []));
        $this->nameAffectationsHIE   = \implode('|', isset($nameAffectations    ['HIE']['current']) ? $nameAffectations     ['HIE']['current'] : (isset($nameAffectations     ['HIE']['next']) ? $nameAffectations     ['HIE']['next'] : []));
        $this->quotiteAffectationsHIE= \implode('|', isset($quotiteAffectations ['HIE']['current']) ? $quotiteAffectations  ['HIE']['current'] : (isset($quotiteAffectations  ['HIE']['next']) ? $quotiteAffectations  ['HIE']['next'] : []));
        
        // Keep the min date and the max date
        $datesDebut = isset($dateDebutAffectations['HIE']['current']) ? $dateDebutAffectations['HIE']['current'] : (isset($dateDebutAffectations['HIE']['next']) ? $dateDebutAffectations['HIE']['next'] : []);
        if (!empty($datesDebut)) {
            $minDate = min(\array_map(function($dateTime) { return $dateTime->getTimestamp(); }, $datesDebut));
            $dateDebutFromTimestamp = new \DateTime();
            $dateDebutFromTimestamp->setTimestamp($minDate);
            $this->dateDebutAffectationsHIE = $dateDebutFromTimestamp;
        } else  {
            $this->dateDebutAffectationsHIE = null;
        }
        $datesFin = \array_merge(isset($dateFinAffectations['HIE']['current']) ? $dateFinAffectations['HIE']['current'] : [], isset($dateFinAffectations['HIE']['next']) ? $dateFinAffectations['HIE']['next'] : []);
        if (!empty($datesFin)) {
            $maxDate = max(array_map(function($dateTime) { return $dateTime->getTimestamp(); }, $datesFin));
            $dateFinFromTimestamp = new \DateTime();
            $dateFinFromTimestamp->setTimestamp($maxDate);
            $this->dateFinAffectationsHIE   = $dateFinFromTimestamp;
        } else {
            $this->dateFinAffectationsHIE   = null;
        }
        
        $this->codeEmploiAffectation    = isset($codeEmploiAffectation      ['HIE']['current']) ? $codeEmploiAffectation    ['HIE']['current'] : (isset($codeEmploiAffectation      ['HIE']['next']) ? $codeEmploiAffectation    ['HIE']['next'] : null);
        $this->libLongEmploiAffectation = isset($libLongEmploiAffectation   ['HIE']['current']) ? $libLongEmploiAffectation ['HIE']['current'] : (isset($libLongEmploiAffectation   ['HIE']['next']) ? $libLongEmploiAffectation ['HIE']['next'] : null);
        $this->codePosteAffectation     = isset($codePosteAffectation       ['HIE']['current']) ? $codePosteAffectation     ['HIE']['current'] : (isset($codePosteAffectation       ['HIE']['next']) ? $codePosteAffectation     ['HIE']['next'] : null);
        $this->libLongPosteAffectation  = isset($libLongPosteAffectation    ['HIE']['current']) ? $libLongPosteAffectation  ['HIE']['current'] : (isset($libLongPosteAffectation    ['HIE']['next']) ? $libLongPosteAffectation  ['HIE']['next'] : null);
        $this->categorieEmploiPoste     = isset($categorieEmploiPoste       ['HIE']['current']) ? $categorieEmploiPoste     ['HIE']['current'] : (isset($categorieEmploiPoste       ['HIE']['next']) ? $categorieEmploiPoste     ['HIE']['next'] : null);
        #endregion

        #region CARRIERE
        $codeQualiteStatutaire  = [];
        $codeGroupeHierarchique = [];
        $codeCategory           = [];
        $codeEchelon            = [];
        $indiceMajore           = [];
        $codeCorps              = [];
        $codeGrade              = [];
        $libLongGrade           = [];
        $temEnseignantChercheur = [];
        $organismePrincipal     = [];
        if (isset($administrativeData->listeCarrieres)) {
            $listeCarrieres = \is_object($administrativeData->listeCarrieres) ? [$administrativeData->listeCarrieres] : $administrativeData->listeCarrieres;
            foreach($listeCarrieres as $listeCarriere) {
                // Convert string to DateTime
                $dateDebutCarriereCurrent = new \DateTime(\substr($listeCarriere->dateEffetCarriere, 0, 10));
                $dateFinCarriereCurrent = isset($listeCarriere->dateFinCarriere) ? new \DateTime(\substr($listeCarriere->dateFinCarriere, 0, 10)) : $maxSihamDate;
                // Distinct the affectations between the observation dates
                $when = $dateDebutCarriereCurrent <= $startObservationDate ? 'current' : ($dateDebutCarriereCurrent <= $endObservationDate ? 'next' : null);
                if (!empty($when)) {
                    if (isset($listeCarriere->codeQualiteStatutaire))   $codeQualiteStatutaire[$when]   = $listeCarriere->codeQualiteStatutaire;
                    if (isset($listeCarriere->codeGroupeHierarchique))  $codeGroupeHierarchique[$when]  = $listeCarriere->codeGroupeHierarchique;
                    if (isset($listeCarriere->libCourtCategorieFP))     $codeCategory[$when]            = $listeCarriere->libCourtCategorieFP;
                    if (isset($listeCarriere->codeEchelon))             $codeEchelon[$when]             = $listeCarriere->codeEchelon;
                    if (isset($listeCarriere->indiceMajore))            $indiceMajore[$when]            = $listeCarriere->indiceMajore;
                    if (isset($listeCarriere->codeCorps))               $codeCorps[$when]               = $listeCarriere->codeCorps;
                    if (isset($listeCarriere->codeGrade))               $codeGrade[$when]               = $listeCarriere->codeGrade;
                    if (isset($listeCarriere->libLongGrade))            $libLongGrade[$when]            = $listeCarriere->libLongGrade;
                    if (isset($listeCarriere->temEnseignantChercheur))  $temEnseignantChercheur[$when]  = $listeCarriere->temEnseignantChercheur;
                    if (isset($listeCarriere->organismePrincipal))      $organismePrincipal[$when]      = $listeCarriere->organismePrincipal;
                }
            }
        }
        $this->codeQualiteStatutaire    = isset($codeQualiteStatutaire['current'])  ? $codeQualiteStatutaire['current'] : (isset($codeQualiteStatutaire['next'])    ? $codeQualiteStatutaire['next']    : null);
        $this->codeGroupeHierarchique   = isset($codeGroupeHierarchique['current']) ? $codeGroupeHierarchique['current']: (isset($codeGroupeHierarchique['next'])   ? $codeGroupeHierarchique['next']   : null);
        $this->codeCategory             = isset($codeCategory['current'])           ? $codeCategory['current']          : (isset($codeCategory['next'])             ? $codeCategory['next']             : null);
        $this->codeEchelon              = isset($codeEchelon['current'])            ? $codeEchelon['current']           : (isset($codeEchelon['next'])              ? $codeEchelon['next']              : null);
        $this->indiceMajore             = isset($indiceMajore['current'])           ? $indiceMajore['current']          : (isset($indiceMajore['next'])             ? $indiceMajore['next']             : null);
        $this->codeCorps                = isset($codeCorps['current'])              ? $codeCorps['current']             : (isset($codeCorps['next'])                ? $codeCorps['next']                : null);
        $this->codeGrade                = isset($codeGrade['current'])              ? $codeGrade['current']             : (isset($codeGrade['next'])                ? $codeGrade['next']                : null);
        $this->libLongGrade             = isset($libLongGrade['current'])           ? $libLongGrade['current']          : (isset($libLongGrade['next'])             ? $libLongGrade['next']             : null);
        $this->temEnseignantChercheur   = isset($temEnseignantChercheur['current']) ? $temEnseignantChercheur['current']: (isset($temEnseignantChercheur['next'])   ? $temEnseignantChercheur['next']   : null);
        $this->organismePrincipal       = isset($organismePrincipal['current'])     ? $organismePrincipal['current']    : (isset($organismePrincipal['next'])       ? $organismePrincipal['next']       : null);
        #endregion

        #region PIP
        $codePIP = [];
        if (isset($administrativeData->listePIP)) {
            $listePIPs = \is_object($administrativeData->listePIP) ? [$administrativeData->listePIP] : $administrativeData->listePIP;
            foreach($listePIPs as $listePIP) {
                // Convert string to DateTime
                $dateDebutPIPCurrent = new \DateTime(\substr($listePIP->dateEffetPIP, 0, 10));
                $dateFinPIPCurrent = isset($listePIP->dateFinPIP) ? new \DateTime(\substr($listePIP->dateFinPIP, 0, 10)) : $maxSihamDate;
                // Distinct the affectations between the observation dates
                $when = $dateDebutPIPCurrent <= $startObservationDate ? 'current' : ($dateDebutPIPCurrent <= $endObservationDate ? 'next' : null);
                if (!empty($when)) {
                    if (isset($listePIP->codePIP)) $codePIP[$when] = $listePIP->codePIP;
                }
            }
        }
        $this->codePIP = isset($codePIP['current']) ? $codePIP['current'] : (isset($codePIP['next']) ? $codePIP['next'] : null);
        #endregion

        #region POSITION ADMINISTRATIVE
        $codePositionStatutaire         = [];
        $codePositionAdministrative     = [];
        $dateDebutPositionAdministrative= [];
        $dateFinPositionAdministrative  = [];
        if (isset($administrativeData->listePositionsAdministratives)) {
            $listePositionsAdministratives = \is_object($administrativeData->listePositionsAdministratives) ? [$administrativeData->listePositionsAdministratives] : $administrativeData->listePositionsAdministratives;
            foreach($listePositionsAdministratives as $listePositionAdministrative) {
                // Convert string to DateTime
                $dateDebutPositionAdministrativeCurrent = new \DateTime(\substr($listePositionAdministrative->dateDebutPositionAdmin, 0, 10));
                $dateFinPositionAdministrativeCurrent   = new \DateTime(\substr($listePositionAdministrative->dateFinReellePositionAdmin, 0, 10));
                // Distinct the affectations between the observation dates
                $when = $dateDebutPositionAdministrativeCurrent <= $startObservationDate ? 'current' : ($dateDebutPositionAdministrativeCurrent <= $endObservationDate ? 'next' : null);
                if (!empty($when)) {
                    $codePositionStatutaire[$when]          = $listePositionAdministrative->codePositionStatutaire;
                    $codePositionAdministrative[$when]      = $listePositionAdministrative->codePositionAdmin;
                    $dateDebutPositionAdministrative[$when] = $dateDebutPositionAdministrativeCurrent;
                    $dateFinPositionAdministrative[$when]   = $dateFinPositionAdministrativeCurrent;
                }
            }
        }
        $this->codePositionStatutaire           = isset($codePositionStatutaire['current'])         ? $codePositionStatutaire['current']            : (isset($codePositionStatutaire['next'])           ? $codePositionStatutaire['next']           : null);
        $this->codePositionAdministrative       = isset($codePositionAdministrative['current'])     ? $codePositionAdministrative['current']        : (isset($codePositionAdministrative['next'])       ? $codePositionAdministrative['next']       : null);
        $this->dateDebutPositionAdministrative  = isset($dateDebutPositionAdministrative['current'])? $dateDebutPositionAdministrative['current']   : (isset($dateDebutPositionAdministrative['next'])  ? $dateDebutPositionAdministrative['next']  : null);
        $this->dateFinPositionAdministrative    = isset($dateFinPositionAdministrative['current'])  ? $dateFinPositionAdministrative['current']     : (isset($dateFinPositionAdministrative['next'])    ? $dateFinPositionAdministrative['next']    : null);
        #endregion

        #region ABSENCE
        $codeAbsence = [];
        $nameAbsence = [];
        if (isset($administrativeData->listeAbsencesConges)) {
            $listeAbsencesConges = \is_object($administrativeData->listeAbsencesConges) ? [$administrativeData->listeAbsencesConges] : $administrativeData->listeAbsencesConges;
            foreach($listeAbsencesConges as $listeAbsenceConges) {
                // Convert string to DateTime
                $dateDebutAbsenceCongeCurrent = new \DateTime(\substr($listeAbsenceConges->dateDebutAbsenceConge, 0, 10));
                $dateFinAbsenceCongeCurrent   = new \DateTime(\substr($listeAbsenceConges->dateFinAbsenceConge, 0, 10));
                // Distinct the affectations between the observation dates
                $when = $dateDebutAbsenceCongeCurrent <= $startObservationDate ? 'current' : ($dateDebutAbsenceCongeCurrent <= $endObservationDate ? 'next' : null);
                if (!empty($when)) {
                    $codeAbsence[$when] = $listeAbsenceConges->codeMotifAbsenceConge;
                    $nameAbsence[$when] = $listeAbsenceConges->libLongMotifAbsenceConge;
                }
            }
        }
        $this->codeAbsence = isset($codeAbsence['current']) ? $codeAbsence['current'] : (isset($codeAbsence['next']) ? $codeAbsence['next'] : null);
        $this->nameAbsence = isset($nameAbsence['current']) ? $nameAbsence['current'] : (isset($nameAbsence['next']) ? $nameAbsence['next'] : null);
        #endregion

        #region MODALITE SERVICE
        $codeTypeModaliteService = [];
        $ratioModaliteService   = [];
        $dateDebutModaliteService= [];
        $dateFinModaliteService  = [];
        if (isset($administrativeData->listeModalitesServices)) {
            $listeModalitesServices = \is_object($administrativeData->listeModalitesServices) ? [$administrativeData->listeModalitesServices] : $administrativeData->listeModalitesServices;
            foreach($listeModalitesServices as $listeModaliteService) {
                // Convert string to DateTime
                $dateDebutModaliteServiceCurrent = new \DateTime(\substr($listeModaliteService->dateDebutModaliteService, 0, 10));
                $dateFinModaliteServiceCurrent   = new \DateTime(\substr($listeModaliteService->dateFinModaliteService, 0, 10));
                // Distinct the affectations between the observation dates
                $when = $dateDebutModaliteServiceCurrent <= $startObservationDate ? 'current' : ($dateDebutModaliteServiceCurrent <= $endObservationDate ? 'next' : null);
                if (!empty($when)) {
                    $codeTypeModaliteService[$when] = $listeModaliteService->codeTypeModaliteService;
                    $ratioModaliteService[$when]    = $listeModaliteService->ratioHeurePresenceTempsPlein;
                    $dateDebutModaliteService[$when]= $dateDebutModaliteServiceCurrent;
                    $dateFinModaliteService[$when]  = $dateFinModaliteServiceCurrent;
                }
            }
        }
        $this->codeTypeModaliteService  = isset($codeTypeModaliteService['current']) ? $codeTypeModaliteService['current']  : (isset($codeTypeModaliteService['next'])  ? $codeTypeModaliteService['next']  : null);
        $this->ratioModaliteService     = isset($ratioModaliteService['current'])    ? $ratioModaliteService['current']     : (isset($ratioModaliteService['next'])     ? $ratioModaliteService['next']     : null);
        $this->dateDebutModaliteService = isset($dateDebutModaliteService['current'])? $dateDebutModaliteService['current'] : (isset($dateDebutModaliteService['next']) ? $dateDebutModaliteService['next'] : null);
        $this->dateFinModaliteService   = isset($dateFinModaliteService['current'])  ? $dateFinModaliteService['current']   : (isset($dateFinModaliteService['next'])   ? $dateFinModaliteService['next']   : null);
        #endregion

    }

}
