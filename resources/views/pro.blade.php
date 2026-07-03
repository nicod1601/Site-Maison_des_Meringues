@include('templet.header', [
    'titre' => 'Espace Professionnel',
    'note'  => 'présentation des produits + infos + contact',
    'title' => 'Professionnel'
])
@vite('resources/css/pro.css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

<main class="pro-body" id="pro-contenu">

    {{-- ══════════════════════════════════════
         HERO
    ══════════════════════════════════════ --}}
    <section class="hero" aria-label="Introduction professionnelle">
        <div class="hero__deco" aria-hidden="true">
            <span class="hero__deco-ring hero__deco-ring--1"></span>
            <span class="hero__deco-ring hero__deco-ring--2"></span>
            <span class="hero__deco-ring hero__deco-ring--3"></span>
        </div>
        <div class="inner">
            <div class="hero__inner">
                <span class="hero__eyebrow">
                    <i class="ti ti-building-store" aria-hidden="true"></i>
                    Espace Professionnel
                </span>
                <h1 class="hero__title">
                    Des meringues <em>d'exception</em><br>
                    pour votre activité
                </h1>
                <p class="hero__sub">
                    Revendeurs, épiceries fines, hôtels &amp; restaurants — nous accompagnons
                    les professionnels qui partagent notre exigence de qualité artisanale.
                </p>
                <div class="hero__ctas">
                    <a href="#contact" class="btn-hero">
                        <i class="ti ti-mail" aria-hidden="true"></i>
                        Prendre contact
                    </a>
                    <a href="#catalogue" class="btn-hero btn-hero--ghost">
                        <i class="ti ti-book-2" aria-hidden="true"></i>
                        Voir le catalogue
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         STATS STRIP
    ══════════════════════════════════════ --}}
    <div class="stats-strip" aria-label="Chiffres clés">
        <div class="stats-strip__inner">
            <div class="stat-item">
                <span class="stat-item__number">100&nbsp;%</span>
                <span class="stat-item__label">Artisanal</span>
            </div>
            <div class="stat-item">
                <span class="stat-item__number">+{{ $parfums->count() }}</span>
                <span class="stat-item__label">Parfums disponibles</span>
            </div>
            <div class="stat-item">
                <span class="stat-item__number">{{ $formes->count() }}</span>
                <span class="stat-item__label">Formes de meringues</span>
            </div>
            <div class="stat-item">
                <span class="stat-item__number">Sur mesure</span>
                <span class="stat-item__label">Commandes pro</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         AVANTAGES
    ══════════════════════════════════════ --}}
    <section class="avantages" aria-labelledby="titre-avantages">
        <div class="inner">
            <p class="section-label">Nos engagements</p>
            <h2 class="section-title" id="titre-avantages">Pourquoi nous choisir&nbsp;?</h2>
            <div class="avantages__grid">

                <div class="avantage-card">
                    <div class="avantage-card__icon"><i class="ti ti-leaf" aria-hidden="true"></i></div>
                    <h3 class="avantage-card__title">Fabrication artisanale</h3>
                    <p class="avantage-card__text">
                        Chaque meringue est confectionnée à la main dans notre atelier normand,
                        sans additif ni conservateur.
                    </p>
                </div>

                <div class="avantage-card">
                    <div class="avantage-card__icon"><i class="ti ti-palette" aria-hidden="true"></i></div>
                    <h3 class="avantage-card__title">Large choix de parfums</h3>
                    <p class="avantage-card__text">
                        Plus de {{ $parfums->count() }} saveurs pour séduire tous les palais
                        et enrichir votre offre produit.
                    </p>
                </div>

                <div class="avantage-card">
                    <div class="avantage-card__icon"><i class="ti ti-package" aria-hidden="true"></i></div>
                    <h3 class="avantage-card__title">Conditionnements adaptés</h3>
                    <p class="avantage-card__text">
                        Sachets et boîtes pensés pour la revente, la dégustation ou l'offre cadeau,
                        prêts à poser en rayon.
                    </p>
                </div>

                <div class="avantage-card">
                    <div class="avantage-card__icon"><i class="ti ti-adjustments-horizontal" aria-hidden="true"></i></div>
                    <h3 class="avantage-card__title">Commandes sur mesure</h3>
                    <p class="avantage-card__text">
                        Volumes, formes, personnalisation : contactez-nous pour construire
                        une offre taillée à vos besoins.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         PROCESSUS — Comment ça marche
    ══════════════════════════════════════ --}}
    <section class="processus" aria-labelledby="titre-processus">
        <div class="inner">
            <p class="section-label section-label--rose">Comment travailler avec nous</p>
            <h2 class="section-title section-title--white" id="titre-processus">
                Un processus simple
            </h2>
            <div class="processus__steps">

                <div class="processus__step">
                    <div class="processus__step-num">1</div>
                    <h3 class="processus__step-title">Prise de contact</h3>
                    <p class="processus__step-text">
                        Écrivez-nous ou appelez-nous pour nous parler de votre projet et de vos volumes souhaités.
                    </p>
                </div>

                <div class="processus__step">
                    <div class="processus__step-num">2</div>
                    <h3 class="processus__step-title">Devis personnalisé</h3>
                    <p class="processus__step-text">
                        Nous vous proposons une offre sur mesure adaptée à votre activité et vos besoins.
                    </p>
                </div>

                <div class="processus__step">
                    <div class="processus__step-num">3</div>
                    <h3 class="processus__step-title">Dégustation & validation</h3>
                    <p class="processus__step-text">
                        Un échantillon peut être envoyé pour valider les parfums et conditionnements choisis.
                    </p>
                </div>

                <div class="processus__step">
                    <div class="processus__step-num">4</div>
                    <h3 class="processus__step-title">Livraison régulière</h3>
                    <p class="processus__step-text">
                        Nous organisons un rythme de livraison adapté à vos ventes et à votre stock.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CATALOGUE
    ══════════════════════════════════════ --}}
    <section class="catalogue" id="catalogue" aria-labelledby="titre-catalogue">
        <div class="inner">
            <p class="section-label">Nos produits</p>
            <h2 class="section-title" id="titre-catalogue">Notre catalogue</h2>

            <div class="catalogue__layout">

                {{-- Colonne gauche --}}
                <div class="catalogue__left">

                    {{-- Formes --}}
                    <div class="cat-block">
                        <div class="cat-block__header">
                            <div class="cat-block__header-icon">
                                <i class="ti ti-circles" aria-hidden="true"></i>
                            </div>
                            <h3 class="cat-block__title">Les Formes</h3>
                            <span class="cat-block__count">{{ $formes->count() }} formes</span>
                        </div>
                        <div class="products-grid">
                            @foreach ($formes as $forme)
                            <article class="prod-card" aria-label="{{ $forme->nom_forme }}">
                                <div class="prod-card__img-wrap">
                                    <img
                                        src="{{ asset('fichier/image/meringues/' . $forme->nom_forme . '.webp') }}"
                                        class="prod-card__img"
                                        alt="{{ $forme->nom_forme }}"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </div>
                                <div class="prod-card__body">
                                    <p class="prod-card__name">{{ $forme->nom_forme }}</p>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>

                    {{-- Conditionnements --}}
                    <div class="cat-block">
                        <div class="cat-block__header">
                            <div class="cat-block__header-icon">
                                <i class="ti ti-package" aria-hidden="true"></i>
                            </div>
                            <h3 class="cat-block__title">Les Conditionnements</h3>
                        </div>

                        @php
                            $diffCondi         = [];
                            $sachetDejaAjoute  = false;
                            $boiteDejaAjoute   = false;
                            $typesDejaAjoutes  = [];

                            foreach ($conditionnements as $c) {
                                if ($c->type === 'sachet_de_10' || $c->type === 'sachet_de_4') {
                                    if (!$sachetDejaAjoute) {
                                        $diffCondi[]      = (object)['type' => 'sachet'];
                                        $sachetDejaAjoute = true;
                                    }
                                } elseif ($c->type === 'boite_de_8') {
                                    if (!$boiteDejaAjoute) {
                                        $diffCondi[]     = (object)['type' => 'boite'];
                                        $boiteDejaAjoute = true;
                                    }
                                } else {
                                    if (!in_array($c->type, $typesDejaAjoutes)) {
                                        $diffCondi[]        = $c;
                                        $typesDejaAjoutes[] = $c->type;
                                    }
                                }
                            }
                        @endphp

                        <div class="products-grid">
                            @foreach ($diffCondi as $condi)
                            <article class="prod-card" aria-label="{{ $condi->type }}">
                                <div class="prod-card__img-wrap">
                                    <img
                                        src="{{ asset('fichier/image/meringues/' . $condi->type . '.webp') }}"
                                        class="prod-card__img"
                                        alt="{{ ucfirst(str_replace('_', ' ', $condi->type)) }}"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </div>
                                <div class="prod-card__body">
                                    <p class="prod-card__name">
                                        {{ ucfirst(str_replace('_', ' ', $condi->type)) }}
                                    </p>
                                    @if ($condi->type === 'sachet')
                                    <div class="prod-card__badges">
                                        <span class="badge">× 4</span>
                                        <span class="badge">× 10</span>
                                    </div>
                                    @elseif ($condi->type === 'boite')
                                    <div class="prod-card__badges">
                                        <span class="badge">× 8</span>
                                    </div>
                                    @endif
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Colonne droite : Parfums --}}
                <aside class="catalogue__right" aria-label="Liste des parfums">
                    <div class="parfums-panel">
                        <div class="parfums-panel__header">
                            <div class="cat-block__header-icon">
                                <i class="ti ti-flower" aria-hidden="true"></i>
                            </div>
                            <h3 class="cat-block__title">Les Parfums</h3>
                        </div>

                        <input
                            type="search"
                            class="parfums-search"
                            placeholder="Rechercher un parfum…"
                            aria-label="Filtrer les parfums"
                            id="parfums-search"
                        >

                        <ul class="parfums-list" role="list" id="parfums-list">
                            @foreach ($parfums as $parfum)
                            <li class="parfum-item" data-nom="{{ strtolower($parfum->nom_parfum) }}">
                                <i class="ti ti-point-filled parfum-item__dot" aria-hidden="true"></i>
                                {{ $parfum->nom_parfum }}
                            </li>
                            @endforeach
                        </ul>

                        <p class="parfums-note">
                            <i class="ti ti-sparkles" aria-hidden="true"></i>
                            D'autres saveurs peuvent être développées sur demande.
                        </p>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CITATION
    ══════════════════════════════════════ --}}
    <section class="citation" aria-label="Citation">
        <div class="inner">
            <p class="citation__text">
                Des meringues confectionnées avec passion, des saveurs qui font voyager,
                une qualité artisanale que vos clients reconnaîtront.
            </p>
            <p class="citation__author">La Maison des Meringues — Normandie</p>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CONTACT
    ══════════════════════════════════════ --}}
    <section class="contact" id="contact" aria-labelledby="titre-contact">
        <div class="inner">
            <p class="section-label section-label--rose">Travailler ensemble</p>
            <h2 class="section-title section-title--white" id="titre-contact">
                Nous contacter
            </h2>

            <div class="contact__layout">

                {{-- Formulaire --}}
                <div class="contact__form-wrap">
                    <h3 class="contact__form-title">
                        <i class="ti ti-send" aria-hidden="true" style="color:var(--rose-lt);margin-right:.4rem;"></i>
                        Envoyer une demande
                    </h3>

                    <form method="POST" action="{{ url('/pro/contact') }}" novalidate>
                        @csrf

                        <div class="form-row">
                            <div>
                                <label class="form-label" for="prenom">Prénom</label>
                                <input
                                    type="text"
                                    id="prenom"
                                    name="prenom"
                                    class="form-input"
                                    placeholder="Marie"
                                    required
                                >
                            </div>
                            <div>
                                <label class="form-label" for="nom">Nom</label>
                                <input
                                    type="text"
                                    id="nom"
                                    name="nom"
                                    class="form-input"
                                    placeholder="Dupont"
                                    required
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="societe">Société / Établissement</label>
                            <input
                                type="text"
                                id="societe"
                                name="societe"
                                class="form-input"
                                placeholder="Épicerie du Marché"
                            >
                        </div>

                        <div class="form-row">
                            <div>
                                <label class="form-label" for="email">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-input"
                                    placeholder="vous@exemple.fr"
                                    required
                                >
                            </div>
                            <div>
                                <label class="form-label" for="telephone">Téléphone</label>
                                <input
                                    type="tel"
                                    id="telephone"
                                    name="telephone"
                                    class="form-input"
                                    placeholder="06 00 00 00 00"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="type_pro">Type d'activité</label>
                            <select id="type_pro" name="type_pro" class="form-select">
                                <option value="" disabled selected>Choisir votre activité…</option>
                                <option value="epicerie">Épicerie fine</option>
                                <option value="restaurant">Restaurant / Hôtel</option>
                                <option value="revendeur">Revendeur</option>
                                <option value="evenement">Événementiel</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="message">Message</label>
                            <textarea
                                id="message"
                                name="message"
                                class="form-textarea"
                                placeholder="Décrivez votre projet, vos volumes estimés, vos questions…"
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="ti ti-send" aria-hidden="true"></i>
                            Envoyer ma demande
                        </button>
                    </form>
                </div>

                {{-- Infos de contact --}}
                <div class="contact__info">
                    <p class="section-sub section-sub--white">
                        Vous êtes professionnel et souhaitez référencer nos produits&nbsp;?
                        Écrivez-nous ou appelez-nous directement — nous répondons sous 24&nbsp;h.
                    </p>

                    <div class="contact__info-cards">
                        <div class="contact-info-card">
                            <div class="contact-info-card__icon">
                                <i class="ti ti-phone" aria-hidden="true"></i>
                            </div>
                            <div>
                                <div class="contact-info-card__label">Téléphone</div>
                                <a href="tel:+33235102449" class="contact-info-card__val">
                                    02 35 10 24 49
                                </a>
                            </div>
                        </div>

                        <div class="contact-info-card">
                            <div class="contact-info-card__icon">
                                <i class="ti ti-mail" aria-hidden="true"></i>
                            </div>
                            <div>
                                <div class="contact-info-card__label">Email</div>
                                <a href="mailto:contact@lamaisondesmeringues.fr" class="contact-info-card__val">
                                    contact@lamaisondesmeringues.fr
                                </a>
                            </div>
                        </div>

                        <div class="contact-info-card">
                            <div class="contact-info-card__icon">
                                <i class="ti ti-map-pin" aria-hidden="true"></i>
                            </div>
                            <div>
                                <div class="contact-info-card__label">Adresse</div>
                                <div class="contact-info-card__val">
                                    18 rte de Fécamp<br>
                                    Ypreville-Biville, 76540
                                </div>
                            </div>
                        </div>

                        <div class="contact-info-card">
                            <div class="contact-info-card__icon">
                                <i class="ti ti-clock" aria-hidden="true"></i>
                            </div>
                            <div>
                                <div class="contact-info-card__label">Horaires</div>
                                <div class="contact-info-card__val">Lun – Sam : 9h – 18h</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<script>
/* Filtre parfums en live */
document.getElementById('parfums-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#parfums-list .parfum-item').forEach(li => {
        li.style.display = li.dataset.nom.includes(q) ? '' : 'none';
    });
});

/* Fallback image manquante */
document.querySelectorAll('.prod-card__img').forEach(img => {
    img.addEventListener('error', () => {
        img.src = '/fichier/image/meringues/oups.webp';
    });
});
</script>

</body>
@include('templet.footer')
</html>