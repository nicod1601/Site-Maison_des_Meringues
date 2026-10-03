<?php

namespace Tests\Feature;

use App\Models\PendingCheckout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutRetourTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.monetico.tpe'       => '1234567',
            'services.monetico.cle'       => str_repeat('A', 40),
            'services.monetico.societe'   => 'societe-test',
            'services.monetico.test_mode' => false,
        ]);
    }

    private function pending(): PendingCheckout
    {
        return PendingCheckout::create([
            'reference'      => 'CMD-TEST-1',
            'user_id'        => User::factory()->create()->id,
            'montant'        => '10.00',
            'mode_livraison' => 'livraison',
            'lignes'         => [[
                'id_ligne' => 1, 'designation' => 'Mini — Fraise',
                'sous_designation' => 'individuel', 'quantite' => 2, 'prix_unitaire' => 5.0,
            ]],
        ]);
    }

    public function test_un_faux_retour_de_paiement_ne_cree_aucune_commande(): void
    {
        $this->pending();

        // Un client malveillant connaît sa référence et forge lui-même le « retour banque »
        $response = $this->post('/checkout/retour', [
            'code-retour' => 'paiement',
            'reference'   => 'CMD-TEST-1',
            'montant'     => '10.00EUR',
            'MAC'         => 'FAUX',
        ]);

        // Pas de 419 (CSRF exempté) mais un refus : requête illisible / sceau invalide
        $response->assertStatus(400);
        $this->assertDatabaseCount('commande', 0);
        $this->assertDatabaseHas('pending_checkouts', ['reference' => 'CMD-TEST-1']);
    }

    public function test_un_retour_non_paye_est_acquitte_sans_commande(): void
    {
        $this->pending();

        $this->post('/checkout/retour', ['code-retour' => 'Annulation', 'reference' => 'CMD-TEST-1'])
            ->assertOk();

        $this->assertDatabaseCount('commande', 0);
    }
}
