<?php

namespace Tests\Feature;

use App\Models\Panier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PanierTest extends TestCase
{
    use RefreshDatabase;

    /** Crée un mini catalogue et renvoie les ids utiles. */
    private function catalogue(bool $live = true): array
    {
        $idForme  = DB::table('forme')->insertGetId(['nom_forme' => 'Mini']);
        $idCondi  = DB::table('conditionnement')->insertGetId(['type' => 'individuel']);
        $idParfum = DB::table('parfum')->insertGetId(['nom_parfum' => 'Fraise']);

        $idFormeCondi = DB::table('forme_condi')->insertGetId([
            'id_forme' => $idForme, 'id_condi' => $idCondi, 'prix' => 2.50,
        ]);

        $idProduit = DB::table('produit')->insertGetId([
            'nom_produit' => 'Mini fraise',
            'id_forme'    => $idForme,
            'id_parfum'   => $idParfum,
            'live'        => $live,
        ]);

        return compact('idForme', 'idProduit', 'idFormeCondi');
    }

    private function ligneDans(Panier $panier, array $c, int $quantite = 1): int
    {
        return DB::table('panier_ligne')->insertGetId([
            'id_panier'      => $panier->id_panier,
            'id_produit'     => $c['idProduit'],
            'id_forme_condi' => $c['idFormeCondi'],
            'quantite'       => $quantite,
            'prix_unitaire'  => 2.50,
        ]);
    }

    public function test_un_client_ne_peut_pas_modifier_ni_supprimer_la_ligne_d_un_autre(): void
    {
        $c      = $this->catalogue();
        $victime = Panier::create(['user_id' => User::factory()->create()->id]);
        $idLigne = $this->ligneDans($victime, $c, 3);

        $attaquant = User::factory()->create();

        $this->actingAs($attaquant)->patch(route('panier.modifier', $idLigne), ['quantite' => 50])->assertNotFound();
        $this->actingAs($attaquant)->delete(route('panier.supprimer', $idLigne))->assertNotFound();

        $this->assertDatabaseHas('panier_ligne', ['id_ligne' => $idLigne, 'quantite' => 3]);
    }

    public function test_un_client_peut_modifier_sa_propre_ligne(): void
    {
        $c      = $this->catalogue();
        $user   = User::factory()->create();
        $panier = Panier::create(['user_id' => $user->id]);
        $idLigne = $this->ligneDans($panier, $c);

        $this->actingAs($user)->patch(route('panier.modifier', $idLigne), ['quantite' => 4])->assertRedirect();

        $this->assertDatabaseHas('panier_ligne', ['id_ligne' => $idLigne, 'quantite' => 4]);
    }

    public function test_la_quantite_est_plafonnee(): void
    {
        $c = $this->catalogue();

        $this->post(route('panier.ajouter'), [
            'id_produit' => $c['idProduit'], 'id_forme_condi' => $c['idFormeCondi'], 'quantite' => 100000,
        ])->assertSessionHasErrors('quantite');
    }

    public function test_un_produit_hors_ligne_ne_peut_pas_etre_ajoute(): void
    {
        $c = $this->catalogue(live: false);

        $this->postJson(route('panier.ajouter'), [
            'id_produit' => $c['idProduit'], 'id_forme_condi' => $c['idFormeCondi'], 'quantite' => 1,
        ])->assertStatus(422);

        $this->assertDatabaseCount('panier_ligne', 0);
    }

    public function test_la_connexion_ne_fusionne_que_le_panier_du_visiteur(): void
    {
        $c    = $this->catalogue();
        $user = User::factory()->create();

        // Panier d'un AUTRE visiteur anonyme : ne doit jamais être touché
        $autre = Panier::create(['session_id' => 'jeton-d-un-autre-visiteur']);
        $this->ligneDans($autre, $c, 7);

        // Notre visiteur ajoute un article, puis se connecte
        $this->postJson(route('panier.ajouter'), [
            'id_produit' => $c['idProduit'], 'id_forme_condi' => $c['idFormeCondi'], 'quantite' => 2,
        ])->assertOk();

        $this->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->assertAuthenticatedAs($user);

        $panierUser = Panier::where('user_id', $user->id)->firstOrFail();
        $this->assertSame(2, (int) $panierUser->lignes()->sum('quantite'));

        // Le panier de l'autre visiteur est intact
        $this->assertDatabaseHas('panier', ['id_panier' => $autre->id_panier, 'session_id' => 'jeton-d-un-autre-visiteur']);
        $this->assertSame(7, (int) $autre->lignes()->sum('quantite'));
    }
}
