import math
import os
import numpy as np
from PIL import Image, ImageDraw, ImageFont, ImageFilter

def hex_to_rgb(hex_str):
    hex_str = hex_str.lstrip('#')
    return np.array([int(hex_str[i:i+2], 16) for i in (0, 2, 4)], dtype=float)

# Brand Color Stops along 135° axis
C_VIOLET_DEEP = hex_to_rgb("#3B0F8F") # Gradient start
C_VIOLET      = hex_to_rgb("#5B1BB0") # Upper-mid
C_PURPLE      = hex_to_rgb("#8A1FC9") # Mid
C_MAGENTA     = hex_to_rgb("#B820D8") # Gradient end
C_BLACK       = hex_to_rgb("#0A0A0A") # Background

def interpolate_gradient_4stop(t):
    # t is in [0, 1]
    # 0.0 -> Violet-deep (#3B0F8F)
    # 0.33 -> Violet (#5B1BB0)
    # 0.66 -> Purple (#8A1FC9)
    # 1.0 -> Magenta (#B820D8)
    t = np.clip(t, 0.0, 1.0)
    rgb = np.zeros(t.shape + (3,), dtype=float)
    
    # Segment 1: 0.0 to 0.33
    mask1 = t < 0.333333
    s1 = t / 0.333333
    rgb[mask1] = (1.0 - s1[mask1, None]) * C_VIOLET_DEEP + s1[mask1, None] * C_VIOLET
    
    # Segment 2: 0.33 to 0.66
    mask2 = (t >= 0.333333) & (t < 0.666667)
    s2 = (t - 0.333333) / 0.333334
    rgb[mask2] = (1.0 - s2[mask2, None]) * C_VIOLET + s2[mask2, None] * C_PURPLE
    
    # Segment 3: 0.66 to 1.0
    mask3 = t >= 0.666667
    s3 = (t - 0.666667) / 0.333333
    rgb[mask3] = (1.0 - s3[mask3, None]) * C_PURPLE + s3[mask3, None] * C_MAGENTA
    
    return rgb

def create_gradient_circle(size=1024, radius=380, center_offset=(0, 0)):
    # 135 degree diagonal gradient: from top-left (-x, -y) to bottom-right (+x, +y)
    # Direction vector at 135° in standard screen coords: dx = 1/sqrt(2), dy = 1/sqrt(2)
    center_x = size / 2.0 + center_offset[0]
    center_y = size / 2.0 + center_offset[1]
    
    y, x = np.ogrid[:size, :size]
    dx = x - center_x
    dy = y - center_y
    dist = np.sqrt(dx*dx + dy*dy)
    
    # Projection along 135° diagonal: (dx + dy) / sqrt(2)
    # Inside the circle of radius R, the projection ranges from -R to +R
    proj = (dx + dy) / math.sqrt(2.0)
    t = (proj + radius) / (2.0 * radius)
    
    rgb = interpolate_gradient_4stop(t)
    
    # Antialiased circle mask (1.5px smoothing)
    mask = np.clip((radius - dist + 0.75) / 1.5, 0.0, 1.0) * 255.0
    
    rgba = np.zeros((size, size, 4), dtype=np.uint8)
    rgba[..., :3] = np.clip(rgb, 0, 255).astype(np.uint8)
    rgba[..., 3] = mask.astype(np.uint8)
    
    return Image.fromarray(rgba, "RGBA")

def render_logo(output_path, bg_color=(10, 10, 10, 255), size=1024, radius=380):
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    canvas = Image.new("RGBA", (size, size), bg_color if bg_color else (0, 0, 0, 0))
    
    circle_img = create_gradient_circle(size=size, radius=radius)
    canvas.paste(circle_img, (0, 0), circle_img)
    
    # Draw wordmark in crisp white
    draw = ImageDraw.Draw(canvas)
    
    font_entu_size = int(size * 0.175) # ~180px
    font_creative_size = int(size * 0.042) # ~43px
    
    font_path = "/usr/share/fonts/noto/NotoSans-Bold.ttf"
    if not os.path.exists(font_path):
        font_path = "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf"
        
    try:
        font_entu = ImageFont.truetype(font_path, font_entu_size)
        font_creative = ImageFont.truetype(font_path, font_creative_size)
    except:
        font_entu = ImageFont.load_default()
        font_creative = ImageFont.load_default()
        
    text_entu = "entu"
    text_creative = "C R E A T I V E"
    
    bbox_entu = draw.textbbox((0, 0), text_entu, font=font_entu)
    w_entu = bbox_entu[2] - bbox_entu[0]
    h_entu = bbox_entu[3] - bbox_entu[1]
    
    bbox_creative = draw.textbbox((0, 0), text_creative, font=font_creative)
    w_creative = bbox_creative[2] - bbox_creative[0]
    h_creative = bbox_creative[3] - bbox_creative[1]
    
    center_y = size / 2.0
    y_entu = center_y - h_entu * 0.82
    x_entu = (size - w_entu) / 2.0
    
    y_creative = y_entu + h_entu + (size * 0.045)
    x_creative = (size - w_creative) / 2.0
    
    # White text (#FFFFFF)
    draw.text((x_entu, y_entu), text_entu, fill=(255, 255, 255, 255), font=font_entu)
    draw.text((x_creative, y_creative), text_creative, fill=(255, 255, 255, 255), font=font_creative)
    
    canvas.save(output_path, "PNG", optimize=True)
    print(f"Generated: {output_path}")

def render_logo_icon(output_path, size=512, radius=230):
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    canvas = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    circle_img = create_gradient_circle(size=size, radius=radius)
    canvas.paste(circle_img, (0, 0), circle_img)
    
    draw = ImageDraw.Draw(canvas)
    font_path = "/usr/share/fonts/noto/NotoSans-Bold.ttf"
    font_size = int(size * 0.42)
    try:
        font = ImageFont.truetype(font_path, font_size)
    except:
        font = ImageFont.load_default()
        
    bbox = draw.textbbox((0, 0), "e", font=font)
    w = bbox[2] - bbox[0]
    h = bbox[3] - bbox[1]
    
    x = (size - w) / 2.0
    y = (size - h) / 2.0 - (size * 0.05)
    draw.text((x, y), "e", fill=(255, 255, 255, 255), font=font)
    canvas.save(output_path, "PNG", optimize=True)
    print(f"Generated icon: {output_path}")

def render_mono_logo(output_path, is_white=True, size=512):
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    canvas = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    draw = ImageDraw.Draw(canvas)
    
    fill_color = (255, 255, 255, 255) if is_white else (10, 7, 16, 255)
    text_color = (10, 7, 16, 255) if is_white else (255, 255, 255, 255)
    
    radius = 230
    draw.ellipse([size/2 - radius, size/2 - radius, size/2 + radius, size/2 + radius], fill=fill_color)
    
    font_path = "/usr/share/fonts/noto/NotoSans-Bold.ttf"
    font_size = int(size * 0.42)
    try:
        font = ImageFont.truetype(font_path, font_size)
    except:
        font = ImageFont.load_default()
        
    bbox = draw.textbbox((0, 0), "e", font=font)
    w = bbox[2] - bbox[0]
    h = bbox[3] - bbox[1]
    x = (size - w) / 2.0
    y = (size - h) / 2.0 - (size * 0.05)
    draw.text((x, y), "e", fill=text_color, font=font)
    canvas.save(output_path, "PNG", optimize=True)
    print(f"Generated mono: {output_path}")

def render_og_image(output_path, width=1200, height=630):
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    # Aurora backdrop: ink base (#0A0710) + large blurred radial glows
    bg = Image.new("RGBA", (width, height), (10, 7, 16, 255))
    
    glow1 = Image.new("RGBA", (width, height), (0, 0, 0, 0))
    draw1 = ImageDraw.Draw(glow1)
    # Glow 1: Violet (#5B1BB0) top-left
    draw1.ellipse([-100, -100, 700, 500], fill=(91, 27, 176, 120))
    glow1 = glow1.filter(ImageFilter.GaussianBlur(140))
    bg.paste(glow1, (0, 0), glow1)
    
    glow2 = Image.new("RGBA", (width, height), (0, 0, 0, 0))
    draw2 = ImageDraw.Draw(glow2)
    # Glow 2: Magenta (#B820D8) bottom-right
    draw2.ellipse([600, 150, 1400, 800], fill=(184, 32, 216, 100))
    glow2 = glow2.filter(ImageFilter.GaussianBlur(160))
    bg.paste(glow2, (0, 0), glow2)
    
    # Faint noise / grain
    noise_arr = np.random.normal(0, 8, (height, width, 3))
    noise_img = Image.fromarray(np.clip(noise_arr + 128, 0, 255).astype(np.uint8), "RGB").convert("RGBA")
    noise_img.putalpha(15) # Very subtle grain
    bg.paste(noise_img, (0, 0), noise_img)
    
    # Place Logo Circle in center-left
    logo_size = 280
    logo_circle = create_gradient_circle(size=logo_size, radius=130)
    bg.paste(logo_circle, (100, (height - logo_size)//2), logo_circle)
    
    # Logo text inside circle
    draw = ImageDraw.Draw(bg)
    font_path = "/usr/share/fonts/noto/NotoSans-Bold.ttf"
    try:
        font_entu_logo = ImageFont.truetype(font_path, 48)
        font_creative_logo = ImageFont.truetype(font_path, 12)
        font_title = ImageFont.truetype(font_path, 54)
        font_tagline = ImageFont.truetype(font_path, 26)
        font_pill = ImageFont.truetype(font_path, 16)
    except:
        font_entu_logo = ImageFont.load_default()
        font_creative_logo = ImageFont.load_default()
        font_title = ImageFont.load_default()
        font_tagline = ImageFont.load_default()
        font_pill = ImageFont.load_default()
        
    # Inside logo circle
    lx = 100 + logo_size // 2
    ly = (height - logo_size) // 2 + logo_size // 2
    bbox_el = draw.textbbox((0, 0), "entu", font=font_entu_logo)
    draw.text((lx - (bbox_el[2] - bbox_el[0]) / 2, ly - 32), "entu", fill=(255, 255, 255, 255), font=font_entu_logo)
    bbox_cl = draw.textbbox((0, 0), "C R E A T I V E", font=font_creative_logo)
    draw.text((lx - (bbox_cl[2] - bbox_cl[0]) / 2, ly + 22), "C R E A T I V E", fill=(255, 255, 255, 255), font=font_creative_logo)
    
    # Main Titles on the right
    tx = 430
    ty = 190
    
    # Pill badge
    pill_text = "PORTFOLIO & ART DIRECTION"
    draw.rounded_rectangle([tx, ty - 50, tx + 260, ty - 18], radius=16, fill=(138, 31, 201, 50), outline=(201, 168, 255, 120))
    draw.text((tx + 18, ty - 44), pill_text, fill=(201, 168, 255, 255), font=font_pill)
    
    draw.text((tx, ty), "Entu Creative", fill=(255, 255, 255, 255), font=font_title)
    draw.text((tx, ty + 80), "Graphic Designer & Art Director", fill=(231, 166, 255, 255), font=font_tagline)
    draw.text((tx, ty + 130), "Bold visual identities and thoughtful packaging design.", fill=(163, 155, 181, 255), font=font_tagline)
    
    bg.save(output_path, "PNG", optimize=True)
    print(f"Generated OG Image: {output_path}")

def render_favicons(base_icon_path):
    icon = Image.open(base_icon_path)
    
    sizes = [
        ("public/favicon-16x16.png", (16, 16)),
        ("public/favicon-32x32.png", (32, 32)),
        ("public/brand/favicon-32x32.png", (32, 32)),
        ("public/apple-touch-icon.png", (180, 180)),
        ("public/android-chrome-192x192.png", (192, 192)),
        ("public/android-chrome-512x512.png", (512, 512)),
    ]
    
    for path, sz in sizes:
        os.makedirs(os.path.dirname(path), exist_ok=True)
        resized = icon.resize(sz, Image.LANCZOS)
        resized.save(path, "PNG", optimize=True)
        print(f"Generated favicon: {path}")
        
    # Generate multi-size favicon.ico
    icon.resize((32, 32), Image.LANCZOS).save("public/favicon.ico", format="ICO", sizes=[(16, 16), (32, 32)])
    print("Generated favicon.ico")

def generate_svgs():
    # Logo SVG (full wordmark inside gradient circle)
    logo_svg = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" width="1024" height="1024">
  <defs>
    <linearGradient id="entuBrandGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#3B0F8F" />
      <stop offset="35%" stop-color="#5B1BB0" />
      <stop offset="70%" stop-color="#8A1FC9" />
      <stop offset="100%" stop-color="#B820D8" />
    </linearGradient>
  </defs>
  <rect width="1024" height="1024" fill="#0A0A0A" />
  <circle cx="512" cy="512" r="380" fill="url(#entuBrandGrad)" />
  <g fill="#FFFFFF" text-anchor="middle" font-family="'Plus Jakarta Sans', 'Montserrat', -apple-system, sans-serif">
    <text x="512" y="525" font-size="180" font-weight="800" letter-spacing="-2">entu</text>
    <text x="512" y="605" font-size="44" font-weight="700" letter-spacing="14">CREATIVE</text>
  </g>
</svg>"""

    # Logo Icon SVG
    logo_icon_svg = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512">
  <defs>
    <linearGradient id="entuBrandGradIcon" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#3B0F8F" />
      <stop offset="35%" stop-color="#5B1BB0" />
      <stop offset="70%" stop-color="#8A1FC9" />
      <stop offset="100%" stop-color="#B820D8" />
    </linearGradient>
  </defs>
  <circle cx="256" cy="256" r="230" fill="url(#entuBrandGradIcon)" />
  <text x="256" y="325" fill="#FFFFFF" text-anchor="middle" font-family="'Plus Jakarta Sans', 'Montserrat', -apple-system, sans-serif" font-size="220" font-weight="800">e</text>
</svg>"""

    # Favicon SVG
    favicon_svg = logo_icon_svg

    # Mono SVGs
    mono_black_svg = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512">
  <circle cx="256" cy="256" r="230" fill="#0A0710" />
  <text x="256" y="325" fill="#FFFFFF" text-anchor="middle" font-family="'Plus Jakarta Sans', 'Montserrat', -apple-system, sans-serif" font-size="220" font-weight="800">e</text>
</svg>"""

    mono_white_svg = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512">
  <circle cx="256" cy="256" r="230" fill="#FFFFFF" />
  <text x="256" y="325" fill="#0A0710" text-anchor="middle" font-family="'Plus Jakarta Sans', 'Montserrat', -apple-system, sans-serif" font-size="220" font-weight="800">e</text>
</svg>"""

    files = [
        ("public/brand/logo.svg", logo_svg),
        ("public/images/logo.svg", logo_svg),
        ("public/brand/logo-icon.svg", logo_icon_svg),
        ("public/brand/favicon.svg", favicon_svg),
        ("public/images/favicon.svg", favicon_svg),
        ("public/brand/logo-mono-black.svg", mono_black_svg),
        ("public/brand/logo-mono-white.svg", mono_white_svg),
    ]
    for path, content in files:
        with open(path, "w") as f:
            f.write(content)
        print(f"Written SVG: {path}")

if __name__ == "__main__":
    print("Generating violet-magenta brand assets...")
    # 1. Main Logo (with black #0A0A0A background)
    render_logo("public/brand/logo.png", bg_color=(10, 10, 10, 255), size=1024)
    # 2. Transparent Logo
    render_logo("public/brand/logo-transparent.png", bg_color=None, size=1024)
    # 3. Logo Icon
    render_logo_icon("public/brand/logo-icon.png", size=512)
    # 4. Mono logos
    render_mono_logo("public/brand/logo-mono-black.png", is_white=False, size=512)
    render_mono_logo("public/brand/logo-mono-white.png", is_white=True, size=512)
    # 5. Favicons
    render_favicons("public/brand/logo-icon.png")
    # 6. SVGs
    generate_svgs()
    # 7. OG Images
    render_og_image("public/brand/og-image.png")
    render_og_image("public/images/og-image.png")
    print("All assets successfully generated!")
