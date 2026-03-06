<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Achat
 *
 * @property int $id
 * @property string $code
 * @property bool $uptodate
 * @property int $personne_id
 * @property string $bien_type
 * @property int $bien_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bien
 * @property-read \App\Models\Contrat|null $contrat
 * @property-read \App\Models\Paiement|null $firstPaiement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Paiement> $paiements
 * @property-read int|null $paiements_count
 * @property-read \App\Models\Paiement|null $pendingPaiement
 * @property-read \App\Models\Personne $personne
 * @method static \Illuminate\Database\Eloquent\Builder|Achat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Achat pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Achat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Achat whereBienId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat whereBienType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat wherePersonneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat whereUptodate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Achat withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Achat withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperAchat {}
}

namespace App\Models{
/**
 * App\Models\Appartement
 *
 * @property int $id
 * @property string $reference
 * @property string $nom
 * @property string $ville
 * @property string $pays
 * @property string $quartier
 * @property int $superficie
 * @property int $montant_location
 * @property int $montant_investit
 * @property int $cout_achat
 * @property string|null $observation
 * @property bool $attestation_villageoise
 * @property bool $titre_foncier
 * @property bool $document_cession
 * @property bool $arreter_approbation
 * @property bool $cours_commune
 * @property bool $placard
 * @property bool $etage
 * @property bool $toilette
 * @property bool $cuisine
 * @property bool $garage
 * @property bool $parking
 * @property bool $cie
 * @property bool $sodeci
 * @property bool $cloture
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $proprietaire_id
 * @property int|null $type_appartement_id
 * @property string|null $status
 * @property-read \App\Models\Achat|null $achat
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \App\Models\Proprietaire $proprietaire
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @property-read \App\Models\TypeAppartement|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement busy()
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement free()
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereArreterApprobation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereAttestationVillageoise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereCie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereCloture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereCoursCommune($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereCoutAchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereCuisine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereDocumentCession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereEtage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereGarage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereMontantInvestit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereMontantLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereObservation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereParking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement wherePlacard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereProprietaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereSodeci($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereSuperficie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereTitreFoncier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereToilette($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereTypeAppartementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appartement whereVille($value)
 * @mixin \Eloquent
 */
	class IdeHelperAppartement {}
}

namespace App\Models{
/**
 * App\Models\Avance
 *
 * @property int $id
 * @property int $visite_id
 * @property int $mois
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Visite $visite
 * @method static \Illuminate\Database\Eloquent\Builder|Avance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Avance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Avance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Avance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avance whereMois($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avance whereVisiteId($value)
 * @mixin \Eloquent
 */
	class IdeHelperAvance {}
}

namespace App\Models{
/**
 * App\Models\Caution
 *
 * @property int $id
 * @property int $visite_id
 * @property int $mois
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Visite $visite
 * @method static \Illuminate\Database\Eloquent\Builder|Caution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Caution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Caution query()
 * @method static \Illuminate\Database\Eloquent\Builder|Caution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Caution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Caution whereMois($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Caution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Caution whereVisiteId($value)
 * @mixin \Eloquent
 */
	class IdeHelperCaution {}
}

namespace App\Models{
/**
 * App\Models\Contrat
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $debut
 * @property \Illuminate\Support\Carbon|null $fin
 * @property string $etat
 * @property string $status
 * @property int $commission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $operation_type
 * @property int $operation_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $operation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat notUptodate()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat processing()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat purchaseProcessing()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat rentProcessing()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat uptodate()
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereOperationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereOperationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contrat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperContrat {}
}

namespace App\Models{
/**
 * App\Models\Depense
 *
 * @property int $id
 * @property string $titre
 * @property int $montant
 * @property string $description
 * @property string $status
 * @property int $type_depense_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @property-read \App\Models\TypeDepense $type
 * @method static \Illuminate\Database\Eloquent\Builder|Depense countDateFilter(array|string $date)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense currentYear()
 * @method static \Illuminate\Database\Eloquent\Builder|Depense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Depense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Depense pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Depense query()
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereTypeDepenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Depense withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Depense withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperDepense {}
}

namespace App\Models{
/**
 * App\Models\Dette
 *
 * @property int $id
 * @property string $code
 * @property int $montant
 * @property string $status
 * @property string $origine_type
 * @property int $origine_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $origine
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Dette countDateFilter(array|string $date)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette currentYear()
 * @method static \Illuminate\Database\Eloquent\Builder|Dette newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dette newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dette paid()
 * @method static \Illuminate\Database\Eloquent\Builder|Dette pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Dette query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereOrigineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereOrigineType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dette withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Dette withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperDette {}
}

namespace App\Models{
/**
 * App\Models\Frais
 *
 * @property int $id
 * @property int $visite_id
 * @property int $mois
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Visite $visite
 * @method static \Illuminate\Database\Eloquent\Builder|Frais newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Frais newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Frais query()
 * @method static \Illuminate\Database\Eloquent\Builder|Frais whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frais whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frais whereMois($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frais whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Frais whereVisiteId($value)
 * @mixin \Eloquent
 */
	class IdeHelperFrais {}
}

namespace App\Models{
/**
 * App\Models\Loyer
 *
 * @property int $id
 * @property string $code
 * @property string $status
 * @property int $montant
 * @property int $contrat_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $mois
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Contrat $contrat
 * @property-read \App\Models\Dette|null $dette
 * @property-read \App\Models\Paiement|null $firstPaiement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Paiement> $paiements
 * @property-read int|null $paiements_count
 * @property-read \App\Models\Paiement|null $pendingPaiement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer currentMonth()
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereContratId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereMois($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loyer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoyer {}
}

namespace App\Models{
/**
 * App\Models\Paiement
 *
 * @property int $id
 * @property string $code
 * @property int $montant
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $payable_type
 * @property int $payable_id
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Dette|null $dette
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $payable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement validated()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement wherePayableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement wherePayableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Paiement withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperPaiement {}
}

namespace App\Models{
/**
 * App\Models\Personne
 *
 * @property int $id
 * @property string $code
 * @property string $nom_complet
 * @property string $telephone
 * @property string|null $email
 * @property string $cni
 * @property string $lieu_naissance
 * @property string $nationalite
 * @property string $ville
 * @property string $quartier
 * @property string $pays
 * @property string $fonctions
 * @property \App\Enums\PersonneCiviliteEnum $civilite
 * @property string|null $animal
 * @property \Illuminate\Support\Carbon $date_naissance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $type_client_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Achat> $achats
 * @property-read int|null $achats_count
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Media|null $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contrat> $contratsAchat
 * @property-read int|null $contrats_achat_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contrat> $contratsBail
 * @property-read int|null $contrats_bail_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Media|null $piece
 * @property-read \App\Models\TypeClient|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Visite> $visites
 * @property-read int|null $visites_count
 * @method static \Illuminate\Database\Eloquent\Builder|Personne newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Personne newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Personne query()
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereAnimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereCivilite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereCni($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereDateNaissance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereFonctions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereLieuNaissance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereNationalite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereNomComplet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereTypeClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne whereVille($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personne withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Personne withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperPersonne {}
}

namespace App\Models{
/**
 * App\Models\Proprietaire
 *
 * @property int $id
 * @property string $code
 * @property string $nom_complet
 * @property string $telephone
 * @property string $email
 * @property string $cni
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereCni($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereNomComplet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Proprietaire withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperProprietaire {}
}

namespace App\Models{
/**
 * App\Models\Societe
 *
 * @property int $id
 * @property string $raison_sociale
 * @property string $slogan
 * @property string $email
 * @property string $boite_postale
 * @property string $forme_juridique
 * @property string $registre
 * @property string $contact
 * @property string $siege
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $frais_dossier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Societe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Societe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Societe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereBoitePostale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereFormeJuridique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereFraisDossier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereRaisonSociale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereRegistre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereSiege($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereSlogan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Societe whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSociete {}
}

namespace App\Models{
/**
 * App\Models\Terrain
 *
 * @property int $id
 * @property string $reference
 * @property string $nom
 * @property string $ville
 * @property string $pays
 * @property string $quartier
 * @property int $montant_location
 * @property int $montant_investit
 * @property int $cout_achat
 * @property int $superficie
 * @property bool $attestation_villageoise
 * @property bool $titre_foncier
 * @property bool $document_cession
 * @property bool $arreter_approbation
 * @property int $proprietaire_id
 * @property int|null $type_terrain_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $status
 * @property-read \App\Models\Achat|null $achat
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \App\Models\Proprietaire $proprietaire
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @property-read \App\Models\TypeTerrain|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain busy()
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain free()
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain query()
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereArreterApprobation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereAttestationVillageoise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereCoutAchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereDocumentCession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereMontantInvestit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereMontantLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain wherePays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereProprietaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereSuperficie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereTitreFoncier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereTypeTerrainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain whereVille($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Terrain withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperTerrain {}
}

namespace App\Models{
/**
 * App\Models\TypeAppartement
 *
 * @property int $id
 * @property string $nom
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeAppartement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTypeAppartement {}
}

namespace App\Models{
/**
 * App\Models\TypeClient
 *
 * @property int $id
 * @property string $nom
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeClient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTypeClient {}
}

namespace App\Models{
/**
 * App\Models\TypeDepense
 *
 * @property int $id
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeDepense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeDepense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeDepense query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeDepense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeDepense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeDepense whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeDepense whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTypeDepense {}
}

namespace App\Models{
/**
 * App\Models\TypeTerrain
 *
 * @property int $id
 * @property string $nom
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeTerrain whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTypeTerrain {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Media|null $photo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\Visite
 *
 * @property int $id
 * @property string $code
 * @property int $montant
 * @property \Illuminate\Support\Carbon $date_expiration
 * @property int $personne_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $status
 * @property int $appartement_id
 * @property int $frais_dossier
 * @property-read \App\Models\Appartement $appartement
 * @property-read \OwenIt\Auditing\Models\Audit|null $audit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Avance|null $avance
 * @property-read \App\Models\Caution|null $caution
 * @property-read \App\Models\Contrat|null $contrat
 * @property-read \App\Models\Dette|null $dette
 * @property-read \App\Models\Frais|null $frais
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\PendingTransition> $pendingTransitions
 * @property-read int|null $pending_transitions_count
 * @property-read \App\Models\Personne $personne
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Asantibanez\LaravelEloquentStateMachines\Models\StateHistory> $stateHistory
 * @property-read int|null $state_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Visite currentYear()
 * @method static \Illuminate\Database\Eloquent\Builder|Visite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visite pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Visite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereAppartementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereDateExpiration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereFraisDossier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite wherePersonneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visite withNameResponsible()
 * @method static \Illuminate\Database\Eloquent\Builder|Visite withResponsible()
 * @mixin \Eloquent
 */
	class IdeHelperVisite {}
}

