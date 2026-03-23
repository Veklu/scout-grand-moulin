[Lire en francais](README.fr.md)

# Scout Grand-Moulin — WordPress Theme

A custom WordPress theme for the 5e Groupe scout Grand-Moulin (Deux-Montagnes, Quebec). Built for Scouts du Canada groups with accessibility (WCAG 2.1 AA) and Quebec privacy law (Loi 25) compliance.

## Features

- **Responsive design** — Mobile-first layout optimized for families
- **Custom page templates** — Units, Registration, Gallery, Calendar, Volunteers, Privacy Policy, Terms
- **Photo gallery** — Album-based gallery with lightbox and categorization
- **Cookie consent** — Loi 25 compliant cookie banner
- **Accessibility** — WCAG 2.1 AA compliant with skip links, ARIA labels, and keyboard navigation
- **Custom block** — Scout bubble block for the Gutenberg editor

## Installation

1. Compress the `scout-grand-moulin` folder as a `.zip`
2. WordPress: Appearance > Themes > Add New > Upload
3. Activate the theme

## Configuration

1. **Logo**: Appearance > Customize > Site Identity
2. **Menus**: Appearance > Menus > create "Main Navigation"
   - For CTA buttons: add the CSS class `menu-item-cta`
3. **Pages**: Create each page and assign the template:
   - Home > set as static front page (Settings > Reading)
   - Units, Registration, Gallery, Calendar, Volunteers, Privacy, Terms
4. **Contact info**: Appearance > Customize > Group Contact Information
5. **Privacy**: Settings > Privacy > select the page

## Internationalization

The theme supports French (default) and English. To switch language, set your WordPress locale via Settings > General > Site Language.

## License

[MIT](LICENSE)
