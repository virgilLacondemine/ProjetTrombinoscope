<?php

namespace Iut\TrombiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProfController extends Controller {

    /**
     * @Route("/prof", name="profTrombiIndex")
     */
    public function indexAction() {
        return $this->render('IutTrombiBundle:Prof:index.html.twig');
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
        return $this->render('IutTrombiBundle:Prof:menu.html.twig', array(
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
     * @Route("/prof/display/{p_idGroupe}/{p_idSemestre}", name="profDisplay")
     */
    public function displayAction($p_idGroupe, $p_idSemestre) {
        $semestreRepository = $this->getSemestreRepo();
        $groupeRepository = $this->getGroupeRepo();
        $etudiantRepository = $this->getEtudiantRepo();
        switch ($p_idGroupe) {
            case -1:
                $listeEtudiant = $etudiantRepository->findAll();
                $semestre = $semestreRepository->find($p_idSemestre);
                $listeGroupe = $groupeRepository->findBy(array('idSemestre' => $p_idSemestre));
                $lesEtudiants = $this->trieEtudiantSemestre($listeGroupe, $listeEtudiant);

                $array = array(
                    'groupes' => $listeGroupe,
                    'etudiants' => $lesEtudiants,
                    'semestre' => $semestre,
                    'p_idGroupe' => $p_idGroupe,
                    'p_idSemestre' => $p_idSemestre,
                );

                return $this->render('IutTrombiBundle:Prof:trombi.html.twig', $array);

            default :
                $groupe = $groupeRepository->find($p_idGroupe);
                $listeGroupe = $groupeRepository->findBy(array('idSemestre' => $p_idSemestre));
                $listeEtudiant = $etudiantRepository->findAll();
                $lesEtudiants = $this->trieEtudiantGroupe($groupe, $listeEtudiant);

                $array = array(
                    'groupe' => $groupe,
                    'groupes' => $listeGroupe,
                    'etudiants' => $lesEtudiants,
                    'p_idGroupe' => $p_idGroupe,
                    'p_idSemestre' => $p_idSemestre,
                );

                return $this->render('IutTrombiBundle:Prof:trombi.html.twig', $array);
        }
    }

//    /**
//     * 
//     * @Route("/prof/displayGroupe", name="profDisplayGroupe")
//     */
//    public function displayGroupeAction() {
//        $doctrine = $this->getDoctrine();
//        $em = $doctrine->getManager();
//        $semestreRepository = $em->getRepository('IutTrombiBundle:Semestre');
//        $groupeRepository = $em->getRepository('IutTrombiBundle:Groupe');
//        $les_semestres = $semestreRepository->findAll();
//        $les_groupes = $groupeRepository->findAll();
//        $array = array('groupes' => $les_groupes,
//            'semestres' => $les_semestres);
//
//        return $this->render('IutTrombiBundle:Prof:editionGroupe.html.twig', $array);
//    }

    /**
     * @Route("/prof/displayArchive", name="profDisplayArchive")
     */
    public function displayArchiveAction() {
        $etudiants_archive = $this->getEtudiantRepo()->findAll();
        $promotions = $this->getPromotionRepo()->findAll();
        $array = array(
            'etudiants' => $etudiants_archive,
            'promotions' => $promotions
        );
        return $this->render('IutTrombiBundle:Prof:archive.html.twig', $array);
    }

    /**
     * @Route("/prof/search", name="profSearch")
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
        return $this->render('IutTrombiBundle:Prof:searchRender.html.twig', $array);
    }

    /**
     * @Route("/prof/exporterEmargementPDF/{p_idGroupe}/{p_idSemestre}",name="profExporterEmargementPDF")
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


        return $this->render('IutTrombiBundle:Prof:index.html.twig');
    }

    /**
     * @Route("/prof/exporterTrombiPDF/{p_idGroupe}/{p_idSemestre}",name="profExporterTrombiPDF")
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
     * @Route("/prof/ExportExcelListe/{p_idGroupe}/{p_idSemestre}",name="exportExcelListe")
     * @param type $p_groupe
     * @param type $p_listeEtudiant
     * 
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
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                
                $sheet->getStyle('C'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                
                $sheet->getStyle('D'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                
                $sheet->getStyle('E'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                
                $sheet->getStyle('F'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                $i++;
            }
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
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
    			)
                    )
                );
                
                $sheet->getStyle('C'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                
                $sheet->getStyle('D'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                
                $sheet->getStyle('E'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                
                $sheet->getStyle('F'.$i)->getBorders()->applyFromArray(
    		array(
    			'allborders' => array(
    				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
    				'color' => array(
    					'rgb' => '000000'
    				)
                            )
                    )
                );
                $i++;
            }
        }
        
        $objWriter = new \PHPExcel_Writer_Excel2007($listeExcel);
        
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:inline;filename=Feuille d\'émargement.xlsx ');
        $objWriter->save('php://output');
        
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
