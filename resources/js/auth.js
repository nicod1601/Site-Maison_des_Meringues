/**
 * Pages d'authentification — petits comportements sans dépendance.
 *  - afficher / masquer le mot de passe
 *  - indicateur de robustesse (champ [data-strength="<id>"])
 *  - vérification de la confirmation (champ [data-match="<id>"])
 *  - état « chargement » du bouton + protection contre le double envoi
 */
document.addEventListener('DOMContentLoaded', () => {

  // 1. Œil afficher / masquer
  document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
    const input = document.getElementById(btn.dataset.togglePassword);
    if (!input) return;

    btn.addEventListener('click', () => {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      input.toggleAttribute('data-password-visible', show);
      btn.setAttribute('aria-pressed', String(show));
      btn.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
      btn.firstElementChild.className = show ? 'ti ti-eye-off' : 'ti ti-eye';
      input.focus({ preventScroll: true });
    });
  });

  // 2. Robustesse du mot de passe
  const LABELS = ['', 'Trop faible', 'Moyen', 'Bon', 'Excellent'];

  const score = (v) => {
    if (!v) return 0;
    let s = 0;
    if (v.length >= 8)  s++;
    if (v.length >= 12) s++;
    if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
    if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) s++;
    return Math.max(1, Math.min(4, s));
  };

  document.querySelectorAll('input[data-strength]').forEach((input) => {
    const box = document.getElementById(input.dataset.strength);
    if (!box) return;
    const label = box.querySelector('.strength__label');

    input.addEventListener('input', () => {
      const level = score(input.value);
      box.hidden = level === 0;
      box.dataset.level = level;
      label.textContent = LABELS[level] ?? '';
    });
  });

  // 3. Confirmation du mot de passe
  document.querySelectorAll('input[data-match]').forEach((input) => {
    const source = document.getElementById(input.dataset.match);
    if (!source) return;

    const check = () => {
      const filled = input.value.length > 0;
      input.classList.toggle('is-match', filled && input.value === source.value);
      input.classList.toggle('is-mismatch', filled && input.value !== source.value);
    };
    input.addEventListener('input', check);
    source.addEventListener('input', check);
  });

  // 4. Bouton en chargement + anti double-clic
  document.querySelectorAll('form.auth-form').forEach((form) => {
    form.addEventListener('submit', (e) => {
      const btn = form.querySelector('.btn-submit');
      if (!btn) return;
      if (btn.classList.contains('is-loading')) { e.preventDefault(); return; }
      btn.classList.add('is-loading');
      btn.setAttribute('aria-busy', 'true');
    });
  });

  // Retour arrière (bfcache) : on rend le bouton cliquable à nouveau
  window.addEventListener('pageshow', (e) => {
    if (!e.persisted) return;
    document.querySelectorAll('.btn-submit.is-loading').forEach((b) => {
      b.classList.remove('is-loading');
      b.removeAttribute('aria-busy');
    });
  });
});
