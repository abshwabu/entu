#!/usr/bin/env python3
"""
Generate public/brand/color-reference.jpg:
Bright mint-green lettering sitting on a dark, semi-transparent slate-teal rounded panel,
over a glossy aqua/teal silk texture.
Then samples real pixel values to confirm target hex values.
"""

import math
import os
import numpy as np
from PIL import Image, ImageDraw, ImageFont, ImageFilter

def hex_to_rgb(hex_code):
    hex_code = hex_code.lstrip('#')
    return tuple(int(hex_code[i:i+2], 16) for i in (0, 2, 4))

def rgb_to_hex(rgb):
    return f"#{int(rgb[0]):02X}{int(rgb[1]):02X}{int(rgb[2]):02X}"

def generate_silk_background(width, height):
    """
    Render glossy aqua/teal silk texture with directional folds and specular sheen.
    Silk Aqua: #2EE6C8
    Silk Teal: #0E8C8A
    Panel Deep: #17333F
    """
    c_aqua = np.array(hex_to_rgb("#2EE6C8"), dtype=np.float32)
    c_teal = np.array(hex_to_rgb("#0E8C8A"), dtype=np.float32)
    c_deep = np.array(hex_to_rgb("#17333F"), dtype=np.float32)

    # Coordinate grids
    y, x = np.mgrid[0:height, 0:width].astype(np.float32)
    
    # Diagonal angle ~35 degrees
    angle = math.radians(35)
    u = x * math.cos(angle) + y * math.sin(angle)
    v = -x * math.sin(angle) + y * math.cos(angle)

    # Multiple sinusoidal wave harmonics for realistic fluid silk draping
    wave1 = np.sin(u * 0.007 + np.cos(v * 0.005) * 1.5)
    wave2 = np.sin(u * 0.014 + v * 0.003) * 0.5
    wave3 = np.cos(u * 0.021 - np.sin(v * 0.008) * 2.0) * 0.25
    
    combined = (wave1 + wave2 + wave3) / 1.75  # Normalized approx -1 to 1
    
    # Non-linear curve to simulate specular fabric sheen
    # Highlights are sharp, shadows deep
    t = (combined + 1.0) / 2.0  # 0 to 1
    
    # Specular gloss factor on the crests of folds
    specular = np.power(np.clip(t, 0.0, 1.0), 3.5) * 0.35
    
    # Interpolate colors across folds:
    # t < 0.5: c_deep to c_teal
    # t >= 0.5: c_teal to c_aqua
    silk_rgb = np.zeros((height, width, 3), dtype=np.float32)
    
    mask_low = t < 0.5
    t_low = t[mask_low] / 0.5
    silk_rgb[mask_low] = (1.0 - t_low[:, None]) * c_deep + t_low[:, None] * c_teal
    
    mask_high = t >= 0.5
    t_high = (t[mask_high] - 0.5) / 0.5
    silk_rgb[mask_high] = (1.0 - t_high[:, None]) * c_teal + t_high[:, None] * c_aqua
    
    # Add specular highlight
    silk_rgb += specular[:, :, None] * (c_aqua - c_teal) * 1.2
    
    # Add subtle fine micro-texture / grain
    np.random.seed(42)
    noise = (np.random.rand(height, width, 1) - 0.5) * 6.0
    silk_rgb = np.clip(silk_rgb + noise, 0, 255).astype(np.uint8)
    
    return Image.fromarray(silk_rgb, mode='RGB')

def create_reference_image(output_path):
    width, height = 1600, 1200
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    
    # 1. Base Silk Background
    base = generate_silk_background(width, height)
    
    # 2. Slate-teal semi-transparent panel layer
    panel_w, panel_h = 1040, 560
    panel_x = (width - panel_w) // 2
    panel_y = (height - panel_h) // 2
    panel_radius = 44
    
    # Create RGBA overlay for panel
    overlay = Image.new("RGBA", (width, height), (0, 0, 0, 0))
    draw_overlay = ImageDraw.Draw(overlay)
    
    # Drop shadow
    shadow_mask = Image.new("L", (width, height), 0)
    shadow_draw = ImageDraw.Draw(shadow_mask)
    shadow_draw.rounded_rectangle(
        [panel_x + 10, panel_y + 16, panel_x + panel_w + 10, panel_y + panel_h + 16],
        radius=panel_radius,
        fill=140
    )
    shadow_blur = shadow_mask.filter(ImageFilter.GaussianBlur(radius=28))
    
    # Paste shadow onto base
    shadow_img = Image.new("RGBA", (width, height), hex_to_rgb("#0F2229") + (0,))
    shadow_img.putalpha(shadow_blur)
    base = Image.alpha_composite(base.convert("RGBA"), shadow_img).convert("RGB")
    
    # Panel gradient: from #1F4452 (top/center) to #17333F (bottom/edges)
    # We create a panel image with 85% opacity (alpha ~ 217)
    panel_rgb_top = hex_to_rgb("#1F4452")
    panel_rgb_bottom = hex_to_rgb("#17333F")
    
    p_img = Image.new("RGBA", (panel_w, panel_h))
    p_arr = np.zeros((panel_h, panel_w, 4), dtype=np.uint8)
    
    for row in range(panel_h):
        ratio = row / float(panel_h)
        r = int(panel_rgb_top[0] * (1.0 - ratio * 0.4) + panel_rgb_bottom[0] * (ratio * 0.4))
        g = int(panel_rgb_top[1] * (1.0 - ratio * 0.4) + panel_rgb_bottom[1] * (ratio * 0.4))
        b = int(panel_rgb_top[2] * (1.0 - ratio * 0.4) + panel_rgb_bottom[2] * (ratio * 0.4))
        p_arr[row, :, 0] = r
        p_arr[row, :, 1] = g
        p_arr[row, :, 2] = b
        p_arr[row, :, 3] = 217  # 85% opacity
        
    panel_surf = Image.fromarray(p_arr, mode="RGBA")
    
    # Mask to rounded rect
    mask = Image.new("L", (panel_w, panel_h), 0)
    mask_draw = ImageDraw.Draw(mask)
    mask_draw.rounded_rectangle([0, 0, panel_w, panel_h], radius=panel_radius, fill=255)
    
    panel_surf.putalpha(Image.composite(panel_surf.split()[3], mask, mask))
    
    # Composite panel onto overlay
    overlay.paste(panel_surf, (panel_x, panel_y), panel_surf)
    
    # Border highlight around panel
    draw_overlay.rounded_rectangle(
        [panel_x, panel_y, panel_x + panel_w, panel_y + panel_h],
        radius=panel_radius,
        outline=hex_to_rgb("#2EE6C8") + (65,),  # Subtle silk aqua outline
        width=2
    )
    
    # 3. Mint-green lettering on the panel
    mint_rgb = hex_to_rgb("#2DF5B0")
    
    # Load fonts
    chosen_font_path = None
    font_candidates = [
        "/usr/share/fonts/noto/NotoSans-Bold.ttf",
        "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf",
        "/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf",
    ]
    for fp in font_candidates:
        if os.path.exists(fp):
            chosen_font_path = fp
            break

    if chosen_font_path:
        font_title = ImageFont.truetype(chosen_font_path, 130)
        font_sub = ImageFont.truetype(chosen_font_path, 32)
        font_meta = ImageFont.truetype(chosen_font_path, 18)
    else:
        font_title = ImageFont.load_default()
        font_sub = ImageFont.load_default()
        font_meta = None
        
    text_entu = "entu"
    text_creative = "C R E A T I V E"
    
    # Measure text
    bbox_entu = draw_overlay.textbbox((0, 0), text_entu, font=font_title)
    w_entu = bbox_entu[2] - bbox_entu[0]
    h_entu = bbox_entu[3] - bbox_entu[1]
    
    bbox_sub = draw_overlay.textbbox((0, 0), text_creative, font=font_sub)
    w_sub = bbox_sub[2] - bbox_sub[0]
    
    center_x = panel_x + panel_w // 2
    center_y = panel_y + panel_h // 2
    
    y_entu = center_y - h_entu // 2 - 35
    y_sub = y_entu + h_entu + 45
    
    # Draw soft glow behind mint lettering
    glow_mask = Image.new("L", (width, height), 0)
    glow_draw = ImageDraw.Draw(glow_mask)
    glow_draw.text((center_x - w_entu // 2, y_entu), text_entu, fill=160, font=font_title)
    glow_blur = glow_mask.filter(ImageFilter.GaussianBlur(radius=12))
    
    glow_img = Image.new("RGBA", (width, height), mint_rgb + (0,))
    glow_img.putalpha(glow_blur)
    overlay = Image.alpha_composite(overlay, glow_img)
    
    # Draw solid mint lettering
    draw_final = ImageDraw.Draw(overlay)
    draw_final.text((center_x - w_entu // 2, y_entu), text_entu, fill=mint_rgb + (255,), font=font_title)
    draw_final.text((center_x - w_sub // 2, y_sub), text_creative, fill=mint_rgb + (255,), font=font_sub)
    
    # Sub-tag or descriptor
    if font_meta:
        tag_text = "BRAND IDENTITY & ART DIRECTION"
        bbox_meta = draw_final.textbbox((0, 0), tag_text, font=font_meta)
        w_meta = bbox_meta[2] - bbox_meta[0]
        draw_final.text((center_x - w_meta // 2, y_sub + 55), tag_text, fill=hex_to_rgb("#2EE6C8") + (190,), font=font_meta)
    
    # Combine base and overlay
    final_img = Image.alpha_composite(base.convert("RGBA"), overlay).convert("RGB")
    final_img.save(output_path, "JPEG", quality=96)
    print(f"Generated color reference image at: {output_path}")

def sample_colors(image_path):
    img = Image.open(image_path).convert("RGB")
    w, h = img.size
    
    # Samples:
    # 1. Center of mint lettering ('entu')
    center_x, center_y = w // 2, h // 2 - 20
    mint_sample = img.getpixel((center_x, center_y))
    
    # 2. Panel surface (top area inside panel away from text)
    panel_sample = img.getpixel((w // 2, h // 2 - 180))
    
    # 3. Panel deep (bottom edge inside panel)
    panel_deep_sample = img.getpixel((w // 2, h // 2 + 200))
    
    # 4. Silk aqua (bright highlight outside panel)
    # Search for brightest pixel with high green & blue
    pixels = np.array(img)
    # Outside panel region (e.g. top 200 rows or left 200 cols)
    outer = pixels[:250, :400]
    aqua_idx = np.argmax(outer[:, :, 1].astype(int) + outer[:, :, 2].astype(int))
    r_idx, c_idx = np.unravel_index(aqua_idx, outer.shape[:2])
    silk_aqua_sample = tuple(outer[r_idx, c_idx])
    
    # 5. Silk teal (midtones outside panel)
    teal_sample = tuple(pixels[100, 700])

    print("--- Sampled Values from Reference Image ---")
    print(f"Mint (lettering):   RGB {mint_sample} -> {rgb_to_hex(mint_sample)}")
    print(f"Panel (surface):    RGB {panel_sample} -> {rgb_to_hex(panel_sample)}")
    print(f"Panel-deep (base):  RGB {panel_deep_sample} -> {rgb_to_hex(panel_deep_sample)}")
    print(f"Silk Aqua (crest):  RGB {silk_aqua_sample} -> {rgb_to_hex(silk_aqua_sample)}")
    print(f"Silk Teal (mid):    RGB {teal_sample} -> {rgb_to_hex(teal_sample)}")
    print("------------------------------------------")

if __name__ == "__main__":
    out = "public/brand/color-reference.jpg"
    create_reference_image(out)
    sample_colors(out)
