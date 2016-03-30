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
        $groupeRespository = $this->getGroupeRepo();
        $semestreRepository = $this->getSemestreRepo();
        $listeSemestre = $semestreRepository->findAll();
        $listeGroupe = $groupeRespository->findAll();
        return $this->render('IutTrombiBundle:Trombi:menu.html.twig', array(
                    'les_semestres' => $listeSemestre,
                    'les_groupes' => $listeGroupe,
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
     * @Route("/display/{p_idGroupe}/{p_idSemestre}/{p_modif}", name="display")
     */
    public function displayAction($p_idGroupe, $p_idSemestre, $p_modif) {
        $semestreRepository = $this->getSemestreRepo();
        $groupeRepository = $this->getGroupeRepo();
        $etudiantRepository = $this->getEtudiantRepo();
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
     * @Route("/displayGroupe", name="displayGroupe")
     */
    public function displayGroupeAction() {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $semestreRepository = $em->getRepository('IutTrombiBundle:Semestre');
        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
        $les_semestres = $semestreRepository->findAll();
        $les_groupes = $groupeRepository->findAll();
        $array = array('groupes' => $les_groupes,
            'semestres' => $les_semestres);

        return $this->render('IutTrombiBundle:Trombi:editionGroupe.html.twig', $array);
    }

    /**
     * @Route("/displayArchive", name="displayArchive")
     */
    public function displayArchiveAction() {
        $etudiants_archive = $this->getEtudiantRepo()->findAll();
        $promotions = $this->getPromotionRepo()->findAll();
        $array = array(
            'etudiants' => $etudiants_archive,
            'promotions' => $promotions
        );
        return $this->render('IutTrombiBundle:Trombi:archive.html.twig', $array);
    }

    /**
     * @Route("/displayMulti", name="displayMulti")
     */
    public function displayMultiAction() {
        $semestreRepository = $this->getSemestreRepo();
        $groupeRepository = $this->getGroupeRepo();
        $etudiantRepository = $this->getEtudiantRepo();

        $les_semestres = $semestreRepository->findAll();
        $les_groupes = $groupeRepository->findAll();
        $les_etu = $etudiantRepository->findAll();

        $array = array(
            'groupes' => $les_groupes,
            'semestres' => $les_semestres,
            'etudiants' => $les_etu);

        return $this->render('IutTrombiBundle:Trombi:ajoutEtuGroupe.html.twig', $array);
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchStudentAction() {
        $search = $_POST['recherche'];
        $etudiants = array();
        $etudiants += $this->getEtudiantRepo()->findBy(array(
            'nom' => $search
        ));
        $etudiants += $this->getEtudiantRepo()->findBy(array(
            'prenom' => $search
        ));
        $array = array(
            'etudiants' => $etudiants,
            'groupes' => $this->getGroupeRepo()->findAll()
        );
        return $this->render('IutTrombiBundle:Trombi:searchRender.html.twig', $array);
    }

    /**
     * 
     * @Route("/import", name="import")
     */
    public function importEtudiants() {
        if ($_FILES['liste_etudiants']['error'] > 0) {
            $erreur = "Erreur lors du transfert du fichier";
        }
        $target_file = $this->get('kernel')->getRootDir() . '/../web/' . basename($_FILES['liste_etudiants']['name']);
        $tranfert = move_uploaded_file($_FILES['liste_etudiants']['tmp_name'], $target_file);
        if ($tranfert) {
            $fichier = fopen($target_file, "r+");
            $liste = array();
            $ligne = fgets($fichier);
            $em = $this->getEM();
            $groupeRepository = $this->getGroupeRepo();
            $td = $groupeRepository->findOneBy(array(
                'idSemestre' => $this->getSemestreRepo()->find(1),
                'idPere' => null
            ));
            $tp = $groupeRepository->findOneBy(array(
                'idSemestre' => $this->getSemestreRepo()->find(1),
                'idPere' => $td
            ));
            $promo = new \Iut\TrombiBundle\Entity\Promotion();
            $promo->setAnnee(getdate()['year'] + 2);
            $em->persist($promo);
            while ($ligne = fgets($fichier)) {
                $liste = explode("	", $ligne);
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
        } else {
            
        }
        if (file_exists($target_file)) {
            unlink(realpath($target_file));
        }
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/addGrp", name="addGrp")
     */
    public function addGrpAction() {

        $form_grp = array(
            'libelle' => $_POST['libelle'],
            'idSemestre' => $_POST['idSemestre'],
            'idPere' => $_POST['idPere'],
        );

        $em = $this->getDoctrine()->getManager();
        $semestreRepository = $em->getRepository('IutTrombiBundle:Semestre');
        $semestre = $semestreRepository->find($form_grp['idSemestre']);

        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
        $groupePere = $groupeRepository->find($form_grp['idPere']);

        $groupe = new \Iut\TrombiBundle\Entity\Groupe();
        $groupe->setLibelle($form_grp['libelle']);
        $groupe->setIdSemestre($semestre);
        $groupe->setIdPere($groupePere);
        $em->persist($groupe);
        $em->flush();

        return $this->redirectToRoute('displayGroupe');
    }

    /**
     * @Route("/modify", name="modify")
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
        $etudiantRepository = $this->getEtudiantRepo();
        $groupeRepository = $this->getGroupeRepo();
        $new_td = $groupeRepository->find($form_etudiant['groupe_td']);
        $new_tp = $groupeRepository->find($form_etudiant['groupe_tp']);
        $etudiant = $etudiantRepository->find($form_etudiant['id']);
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

        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/modifMultiEtuGrp", name="modifMultiEtuGrp")
     */
    public function modifMultiEtuGrpAction() {

        $form = array(
            'groupeTD' => $_POST['groupeTD'],
            'groupeTP' => $_POST['groupeTP'],
            'etudiants' => $_POST['lesEtudiants'],
        );

        $em = $this->getEM();
        $etudiantRepository = $this->getEtudiantRepo();
        $groupeRepository = $this->getGroupeRepo();
        $groupeTD = $groupeRepository->find($form['groupeTD']);
        $groupeTP = $groupeRepository->find($form['groupeTP']);
        foreach ($form['etudiants'] as $etudiant) {
            $unEtu = $etudiantRepository->find($etudiant);
            foreach ($unEtu->getIdGroupe() as $groupeE) {
                $groupeE->removeIdEtudiant($unEtu);
                $unEtu->removeIdGroupe($groupeE);
            }
            $unEtu = $etudiantRepository->find($etudiant);
            $unEtu->addIdGroupe($groupeTD);
            $unEtu->addIdGroupe($groupeTP);
            $em->persist($unEtu);
            $em->flush();
        }
        return $this->redirectToRoute('displayMulti');
    }

    /**
     * @Route("/modifGrp", name="modifGrp")
     */
    public function modifGrpAction() {

        $form_groupe = array(
            'id' => $_POST['id'],
            'libelle' => $_POST['libelle'],
            'idSemestre' => $_POST['idSemestre'],
            'idPere' => $_POST['idPere']
        );

        $em = $this->getDoctrine()->getManager();
        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
        $semestreRepository = $em->getRepository('IutTrombiBundle:Semestre');
        $new_semestre = $semestreRepository->find($form_groupe['idSemestre']);
        $new_pere = $groupeRepository->find($form_groupe['idPere']);
        $groupe = $groupeRepository->find($form_groupe['id']);

        $groupe->setLibelle($form_groupe['libelle']);
        $groupe->setIdSemestre($new_semestre);
        $groupe->setIdPere($new_pere);
        $em->persist($groupe);
        $em->flush();

        return $this->redirectToRoute('displayGroupe');
    }

    /**
     * @Route("/supp/{idEtudiant}", name="supp")
     */
    public function suppressionEtudiantAction($idEtudiant) {
        $em = $this->getEM();
        $etudiantRepository = $this->getEtudiantRepo();
        $etudiant = $etudiantRepository->find($idEtudiant);
        $em->remove($etudiant);
        $em->flush();
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/suppGrp/{idGroupe}", name="suppGrp")
     */
    public function suppressionGroupeAction($idGroupe) {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
        $groupe = $groupeRepository->find($idGroupe);
        $em->remove($groupe);
        $em->flush();
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/addStudent", name="addStudent")
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
            $em = $this->getDoctrine()->getManager();
            $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
            $new_td = $groupeRepository->find($form_etudiant['groupe_td']);
            $new_tp = $groupeRepository->find($form_etudiant['groupe_tp']);
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

            return $this->render('IutTrombiBundle:Trombi:index.html.twig');
        } else {
            return $this->render('IutTrombiBundle:Trombi:imgError.html.twig');
        }
    }

    /**
     * @Route("/nextSemestre",name="nextSemestre")
     */
    public function changerSemestreAction() {
        $em = $this->getEM();
        $groupeRepository = $this->getGroupeRepo();
        $etudiantRepository = $this->getEtudiantRepo();
        $semestreRepository = $this->getSemestreRepo();
        $list_etudiant = $etudiantRepository->findAll();
        $list_semestre = $semestreRepository->findAll();
        foreach (array_reverse($list_semestre) as $semestre) {
            $list_groupe = $groupeRepository->findBy(array(
                'idSemestre' => $semestre
            ));
            if ($semestre->getId() == 4) {
                $nextSem = $semestre;
            } else {
                $new_td = $groupeRepository->findBy(array(
                    'idSemestre' => $nextSem
                ));
                $new_tp = $groupeRepository->findBy(array(
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
                                //$em->persist($new_td[0]);
                            }
                            //$em->persist($etudiant);
                            //$em->persist($groupe);
                            $em->flush();
                        } else {
                            $etudiant->removeIdGroupe($groupe);
                            $groupe->removeIdEtudiant($etudiant);
                            if (isset($new_tp)) {
                                $etudiant->addIdGroupe($new_tp[0]);
                                $new_tp[0]->addIdEtudiant($etudiant);
                                //$em->persist($new_tp[0]);
                            }
                            //$em->persist($etudiant);
                            // $em->persist($groupe);
                            $em->flush();
                        }
                    }
                }
            }
        }
        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/exporterEmargementPDF/{p_idGroupe}/{p_idSemestre}",name="exporterEmargementPDF")
     */
    public function exporterEmargementPDFAction($p_idGroupe, $p_idSemestre) {
        $etudiantRepository = $this->getEtudiantRepo();
        $groupeRepository = $this->getGroupeRepo();
        $semestreRepository = $this->getSemestreRepo();
        $etudiants = $etudiantRepository->findAll();

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('arial', '', 10);
        if ($p_idGroupe == -1) {
            $pdf->Cell(160, 7, 'Feuille d\'emargement - ' . $semestreRepository->find($p_idSemestre)->getLibelle());
        } else {
            $pdf->Cell(160, 7, 'Feuille d\'emargement - ' . $semestreRepository->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $groupeRepository->find($p_idGroupe)->getLibelle());
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
            $semestre = $semestreRepository->find($p_idSemestre);
            $groupes = $groupeRepository->findBy(array(
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
            $groupe = $groupeRepository->find($p_idGroupe);
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


        return $this->render('IutTrombiBundle:Trombi:index.html.twig');
    }

    /**
     * @Route("/exporterTrombiPDF/{p_idGroupe}/{p_idSemestre}",name="exporterTrombiPDF")
     */
    public function exporterTrombiPDFAction($p_idGroupe, $p_idSemestre) {
        $etudiantRepository = $this->getEtudiantRepo();
        $groupeRepository = $this->getGroupeRepo();
        $semestreRepository = $this->getSemestreRepo();
        $etudiants = $etudiantRepository->findAll();

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('arial', '', 10);
        $x = 22;
        $y = 35;
        if ($p_idGroupe == -1) {
            $pdf->Cell(160, 7, 'Trombinoscope - ' . $semestreRepository->find($p_idSemestre)->getLibelle());
        } else {
            $pdf->Cell(160, 7, 'Trombinoscope - ' . $semestreRepository->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $groupeRepository->find($p_idGroupe)->getLibelle());
        }
        $pdf->Ln();
        $pdf->Ln();
        if ($p_idGroupe == -1) {
            $semestre = $semestreRepository->find($p_idSemestre);
            $groupes = $groupeRepository->findBy(array(
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
                        $pdf->Cell(160, 7, 'Trombinoscope - ' . $semestreRepository->find($p_idSemestre)->getLibelle());
                        $y = -10;
                    }
                    $y += 50;
                } else {
                    $x += 45;
                }
            }
            $pdf->Output('D', 'trombinoscope_' . $semestre->getLibelle() . '.pdf', true);
        } else {
            $groupe = $groupeRepository->find($p_idGroupe);
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
                        $pdf->Cell(160, 7, 'Trombinoscope - ' . $semestreRepository->find($p_idSemestre)->getLibelle() . ' - Groupe ' . $groupeRepository->find($p_idGroupe)->getLibelle());
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
     * @Route("/exportExcelListe/{p_idGroupe}/{p_idSemestre}",name="exportExcelListe")
     * @param type $p_groupe
     * @param type $p_listeEtudiant
     * 
     */
    public function exportExcelListe($p_idGroupe, $p_idSemestre) {
        $groupeRepository = $this->getGroupeRepo();
        $semestreRepository = $this->getSemestreRepo();
        $etudiantsRepository = $this->getEtudiantRepo();
        $etudiants = $etudiantsRepository->findAll();
        header("Content-type:application/vnd.ms-excel");
        if ($p_idGroupe == -1) {
            $semestre = $semestreRepository->find($p_idSemestre);
            $groupes = $groupeRepository->findBy(array(
                'idSemestre' => $semestre
            ));
            $this->trieEtudiantSemestre($groupes, $etudiants);
        } else {
            $groupe = $groupeRepository->find($p_idGroupe);
            $this->trieEtudiantGroupe($groupe, $etudiants);
        }
        print '<table brder=1>'
                . '<TR><TD>Nom</TD>'
                . '<TD>Prenom</TD><TD>TD</TD><TD>TP</TD></TR><TR>';
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
     * @return type
     */
    public function getWebDir() {
        return $this->get('kernel')->getRootDir() . '/../web/';
    }

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
     * Vérifie si l'image correspond au critère d'upload.
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
