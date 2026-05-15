# randomizer-wheel

A customizable spin wheel WordPress plugin for random selections, giveaways, content ideas, and interactive decisions.

## Shortcodes

- `[randomizer_wheel]` — full interactive wheel.
- `[randomizer_wheel_hero]` — generic decorative/demo hero wheel.

## Full wheel attributes

Supported by `[randomizer_wheel]`:

| Attribute | Default | Description |
| --- | --- | --- |
| `title` | Empty | Optional heading shown above the wheel input panel. |
| `placeholder` | `Example:` followed by `Option A` through `Option D` | Placeholder text for the item textarea. |
| `logo` | Bundled Randomizer Wheel logo | Optional logo URL for the center/presentation logos. |
| `logo_alt` | `Randomizer Wheel logo` | Alt text for wheel logo images. |
| `show_presentation` | `true` | Set to `false` to hide presentation mode controls and markup. |
| `show_remove_winner` | `true` | Set to `false` to hide remove-winner controls. |
| `min_items` | `2` | Minimum number of parsed items required before spinning. |
| `accent_color` | Admin setting, then `#b8860b` | Accent color for the wheel pointer, winner modal accent text, and close button. |
| `wheel_palette` | Admin setting, then `classic` | Canvas slice palette. Supported values: `classic`, `bourbon`, `bright`, `muted`, `monochrome`. |

### Full wheel examples

```text
[randomizer_wheel title="Prize Wheel"]
```

```text
[randomizer_wheel show_presentation="false" show_remove_winner="false"]
```

```text
[randomizer_wheel min_items="3" placeholder="Add one prize per line"]
```

```text
[randomizer_wheel wheel_palette="bright" accent_color="#ee9b00"]
```

## Hero wheel attributes

Supported by `[randomizer_wheel_hero]`:

| Attribute | Default | Description |
| --- | --- | --- |
| `title` | Empty | Optional link title text; falls back to `cta_text` when empty. |
| `items` | `Option A` through `Option T` | Pipe-separated hero wheel demo items. |
| `link` | `/randomizer-wheel/` | URL opened by the hero wheel link. |
| `cta_text` | `Create Your Randomizer Wheel` | Accessible label for the hero wheel link. |
| `logo` | Bundled Randomizer Wheel logo | Optional hero logo URL. |
| `logo_alt` | `Randomizer Wheel logo` | Alt text for the hero logo. |
| `accent_color` | Admin setting, then `#b8860b` | Hero pointer accent color for this shortcode instance. |
| `wheel_palette` | Admin setting, then `classic` | Hero canvas slice palette. Supported values: `classic`, `bourbon`, `bright`, `muted`, `monochrome`. |

### Hero wheel examples

```text
[randomizer_wheel_hero items="Option A|Option B|Option C"]
```

```text
[randomizer_wheel_hero link="/spin/" cta_text="Create your prize wheel"]
```

```text
[randomizer_wheel_hero wheel_palette="muted" accent_color="#6a4c93"]
```

## Admin settings

Site-wide defaults are available in **Settings → Randomizer Wheel**.

The settings page stores defaults for the full wheel and hero wheel in one option array named `rwp_settings`. Shortcode attributes always take priority over saved admin defaults, and saved admin defaults take priority over the bundled generic defaults.

### Full wheel defaults

- Default title
- Default placeholder text
- Default logo URL
- Default logo alt text
- Show presentation mode by default
- Show remove winner by default
- Default minimum items

### Hero wheel defaults

- Default hero title
- Default hero items
- Default hero link
- Default hero CTA text
- Default hero logo URL
- Default hero logo alt text

### Frontend theme controls

The settings page includes a direct **Settings** link from the WordPress Plugins screen. Logo URL fields include Media Library selection buttons, and the accent color field uses the WordPress color picker.

The following frontend theme defaults are stored in `rwp_settings` and are applied to both the full wheel and hero wheel:

- Accent color (`accent_color`) — wheel pointer, winner modal accent text, and close button.
- Wheel palette (`wheel_palette`) — canvas slice colors. Supported palettes are `classic`, `bourbon`, `bright`, `muted`, and `monochrome`.

Frontend theme resolution is always:

1. Shortcode attribute for that rendered wheel instance.
2. Saved admin setting from `rwp_settings`.
3. Bundled default (`#b8860b` for `accent_color`, `classic` for `wheel_palette`).

`wheel_palette` is passed to JavaScript per instance, so multiple wheels on one page can use different palettes. Buttons use the plugin's existing button styling and are not independently color-configurable.

### Palette Preview

| Palette | Colors |
|---|---|
| Classic | '#8b5a2b', '#c28a2c', '#2f2f2f', '#d7b377', '#5c4033', '#f3e1b6', '#9c6a2f', '#3a3a3a', '#b8860b', '#6b4423' |
| Bourbon | '#8b5a2b', '#c28a2c', '#2f2f2f', '#d7b377', '#5c4033', '#f3e1b6', '#9c6a2f', '#3a3a3a', '#b8860b', '#6b4423' |
| Bright | '#ef476f', '#ffd166', '#06d6a0', '#118ab2', '#8338ec', '#ff9f1c', '#2ec4b6', '#e71d36', '#3a86ff', '#fb5607' |
| Muted | '#6d6875', '#b5838d', '#e5989b', '#ffb4a2', '#9a8c98', '#c9ada7', '#4a4e69', '#8d99ae', '#a5a58d', '#b7b7a4' |
| Monochrome | '#6d6875', '#b5838d', '#e5989b', '#ffb4a2', '#9a8c98', '#c9ada7', '#4a4e69', '#8d99ae', '#a5a58d', '#b7b7a4' |
