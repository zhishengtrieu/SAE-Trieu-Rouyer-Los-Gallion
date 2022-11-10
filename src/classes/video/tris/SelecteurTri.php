<?php
declare(strict_types=1);
namespace netvod\video\tris;

class SelecteurTri{

    const TRI_ALPHA = 1;
    const TRI_ANTI_ALPHA = 2;
    const TRI_NB_GRAND_EPISODE = 3;
    const TRI_NB_PETIT_EPISODE = 4;
    const TRI_MOYENNE_NOTE_PLUS_GRANDE =5;
    const TRI_MOYENNE_NOTE_PLUS_PETITE =6;

    public static function selectionnerTri(int $indice_selection) : ?Tri{
        switch ($indice_selection){
            case self::TRI_ALPHA:
                $tri =new TriAlpha();
            break;
            case self::TRI_ANTI_ALPHA:
                $tri = new TriAntiAplha();
            break;
            case self::TRI_NB_GRAND_EPISODE:
                $tri = new TriNbGrandEpisode();
            break;
            case self::TRI_NB_PETIT_EPISODE:
                $tri = new TriNbPetitEpisode();
            break;
            case self::TRI_MOYENNE_NOTE_PLUS_GRANDE:
                $tri = new TriMoyennePlusGrande();
            break;
            case self::TRI_MOYENNE_NOTE_PLUS_PETITE:
                $tri = new TriMoyennePlusPetite();
            break;
            default:
                $tri = null;
        }
        return  $tri;
    }
}