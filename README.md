# moodle-tiny_smaller_fonts

A [TinyMCE](https://www.tiny.cloud/) editor plugin for Moodle that adds a font size picker to the toolbar and Format menu, letting users change the font size of selected text.

This plugin is a fork of [moodle-tiny_fontsize](https://github.com/finspire-fi/moodle-tiny_fontsize) by Mikko Haiku, renamed and re-scoped as `tiny_smaller_fonts`. The default configured sizes are smaller than the original, but administrators can configure any sizes they like.

## Features

- Toolbar button and Format menu entry for choosing a font size
- The list of available sizes and the CSS unit they're expressed in (`pt`, `px`, `em`, `rem`, or `%`) are both configurable by an administrator
- If fewer than two sizes are configured, the picker is hidden rather than shown empty

## Requirements

- Moodle 5.2 (2026042000) or later

## Installation

Copy (or clone) this repository into your Moodle installation at:

```
public/lib/editor/tiny/plugins/smaller_fonts
```

(On installations that still use the pre-5.0 document root, use `lib/editor/tiny/plugins/smaller_fonts` instead.)

Then visit *Site administration &raquo; Notifications* to complete the installation.

## Settings

Go to *Site administration &raquo; Plugins &raquo; Text editor &raquo; TinyMCE editor &raquo; Smaller fonts* to configure:

| Setting | Description |
| --- | --- |
| Font sizes | The list of sizes offered in the picker, one per line |
| Font size unit | The CSS unit applied to every size (`pt`, `px`, `em`, `rem`, `%`) |

## License

Licensed under the [GNU GPL v3 or later](https://www.gnu.org/copyleft/gpl.html).
# tiny_smaller_fonts
