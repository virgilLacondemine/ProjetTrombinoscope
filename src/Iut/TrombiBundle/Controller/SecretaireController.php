<?php

namespace Iut\TrombiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecretaireController extends Controller {

    /**
     * Affiche la page d'accueil
     * @Route("/secretaire", name="trombi_index")
     */
    public function indexAction() {
        return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
    }

    /**
     * Methode de génération du menu.
     *
     * Genere le menu de l'application en fonction des groupes TD/TP
     */
    public function menuAction() {
        return $this->render('IutTrombiBundle:Secretaire:menu.html.twig', array(
                    'les_semestres' => $this->getSemestreRepo()->findAll(),
                    'les_groupes' => $this->getGroupeRepo()->findAll(),
                    'promotions' => $this->getPromotionRepo()->findAll()
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
     * @Route("/secretaire/display/{p_idGroupe}/{p_idSemestre}/{p_modif}", name="display")
     */
    public function displayAction($p_idGroupe, $p_idSemestre, $p_modif) {
        $semestreRepository = $this->getSemestreRepo();
        $groupeRepository = $this->getGroupeRepo();
        $etudiantRepository = $this->getEtudiantRepo();
        //On regarde si on doit afficher un semstre ou un groupe TD/TP
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

                return $this->render('IutTrombiBundle:Secretaire:trombi.html.twig', $array);

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

                return $this->render('IutTrombiBundle:Secretaire:trombi.html.twig', $array);
        }
    }

    /**
     * Affichage des groupes pour l'édition
     * 
     * @Route("/secretaire/displayGroupe", name="displayGroupe")
     */
    public function displayGroupeAction() {

        $array = array(
            'groupes' => $this->getGroupeRepo()->findAll(),
            'semestres' => $this->getSemestreRepo()->findAll()
        );

        return $this->render('IutTrombiBundle:Secretaire:editionGroupe.html.twig', $array);
    }

    /**
     * Affichage de l'archive en fonction des promotions.
     * 
     * @Route("/secretaire/displayArchive", name="displayArchive")
     */
    public function displayArchiveAction() {

        $array = array(
            'etudiants' => $this->getEtudiantRepo()->findAll(),
            'promotions' => $this->getPromotionRepo()->findAll()
        );

        return $this->render('IutTrombiBundle:Secretaire:archive.html.twig', $array);
    }

    /**
     * Affichage des étudiants selon les groupes pour un modification multiple.
     * 
     * @Route("/secretaire/displayMulti", name="displayMulti")
     */
    public function displayMultiAction() {

        $array = array(
            'groupes' => $this->getGroupeRepo()->findAll(),
            'semestres' => $this->getSemestreRepo()->findAll(),
            'etudiants' => $this->getEtudiantRepo()->findAll());

        return $this->render('IutTrombiBundle:Secretaire:ajoutEtuGroupe.html.twig', $array);
    }

    /**
     * Recherche un étudiant par nom ou prenom.
     * 
     * @Route("/secretaire/search", name="search")
     */
    public function searchStudentAction() {
        //On récupère le contenu du champ de recherche
        $search = $_POST['recherche'];

        $etudiants = array();

        //On récupère les étudiants dont le nom correspond à la recherche
        $etudiants += $this->getEtudiantRepo()->findBy(array(
            'nom' => $search
        ));
        //On récupère les étudiants dont le prenon correspond à la recherche
        $etudiants += $this->getEtudiantRepo()->findBy(array(
            'prenom' => $search
        ));

        $array = array(
            'etudiants' => $etudiants,
            'groupes' => $this->getGroupeRepo()->findAll()
        );

        return $this->render('IutTrombiBundle:Secretaire:searchRender.html.twig', $array);
    }

    /**
     * Création d'étudiant depuis une liste Apogee.
     * Créer aussi une promotion en fonction de l'année d'inscription de l'étudiant
     * 
     * @Route("/secretaire/import", name="import")
     */
    public function importEtudiants() {
        if ($_FILES['liste_etudiants']['error'] > 0) {
            $erreur = "Erreur lors du transfert du fichier";
        }
        $target_file = $this->getWebDir() . basename($_FILES['liste_etudiants']['name']);
        $tranfert = move_uploaded_file($_FILES['liste_etudiants']['tmp_name'], $target_file);
        //Si l'upload est effectué
        if ($tranfert) {
            $fichier = fopen($target_file, "r+");
            $liste = array();
            $ligne = fgets($fichier);
            $set_promo = FALSE;
            $em = $this->getEM();
            $groupeRepository = $this->getGroupeRepo();
            //On récupère un groupe de td par défaut.
            $td = $groupeRepository->findOneBy(array(
                'idSemestre' => $this->getSemestreRepo()->find(1),
                'idPere' => null
            ));
            //On récupère un groupe de tp par défaut.
            $tp = $groupeRepository->findOneBy(array(
                'idSemestre' => $this->getSemestreRepo()->find(1),
                'idPere' => $td
            ));
            $promo = new \Iut\TrombiBundle\Entity\Promotion();
            while ($ligne = fgets($fichier)) {
                $liste = explode("	", $ligne);
                //On créer une nouvelle promotion.
                if (!$set_promo) {
                    $promo->setAnnee($liste[0] + 2);
                    $em->persist($promo);
                    $set_promo = TRUE;
                }
                $etu = new \Iut\TrombiBundle\Entity\Etudiant();
                $etu->setNom($liste[28]);
                $etu->setPrenom($liste[30]);
                $etu->setNoetudiant($liste[25]);
                $etu->setUrlPhoto("img/photos/default.gif");
                $etu->setPromotion($promo);
                $etu->addIdGroupe($td);
                $td->addIdEtudiant($etu);
                $em->persist($etu);
                $em->persist($td);
                $etu->addIdGroupe($tp);
                $em->persist($etu);
                $em->flush();
            }
            fclose($fichier);
        }
        //On supprime la liste Apogee du répertoire.
        if (file_exists($target_file)) {
            unlink(realpath($target_file));
        }
        return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
    }

    /**
     * Création d'un groupe TD/TP
     * 
     * @Route("/secretaire/addGrp", name="addGrp")
     */
    public function addGrpAction() {

        $form_grp = array(
            'libelle' => $_POST['libelle'],
            'idSemestre' => $_POST['idSemestre'],
            'idPere' => $_POST['idPere'],
        );

        $groupe = new \Iut\TrombiBundle\Entity\Groupe();
        $groupe->setLibelle($form_grp['libelle']);
        $groupe->setIdSemestre($this->getSemestreRepo()->find($form_grp['idSemestre']));
        $groupe->setIdPere($this->getGroupeRepo()->find($form_grp['idPere']));
        $em->persist($groupe);
        $em->flush();

        return $this->redirectToRoute('displayGroupe');
    }

    /**
     * Modifie un étudiant.
     * 
     * @Route("/secretaire/modify", name="modify")
     */
    public function modifyAction() {

        $form_etudiant = array(
            'id' => $_POST['id'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'no_etudiant' => $_POST['no_etudiant'],
            'groupe_td' => $_POST['groupe_td'],
            'groupe_tp' => $_POST['groupe_tp']
        );

        $em = $this->getEM();
        $new_td = $this->getGroupeRepo()->find($form_etudiant['groupe_td']);
        $new_tp = $this->getGroupeRepo()->find($form_etudiant['groupe_tp']);
        $etudiant = $this->getEtudiantRepo()->find($form_etudiant['id']);

        foreach ($etudiant->getIdGroupe() as $groupeE) {
            $groupeE->removeIdEtudiant($etudiant);
            $etudiant->removeIdGroupe($groupeE);
        }

        $etudiant->setNom($form_etudiant['nom']);
        $etudiant->setPrenom($form_etudiant['prenom']);
        $etudiant->setNoEtudiant($form_etudiant['no_etudiant']);
        $etudiant->addIdGroupe($new_td);
        $new_td->addIdEtudiant($etudiant);
        $new_tp->addIdEtudiant($etudiant);
        $em->persist($etudiant);
        $em->persist($new_td);
        $em->persist($new_tp);
        $etudiant->addIdGroupe($new_tp);
        $em->persist($etudiant);
        $em->flush();

        return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
    }

    /**
     * Modifie le groupe de TD/TP de plusieur étudiant.
     * 
     * @Route("/secretaire/modifMultiEtuGrp", name="modifMultiEtuGrp")
     */
    public function modifMultiEtuGrpAction() {

        $form = array(
            'groupeTD' => $_POST['groupeTD'],
            'groupeTP' => $_POST['groupeTP'],
            'etudiants' => $_POST['lesEtudiants'],
        );

        $em = $this->getEM();
        $groupeTD = $this->getGroupeRepo()->find($form['groupeTD']);
        $groupeTP = $this->getGroupeRepo()->find($form['groupeTP']);
        foreach ($form['etudiants'] as $etudiant) {
            $unEtu = $this->getEtudiantRepo()->find($etudiant);
            foreach ($unEtu->getIdGroupe() as $groupeE) {
                $groupeE->removeIdEtudiant($unEtu);
                $unEtu->removeIdGroupe($groupeE);
            }
            $unEtu = $this->getEtudiantRepo()->find($etudiant);
            $unEtu->addIdGroupe($groupeTD);
            $unEtu->addIdGroupe($groupeTP);
            $em->persist($unEtu);
            $em->flush();
        }
        return $this->redirectToRoute('displayMulti');
    }

    /**
     * Modification d'un groupe TD/TP.
     * 
     * @Route("/secretaire/modifGrp", name="modifGrp")
     */
    public function modifGrpAction() {

        $form_groupe = array(
            'id' => $_POST['id'],
            'libelle' => $_POST['libelle'],
            'idSemestre' => $_POST['idSemestre'],
            'idPere' => $_POST['idPere']
        );

        $em = $this->getEM();
        $new_semestre = $this->getSemestreRepo()->find($form_groupe['idSemestre']);
        $new_pere = $this->getGroupeRepo()->find($form_groupe['idPere']);
        $groupe = $this->getGroupeRepo()->find($form_groupe['id']);

        $groupe->setLibelle($form_groupe['libelle']);
        $groupe->setIdSemestre($new_semestre);
        $groupe->setIdPere($new_pere);
        $em->persist($groupe);
        $em->flush();

        return $this->redirectToRoute('displayGroupe');
    }

    /**
     * Supprime un étudiant.
     * 
     * @Route("/secretaire/supp/{idEtudiant}", name="supp")
     */
    public function suppressionEtudiantAction($idEtudiant) {
        $em = $this->getEM();
        $etudiant = $this->getEtudiantRepo()->find($idEtudiant);
        $em->remove($etudiant);
        $em->flush();
        return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
    }

    /**
     * Supprime un groupe TD/TP.
     * 
     * @Route("/secretaire/suppGrp/{idGroupe}", name="suppGrp")
     */
    public function suppressionGroupeAction($idGroupe) {
        $em = $this->getEM();
        $groupe = $this->getGroupeRepo()->find($idGroupe);
        $em->remove($groupe);
        $em->flush();
        return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
    }

    /**
     * Ajoute un étudiant.
     * 
     * @Route("/secretaire/addStudent", name="addStudent")
     */
    public function addStudentAction() {

        $form_etudiant = array(
            'id' => $_POST['id'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'no_etudiant' => $_POST['no_etudiant'],
            'groupe_td' => $_POST['groupe_td'],
            'groupe_tp' => $_POST['groupe_tp'],
            'promotion' => $_POST['promotion']
        );

        $img = array(
            'nom' => $_FILES['img']['name'],
            'type' => $_FILES['img']['type'],
            'taille' => $_FILES['img']['size'],
            'dimensions' => getimagesize($_FILES['img']['tmp_name']),
            'location_tmp' => $_FILES['img']['tmp_name']
        );

        if ($this->checkImg($img)) {
            $urlPhoto = $this->uploadImg($form_etudiant['nom'], $form_etudiant['prenom'], $img);
            $em = $this->getEM();
            $new_td = $this->getGroupeRepo()->find($form_etudiant['groupe_td']);
            $new_tp = $this->getGroupeRepo()->find($form_etudiant['groupe_tp']);
            $etudiant = new \Iut\TrombiBundle\Entity\Etudiant();
            $etudiant->setNom($form_etudiant['nom']);
            $etudiant->setPrenom($form_etudiant['prenom']);
            $etudiant->setNoetudiant($form_etudiant['no_etudiant']);
            $etudiant->setUrlPhoto($urlPhoto);
            $etudiant->setPromotion($this->getPromotionRepo()->find($form_etudiant['promotion']));
            $etudiant->addIdGroupe($new_td);
            $new_td->addIdEtudiant($etudiant);
            $new_tp->addIdEtudiant($etudiant);
            $em->persist($etudiant);
            $em->persist($new_td);
            $em->persist($new_tp);
            $etudiant->addIdGroupe($new_tp);
            $em->persist($etudiant);
            $em->flush();

            return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
        } else {
            return $this->render('IutTrombiBundle:Secretaire:imgError.html.twig');
        }
    }

    /**
     * Passe les étudiants d'un semestre au suivant.
     * Si les étudiants sont au dernier semestre, on ne les lie à aucun groupe.
     * 
     * @Route("/secretaire/nextSemestre",name="nextSemestre")
     */
    public function changerSemestreAction() {
        $em = $this->getEM();
        $list_etudiant = $this->getEtudiantRepo()->findAll();
        $list_semestre = $this->getSemestreRepo()->findAll();
        foreach (array_reverse($list_semestre) as $semestre) {
            $list_groupe = $this->getGroupeRepo()->findBy(array(
                'idSemestre' => $semestre
            ));
            if ($semestre->getId() == 4) {
                $nextSem = $semestre;
            } else {
                $new_td = $this->getGroupeRepo()->findBy(array(
                    'idSemestre' => $nextSem
                ));
                $new_tp = $this->getGroupeRepo()->findBy(array(
                    'idPere' => $new_td
                ));
                $nextSem = $semestre;
            }
            foreach ($list_etudiant as $etudiant) {
                foreach ($list_groupe as $groupe) {
                    $etu_idGroupe = $etudiant->getIdGroupe();
                    if ($etu_idGroupe->contains($groupe)) {
                        if ($groupe->getIdPere() == null) {
                            $groupe->removeIdEtudiant($etudiant);
                            $etudiant->removeIdGroupe($groupe);
                            if (isset($new_td)) {
                                $etudiant->addIdGroupe($new_td[0]);
                                $new_td[0]->addIdEtudiant($etudiant);
                            }
                            $em->flush();
                        } else {
                            $etudiant->removeIdGroupe($groupe);
                            $groupe->removeIdEtudiant($etudiant);
                            if (isset($new_tp)) {
                                $etudiant->addIdGroupe($new_tp[0]);
                                $new_tp[0]->addIdEtudiant($etudiant);
                            }
                            $em->flush();
                        }
                    }
                }
            }
        }
        return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
    }

    /**
     * Créer une feuille d'émargement PDF en fonction du Trombinoscope visualisé.
     * 
     * @Route("/secretaire/exporterEmargementPDF/{p_idGroupe}/{p_idSemestre}",name="exporterEmargementPDF")
     */
    public function exporterEmargementPDFAction($p_idGroupe, $p_idSemestre) {
        $etudiants = $this->getEtudiantRepo()->findAll();

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('arial', '', 10);
        if ($p_idGroupe == -1) {
            $pdf->Cell(160, 7, 'Feuille d\'emargement - ' . $this->getSemestreRepo()->find($p_idSemestre)->getLibelle());
        } else {
            $pdf->Cell(160, 7, 'Feuille d\'emargement - ' . $this->getSemestreRepo()->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $this->getGroupeRepo()->find($p_idGroupe)->getLibelle());
        }
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(80, 7, 'Enseignant :', 'LT');
        $pdf->Cell(80, 7, 'Date :', 'RT');
        $pdf->Ln();
        $pdf->Cell(160, 7, '', 'LR');
        $pdf->Ln();
        $pdf->Cell(80, 7, 'Matiere :', 'L');
        $pdf->Cell(80, 7, 'Horaire :', 'R');
        $pdf->Ln();
        $pdf->Cell(160, 7, '', 'LRB');
        $pdf->Ln();
        $pdf->Ln();
        if ($p_idGroupe == -1) {
            $semestre = $this->getSemestreRepo()->find($p_idSemestre);
            $groupes = $this->getGroupeRepo()->findBy(array(
                'idSemestre' => $semestre
            ));
            $liste_etudiant = $this->trieEtudiantSemestre($groupes, $etudiants);
            //En-tête
            $pdf->Cell(15, 5, 'Effectif :');
            $pdf->Cell(10, 5, count($liste_etudiant));
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell(21, 7, 'No Etudiant', 1);
            $pdf->Cell(70, 7, 'Nom Prenom', 1);
            $pdf->Cell(10, 7, 'TD', 1);
            $pdf->Cell(10, 7, 'TP', 1);
            $pdf->Cell(40, 7, 'Emargement', 1);
            $pdf->Ln();
            foreach ($liste_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                $pdf->Cell(21, 5, $etudiant->getNoEtudiant(), 1);
                $pdf->Cell(70, 5, $etudiant->getNom() . '  ' . $etudiant->getPrenom(), 1);
                $pdf->Cell(10, 5, $td->getLibelle(), 1);
                $pdf->Cell(10, 5, $tp->getLibelle(), 1);
                $pdf->Cell(40, 5, ' ', 1);
                $pdf->Ln();
            }
            $pdf->Output('D', 'feuille_emargement_' . $semestre->getLibelle() . '.pdf', true);
        } else {
            $groupe = $this->getGroupeRepo()->find($p_idGroupe);
            $liste_etudiant = $this->trieEtudiantGroupe($groupe, $etudiants);
            $pdf->Cell(15, 5, 'Effectif :');
            $pdf->Cell(10, 5, count($liste_etudiant));
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell(21, 7, 'No Etudiant', 1);
            $pdf->Cell(70, 7, 'Nom Prenom', 1);
            $pdf->Cell(10, 7, 'TD', 1);
            $pdf->Cell(10, 7, 'TP', 1);
            $pdf->Cell(40, 7, 'Emargement', 1);
            $pdf->Ln();
            foreach ($liste_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                $pdf->Cell(21, 5, $etudiant->getNoEtudiant(), 1);
                $pdf->Cell(70, 5, $etudiant->getNom() . '  ' . $etudiant->getPrenom(), 1);
                $pdf->Cell(10, 5, $td->getLibelle(), 1);
                $pdf->Cell(10, 5, $tp->getLibelle(), 1);
                $pdf->Cell(40, 5, ' ', 1);
                $pdf->Ln();
            }
            $pdf->Output('D', 'feuille_emargement_' . $groupe->getLibelle() . '.pdf', true);
        }
        return $this->render('IutTrombiBundle:Secretaire:index.html.twig');
    }

    /**
     * Créer un trombinoscope PDF du trombinoscope visualisé.
     * 
     * @Route("/secretaire/exporterTrombiPDF/{p_idGroupe}/{p_idSemestre}",name="exporterTrombiPDF")
     */
    public function exporterTrombiPDFAction($p_idGroupe, $p_idSemestre) {
        $etudiants = $this->getEtudiantRepo()->findAll();

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('arial', '', 10);
        $x = 22;
        $y = 35;
        if ($p_idGroupe == -1) {
            $pdf->Cell(160, 7, 'Trombinoscope - ' . $this->getSemestreRepo()->find($p_idSemestre)->getLibelle());
        } else {
            $pdf->Cell(160, 7, 'Trombinoscope - ' . $this->getSemestreRepo()->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $this->getGroupeRepo()->find($p_idGroupe)->getLibelle());
        }
        $pdf->Ln();
        $pdf->Ln();
        if ($p_idGroupe == -1) {
            $semestre = $this->getSemestreRepo()->find($p_idSemestre);
            $groupes = $this->getGroupeRepo()->findBy(array(
                'idSemestre' => $semestre
            ));
            $liste_etudiant = $this->trieEtudiantSemestre($groupes, $etudiants);
            foreach ($liste_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                $pdf->Image($this->getWebDir() . $etudiant->getUrlPhoto(), $x, $y, 30, 30);
                $pdf->Text($x, $y + 35, $etudiant->getNom());
                $pdf->Text($x, $y + 40, $etudiant->getPrenom());
                $pdf->Text($x, $y + 45, $td->getLibelle());
                $pdf->Text($x + 23, $y + 45, $tp->getLibelle());
                $pdf->Rect($x - 1, $y - 1, 32, 47);
                if ($x >= 157) {
                    $x = 22;
                    if ($y >= 235) {
                        $pdf->AddPage();
                        $pdf->Cell(160, 7, 'Trombinoscope - ' . $this->getSemestreRepo()->find($p_idSemestre)->getLibelle());
                        $y = -10;
                    }
                    $y += 50;
                } else {
                    $x += 45;
                }
            }
            $pdf->Output('D', 'trombinoscope_' . $semestre->getLibelle() . '.pdf', true);
        } else {
            $groupe = $this->getGroupeRepo()->find($p_idGroupe);
            $liste_etudiant = $this->trieEtudiantGroupe($groupe, $etudiants);
            foreach ($liste_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                $pdf->Image($this->getWebDir() . $etudiant->getUrlPhoto(), $x, $y, 30, 30);
                $pdf->Text($x, $y + 35, $etudiant->getNom());
                $pdf->Text($x, $y + 40, $etudiant->getPrenom());
                $pdf->Text($x, $y + 45, $td->getLibelle());
                $pdf->Text($x + 23, $y + 45, $tp->getLibelle());
                $pdf->Rect($x - 1, $y - 1, 32, 47);
                if ($x >= 157) {
                    $x = 22;
                    if ($y >= 235) {
                        $pdf->AddPage();
                        $pdf->Cell(160, 7, 'Trombinoscope - ' . $this->getSemestreRepo()->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $this->getGroupeRepo()->find($p_idGroupe)->getLibelle());
                        $y = -10;
                    }
                    $y += 50;
                } else {
                    $x += 45;
                }
            }
            $pdf->Output('D', 'trombinoscope_' . $groupe->getLibelle() . '.pdf', true);
        }
    }

    /**
     * Créer une liste d'émargement Excel du trombinoscope visualisé.
     * 
     * @Route("/secretaire/ExportExcelListe/{p_idGroupe}/{p_idSemestre}",name="exportExcelListe")
     * @param type $p_idGroupe
     * @param type $p_idSemestre
     */
    public function exportExcelListe($p_idGroupe, $p_idSemestre) {
        $etudiantRepository = $this->getEtudiantRepo();
        $groupeRepository = $this->getGroupeRepo();
        $semestreRepository = $this->getSemestreRepo();
        $etudiants = $etudiantRepository->findAll();
        
        
        $listeExcel = new \PHPExcel();
        
        
        $listeExcel->getProperties()->setCreator("IUT de Valence")
                                    ->setTitle("Feuille d'émargement");
     
        $sheet = $listeExcel->getActiveSheet();
        if ($p_idGroupe == -1) {
            $sheet->setCellValue('A1','Feuille d\'émargement - ' . $semestreRepository->find($p_idSemestre)->getLibelle());
        } else {
            $sheet->setCellValue('A1', 'Feuille d\'emargement - ' . $semestreRepository->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $groupeRepository->find($p_idGroupe)->getLibelle());
        }
        
        
        $sheet->setCellValue('B4','Enseignant :');
        $sheet->setCellValue('E4', 'Date :');
        $sheet->setCellValue('B6', 'Matiere :');
        $sheet->setCellValue('E6', 'Horaire :');
            
        $sheet->setCellValue('B12', 'No Etudiant');
        $sheet->setCellValue('C12', 'Nom Prenom');
        $sheet->setCellValue('D12', 'TD');
        $sheet->setCellValue('E12', 'TP');
        $sheet->setCellValue('F12', 'Emargement');
        
        $sheet->getColumnDimension('C')->setWidth(28);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        
        $sheet->getStyle('B12')->getFont()
            ->applyFromArray(array(
                'bold'=>true,
                'size'=>12));
        
        $sheet->getStyle('C12')->getFont()
            ->applyFromArray(array(
                'bold'=>true,
                'size'=>12));
        
        $sheet->getStyle('D12')->getFont()
            ->applyFromArray(array(
                'bold'=>true,
                'size'=>12));
        
        $sheet->getStyle('E12')->getFont()
            ->applyFromArray(array(
                'bold'=>true,
                'size'=>12));
        
        $sheet->getStyle('F12')->getFont()
            ->applyFromArray(array(
                'bold'=>true,
                'size'=>12));
            
        $i = 13;
        if ($p_idGroupe == -1) {
            $semestre = $semestreRepository->find($p_idSemestre);
            $groupes = $groupeRepository->findBy(array(
                'idSemestre' => $semestre
            ));
            $liste_etudiant = $this->trieEtudiantSemestre($groupes, $etudiants);
            $sheet->setCellValue('B9', 'Effetif : '.count($liste_etudiant));
            
            foreach ($liste_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                $sheet->setCellValue('B'.$i, $etudiant->getNoEtudiant());
                $sheet->setCellValue('C'.$i, $etudiant->getNom() . '  ' . $etudiant->getPrenom());
                $sheet->setCellValue('D'.$i, $td->getLibelle());
                $sheet->setCellValue('E'.$i, $tp->getLibelle());
                
                
                $sheet->getStyle('B'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                
                $sheet->getStyle('C'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                
                $sheet->getStyle('D'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                
                $sheet->getStyle('E'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                
                $sheet->getStyle('F'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                $i++;
            }
            $objWriter = new \PHPExcel_Writer_Excel2007($listeExcel);
        
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition:inline;filename=Feuille d\'émargement - '. $semestre->getLibelle() .'.xlsx ');
            $objWriter->save('php://output');
            
        } else {
            $groupe = $groupeRepository->find($p_idGroupe);
            
            $liste_etudiant = $this->trieEtudiantGroupe($groupe, $etudiants);
            $sheet->setCellValue('B9', 'Effetif : '.count($liste_etudiant));
           
            foreach ($liste_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                
                $sheet->setCellValue('B'.$i, $etudiant->getNoEtudiant());
                $sheet->setCellValue('C'.$i, $etudiant->getNom() . '  ' . $etudiant->getPrenom());
                $sheet->setCellValue('D'.$i, $td->getLibelle());
                $sheet->setCellValue('E'.$i, $tp->getLibelle());
                
                $sheet->getStyle('B'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
    			)
                    )
                );
                
                $sheet->getStyle('C'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                
                $sheet->getStyle('D'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                
                $sheet->getStyle('E'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                            )
                        )
                    )
                );
                
                $sheet->getStyle('F'.$i)->getBorders()->applyFromArray(
    		array('allborders' => array(
    			'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    			'color' => array(
    				'rgb' => '000000')
                            )
                    )
                );
                $i++;
            }
            $objWriter = new \PHPExcel_Writer_Excel2007($listeExcel);
        
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition:inline;filename=Feuille d\'émargement - '.$groupe->getLibelle().'.xlsx ');
            $objWriter->save('php://output');
        }
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }
    
    
        /**
     * @Route("/secretaire/exportExcelTrombi/{p_idGroupe}/{p_idSemestre}",name="exportExcelTrombi")
     * @param type $p_groupe
     * @param type $p_listeEtudiant
     * 
     */
    public function exportExcelTrombi($p_idGroupe, $p_idSemestre) {
        
        $etudiantRepository = $this->getEtudiantRepo();
        $groupeRepository = $this->getGroupeRepo();
        $semestreRepository = $this->getSemestreRepo();
        $etudiants = $etudiantRepository->findAll();
        
        $trombiExcel = new \PHPExcel();
        $trombiExcel->getProperties()->setCreator("IUT de Valence")
                                    ->setTitle("Trombinoscope");
     
        $sheet = $trombiExcel->getActiveSheet();
        
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        
        if ($p_idGroupe == -1) {
            $sheet->setCellValue('A1','Trombinoscope - ' . $semestreRepository->find($p_idSemestre)->getLibelle());
        } else {
            $sheet->setCellValue('A1', 'Trombinoscope - ' . $semestreRepository->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $groupeRepository->find($p_idGroupe)->getLibelle());
        }
        
        if ($p_idGroupe == -1) {
            $groupe = $groupeRepository->find($p_idGroupe);
            $liste_etudiant = $this->trieEtudiantGroupe($groupe, $etudiants);
            
            $row=4;
            $i=0;
            $column='B';
            
            foreach ($liste_etudiant as $etudiant) {
                
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                
                $sheet->getRowDimension($row)->setRowHeight(95);

                $objDrawing = new \PHPExcel_Worksheet_Drawing();

                $objDrawing->setPath($etudiant->getUrlPhoto());
                $objDrawing->setHeight(120);
                $objDrawing->setCoordinates($column . $row);
                $objDrawing->setWorksheet($sheet);

                $rowNom = $row+1;
                $rowPrenom = $row+2;
                $rowGroupe = $row+3;

                $TDTP = $td->getLibelle() + ' ' + $td->getLibelle();

                $sheet->setCellValue($column . $rowNom, $etudiant->getNom());
                $sheet->setCellValue($column . $rowPrenom, $etudiant->getPrenom());
                $sheet->setCellValue($column . $rowGroupe, $TDTP);
                    
                switch($column) {
                    case 'B':
                        $column='D';
                        break;

                    case 'D':
                        $column='F';
                        break;

                    case 'F':
                        $column='H';
                        break;

                    default:
                        $column='B';
                        break;
                }
                    
                $i ++;
                if($i==4){
                    $i=0;
                    $row+=8;
                }
            }
            $objWriter = new \PHPExcel_Writer_Excel2007($trombiExcel);

            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition:inline;filename=Trombinoscope - '.$semestre->getLibelle().'.xlsx ');
            $objWriter->save('php://output');
        } else {
            $groupe = $groupeRepository->find($p_idGroupe);
            $liste_etudiant = $this->trieEtudiantGroupe($groupe, $etudiants);
            
            $row=4;
            $i=0;
            $column='B';
            
            foreach ($liste_etudiant as $etudiant) {
                foreach ($etudiant->getIdGroupe() as $groupe_etudiant) {
                    if ($groupe_etudiant->getIdPere() == null) {
                        $td = $groupe_etudiant;
                    } else {
                        $tp = $groupe_etudiant;
                    }
                }
                
                $sheet->getRowDimension($row)->setRowHeight(95);

                $objDrawing = new \PHPExcel_Worksheet_Drawing();

                $objDrawing->setPath($etudiant->getUrlPhoto());
                $objDrawing->setHeight(120);
                $objDrawing->setCoordinates($column . $row);
                $objDrawing->setWorksheet($sheet);

                $rowNom = $row+1;
                $rowPrenom = $row+2;
                $rowGroupe = $row+3;

                $groupeTD = $td->getLibelle().'    '.$tp->getLibelle();

                $sheet->setCellValue($column . $rowNom, $etudiant->getNom());
                $sheet->setCellValue($column . $rowPrenom, $etudiant->getPrenom());
                $sheet->setCellValue($column . $rowGroupe, $groupeTD);
                    
                    
                switch($column) {
                    case 'B':
                        $column='D';
                        break;

                    case 'D':
                        $column='F';
                        break;

                    case 'F':
                        $column='H';
                        break;

                    default:
                        $column='B';
                        break;
                }

                $i ++;
                if($i==4){
                    $i=0;
                    $row+=8;
                }
            }
            $objWriter = new \PHPExcel_Writer_Excel2007($trombiExcel);

            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition:inline;filename=Trombinoscope - '.$groupe->getLibelle().'.xlsx ');
            $objWriter->save('php://output');
        }
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * Methode pour récuperer l'entity manager de doctrine
     * @return type
     */
    public function getEM() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Methode pour récuperer le repository Etudiant
     * @return type
     */
    public function getEtudiantRepo() {
        return $this->getEM()->getRepository('IutTrombiBundle:Etudiant');
    }

    /**
     * Methode pour récuperer le repository Groupe
     * @return type
     */
    public function getGroupeRepo() {
        return $this->getEM()->getRepository('IutTrombiBundle:Groupe');
    }

    /**
     * Methode pour récuperer le repository Semestre
     * @return type
     */
    public function getSemestreRepo() {
        return $this->getEM()->getRepository('IutTrombiBundle:Semestre');
    }

    /**
     * Methode pour récuperer le repository Promotion
     * @return type
     */
    public function getPromotionRepo() {
        return $this->getEM()->getRepository('IutTrombiBundle:Promotion');
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

    /**
     * Methode qui retourne le répertoire web du bundle.
     * 
     * @return type
     */
    public function getWebDir() {
        return $this->get('kernel')->getRootDir() . '/../web/';
    }
    
    /**
     * Methode qui retourne le répertoire photos dans lequel sont stockées les photos des étudiants.
     * @return type
     */
    public function getPhotosDir() {
        return $this->getWebDir() . 'img/photos/';
    }

    /**
     * Upload l'image.
     */
    public function uploadImg($nom, $prenom, $img) {
        $extension = strtolower(strrchr($img['nom'], '.'));
        $target_file = $this->getPhotosDir() . $nom . '_' . $prenom . $extension;
        $tranfert = move_uploaded_file($img['location_tmp'], $target_file);
        if ($tranfert) {
            return 'img/photos/' . $nom . '_' . $prenom . $extension;
        } else {
            return 'img/photos/default.gif';
        }
    }

    /**
     * Vérifie si l'image correspond aux critères d'upload.
     * 
     * @param type $img
     * @return boolean
     */
    public function checkImg($img) {
        if ($img['type'] == 'image/jpg' || $img['type'] == 'image/jpeg' || $img['type'] == 'image/png') {
            if ($img['taille'] < 1048576) {
                if ($img['dimensions'][0] == 222 && $img['dimensions'][1] == 222) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}