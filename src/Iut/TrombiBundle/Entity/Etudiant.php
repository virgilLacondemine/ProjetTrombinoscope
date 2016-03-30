<?php

namespace Iut\TrombiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etudiant
 *
 * @ORM\Table(name="etudiant", indexes={@ORM\Index(name="promotion", columns={"promotion"})})
 * @ORM\Entity
 */
class Etudiant {

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=25, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=20, nullable=false)
     */
    private $prenom;

    /**
     * @var integer
     *
     * @ORM\Column(name="noEtudiant", type="integer", nullable=false)
     */
    private $noetudiant;

    /**
     * @var string
     *
     * @ORM\Column(name="url_photo", type="string", length=255, nullable=false)
     */
    private $urlPhoto;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Iut\TrombiBundle\Entity\Promotion
     *
     * @ORM\ManyToOne(targetEntity="Iut\TrombiBundle\Entity\Promotion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="promotion", referencedColumnName="id")
     * })
     */
    private $promotion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Iut\TrombiBundle\Entity\Groupe", inversedBy="idEtudiant")
     * @ORM\JoinTable(name="dans",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_etudiant", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_groupe", referencedColumnName="id")
     *   }
     * )
     */
    private $idGroupe;

    /**
     * Constructor
     */
    public function __construct() {
        $this->idGroupe = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set noetudiant
     *
     * @param integer $noetudiant
     * @return Etudiant
     */
    public function setNoetudiant($noetudiant) {
        $this->noetudiant = $noetudiant;

        return $this;
    }

    /**
     * Get noetudiant
     *
     * @return integer 
     */
    public function getNoetudiant() {
        return $this->noetudiant;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Etudiant
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Etudiant
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Set urlPhoto
     *
     * @param string $urlPhoto
     * @return Etudiant
     */
    public function setUrlPhoto($urlPhoto) {
        $this->urlPhoto = $urlPhoto;

        return $this;
    }

    /**
     * Get urlPhoto
     *
     * @return string 
     */
    public function getUrlPhoto() {
        return $this->urlPhoto;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set promotion
     *
     * @param \Iut\TrombiBundle\Entity\Promotion $promotion
     * @return Etudiant
     */
    public function setPromotion(\Iut\TrombiBundle\Entity\Promotion $promotion = null) {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return \Iut\TrombiBundle\Entity\Promotion 
     */
    public function getPromotion() {
        return $this->promotion;
    }

    /**
     * Add idGroupe
     *
     * @param \Iut\TrombiBundle\Entity\Groupe $idGroupe
     * @return Etudiant
     */
    public function addIdGroupe(\Iut\TrombiBundle\Entity\Groupe $idGroupe) {
        $this->idGroupe[] = $idGroupe;

        return $this;
    }

    /**
     * Remove idGroupe
     *
     * @param \Iut\TrombiBundle\Entity\Groupe $idGroupe
     */
    public function removeIdGroupe(\Iut\TrombiBundle\Entity\Groupe $idGroupe) {
        $this->idGroupe->removeElement($idGroupe);
    }

    /**
     * Get idGroupe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdGroupe() {
        return $this->idGroupe;
    }

}
