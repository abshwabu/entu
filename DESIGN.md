# Entu Creative — Brand Design System & Theming Specification

## 1. Brand Identity Overview
Entu Creative is an editorial graphic design and art direction studio. The brand identity is grounded in an ultra-high-contrast electric aesthetic: a pure black ground (`#0A0A0A`) punctuated by a signature vivid lime-green aurora gradient (`#A6F000` &rarr; `#7ED321` &rarr; `#4FA800` &rarr; `#2A6B00`), with lowercase geometric wordmark "entu" and letter-spaced "CREATIVE".

---

## 2. Sampled Color Palette & Scale

Colors sampled from `public/brand/logo.png` using direct pixel analysis:
- **Bright Electric Lime**: `#A1EB00` &rarr; mapped to `brand-400` (`#A6F000`)
- **Core Lime Gradient**: `#78CA09` &rarr; mapped to `brand-500` (`#7ED321`)
- **Mid Leaf Green**: `#6ABD16` &rarr; mapped to `brand-700` (`#4FA800`)
- **Deep Emerald Base**: `#263D0A` &rarr; mapped to `brand-800` (`#2A6B00`)
- **Absolute Ground**: `#0A0A0A` &rarr; mapped to `ink`

### 2.1 The Semantic Brand Scale
| Token | Hex | RGB Tuple | Role & Context |
|---|---|---|---|
| `brand-50` | `#F7FEE7` | `247, 254, 231` | Tinted background highlight in light mode |
| `brand-100` | `#ECFCCB` | `236, 252, 203` | Subtle badge background in light mode |
| `brand-200` | `#D9F99D` | `217, 249, 157` | Accent border in light mode |
| `brand-300` | `#BEF264` | `190, 242, 100` | Vibrant chart / illustrative highlight |
| `brand-400` | `#A6F000` | `166, 240, 0` | **Primary Brand Lime** — Primary CTAs, active pills, cursor |
| `brand-500` | `#7ED321` | `126, 211, 33` | **Core Brand Lime** — Button hover state, aurora mid-tone |
| `brand-600` | `#65B80A` | `101, 184, 10` | Vibrant emerald link hover |
| `brand-700` | `#4FA800` | `79, 168, 0` | Darker emerald accent |
| `brand-800` | `#2A6B00` | `42, 107, 0` | **Accessible text accent on light backgrounds** (WCAG AA) |
| `brand-900` | `#1E4D00` | `30, 77, 0` | Deep emerald (WCAG AAA text on light backgrounds) |
| `brand-950` | `#0E2900` | `14, 41, 0` | Deepest foliage shadow |

### 2.2 Semantic Neutrals & Surfaces
| Token | Hex | CSS Variable | Purpose |
|---|---|---|---|
| `ink` | `#0A0A0A` | `--color-ink` | Primary dark canvas background & primary light body text |
| `ink-soft` | `#141414` | `--color-ink-soft` | Elevated dark surface (cards, dropdowns, inputs) |
| `ink-raised` | `#1C1C1C` | `--color-ink-raised` | Raised dark surface (modals, tooltips, lightboxes) |
| `paper` | `#F6F8F2` | `--color-paper` | Crisp warm paper canvas in light mode |
| `paper-raised`| `#FFFFFF` | `--color-paper-raised`| Clean elevated surface in light mode |
| `line` | `rgba(255,255,255,0.08)` / `rgba(10,10,10,0.08)` | `--color-line` | Subtle structural border lines |
| `text-muted` | `#8A8F85` | `--color-text-muted` | Metadata, tags, captions, form hints |

---

## 3. WCAG AA / AAA Accessibility Compliance

The palette has been mathematically verified using relative luminance calculations under WCAG 2.1 standards:

| Foreground | Background | Contrast Ratio | WCAG Compliance | Usage Case |
|---|---|---|---|---|
| `#0A0A0A` (Ink) | `#A6F000` (Brand 400) | **14.22 : 1** | **AAA** (Exceeds 7.0:1) | Primary CTA button label, active filter pills |
| `#0A0A0A` (Ink) | `#7ED321` (Brand 500) | **10.58 : 1** | **AAA** (Exceeds 7.0:1) | Primary CTA hover state |
| `#A6F000` (Brand 400) | `#0A0A0A` (Ink) | **14.22 : 1** | **AAA** (Exceeds 7.0:1) | Dark mode headlines, active nav links, cursor |
| `#7ED321` (Brand 500) | `#0A0A0A` (Ink) | **10.58 : 1** | **AAA** (Exceeds 7.0:1) | Dark mode badges, quote marks, focus rings |
| `#2A6B00` (Brand 800) | `#F6F8F2` (Paper) | **6.13 : 1** | **AA** (Exceeds 4.5:1) | Text links and badges in light mode |
| `#1E4D00` (Brand 900) | `#F6F8F2` (Paper) | **8.18 : 1** | **AAA** (Exceeds 7.0:1) | Small text links and high-contrast light mode body |
| `#F6F8F2` (Paper) | `#0A0A0A` (Ink) | **18.50 : 1** | **AAA** (Exceeds 7.0:1) | Primary dark mode body text |
| `#0A0A0A` (Ink) | `#F6F8F2` (Paper) | **18.50 : 1** | **AAA** (Exceeds 7.0:1) | Primary light mode body text |

> [!IMPORTANT]
> **Light Mode Rule**: Never use light lime (`#A6F000` or `#7ED321`) as bare text on white or paper backgrounds (contrast ratio < 1.6:1). In light mode, always use `#A6F000` as a solid fill with `#0A0A0A` text, or switch the text color to `#2A6B00` (`brand-800`) or `#1E4D00` (`brand-900`).

---

## 4. Design Guidelines & Usage Rules

1. **Single Accent Rule**: Lime is the sole chromatic accent on the website. No secondary accent colors (amber, orange, purple, blue) may be introduced in UI chrome or actions.
2. **Dark Mode Default**: The portfolio defaults to dark mode (`#0A0A0A` Ink ground). Light mode (`#F6F8F2` Paper ground) is toggled via navbar and persists in `localStorage`.
3. **The Signature Aurora Drift (`.bg-brand-aurora`)**:
   - Implemented via dynamic radial gradients transitioning between `#A6F000`, `#7ED321`, `#4FA800`, and `#0A0A0A`.
   - Subtle 24s infinite animation drift (`keyframes aurora-drift`).
   - Automatically disabled when `prefers-reduced-motion: reduce` is detected.
4. **Primary CTA Buttons**:
   - Filled with `brand-400` (`#A6F000`), heavy font weight (`font-bold`), text color `ink` (`#0A0A0A`).
   - Hover state smoothly transitions to `brand-500` (`#7ED321`).
5. **Secondary CTA Buttons**:
   - Transparent background, subtle border (`border-line` or `border-brand-400/40`), text adapts to theme.
6. **Custom Cursor**:
   - Ring: 32px diameter, `border-brand-400` with 0.8 opacity, smooth spring tracking.
   - Dot: 6px diameter, filled with `#A6F000`.
   - Hidden on touch screens and when `prefers-reduced-motion` is active.
7. **Typography**:
   - Display & Headings: `font-display` with Montserrat / Syne (`font-black`, `font-extrabold`).
   - Body & Meta: `font-sans` with Plus Jakarta Sans (`font-normal`, `font-medium`, `font-semibold`).
   - Monospace: `font-mono` for categories, metrics, and timestamps.

---

## 5. Filament Admin Panel Integration

The Filament 3 Admin Panel at `/admin` matches the public brand identity:
- **Default Theme**: Dark mode (`ThemeMode::Dark` via `AdminPanelProvider`).
- **Primary Color**: Custom RGB palette scale matching `brand-50` through `brand-950`.
- **Branding**:
  - `brandName`: "Entu Creative"
  - `brandLogo`: Rendered via `resources/views/filament/brand.blade.php` displaying `/brand/logo-icon.svg` alongside crisp wordmark typography.
  - `favicon`: Set to `/brand/favicon-32x32.png`.

---

## 6. Generated Brand Assets & Vector Guidelines

All assets are located in `public/brand/`:
- `logo.svg`: Primary vector logo with electric swirl gradient, pure black ground, and letter-spaced typography.
- `logo.png`: 512x512 high-resolution raster master logo.
- `logo-transparent.png`: Transparent background edition for overlays and presentations.
- `logo-icon.svg` & `logo-icon.png`: Standalone circular mark for avatars, navbars, and app icons.
- `logo-mono-black.svg` & `logo-mono-white.svg`: Single-color variations for print, embroidery, and monochrome engraving.
- `favicon.ico`, `favicon-16x16.png`, `favicon-32x32.png`: Multi-resolution browser favicons.
- `apple-touch-icon.png`: 180x180 iOS home screen icon.
- `android-chrome-192x192.png` & `android-chrome-512x512.png`: PWA manifest icons.
- `site.webmanifest`: Web application manifest referencing icons and `#0A0A0A` theme color.
- `og-image.png`: 1200x630 OpenGraph / Twitter Card preview image.

### 6.1 Swapping Brand Vectors
If a new vector logo is produced by the design team:
1. Replace `public/brand/logo.svg` with the new SVG file.
2. Replace `public/brand/logo-icon.svg` with the isolated circular mark.
3. Run `python3 scripts/generate_brand_identity.py` to regenerate all derived PNGs, favicons, manifests, and OG assets automatically.
