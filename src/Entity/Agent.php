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
     * @var string|null
     *
     * @ORM\Column(name="codeEmploiAffectationHierarchique", type="string", length=10, nullable=true)
     */
    private $codeEmploiAffectationHierarchique;

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
     * @ORM\Column(name="codeUOAffectationHierarchique", type="string", length=10, nullable=true)
     */
    private $codeUOAffectationHierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongUOAffectationHierarchique", type="string", length=100, nullable=true)
     */
    private $libLongUOAffectationHierarchique;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDebutAffectationHierarchique", type="date", nullable=true)
     */
    private $dateDebutAffectationHierarchique;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateFinAffectationHierarchique", type="date", nullable=true)
     */
    private $dateFinAffectationHierarchique;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quotiteAffectationHierarchique", type="smallint", nullable=true)
     */
    private $quotiteAffectationHierarchique;

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
     * @ORM\Column(name="mailPro", type="string", length=100, nullable=true)
     */
    private $mailPro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numDossierHarpege", type="string", length=12, nullable=true)
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
     * @ORM\Column(name="codePIP", type="string", length=8, nullable=true)
     */
    private $codePIP;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongPIP", type="string", length=45, nullable=true)
     */
    private $libLongPIP;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeCorps", type="string", length=4, nullable=true)
     */
    private $codeCorps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libCourtCorps", type="string", length=18, nullable=true)
     */
    private $libCourtCorps;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongCorps", type="string", length=45, nullable=true)
     */
    private $libLongCorps;

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
     * @ORM\Column(name="codeEchelon", type="string", length=2, nullable=true)
     */
    private $codeEchelon;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeGroupeHierarchique", type="string", length=2, nullable=true)
     */
    private $codeGroupeHierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongGroupeHierarchique", type="string", length=45, nullable=true)
     */
    private $libLongGroupeHierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="temEnseignantChercheur", type="string", length=1, nullable=true)
     */
    private $temEnseignantChercheur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mailPerso", type="string", length=100, nullable=true)
     */
    private $mailPerso;

    /**
     * @var string|null
     *
     * @ORM\Column(name="temEtat", type="string", length=1, nullable=true)
     */
    private $temEtat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="affectationHierarchique", type="text", length=65535, nullable=true)
     */
    private $affectationHierarchique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="affectationsFonctionnelles", type="text", length=65535, nullable=true)
     */
    private $affectationsFonctionnelles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="affectationsAdresses", type="text", length=65535, nullable=true)
     */
    private $affectationsAdresses;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeEmploiAffectation", type="string", length=10, nullable=true)
     */
    private $codeEmploiAffectation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="categorieEmploiPoste", type="string", length=4, nullable=true)
     */
    private $categorieEmploiPoste;

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
    private $indiceMajore;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codeQualiteStatutaire", type="string", length=1, nullable=true)
     */
    private $codeQualiteStatutaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libLongQualiteStatutaire", type="string", length=45, nullable=true)
     */
    private $libLongQualiteStatutaire;

    #endregion

    #region Getters and Setters

    public function getMatricule(): ?string {
        return $this->matricule;
    }
    public function setMatricule(?string $matricule): self {
        $this->matricule = $matricule;

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

    public function getCodeEmploiAffectationHierarchique(): ?string {
        return $this->codeEmploiAffectationHierarchique;
    }
    public function setCodeEmploiAffectationHierarchique(?string $codeEmploiAffectationHierarchique): self {
        $this->codeEmploiAffectationHierarchique = $codeEmploiAffectationHierarchique;

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

    public function getCodeUOAffectationHierarchique(): ?string {
        return $this->codeUOAffectationHierarchique;
    }
    public function setCodeUOAffectationHierarchique(?string $codeUOAffectationHierarchique): self {
        $this->codeUOAffectationHierarchique = $codeUOAffectationHierarchique;

        return $this;
    }

    public function getLibLongUOAffectationHierarchique(): ?string {
        return $this->libLongUOAffectationHierarchique;
    }
    public function setLibLongUOAffectationHierarchique(?string $libLongUOAffectationHierarchique): self {
        $this->libLongUOAffectationHierarchique = $libLongUOAffectationHierarchique;

        return $this;
    }

    public function getDateDebutAffectationHierarchique(): ?\DateTimeInterface {
        return $this->dateDebutAffectationHierarchique;
    }
    public function setDateDebutAffectationHierarchique(?\DateTimeInterface $dateDebutAffectationHierarchique): self {
        $this->dateDebutAffectationHierarchique = $dateDebutAffectationHierarchique;

        return $this;
    }

    public function getDateFinAffectationHierarchique(): ?\DateTimeInterface {
        return $this->dateFinAffectationHierarchique;
    }
    public function setDateFinAffectationHierarchique(?\DateTimeInterface $dateFinAffectationHierarchique): self {
        $this->dateFinAffectationHierarchique = $dateFinAffectationHierarchique;

        return $this;
    }

    public function getQuotiteAffectationHierarchique(): ?int {
        return $this->quotiteAffectationHierarchique;
    }
    public function setQuotiteAffectationHierarchique(?int $quotiteAffectationHierarchique): self {
        $this->quotiteAffectationHierarchique = $quotiteAffectationHierarchique;

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

    public function getMailPro(): ?string {
        return $this->mailPro;
    }
    public function setMailPro(?string $mailPro): self {
        $this->mailPro = $mailPro;

        return $this;
    }

    public function getNumDossierHarpege(): ?string {
        return $this->numDossierHarpege;
    }
    public function setNumDossierHarpege(?string $numDossierHarpege): self {
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

    public function getCodePIP(): ?string {
        return $this->codePIP;
    }
    public function setCodePIP(?string $codePIP): self {
        $this->codePIP = $codePIP;

        return $this;
    }

    public function getLibLongPIP(): ?string {
        return $this->libLongPIP;
    }
    public function setLibLongPIP(?string $libLongPIP): self {
        $this->libLongPIP = $libLongPIP;

        return $this;
    }

    public function getCodeCorps(): ?string {
        return $this->codeCorps;
    }
    public function setCodeCorps(?string $codeCorps): self {
        $this->codeCorps = $codeCorps;

        return $this;
    }

    public function getLibCourtCorps(): ?string {
        return $this->libCourtCorps;
    }
    public function setLibCourtCorps(?string $libCourtCorps): self {
        $this->libCourtCorps = $libCourtCorps;

        return $this;
    }

    public function getLibLongCorps(): ?string {
        return $this->libLongCorps;
    }
    public function setLibLongCorps(?string $libLongCorps): self {
        $this->libLongCorps = $libLongCorps;

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

    public function getCodeEchelon(): ?string {
        return $this->codeEchelon;
    }
    public function setCodeEchelon(?string $codeEchelon): self {
        $this->codeEchelon = $codeEchelon;

        return $this;
    }

    public function getCodeGroupeHierarchique(): ?string {
        return $this->codeGroupeHierarchique;
    }
    public function setCodeGroupeHierarchique(?string $codeGroupeHierarchique): self {
        $this->codeGroupeHierarchique = $codeGroupeHierarchique;

        return $this;
    }

    public function getLibLongGroupeHierarchique(): ?string {
        return $this->libLongGroupeHierarchique;
    }
    public function setLibLongGroupeHierarchique(?string $libLongGroupeHierarchique): self {
        $this->libLongGroupeHierarchique = $libLongGroupeHierarchique;

        return $this;
    }

    public function getTemEnseignantChercheur(): ?string {
        return $this->temEnseignantChercheur;
    }
    public function setTemEnseignantChercheur(?string $temEnseignantChercheur): self {
        $this->temEnseignantChercheur = $temEnseignantChercheur;

        return $this;
    }

    public function getMailPerso(): ?string {
        return $this->mailPerso;
    }
    public function setMailPerso(?string $mailPerso): self {
        $this->mailPerso = $mailPerso;

        return $this;
    }

    public function getTemEtat(): ?string {
        return $this->temEtat;
    }
    public function setTemEtat(?string $temEtat): self {
        $this->temEtat = $temEtat;

        return $this;
    }

    public function getAffectationHierarchique(): ?string {
        return $this->affectationHierarchique;
    }
    public function setAffectationHierarchique(?string $affectationHierarchique): self {
        $this->affectationHierarchique = $affectationHierarchique;

        return $this;
    }

    public function getAffectationsFonctionnelles(): ?string {
        return $this->affectationsFonctionnelles;
    }
    public function setAffectationsFonctionnelles(?string $affectationsFonctionnelles): self {
        $this->affectationsFonctionnelles = $affectationsFonctionnelles;

        return $this;
    }

    public function getAffectationsAdresses(): ?string {
        return $this->affectationsAdresses;
    }
    public function setAffectationsAdresses(?string $affectationsAdresses): self {
        $this->affectationsAdresses = $affectationsAdresses;

        return $this;
    }

    public function getCodeEmploiAffectation(): ?string {
        return $this->codeEmploiAffectation;
    }
    public function setCodeEmploiAffectation(?string $codeEmploiAffectation): self {
        $this->codeEmploiAffectation = $codeEmploiAffectation;

        return $this;
    }

    public function getCategorieEmploiPoste(): ?string {
        return $this->categorieEmploiPoste;
    }
    public function setCategorieEmploiPoste(?string $categorieEmploiPoste): self {
        $this->categorieEmploiPoste = $categorieEmploiPoste;

        return $this;
    }

    public function getCivilite(): ?bool {
        return $this->civilite;
    }
    public function setCivilite(?bool $civilite): self {
        $this->civilite = $civilite;

        return $this;
    }

    public function getIndiceMajore(): ?int {
        return $this->indiceMajore;
    }
    public function setIndiceMajore(?int $indiceMajore): self {
        $this->indiceMajore = $indiceMajore;

        return $this;
    }

    public function getCodeQualiteStatutaire(): ?string {
        return $this->codeQualiteStatutaire;
    }
    public function setCodeQualiteStatutaire(?string $codeQualiteStatutaire): self {
        $this->codeQualiteStatutaire = $codeQualiteStatutaire;

        return $this;
    }

    public function getLibLongQualiteStatutaire(): ?string {
        return $this->libLongQualiteStatutaire;
    }
    public function setLibLongQualiteStatutaire(?string $libLongQualiteStatutaire): self {
        $this->libLongQualiteStatutaire = $libLongQualiteStatutaire;

        return $this;
    }

    #endregion

    /**
     * Set attributes from response of webservice listAgent
     * @param listAgent object response of webservice 
     */
    public function addListAgentFields($listAgent) {
        
        foreach($listAgent as $attribute => $value) {
            if (\property_exists($this, $attribute)) {
                if (strpos($attribute, 'date') !== false) {
                    $this->$attribute = new \DateTime(substr($value,0,10));
                } else {
                    $this->$attribute = $value;
                }
            }
        }
    }

}
