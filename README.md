# Thème WordPress — 5e Groupe scout Grand-Moulin

## Installation
1. Compresser le dossier `scout-grand-moulin` en `.zip`
2. WordPress : Apparence → Thèmes → Ajouter → Téléverser
3. Activer le thème

## Configuration
1. **Logo** : Apparence → Personnaliser → Identité du site
2. **Menus** : Apparence → Menus → créer « Navigation principale »
   - Pour le CTA : ajouter la classe CSS `menu-item-cta`
3. **Pages** : Créer chaque page et assigner le modèle :
   - Accueil → page d'accueil statique (Réglages → Lecture)
   - Nos unités, Inscription, Galerie, Agenda, Notre équipe, Confidentialité, Conditions
4. **Coordonnées** : Apparence → Personnaliser → Coordonnées du groupe
5. **Confidentialité** : Réglages → Confidentialité → sélectionner la page

## Plugin d'inscription
Le formulaire est statique (HTML/JS). Pour la production, développer le plugin
selon `addendum-plugin-inscription.docx` et `addendum-securite-qr.docx`.
