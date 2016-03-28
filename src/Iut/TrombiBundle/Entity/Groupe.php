<?php

namespace Iut\TrombiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe", indexes={@ORM\Index(name="id_semestre", columns={"id_semestre"}), @ORM\Index(name="id_pere", columns={"id_pere"})})
 * @ORM\Entity
 */
class Groupe
{
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=10, nullable=false)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Iut\TrombiBundle\Entity\Groupe
     *
     * @ORM\ManyToOne(targetEntity="Iut\TrombiBundle\Entity\Groupe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pere", referencedColumnName="id")
     * })
     */
    private $idPere;

    /**
     * @var \Iut\TrombiBundle\Entity\Semestre
     *
     * @ORM\ManyToOne(targetEntity="Iut\TrombiBundle\Entity\Semestre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_semestre", referencedColumnName="id")
     * })
     */
    private $idSemestre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Iut\TrombiBundle\Entity\Etudiant", mappedBy="idGroupe")
     */
    private $idEtudiant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idEtudiant = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Groupe
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idPere
     *
     * @param \Iut\TrombiBundle\Entity\Groupe $idPere
     * @return Groupe
     */
    public function setIdPere(\Iut\TrombiBundle\Entity\Groupe $idPere = null)
    {
        $this->idPere = $idPere;

        return $this;
    }

    /**
     * Get idPere
     *
     * @return \Iut\TrombiBundle\Entity\Groupe 
     */
    public function getIdPere()
    {
        return $this->idPere;
    }

    /**
     * Set idSemestre
     *
     * @param \Iut\TrombiBundle\Entity\Semestre $idSemestre
     * @return Groupe
     */
    public function setIdSemestre(\Iut\TrombiBundle\Entity\Semestre $idSemestre = null)
    {
        $this->idSemestre = $idSemestre;

        return $this;
    }

    /**
     * Get idSemestre
     *
     * @return \Iut\TrombiBundle\Entity\Semestre 
     */
    public function getIdSemestre()
    {
        return $this->idSemestre;
    }

    /**
     * Add idEtudiant
     *
     * @param \Iut\TrombiBundle\Entity\Etudiant $idEtudiant
     * @return Groupe
     */
    public function addIdEtudiant(\Iut\TrombiBundle\Entity\Etudiant $idEtudiant)
    {
        $this->idEtudiant[] = $idEtudiant;

        return $this;
    }

    /**
     * Remove idEtudiant
     *
     * @param \Iut\TrombiBundle\Entity\Etudiant $idEtudiant
     */
    public function removeIdEtudiant(\Iut\TrombiBundle\Entity\Etudiant $idEtudiant)
    {
        $this->idEtudiant->removeElement($idEtudiant);
    }

    /**
     * Get idEtudiant
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdEtudiant()
    {
        return $this->idEtudiant;
    }
}
