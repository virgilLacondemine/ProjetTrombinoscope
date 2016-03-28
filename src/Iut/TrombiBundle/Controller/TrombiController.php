<?php

namespace Iut\TrombiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TrombiController extends Controller {

    /**
     * @Route("/", name="trombi_index")
     */
    public function indexAction() {
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * Methode de génération du menu.
     * 
     * Genere le menu de l'application en fonction des groupes TD/TP
     * de l'année courante.
     */
    public function menuAction() {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $groupeRespository = $em->getRepository('IutTrombiBundle:Groupe');
        $semestreRepository = $em->getRepository('IutTrombiBundle:Semestre');
        $listeSemestre = $semestreRepository->findAll();
        $listeGroupe = $groupeRespository->findAll();
        return $this->render('IutTrombiBundle:Trombi:menu.html.twig', array(
                    'les_semestres' => $listeSemestre,
                    'les_groupes' => $listeGroupe
        ));
    }

    /**
     * Methode d'affichage des trombinoscope.
     * 
     * Affiche le trombinoscope du groupe/semestre d'id $p_idGroupe / p_idSemstre.
     * Affiche le trombinoscope en mode edition.
     * 
     * Deux type d'affichage :
     * -Affichage d'un semestre (parametre $p_idGroupe = null)
     * -Affichage d'un groupe TD/TP (dans le reste des cas)
     * 
     * @param type $p_idGroupe
     * @param type $p_idSemestre
     * @param type $p_modif
     * 
     * @Route("/display/{p_idGroupe}/{p_idSemestre}/{p_modif}", name="display")
     */
    public function displayAction($p_idGroupe, $p_idSemestre, $p_modif) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $semestreRepository = $em->getRepository('IutTrombiBundle:Semestre');
        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
        $etudiantRepository = $em->getRepository('IutTrombiBundle:Etudiant');
        switch ($p_idGroupe) {
            case -1:
                $listeEtudiant = $etudiantRepository->findAll();
                $semestre = $semestreRepository->find($p_idSemestre);
                $listeGroupe = $groupeRepository->findBy(array('idSemestre' => $p_idSemestre));
                $lesEtudiants = $this->trieEtudiantSemestre($listeGroupe, $listeEtudiant);

                $array = array('groupes' => $listeGroupe,
                    'etudiants' => $lesEtudiants,
                    'semestre' => $semestre,
                    'p_idGroupe' => $p_idGroupe,
                    'p_idSemestre' => $p_idSemestre,
                    'p_modif' => $p_modif);

                return $this->render('IutTrombiBundle:Trombi:trombi.html.twig', $array);

            default :
                $groupe = $groupeRepository->find($p_idGroupe);
                $listeGroupe = $groupeRepository->findBy(array('idSemestre' => $p_idSemestre));
                $listeEtudiant = $etudiantRepository->findAll();
                $lesEtudiants = $this->trieEtudiantGroupe($groupe, $listeEtudiant);

                $array = array('groupe' => $groupe,
                    'groupes' => $listeGroupe,
                    'etudiants' => $lesEtudiants,
                    'p_idGroupe' => $p_idGroupe,
                    'p_idSemestre' => $p_idSemestre,
                    'p_modif' => $p_modif);

                return $this->render('IutTrombiBundle:Trombi:trombi.html.twig', $array);
        }
    }

    /**
     * 
     * @Route("/import", name="import")
     */
    public function importEtudiants() {
        if ($_FILES['liste_etudiants']['error'] > 0) {
            $erreur = "Erreur lors du transfert du fichier";
        }
        $target_file = "C://wamp/www/ProjetTrombinoscope/web/" . basename($_FILES['liste_etudiants']['name']);
        $tranfert = move_uploaded_file($_FILES['liste_etudiants']['tmp_name'], $target_file);
        if ($tranfert) {
            $fichier = fopen($target_file, "r+");
            $liste = array();
            $ligne = fgets($fichier);
            $em = $this->getDoctrine()->getManager();
            $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
            $td1 = $groupeRepository->find(1);
            $tp1 = $groupeRepository->find(2);
            while ($ligne = fgets($fichier)) {
                $liste = explode("	", $ligne);
                $etu = new \Iut\TrombiBundle\Entity\Etudiant();
                $etu->setNom($liste[28]);
                $etu->setPrenom($liste[30]);
                $etu->setUrlPhoto("img/photos/default.gif");
                $etu->addIdGroupe($td1);
                $td1->addIdEtudiant($etu);
                $em->persist($etu);
                $em->persist($td1);
                $etu->addIdGroupe($tp1);
                $em->persist($etu);
                $em->flush();
            }
            fclose($fichier);
        } else {
            
        }
        if (file_exists($target_file)) {
            unlink(realpath($target_file));
        }
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/modify", name="modify")
     */
    public function modifyAction() {

        $form_etudiant = array(
            'id' => $_POST['id'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'groupe_td' => $_POST['groupe_td'],
            'groupe_tp' => $_POST['groupe_tp']
        );

        $em = $this->getDoctrine()->getManager();
        $etudiantRepository = $em->getRepository('IutTrombiBundle:Etudiant');
        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
        $new_td = $groupeRepository->find($form_etudiant['groupe_td']);
        $new_tp = $groupeRepository->find($form_etudiant['groupe_tp']);
        $etudiant = $etudiantRepository->find($form_etudiant['id']);
        foreach ($etudiant->getIdGroupe() as $groupeE) {
            $groupeE->removeIdEtudiant($etudiant);
            $etudiant->removeIdGroupe($groupeE);
        }
        $etudiant->setNom($form_etudiant['nom']);
        $etudiant->setPrenom($form_etudiant['prenom']);
        $etudiant->setNom($form_etudiant['nom']);
        $etudiant->addIdGroupe($new_td);
        $new_td->addIdEtudiant($etudiant);
        $new_tp->addIdEtudiant($etudiant);
        $em->persist($etudiant);
        $em->persist($new_td);
        $em->persist($new_tp);
        $etudiant->addIdGroupe($new_tp);
        $em->persist($etudiant);
        $em->flush();

        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/supp/{idEtudiant}", name="supp")
     */
    public function suppressionEtudiantAction($idEtudiant) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $etudiantRepository = $em->getRepository('IutTrombiBundle:Etudiant');
        $etudiant = $etudiantRepository->find($idEtudiant);
        $em->remove($etudiant);
        $em->flush();
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/nextSemestre",name="nextSemestre")
     */
    public function changerSemestreAction() {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
        $etudiantRepository = $em->getRepository('IutTrombiBundle:Etudiant');
        $semestreRepository = $em->getRepository('IutTrombiBundle:Semestre');
        $list_groupe = $groupeRepository->findAll();
        $list_etudiant = $etudiantRepository->findAll();
        $list_semestre = $semestreRepository->findAll();
        foreach (array_reverse($list_semestre) as $semestre) {
            $new_td = $groupeRepository->find(7);
            $new_tp = $groupeRepository->findBy(
                    array('idPere' => $new_td));
            foreach ($list_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $idgroupe) {
                    foreach ($list_groupe as $groupe) {
                        if ($idgroupe->getId() == $groupe->getId()) {
                            if ($groupe->getIdPere() == null) {
                                $groupe->removeIdEtudiant($etudiant);
                                $etudiant->removeIdGroupe($groupe);
                                $etudiant->addIdGroupe($new_td);
                                $new_td->addIdEtudiant($etudiant);
                                $em->persist($etudiant);
                                $em->persist($new_td);
                                $em->persist($groupe);
                                $em->flush();
                            } else {
                                $etudiant->addIdGroupe($new_tp[0]);
                                $new_tp[0]->addIdEtudiant($etudiant);
                                $etudiant->removeIdGroupe($groupe);
                                $groupe->removeIdEtudiant($etudiant);
                                $em->persist($etudiant);
                                $em->persist($new_tp[0]);
                                $em->flush();
                            }
                        }
                    }
                }
            }
        }
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * Methode de filtrage des Etudiants pour un semestre
     * 
     * @param type $p_listeGroupe
     * @param type $p_listeEtudiant
     */
    public function trieEtudiantSemestre($p_listeGroupe, $p_listeEtudiant) {
        foreach ($p_listeGroupe as $groupe) {
            foreach ($p_listeEtudiant as $etudiant) {
                $groupeEtudiant = $etudiant->getIdGroupe();
                if ($groupeEtudiant->contains($groupe)) {
                    $lesEtudiants[] = $etudiant;
                }
            }
        }
        if (isset($lesEtudiants)) {
            $lesEtudiants = array_unique($lesEtudiants, $sort_flags = SORT_REGULAR);
        } else {
            $lesEtudiants = null;
        }
        return $lesEtudiants;
    }

    /**
     * Methode de filtrage des Etudiants pour un groupe TD/TP
     * 
     * @param type $p_groupe
     * @param type $p_listeEtudiant
     */
    public function trieEtudiantGroupe($p_groupe, $p_listeEtudiant) {
        $lesEtudiants = null;
        foreach ($p_listeEtudiant as $etudiant) {
            $groupeEtudiant = $etudiant->getIdGroupe();
            if ($groupeEtudiant->contains($p_groupe)) {
                $lesEtudiants[] = $etudiant;
            }
        }
        if (isset($lesEtudiants)) {
            $lesEtudiants = array_unique($lesEtudiants, $sort_flags = SORT_REGULAR);
        } else {
            $lesEtudiants = null;
        }
        return $lesEtudiants;
    }

}

//AJOUT
