<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    // Classique (jeu de base)
    case Villageois = 'villageois';
    case LoupGarou = 'loup_garou';
    case Voyante = 'voyante';
    case Sorciere = 'sorciere';
    case Chasseur = 'chasseur';
    case Cupidon = 'cupidon';
    case PetiteFille = 'petite_fille';
    case Voleur = 'voleur';
    case Capitaine = 'capitaine';

    // Nouvelle Lune
    case Salvateur = 'salvateur';
    case Ancien = 'ancien';
    case BoucEmissaire = 'bouc_emissaire';
    case IdiotDuVillage = 'idiot_du_village';
    case DeuxSoeurs = 'deux_soeurs';
    case TroisFreres = 'trois_freres';
    case JoueurDeFlute = 'joueur_de_flute';
    case ChienLoup = 'chien_loup';
    case Comedien = 'comedien';
    case Renard = 'renard';
    case ChevalierEpeeRouillee = 'chevalier_epee_rouillee';

    // Personnages
    case GrandMechantLoup = 'grand_mechant_loup';
    case EnfantSauvage = 'enfant_sauvage';
    case LoupGarouBlanc = 'loup_garou_blanc';
    case JugeBegue = 'juge_begue';
    case ServanteDevouee = 'servante_devouee';
    case MontreurDOurs = 'montreur_d_ours';
    case InfectPereDesLoups = 'infect_pere_des_loups';
    case AbominableSectaire = 'abominable_sectaire';
    case Ange = 'ange';
    case Corbeau = 'corbeau';
    case Gitane = 'gitane';

    /**
     * @return array<int, self>
     */
    public static function classic(): array
    {
        return [
            self::Villageois,
            self::LoupGarou,
            self::Voyante,
            self::Sorciere,
            self::Chasseur,
            self::Cupidon,
            self::PetiteFille,
            self::Voleur,
            self::Capitaine,
        ];
    }

    /**
     * @return array<int, self>
     */
    public static function newMoon(): array
    {
        return [
            self::Salvateur,
            self::Ancien,
            self::BoucEmissaire,
            self::IdiotDuVillage,
            self::DeuxSoeurs,
            self::TroisFreres,
            self::JoueurDeFlute,
            self::ChienLoup,
            self::Comedien,
            self::Renard,
            self::ChevalierEpeeRouillee,
        ];
    }

    /**
     * @return array<int, self>
     */
    public static function characters(): array
    {
        return [
            self::GrandMechantLoup,
            self::EnfantSauvage,
            self::LoupGarouBlanc,
            self::JugeBegue,
            self::ServanteDevouee,
            self::MontreurDOurs,
            self::InfectPereDesLoups,
            self::AbominableSectaire,
            self::Ange,
            self::Corbeau,
            self::Gitane,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::Villageois => 'Villageois',
            self::LoupGarou => 'Loup-Garou',
            self::Voyante => 'Voyante',
            self::Sorciere => 'Sorcière',
            self::Chasseur => 'Chasseur',
            self::Cupidon => 'Cupidon',
            self::PetiteFille => 'Petite Fille',
            self::Voleur => 'Voleur',
            self::Capitaine => 'Capitaine',
            self::Salvateur => 'Salvateur',
            self::Ancien => 'Ancien',
            self::BoucEmissaire => 'Bouc Émissaire',
            self::IdiotDuVillage => 'Idiot du Village',
            self::DeuxSoeurs => 'Deux Sœurs',
            self::TroisFreres => 'Trois Frères',
            self::JoueurDeFlute => 'Joueur de Flûte',
            self::ChienLoup => 'Chien-Loup',
            self::Comedien => 'Comédien',
            self::Renard => 'Renard',
            self::ChevalierEpeeRouillee => 'Chevalier à l\'Épée Rouillée',
            self::GrandMechantLoup => 'Grand Méchant Loup',
            self::EnfantSauvage => 'Enfant Sauvage',
            self::LoupGarouBlanc => 'Loup-Garou Blanc',
            self::JugeBegue => 'Juge Bègue',
            self::ServanteDevouee => 'Servante Dévouée',
            self::MontreurDOurs => 'Montreur d\'Ours',
            self::InfectPereDesLoups => 'Infect Père des Loups',
            self::AbominableSectaire => 'Abominable Sectaire',
            self::Ange => 'Ange',
            self::Corbeau => 'Corbeau',
            self::Gitane => 'Gitane',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Villageois => 'Simple habitant du village. Doit démasquer les Loups-Garous lors des votes du jour.',
            self::LoupGarou => 'Chaque nuit, dévore une victime avec ses congénères. Doit se faire passer pour un villageois le jour.',
            self::Voyante => 'Chaque nuit, découvre la véritable identité d\'un joueur de son choix.',
            self::Sorciere => 'Possède deux potions : une de guérison pour sauver la victime des Loups-Garous, une de poison pour éliminer un joueur.',
            self::Chasseur => 'Lorsqu\'il meurt, il élimine immédiatement le joueur de son choix d\'un coup de fusil.',
            self::Cupidon => 'La première nuit, désigne deux Amoureux. Si l\'un meurt, l\'autre meurt de chagrin.',
            self::PetiteFille => 'Peut espionner les Loups-Garous pendant leur réveil nocturne, à ses risques et périls.',
            self::Voleur => 'En début de partie, peut échanger sa carte avec l\'une des deux cartes restantes.',
            self::Capitaine => 'Élu par le village. Sa voix compte double lors des votes et il désigne son successeur en mourant.',
            self::Salvateur => 'Chaque nuit, protège un joueur de son choix de l\'attaque des Loups-Garous. Ne peut pas protéger deux nuits de suite la même personne.',
            self::Ancien => 'Survit à la première morsure des Loups-Garous. Si éliminé par le village, tous les villageois perdent leurs pouvoirs.',
            self::BoucEmissaire => 'En cas d\'égalité au vote du village, c\'est lui qui est éliminé à la place. Il désigne alors qui votera le lendemain.',
            self::IdiotDuVillage => 'S\'il est désigné par le vote du village, il est épargné mais perd son droit de vote pour le reste de la partie.',
            self::DeuxSoeurs => 'Les deux Sœurs se reconnaissent la première nuit et savent qu\'elles peuvent se faire confiance.',
            self::TroisFreres => 'Les trois Frères se reconnaissent la première nuit et savent qu\'ils peuvent se faire confiance.',
            self::JoueurDeFlute => 'Chaque nuit, charme deux joueurs. Gagne seul s\'il parvient à charmer tous les joueurs encore en vie.',
            self::ChienLoup => 'Choisit en début de partie son camp : Villageois ou Loups-Garous.',
            self::Comedien => 'Trois cartes de pouvoirs sont placées au centre de la table par le maître du jeu. À tour de rôle, il choisit l\'une d\'elles et utilise son pouvoir une seule fois ; la carte est ensuite retirée.',
            self::Renard => 'Chaque nuit, flaire trois joueurs voisins. S\'il y a un Loup-Garou parmi eux, il le sait. Sinon, il perd son pouvoir.',
            self::ChevalierEpeeRouillee => 'S\'il est tué par les Loups-Garous, sa rouille contamine le premier Loup-Garou assis à sa gauche, qui meurt à son tour la nuit suivante.',
            self::GrandMechantLoup => 'Tant qu\'aucun Loup-Garou n\'est mort, il dévore une victime supplémentaire chaque nuit après ses congénères.',
            self::EnfantSauvage => 'Choisit un modèle la première nuit. Reste villageois tant que ce modèle est en vie ; bascule chez les Loups si le modèle meurt.',
            self::LoupGarouBlanc => 'Joue avec les Loups-Garous, mais une nuit sur deux peut dévorer un Loup-Garou. Gagne seul.',
            self::JugeBegue => 'Une fois dans la partie, peut déclencher un second vote du village immédiatement après le premier, via un signe convenu avec le maître du jeu.',
            self::ServanteDevouee => 'Avant qu\'une carte ne soit révélée à la mort, peut échanger sa carte avec celle de l\'éliminé et prendre son rôle.',
            self::MontreurDOurs => 'Chaque matin, son ours grogne si l\'un de ses voisins immédiats est un Loup-Garou.',
            self::InfectPereDesLoups => 'Une fois dans la partie, au lieu de dévorer la victime, les Loups-Garous peuvent la transformer en Loup-Garou.',
            self::AbominableSectaire => 'En début de partie, le maître du jeu sépare la table en deux camps (gauche / droite). Gagne seul s\'il survit jusqu\'à l\'élimination totale du camp opposé.',
            self::Ange => 'Souhaite être éliminé dès le premier tour de jeu (vote du village ou attaque des Loups). S\'il y parvient, il gagne seul ; sinon il devient simple villageois.',
            self::Corbeau => 'Chaque nuit, désigne un joueur. Le lendemain, ce joueur reçoit deux voix supplémentaires lors du vote du village.',
            self::Gitane => 'Chaque nuit, peut poser une question fermée à un joueur déjà éliminé. Le maître du jeu transmet la réponse par oui ou non.',
        };
    }
}
