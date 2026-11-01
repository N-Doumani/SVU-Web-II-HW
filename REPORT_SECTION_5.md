# 5. Styling and Responsiveness (CSS, Bootstrap 5)

## Overview
The interface is built with **Bootstrap 5.3** (grid system and components) and **Bootstrap Icons**, plus one project stylesheet (`assets/css/style.css`) that adds a consistent visual theme. A small script (`assets/js/ui.js`) handles presentation behaviour only, such as notifications, image preview and the delete confirmation dialog. All pages share the same header and footer (`includes/header.php`, `includes/footer.php`), so the navigation, fonts and styles are identical across the site.

## Design system
- **Design tokens:** colours, border radius and shadows are defined once as CSS variables in `:root` and mapped onto Bootstrap's own variables (`--bs-primary`, `--bs-body-font-family`, …). Changing one value updates the whole site.
- **Typography:** Inter (Google Fonts). Heading sizes use `clamp()`, so they scale smoothly between phone and desktop.
- **Components:** buttons, forms, tables, cards, pagination, modals, toasts and alerts all use Bootstrap classes, with light overrides for a single consistent look.

## Responsive layout (Bootstrap grid)
| Screen | Behaviour |
|---|---|
| **Desktop (≥ 992px)** | Full navbar; student form in two columns (photo card `col-lg-4` + details `col-lg-8`); the table shows all columns. |
| **Tablet (768–991px)** | Navbar collapses into a menu button; the *Address* and *Added* columns are hidden (`d-none d-xl-table-cell`, `d-none d-lg-table-cell`). |
| **Phone (< 768px)** | Each table row turns into a stacked card (`.table-stack`, CSS `display:block` + `td::before { content: attr(data-label) }`); form fields stack in one column; action buttons become full width; pagination shows only Previous / current / Next. |

The site was tested at 375px, 768px and 1366px with no horizontal scrolling.

## Page-by-page
- **Home (`index.html`):** gradient hero with call-to-action buttons and four quick-action cards (`col-12 col-sm-6 col-lg-3`).
- **Login / Register:** split layout on desktop (brand panel + form card); on mobile, only the centred form card is shown. Includes a show/hide password button and inline error alerts.
- **Add / Update Student:** one shared form partial (`includes/student_form.php`) with a live profile-picture preview, Bootstrap validation states (`.is-invalid` / `.was-validated`) and a loading spinner on submit.
- **View Students:** search bar and sort menu, a table with avatar initials when there is no photo, Edit / Delete buttons with tooltips, Bootstrap pagination, an empty state and a **modal confirmation** before deleting.

## Accessibility and quality
- Semantic HTML (`nav`, `main`, `footer`, `th scope`), `aria-label`s on icon-only buttons, a *Skip to content* link and visible keyboard focus rings.
- All dynamic values are escaped with `htmlspecialchars()` (helper `e()`) to prevent XSS.
- `prefers-reduced-motion` is respected, and the CSS is organised into 15 commented sections.

## Suggested screenshots for this section
1. Home page — desktop and mobile
2. View Students — desktop table vs. mobile cards
3. Add Student form showing validation errors
4. Delete confirmation modal
5. Mobile navbar opened
