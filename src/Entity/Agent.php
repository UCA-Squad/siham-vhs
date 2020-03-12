<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agent
 *
 * @ORM\Table(name="agent", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
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

    #endregion

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
        if (isset($personalData->listeNumerosMails)) {
            $listeNumerosMails = \is_object($personalData->listeNumerosMails) ? [$personalData->listeNumerosMails] : $personalData->listeNumerosMails;
            foreach($listeNumerosMails as $numeroMail) {
                if ($numeroMail->codeTypologieNumeroMail == 'MPE')
                    $this->mailPerso = $numeroMail->numeroMail;
                else if ($numeroMail->codeTypologieNumeroMail == 'PPE')
                	$this->portablePerso = $numeroMail->numeroMail;
            }
        }

        //Concatenate agent functions
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
    public function addAdministrativeData($administrativeData) {
        $dateNow = new \DateTime();
        $dateEndSIHAM = new \DateTime('0001-01-01');

        $codeUOAffectationsADR = []; $nameAffectationsADR = []; $quotiteAffectationsADR = [];
        $codeUOAffectationsFUN = []; $nameAffectationsFUN = []; $quotiteAffectationsFUN = [];
        $codeUOAffectationsHIE = NULL; $nameAffectationsHIE = NULL; $quotiteAffectationsHIE = NULL; $dateDebutAffectationsHIE = NULL; $dateFinAffectationsHIE = NULL;
        $codeEmploiAffectation = NULL; $libLongEmploiAffectation = NULL;
        $codePosteAffectation = NULL; $libLongPosteAffectation = NULL;
        $categorieEmploiPoste = NULL;
        if (isset($administrativeData->listeAffectations)) {
            $listeAffectations = \is_object($administrativeData->listeAffectations) ? [$administrativeData->listeAffectations] : $administrativeData->listeAffectations;
            foreach($listeAffectations as $listeAffectation) {
                if ($listeAffectation->codeTypeRattachement == 'ADR') {
                    if (isset($listeAffectation->codeUOAffectation))        $codeUOAffectationsADR[]    = $listeAffectation->codeUOAffectation;
                    if (isset($listeAffectation->libLongCodeUOAffectation)) $nameAffectationsADR[]    = $listeAffectation->libLongCodeUOAffectation;
                    if (isset($listeAffectation->quotiteAffectation))       $quotiteAffectationsADR[] = $listeAffectation->quotiteAffectation;
                } else if ($listeAffectation->codeTypeRattachement == 'FUN') {
                    if (isset($listeAffectation->codeUOAffectation))        $codeUOAffectationsFUN[]    = $listeAffectation->codeUOAffectation;
                    if (isset($listeAffectation->libLongCodeUOAffectation)) $nameAffectationsFUN[]    = $listeAffectation->libLongCodeUOAffectation;
                    if (isset($listeAffectation->quotiteAffectation))       $quotiteAffectationsFUN[] = $listeAffectation->quotiteAffectation;
                } else if ($listeAffectation->codeTypeRattachement == 'HIE') {
                    if (isset($listeAffectation->codeUOAffectation) && empty($codeUOAffectationsHIE)) $codeUOAffectationsHIE = $listeAffectation->codeUOAffectation;
                    if (isset($listeAffectation->libLongCodeUOAffectation) && empty($nameAffectationsHIE)) $nameAffectationsHIE = $listeAffectation->libLongCodeUOAffectation;
                    // Keep the smallest start date
                    if (isset($listeAffectation->dateDebutAffectation)) {
                        $dateDebutAffectationsHIECurrent = new \DateTime(\substr($listeAffectation->dateDebutAffectation,0,10));
                        if (empty($this->dateDebutAffectationsHIE) || $this->dateDebutAffectationsHIE >= $dateDebutAffectationsHIECurrent)
                            $dateDebutAffectationsHIE = $dateDebutAffectationsHIECurrent;
                    }
                    // and the biggest end date
                    if (isset($listeAffectation->dateFinAffectation)) {
                        $dateFinAffectationsHIECurrent = new \DateTime(\substr($listeAffectation->dateFinAffectation,0,10));
                        if (empty($this->dateFinAffectationsHIE) || $this->dateFinAffectationsHIE <= $dateFinAffectationsHIECurrent)
                            $dateFinAffectationsHIE = $dateFinAffectationsHIECurrent;
                    }
                    if (isset($listeAffectation->quotiteAffectation) && empty($quotiteAffectationsHIE)) $quotiteAffectationsHIE = $listeAffectation->quotiteAffectation;
                    if (isset($listeAffectation->codeEmploiAffectation))    $codeEmploiAffectation      = $listeAffectation->codeEmploiAffectation;
                    if (isset($listeAffectation->libLongEmploiAffectation)) $libLongEmploiAffectation   = $listeAffectation->libLongEmploiAffectation;
                    if (isset($listeAffectation->codePosteAffectation))     $codePosteAffectation       = $listeAffectation->codePosteAffectation;
                    if (isset($listeAffectation->libLongPoste))             $libLongPosteAffectation    = $listeAffectation->libLongPoste;
                    if (isset($listeAffectation->categorieEmploiPoste))     $categorieEmploiPoste       = $listeAffectation->categorieEmploiPoste;
                }
            }
        }
        $this->codeUOAffectationsADR = \implode('|', $codeUOAffectationsADR); $this->nameAffectationsADR = \implode('|', $nameAffectationsADR); $this->quotiteAffectationsADR = \implode('|', $quotiteAffectationsADR);
        $this->codeUOAffectationsFUN = \implode('|', $codeUOAffectationsFUN); $this->nameAffectationsFUN = \implode('|', $nameAffectationsFUN); $this->quotiteAffectationsFUN = \implode('|', $quotiteAffectationsFUN);
        $this->codeUOAffectationsHIE = $codeUOAffectationsHIE; $this->nameAffectationsHIE = $nameAffectationsHIE; $this->quotiteAffectationsHIE = $quotiteAffectationsHIE; $this->dateDebutAffectationsHIE =$dateDebutAffectationsHIE; $this->dateFinAffectationsHIE = $dateFinAffectationsHIE;
        $this->codeEmploiAffectation = $codeEmploiAffectation; $this->libLongEmploiAffectation = $libLongEmploiAffectation;
        $this->codePosteAffectation = $codePosteAffectation; $this->libLongPosteAffectation = $libLongPosteAffectation;
        $this->categorieEmploiPoste = $categorieEmploiPoste;

        $codeQualiteStatutaire = NULL;
        $codeGroupeHierarchique = NULL;
        $codeCategory = NULL;
        $codeEchelon = NULL;
        $indiceMajore = NULL;
        $codeCorps = NULL;
        $codeGrade = NULL;
        $temEnseignantChercheur = NULL;
        $organismePrincipal = NULL;
        if (isset($administrativeData->listeCarrieres)) {
            $listeCarrieres = \is_object($administrativeData->listeCarrieres) ? [$administrativeData->listeCarrieres] : $administrativeData->listeCarrieres;
            foreach($listeCarrieres as $listeCarriere) {
                // only date effect so keep on saving the last
                if (isset($listeCarriere->codeQualiteStatutaire))   $codeQualiteStatutaire = $listeCarriere->codeQualiteStatutaire;
                if (isset($listeCarriere->codeGroupeHierarchique))  $codeGroupeHierarchique = $listeCarriere->codeGroupeHierarchique;
                if (isset($listeCarriere->libCourtCategorieFP))     $codeCategory = $listeCarriere->libCourtCategorieFP;
                if (isset($listeCarriere->codeEchelon))             $codeEchelon = $listeCarriere->codeEchelon;
                if (isset($listeCarriere->indiceMajore))            $indiceMajore = $listeCarriere->indiceMajore;
                if (isset($listeCarriere->codeCorps))               $codeCorps = $listeCarriere->codeCorps;
                if (isset($listeCarriere->codeGrade))               $codeGrade = $listeCarriere->codeGrade;
                if (isset($listeCarriere->temEnseignantChercheur))  $temEnseignantChercheur = $listeCarriere->temEnseignantChercheur;
                if (isset($listeCarriere->organismePrincipal))      $organismePrincipal = $listeCarriere->organismePrincipal;
            }
        }
        $this->codeQualiteStatutaire = $codeQualiteStatutaire;
        $this->codeGroupeHierarchique = $codeGroupeHierarchique;
        $this->codeCategory = $codeCategory;
        $this->codeEchelon = $codeEchelon;
        $this->indiceMajore = $indiceMajore;
        $this->codeCorps = $codeCorps;
        $this->codeGrade = $codeGrade;
        $this->temEnseignantChercheur = $temEnseignantChercheur;
        $this->organismePrincipal = $organismePrincipal;

        $codePIP = NULL;
        if (isset($administrativeData->listePIP)) {
            $listePIPs = \is_object($administrativeData->listePIP) ? [$administrativeData->listePIP] : $administrativeData->listePIP;
            foreach($listePIPs as $listePIP) {
                // only date effect so keep on saving the last
                if (isset($listePIP->codePIP)) $codePIP = $listePIP->codePIP;
            }
        }
        $this->codePIP = $codePIP;

        $codePositionStatutaire = NULL;
        $codePositionAdministrative = NULL;
        $dateDebutPositionAdministrative = NULL;
        $dateFinPositionAdministrative = NULL;
        if (isset($administrativeData->listePositionsAdministratives)) {
            $listePositionsAdministratives = \is_object($administrativeData->listePositionsAdministratives) ? [$administrativeData->listePositionsAdministratives] : $administrativeData->listePositionsAdministratives;
            foreach($listePositionsAdministratives as $listePositionAdministrative) {
                // keep the first start date and the related end date
                if (isset($listePositionAdministrative->codePositionStatutaire)
                && isset($listePositionAdministrative->codePositionAdmin)
                && isset($listePositionAdministrative->dateDebutPositionAdmin)
                && isset($listePositionAdministrative->dateFinReellePositionAdmin)
                ) {
                    $dateDebutPositionAdministrativeCurrent = new \DateTime(\substr($listePositionAdministrative->dateDebutPositionAdmin,0,10));
                    $dateFinPositionAdministrativeCurrent = new \DateTime(\substr($listePositionAdministrative->dateFinReellePositionAdmin,0,10));

                    if ($dateDebutPositionAdministrativeCurrent <= $dateNow && ($dateFinPositionAdministrativeCurrent >= $dateNow || $dateFinPositionAdministrativeCurrent == $dateEndSIHAM)) {
                        $codePositionStatutaire = $listePositionAdministrative->codePositionStatutaire;
                        $codePositionAdministrative = $listePositionAdministrative->codePositionAdmin;
                        $dateDebutPositionAdministrative = $dateDebutPositionAdministrativeCurrent;
                        $dateFinPositionAdministrative = $dateFinPositionAdministrativeCurrent;
                    }
                }
            }
        }
        $this->codePositionStatutaire = $codePositionStatutaire;
        $this->codePositionAdministrative = $codePositionAdministrative;
        $this->dateDebutPositionAdministrative = $dateDebutPositionAdministrative;
        $this->dateFinPositionAdministrative = $dateFinPositionAdministrative;

        $codeAbsence = NULL;
        $nameAbsence = NULL;
        if (isset($administrativeData->listeAbsencesConges)) {
            $listeAbsencesConges = \is_object($administrativeData->listeAbsencesConges) ? [$administrativeData->listeAbsencesConges] : $administrativeData->listeAbsencesConges;
            foreach($listeAbsencesConges as $listeAbsenceConges) {
                // keep the first start date and the related end date
                if (isset($listeAbsenceConges->codeMotifAbsenceConge)
                && isset($listeAbsenceConges->libLongMotifAbsenceConge)
                && isset($listeAbsenceConges->DateDebutAbsenceConge)
                && isset($listeAbsenceConges->DateFinAbsenceConge)
                ) {
                    $dateDebutAbsenceCongeCurrent = new \DateTime(\substr($listeAbsenceConges->dateDebutAbsenceConge,0,10));
                    $dateFinAbsenceCongeCurrent = new \DateTime(\substr($listeAbsenceConges->dateFinAbsenceConge,0,10));

                    if ($dateDebutAbsenceCongeCurrent <= $dateNow && ($dateFinAbsenceCongeCurrent >= $dateNow || $dateFinAbsenceCongeCurrent == $dateEndSIHAM)) {
                        $codeAbsence = $listeAbsenceConges->codeMotifAbsenceConge;
                        $nameAbsence = $listeAbsenceConges->libLongMotifAbsenceConge;
                    }
                }
            }
        }
        $this->codeAbsence = $codeAbsence;
        $this->nameAbsence = $nameAbsence;
    }

}
