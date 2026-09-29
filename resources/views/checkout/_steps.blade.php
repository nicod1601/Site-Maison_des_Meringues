@include('checkout._icons')
@php
	// $etape : étape courante (2 = livraison & paiement, 3 = confirmation)
	// $erreur : true si l'étape courante est en échec
	$etape  = $etape  ?? 2;
	$erreur = $erreur ?? false;
	$etapes = [1 => 'Panier', 2 => 'Livraison & paiement', 3 => 'Confirmation'];
@endphp

<ol class="co-steps" aria-label="Étapes de commande">
	@foreach($etapes as $n => $label)
		@php
			if ($n < $etape)       { $etat = 'done'; }
			elseif ($n === $etape) { $etat = $erreur ? 'error' : ($etape === 3 ? 'done' : 'active'); }
			else                   { $etat = 'todo'; }
		@endphp
		<li class="co-steps__item co-steps__item--{{ $etat }}" @if(in_array($etat, ['active', 'error'])) aria-current="step" @endif>
			<span class="co-steps__num">
				@if($etat === 'done')
					{!! coicon('check') !!}
				@elseif($etat === 'error')
					{!! coicon('x') !!}
				@else
					{{ $n }}
				@endif
			</span>
			<span class="co-steps__label">{{ $label }}</span>
		</li>
		@if(! $loop->last)
			<li class="co-steps__line {{ $n < $etape ? 'co-steps__line--done' : '' }}" aria-hidden="true"></li>
		@endif
	@endforeach
</ol>
