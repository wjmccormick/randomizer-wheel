# randomizer-wheel

A customizable spin wheel WordPress plugin for random selections, giveaways, content ideas, and interactive decisions.

## Shortcodes

- `[randomizer_wheel]` — preferred shortcode for the full interactive wheel.
- `[randomized_wheel]` — backward-compatible alias for the full interactive wheel.
- `[randomizer_wheel_hero]` — generic decorative/demo hero wheel.
- `[whiskey_wheel_hero]` — backward-compatible alias for the hero wheel.

## Full wheel attributes

Supported by `[randomizer_wheel]` and `[randomized_wheel]`:

| Attribute | Default | Description |
| --- | --- | --- |
| `title` | Empty | Optional heading shown above the wheel input panel. |
| `placeholder` | `Example:` followed by `Option A` through `Option D` | Placeholder text for the item textarea. |
| `logo` | Bundled Randomizer Wheel logo | Optional logo URL for the center/presentation logos. |
| `logo_alt` | `Randomizer Wheel logo` | Alt text for wheel logo images. |
| `show_presentation` | `true` | Set to `false` to hide presentation mode controls and markup. |
| `show_remove_winner` | `true` | Set to `false` to hide remove-winner controls. |
| `min_items` | `2` | Minimum number of parsed items required before spinning. |

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

## Hero wheel attributes

Supported by `[randomizer_wheel_hero]` and `[whiskey_wheel_hero]`:

| Attribute | Default | Description |
| --- | --- | --- |
| `title` | Empty | Optional link title text; falls back to `cta_text` when empty. |
| `items` | `Option A` through `Option T` | Pipe-separated hero wheel demo items. |
| `link` | `/randomizer-wheel/` | URL opened by the hero wheel link. |
| `cta_text` | `Create Your Randomizer Wheel` | Accessible label for the hero wheel link. |
| `logo` | Bundled Randomizer Wheel logo | Optional hero logo URL. |
| `logo_alt` | `Randomizer Wheel logo` | Alt text for the hero logo. |

### Hero wheel examples

```text
[randomizer_wheel_hero items="Option A|Option B|Option C"]
```

```text
[randomizer_wheel_hero link="/spin/" cta_text="Create your prize wheel"]
```
