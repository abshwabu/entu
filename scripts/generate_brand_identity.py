import math
import os
import numpy as np
from PIL import Image, ImageDraw, ImageFont, ImageFilter

def create_gradient_circle(size=1024, radius=380):
    # Create high-resolution mesh/swirl gradient inside the circle
    img = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    center_x, center_y = size / 2.0, size / 2.0
    
    # Grid coordinates
    y, x = np.ogrid[:size, :size]
    dx = x - center_x
    dy = y - center_y
    dist = np.sqrt(dx*dx + dy*dy)
    angle = np.arctan2(dy, dx) # -pi to pi
    
    # Swirl distortion
    swirl_strength = 2.4
    dist_norm = dist / radius
    swirled_angle = angle + swirl_strength * np.sin(dist_norm * math.pi)
    
    # Normalize swirled angle to [0, 1]
    norm_angle = (swirled_angle % (2 * math.pi)) / (2 * math.pi)
    
    # Colors:
    # Bright electric lime: #A6F000 -> (166, 240, 0)
    # Core lime: #7ED321 -> (126, 211, 33)
    # Mid green: #4FA800 -> (79, 168, 0)
    # Deep emerald green: #2A6B00 -> (42, 107, 0)
    c_bright = np.array([166, 240, 0], dtype=float)
    c_core = np.array([126, 211, 33], dtype=float)
    c_mid = np.array([79, 168, 0], dtype=float)
    c_deep = np.array([42, 107, 0], dtype=float)
    
    # Blend based on angle and radial distance
    t1 = np.clip(np.sin(norm_angle * math.pi * 2) * 0.5 + 0.5, 0, 1)
    t2 = np.clip(np.cos(norm_angle * math.pi * 2) * 0.5 + 0.5, 0, 1)
    radial_factor = np.clip(dist_norm, 0, 1)
    
    # Combine colors
    base_color = (1.0 - t1[..., None]) * c_bright + t1[..., None] * c_core
    swirl_color = (1.0 - t2[..., None]) * c_mid + t2[..., None] * c_deep
    
    # Edge is deeper emerald, center-top has bright electric lime highlights
    final_rgb = (1.0 - radial_factor[..., None] * 0.5) * base_color + (radial_factor[..., None] * 0.5) * swirl_color
    
    # Anti-aliased circle mask
    mask = np.clip((radius - dist + 1.0) / 2.0, 0, 1) * 255
    
    rgba = np.zeros((size, size, 4), dtype=np.uint8)
    rgba[..., :3] = np.clip(final_rgb, 0, 255).astype(np.uint8)
    rgba[..., 3] = mask.astype(np.uint8)
    
    circle_img = Image.fromarray(rgba, "RGBA")
    return circle_img

def render_logo(output_path, bg_color=(10, 10, 10, 255), size=1024):
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    canvas = Image.new("RGBA", (size, size), bg_color if bg_color else (0, 0, 0, 0))
    
    circle_img = create_gradient_circle(size=size, radius=370)
    canvas.paste(circle_img, (0, 0), circle_img)
    
    # Draw wordmark
    draw = ImageDraw.Draw(canvas)
    
    font_entu_size = int(size * 0.17) # ~174px
    font_creative_size = int(size * 0.045) # ~46px
    
    try:
        font_entu = ImageFont.truetype("/usr/share/fonts/liberation/LiberationSans-Bold.ttf", font_entu_size)
        font_creative = ImageFont.truetype("/usr/share/fonts/liberation/LiberationSans-Bold.ttf", font_creative_size)
    except:
        font_entu = ImageFont.load_default()
        font_creative = ImageFont.load_default()
        
    text_entu = "entu"
    text_creative = "C R E A T I V E"
    
    # Center measurements
    bbox_entu = draw.textbbox((0, 0), text_entu, font=font_entu)
    w_entu = bbox_entu[2] - bbox_entu[0]
    h_entu = bbox_entu[3] - bbox_entu[1]
    
    bbox_creative = draw.textbbox((0, 0), text_creative, font=font_creative)
    w_creative = bbox_creative[2] - bbox_creative[0]
    h_creative = bbox_creative[3] - bbox_creative[1]
    
    center_y = size / 2.0
    y_entu = center_y - h_entu * 0.75
    x_entu = (size - w_entu) / 2.0
    
    y_creative = y_entu + h_entu + (size * 0.04)
    x_creative = (size - w_creative) / 2.0
    
    draw.text((x_entu, y_entu), text_entu, fill=(10, 10, 10, 255), font=font_entu)
    draw.text((x_creative, y_creative), text_creative, fill=(10, 10, 10, 255), font=font_creative)
    
    canvas.save(output_path, "PNG", optimize=True)
    print(f"Saved: {output_path}")
    return canvas

def sample_colors(image_path):
    img = Image.open(image_path).convert("RGB")
    arr = np.array(img)
    
    # Find circle pixels (not background (10, 10, 10) and not text (10, 10, 10))
    # Green pixels: G > R and G > B and G > 50
    r = arr[..., 0]
    g = arr[..., 1]
    b = arr[..., 2]
    
    green_mask = (g > 60) & (b < 100) & (g > r * 0.8)
    green_pixels = arr[green_mask]
    
    # Bright lime: Highest G + R, high lightness
    brightness = 0.299 * green_pixels[:, 0] + 0.587 * green_pixels[:, 1] + 0.114 * green_pixels[:, 2]
    bright_idx = np.argsort(brightness)
    
    bright_lime = green_pixels[bright_idx[-100:]].mean(axis=0).astype(int)
    deep_green = green_pixels[bright_idx[:100]].mean(axis=0).astype(int)
    
    # Core lime: around 65th percentile brightness
    core_idx = int(len(bright_idx) * 0.70)
    core_lime = green_pixels[bright_idx[core_idx-50:core_idx+50]].mean(axis=0).astype(int)
    
    # Mid green: around 35th percentile brightness
    mid_idx = int(len(bright_idx) * 0.35)
    mid_green = green_pixels[bright_idx[mid_idx-50:mid_idx+50]].mean(axis=0).astype(int)
    
    # Black background: corners
    black_color = arr[10, 10]
    
    def to_hex(rgb):
        return f"#{rgb[0]:02X}{rgb[1]:02X}{rgb[2]:02X}"
        
    sampled = {
        "bright_lime": to_hex(bright_lime),
        "core_lime": to_hex(core_lime),
        "mid_green": to_hex(mid_green),
        "deep_green": to_hex(deep_green),
        "black": to_hex(black_color),
    }
    print("Sampled colors:", sampled)
    return sampled

def generate_variants():
    # 1. Master logo
    logo = render_logo("public/brand/logo.png", bg_color=(10, 10, 10, 255), size=1024)
    # 2. Transparent background logo
    render_logo("public/brand/logo-transparent.png", bg_color=None, size=1024)
    
    # 3. Icon only (512x512)
    icon = Image.new("RGBA", (512, 512), (0, 0, 0, 0))
    circle_512 = create_gradient_circle(size=512, radius=240)
    icon.paste(circle_512, (0, 0), circle_512)
    
    # Draw smaller geometric "e" in center of icon
    draw = ImageDraw.Draw(icon)
    try:
        font_e = ImageFont.truetype("/usr/share/fonts/liberation/LiberationSans-Bold.ttf", 240)
    except:
        font_e = ImageFont.load_default()
    bbox_e = draw.textbbox((0, 0), "e", font=font_e)
    w_e = bbox_e[2] - bbox_e[0]
    h_e = bbox_e[3] - bbox_e[1]
    draw.text(((512 - w_e)/2, (512 - h_e)/2 - 25), "e", fill=(10, 10, 10, 255), font=font_e)
    icon.save("public/brand/logo-icon.png", "PNG", optimize=True)
    
    # 4. Mono Black (transparent bg)
    mono_black = Image.new("RGBA", (512, 512), (0, 0, 0, 0))
    draw_mb = ImageDraw.Draw(mono_black)
    draw_mb.ellipse((16, 16, 496, 496), fill=(10, 10, 10, 255))
    draw_mb.text(((512 - w_e)/2, (512 - h_e)/2 - 25), "e", fill=(246, 248, 242, 255), font=font_e)
    mono_black.save("public/brand/logo-mono-black.png", "PNG", optimize=True)
    
    # 5. Mono White (transparent bg)
    mono_white = Image.new("RGBA", (512, 512), (0, 0, 0, 0))
    draw_mw = ImageDraw.Draw(mono_white)
    draw_mw.ellipse((16, 16, 496, 496), fill=(246, 248, 242, 255))
    draw_mw.text(((512 - w_e)/2, (512 - h_e)/2 - 25), "e", fill=(10, 10, 10, 255), font=font_e)
    mono_white.save("public/brand/logo-mono-white.png", "PNG", optimize=True)
    
    # 6. Favicon set
    fav_16 = icon.resize((16, 16), Image.Resampling.LANCZOS)
    fav_32 = icon.resize((32, 32), Image.Resampling.LANCZOS)
    fav_48 = icon.resize((48, 48), Image.Resampling.LANCZOS)
    fav_180 = icon.resize((180, 180), Image.Resampling.LANCZOS)
    fav_192 = icon.resize((192, 192), Image.Resampling.LANCZOS)
    fav_512 = icon.resize((512, 512), Image.Resampling.LANCZOS)
    
    fav_16.save("public/favicon-16x16.png", "PNG")
    fav_32.save("public/favicon-32x32.png", "PNG")
    fav_32.save("public/brand/favicon-32x32.png", "PNG")
    fav_180.save("public/apple-touch-icon.png", "PNG")
    fav_192.save("public/android-chrome-192x192.png", "PNG")
    fav_512.save("public/android-chrome-512x512.png", "PNG")
    fav_48.save("public/favicon.ico", format="ICO", sizes=[(16, 16), (32, 32), (48, 48)])
    
    # 7. Generate OG Image (1200x630)
    og = Image.new("RGBA", (1200, 630), (10, 10, 10, 255))
    
    # Draw soft aurora gradient on the right/center
    y_grid, x_grid = np.ogrid[:630, :1200]
    aurora_center_x, aurora_center_y = 900, 315
    dx_og = x_grid - aurora_center_x
    dy_og = (y_grid - aurora_center_y) * 1.5
    dist_og = np.sqrt(dx_og*dx_og + dy_og*dy_og)
    aurora_alpha = np.clip((500 - dist_og) / 500, 0, 1) ** 1.8
    
    # Aurora green color
    c_aurora = np.array([126, 211, 33], dtype=float)
    og_arr = np.array(og)
    og_arr[..., 0] = np.clip(10 + aurora_alpha * 80, 0, 255).astype(np.uint8)
    og_arr[..., 1] = np.clip(10 + aurora_alpha * 180, 0, 255).astype(np.uint8)
    og_arr[..., 2] = np.clip(10 + aurora_alpha * 20, 0, 255).astype(np.uint8)
    og = Image.fromarray(og_arr, "RGBA")
    
    # Paste logo icon on OG image
    logo_for_og = icon.resize((320, 320), Image.Resampling.LANCZOS)
    og.paste(logo_for_og, (780, 155), logo_for_og)
    
    # Typography on OG image
    draw_og = ImageDraw.Draw(og)
    try:
        font_og_title = ImageFont.truetype("/usr/share/fonts/liberation/LiberationSans-Bold.ttf", 64)
        font_og_sub = ImageFont.truetype("/usr/share/fonts/liberation/LiberationSans-Bold.ttf", 26)
        font_og_tag = ImageFont.truetype("/usr/share/fonts/liberation/LiberationSans-Bold.ttf", 20)
    except:
        font_og_title = font_og_sub = font_og_tag = ImageFont.load_default()
        
    draw_og.text((90, 160), "ENTU CREATIVE", fill=(166, 240, 0, 255), font=font_og_tag)
    draw_og.text((90, 220), "Graphic Designer\n& Art Director", fill=(246, 248, 242, 255), font=font_og_title, spacing=12)
    draw_og.text((90, 420), "Brand Identity • Packaging • Editorial", fill=(142, 148, 132, 255), font=font_og_sub)
    
    os.makedirs("public/images", exist_ok=True)
    og.convert("RGB").save("public/images/og-image.png", "PNG", optimize=True)
    og.convert("RGB").save("public/brand/og-image.png", "PNG", optimize=True)
    print("Generated all logo and favicon variants successfully!")

if __name__ == "__main__":
    generate_variants()
    sampled = sample_colors("public/brand/logo.png")
