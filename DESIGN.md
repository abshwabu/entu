# Entu Creative — Violet–Magenta Gradient Brand Design System & Theming Specification

## 1. Brand Identity Overview
Entu Creative is an editorial graphic design and art direction studio based in Addis Ababa, Ethiopia. Following the client's brand update, this specification documents the **Violet–Magenta Gradient** visual identity, which completely replaces the earlier experimental palettes across the entire public application and Filament 3 admin.

The core logo at `public/brand/logo.png` features a 135° diagonal gradient disc transitioning from deep indigo-violet (top-left) to vivid magenta-purple (bottom-right), inscribed with a lowercase geometric "entu" wordmark and letter-spaced "CREATIVE" descriptor, resting on an obsidian-black ground (`#0A0A0A`).

---

## 2. Logo Sampling & Physical Pixel Reference

Physical pixel values were sampled directly from `public/brand/logo.png` (1024×1024) using a GD analysis script (`scripts/sample_logo.php`). The samples verify and confirm the exact chromatic trajectory along the 135° diagonal axis:

| Color Stage | Estimated Hex | Sampled Hex | Sampled RGB | Role & Meaning |
|---|---|---|---|---|
| **Violet-deep** (gradient start) | `#3B0F8F` | `#3F1093` | `rgb(63, 16, 147)` | Deep indigo-violet origin (top-left, 15% diameter) |
| **Violet** (upper-mid) | `#5B1BB0` | `#5A1AAF` | `rgb(90, 26, 175)` | Saturated royal violet (35% diameter) |
| **Purple** (mid) | `#8A1FC9` | `#881EC8` | `rgb(136, 30, 200)` | Luminous electric purple (55% diameter) |
| **Magenta** (gradient end) | `#B820D8` | `#B01FD5` | `rgb(176, 31, 213)` | Vivid magenta-purple terminus (bottom-right, 85% diameter) |
| **Black** (logo canvas background) | `#0A0A0A` | `#0A0A0A` | `rgb(10, 10, 10)` | Pure dark neutral ground |

**Gradient Vector Angle**: Exactly **135°** (`linear-gradient(135deg, ...)`).

All prior tokens and utilities (`lime`, `teal`, `mint`, `aqua`, `silk`) have been completely purged from the codebase.

---

## 3. Design Tokens & Theme Architecture

The system uses Tailwind CSS v4 `@theme` tokens in `resources/css/app.css` paired with synchronized CSS root variables.

### 3.1 The Brand Palette (50–950)
The brand scale smoothly interpolates between violet-deep and vivid magenta, centered at purple (500 ≈ `#881EC8`):

| Token | Hex | RGB Tuple | Usage & Context |
|---|---|---|---|
| `brand-50` | `#FBF6FE` | `251, 246, 254` | Faint lilac-tinted surface highlight |
| `brand-100` | `#F4EAFD` | `244, 234, 253` | Soft hover states in light mode |
| `brand-200` | `#E8D5FB` | `232, 213, 251` | Subtle pill borders & inactive controls |
| `brand-300` | `#D5B2F7` | `213, 178, 247` | Mid-range lilac decorative strokes |
| `brand-400` | `#BC7FF2` | `188, 127, 242` | Bright hover states & glowing aura rings |
| `brand-500` | `#881EC8` | `136, 30, 200` | **Core Brand Purple** — Primary accents & mid-gradient anchor |
| `brand-600` | `#7817B5` | `120, 23, 181` | Deep purple interactive borders & badges |
| `brand-700` | `#5A1AAF` | `90, 26, 175` | Upper-mid violet anchor |
| `brand-800` | `#3F1093` | `63, 16, 147` | Deep indigo-violet origin anchor |
| `brand-900` | `#2D0A6C` | `45, 10, 108` | Deepest shadow tone |
| `brand-950` | `#1A0444` | `26, 4, 68` | Nocturnal indigo-black |

### 3.2 High-Luminance Dark Mode Tints
Saturated brand steps (500–900) are too dark to read as text on `#0A0710`. Two specialized pastel tints ensure accessible typographic accents in dark mode:
- **`lilac`** (`#C9A8FF` / `rgb(201, 168, 255)`): Links, active nav states, eyebrow tags, hover outlines.
- **`orchid`** (`#E7A6FF` / `rgb(231, 166, 255)`): Headline gradient text endpoint, active ping indicators.

### 3.3 Neutrals & Structural Tokens
- **Dark Mode (Default)**:
  - `ink` (`#0A0710`): Page canvas ground (faintly violet-tinted near-black).
  - `ink-soft` (`#120C1C`): Surface canvas (cards, navbar, modals).
  - `ink-raised` (`#1B1329`): Elevated surfaces (dropdowns, tooltips, hover cards).
  - `line` (`rgba(255, 255, 255, 0.10)`): Crisp hairline dividers and borders.
  - `text-muted-dark` (`#9E92B6`): Secondary captions and timestamps.
- **Light Mode**:
  - `paper` (`#F7F4FB`): Page canvas ground (faint violet-tinted off-white).
  - `paper-raised` (`#FFFFFF`): Elevated clean white cards.
  - `text-ink` (`#17102A`): Deep obsidian-violet readable body and heading text.
  - `text-muted-light` (`#6B5E87`): Secondary captions and metadata.

### 3.4 Brand Gradient Utilities
```css
/* Signature 135deg Gradient */
.bg-brand-gradient {
    background-image: linear-gradient(135deg, #3F1093 0%, #5A1AAF 30%, #881EC8 65%, #B01FD5 100%);
}

.border-brand-gradient {
    border-image: linear-gradient(135deg, #3F1093, #B01FD5) 1;
}

.text-brand-gradient {
    background-image: linear-gradient(135deg, #C9A8FF 0%, #E7A6FF 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
```

---

## 4. Dark Mode (Default Studio Aesthetic)

1. **Canvas & Surfaces**: The default background is `bg-ink` (`#0A0710`). Cards and sticky navigation use `bg-ink-soft` (`#120C1C`) with subtle `border-line` (`white/10`) borders. Elevated popups and active items use `bg-ink-raised` (`#1B1329`). Lightbox and footer use near-black `#0A0710`.
2. **Typography**: Headings and body copy use `#FFFFFF` or off-white. Secondary text uses `text-text-muted-dark` (`#9E92B6`). Small typographic accents (eyebrow tags, links, active category pills, quote indicators) use `text-lilac` (`#C9A8FF`) or `text-orchid` (`#E7A6FF`), never dark violet steps.
3. **Buttons**:
   - Primary: `.bg-brand-gradient text-white font-bold shadow-lg hover:shadow-brand-500/30 hover:brightness-110`.
   - Secondary / Outline: Transparent background, `border-lilac/70 text-white hover:bg-brand-500/10`.
4. **Headline Accents**: Expressive hero titles use `.text-brand-gradient` restricted to `lilac` &rarr; `orchid`, ensuring crisp contrast (> 10:1) on `#0A0710`.
5. **Signature Aurora Backdrop (`.bg-aurora`)**: Two large blurred radial blooms (violet and magenta) drifting over `ink`, layered with a fine grain texture. Animation duration is 24s; gracefully disabled under `prefers-reduced-motion`.
6. **Focus Ring & Details**:
   - Focus ring: 2px `lilac` with a soft violet outer glow (`box-shadow: 0 0 0 3px rgba(136, 30, 200, 0.45)`).
   - `::selection`: Solid magenta background (`#B01FD5`), pure white text.
   - Custom Cursor: Gradient ring and glowing dot with mix-blend-difference tracking the mouse on desktop.
   - Scrollbar thumb & scroll progress bar: 135° brand gradient fill.

---

## 5. Light Mode (Brand as Decoration, NOT Text)

> [!IMPORTANT]
> ### Strict Light Mode Rule: No Text May Use Brand Colors
> To ensure flawless accessibility and editorial legibility, **no text in light mode uses the violet, purple, or magenta brand colors**.
>
> **Enforced Rules**:
> 1. All headings and body text are rendered in `text-ink` (`#17102A`) or `text-text-muted-light` (`#6B5E87`).
> 2. Links are `text-ink` with a thick gradient underline that expands on hover (`bg-gradient-to-r from-brand-violet to-brand-magenta`).
> 3. The brand gradient is reserved strictly for non-text graphical roles:
>    - Solid button fills with white text (verified > 4.5:1 AA contrast).
>    - Active category filter pill fills with crisp white text.
>    - Underline bars and accent highlights behind headings.
>    - Card top-accent strips, borders, and dividers.
>    - Badges & chips: tinted fill (`bg-brand-500/10`) with obsidian `text-text-ink`.
>    - Form control focus rings: 2px `text-ink` inner ring with violet outer glow.
>    - Subtle section atmospheric washes (`brand-500` at 5–8% opacity).

---

## 6. WCAG 2.1 Contrast Ratios & Compliance Verification

Every textual pair has been mathematically audited using the WCAG 2.1 relative luminance specification ($L = 0.2126R + 0.7152G + 0.0722B$):

| Foreground Element | Background Element | Contrast Ratio | WCAG Rating | Purpose & Verified Status |
|---|---|---|---|---|
| **White** (`#FFFFFF`) | **Magenta** (`#B01FD5`) | **4.96 : 1** | **Passes AA** (&gt; 4.5:1) | White text on lightest terminus of primary button gradient |
| **White** (`#FFFFFF`) | **Purple** (`#881EC8`) | **6.73 : 1** | **Passes AA** (&gt; 4.5:1) | White text on center mid-point of primary buttons |
| **White** (`#FFFFFF`) | **Violet** (`#5A1AAF`) | **9.42 : 1** | **Passes AAA** (&gt; 7.0:1) | White text on upper-mid gradient of primary buttons |
| **White** (`#FFFFFF`) | **Violet-deep** (`#3F1093`) | **12.72 : 1** | **Passes AAA** (&gt; 7.0:1) | White text on start origin of primary buttons |
| **White** (`#FFFFFF`) | **Ink** (`#0A0710`) | **19.98 : 1** | **Passes AAA** (&gt; 7.0:1) | White headings & body copy on dark mode canvas |
| **Lilac** (`#C9A8FF`) | **Ink** (`#0A0710`) | **10.02 : 1** | **Passes AAA** (&gt; 7.0:1) | Dark mode accent labels, active nav links, focus rings |
| **Orchid** (`#E7A6FF`) | **Ink** (`#0A0710`) | **10.75 : 1** | **Passes AAA** (&gt; 7.0:1) | Dark mode headline gradient endpoint & live indicators |
| **Text-ink** (`#17102A`) | **Paper** (`#F7F4FB`) | **16.86 : 1** | **Passes AAA** (&gt; 7.0:1) | Light mode readable body and heading text |
| **Text-ink** (`#17102A`) | **Paper-raised** (`#FFFFFF`) | **18.06 : 1** | **Passes AAA** (&gt; 7.0:1) | Light mode cards & elevated surfaces |
| **Muted-Dark** (`#9E92B6`)| **Ink** (`#0A0710`) | **6.89 : 1** | **Passes AA** (&gt; 4.5:1) | Dark mode secondary captions, dates, and metadata |
| **Muted-Light** (`#6B5E87`)| **Paper** (`#F7F4FB`) | **5.39 : 1** | **Passes AA** (&gt; 4.5:1) | Light mode secondary captions, dates, and metadata |

**Result**: 100% of text/background combinations meet or exceed WCAG 2.1 AA requirements, with the majority qualifying for AAA.

---

## 7. Filament 3 Admin Integration
The administrative dashboard at `/admin` is fully synchronized with the brand:
- **Default Theme**: Dark mode (`ThemeMode::Dark`).
- **Primary Color Scale**: Configured in `app/Providers/Filament/AdminPanelProvider.php` using exact RGB triplets:
  - `50 => '251, 246, 254'`, `100 => '244, 234, 253'`, `200 => '232, 213, 251'`, `300 => '213, 178, 247'`, `400 => '188, 127, 242'`, `500 => '136, 30, 200'`, `600 => '120, 23, 181'`, `700 => '90, 26, 175'`, `800 => '63, 16, 147'`, `900 => '45, 10, 108'`, `950 => '26, 4, 68'`.
- **Brand Identity Component**: `resources/views/filament/brand.blade.php` displays the brand logo inside a dark rounded backing with `text-brand-purple dark:text-lilac` studio title.
- **Favicon & Logo**: Uses `/brand/favicon-32x32.png` and `/brand/logo.png`.

---

## 8. Brand Assets & Logo Component Architecture

### 8.1 Regenerated Brand Suite
All brand files have been regenerated to match the new violet-magenta gradient:
- `public/brand/logo.png`: 1024×1024 high-res master logo with dark `#0A0A0A` backing.
- `public/brand/logo-transparent.png`: Gradient disc on transparent canvas.
- `public/brand/logo-icon.png` & `logo-icon.svg`: Monogram glyph.
- `public/brand/logo.svg`: Scalable vector master logo with linear gradient definition.
- `public/brand/og-image.png` & `public/images/og-image.png`: 1200×630 Open Graph preview card with atmospheric aurora glow, logo, and studio metadata.
- Complete favicon suite: `favicon.ico`, `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`, `android-chrome-192x192.png`, `android-chrome-512x512.png`.

### 8.2 Logo Component (`<x-logo>`)
The blade component `resources/views/components/logo.blade.php` provides a universal interface:
- **Automatic Background Handling**: Because `logo.png` includes a baked-in black background, when placed on light surfaces it is wrapped in an elegant dark rounded pill/backing (`p-1 bg-[#0A0A0A] rounded-xl ring-1 ring-white/10`).
- **Seamless SVG Swap**: If an SVG is placed at `public/brand/logo.svg` or requested via `useSvg="true"`, it dynamically switches with zero layout breakage.
