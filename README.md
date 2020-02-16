# Przeslijmi XlsxPeasant - my approach to generate and read XLSX files

Tool that creates XML files, and packs them into ZIP archive (using `ZipArchive` class).

## Table of contents

1. [Hello world](#hello-world)
1. [Defining cells values](#defining-cells-values)
1. [Defining cells design](#defining-cells-design)
   1. [Fill color](#fill-color)
   1. [Font](#font)
   1. [Cell align](#cell-align)
   1. [Format](#format)
   1. [Color](#color)
   1. [Application design defaults](#application-design-defaults)

## Hello world

All usage examples are defined in `tests/ProperCreationTest.php`. Ready, generated example files are located in `examples/` folder.

```php
// Create instance.
$xlsx = new Xlsx();

// Add sheet.
$sheet = $xlsx->getBook()->addSheet('Simplest Test');

// Define cell value.
$sheet->getCell(1, 1)->setValue('Hello World!');

// Generate file.
$xlsx->generate($uri, true);
```

## Defining cells values

To create cell and define its value use below command.

```php
$sheet->getCell(1, 1)->setValue('Hello World!');
```

Row has to be given in first param, column in second. Both starting with 1 (not zero).

You can also create cell consisting of more than one part. Use multipart cells if you want
to use different fonts or colors inside one cell.

```php
$sheet->getCell(1, 1)->setValueParts([
    [
        'first part value'
    ],
    [
        'second part value'
    ]
]);
```

## Defining cells design

After creating instance you can create cells.

Before creating cells you have to define all design statements to use in cells, for eg.:

```php
$xlsx->useAlign('CM');
```

For more examples see next sections.

You can restore definitions to [application design defaults](#application-design-defaults) at any time by calling:

```php
$xlsx->useFill(null);
$xlsx->useFont(null);
$xlsx->useAlign(null);
$xlsx->useFormat(null);
$xlsx->useWrapText(null);
```

Or call to restore all defaults:

```php
$xlsx->useDefaults();
```

which is a synonim to previous 5 statements.

### Fill color

You have to send `Fill` object, which can be created:
- from `::factory` (which parameters are identical to [`Color` object `::factory`](#color),
- or from `constructor` (which only possible parameter is [`Color` object itself](#color).

```php
$xlsx->useFill(Fill::factory('red'));
$xlsx->useFill(new Fill(Color::factory('red')));
```

### Font

In first param font name is defined. Second param is a string of definitions or [`Color` object itself](#color).

```php
$xlsx->useFont(Font::factory('Arial', '15 orange bold italic underline'));
$xlsx->useFont(Font::factory('Arial', '15'));
$xlsx->useFont(Font::factory('Arial', Color::factory('red')));
```

You can also define only name of font (without any specifications) or only specifications without
font name. Application will not overwrite not-given specifications and will be using other defined
(in Style or in Cell).

```php
$xlsx->useFont(Font::factory('Arial'));
$xlsx->useFont(Font::factory(null, '20 bold'));
```

#### Font name

Font are not stored inside XLSX file - you have to use only those fonts which are for sure present
on machine which will be used to open generated XLSX file.

#### Font definition

Inside font definition `string` you can use three element in any order:
- font size as an integer (not decimal) value,
- `bold`, `underline`, `italic` key words,
- color name ([see `Color` object definition](#color)).

While defining only font size remember to keep font definition a `string` not `integer`.

### Cell align

You define cell align by giving two chars, separated by nothing, with:
- first char defining horizontal align [L, C, R],
- second char defining vertical align [T, M, B].

```php
$xlsx->useAlign('CM');
$xlsx->useAlign('LT');
```

### Format

Format sets how the values are presented in cell. It can be numerical format, date format, hidden
format, etc.

```php
$xlsx->useFormat(new NumFormat(0, 0, 'ppl'));
```

#### Numeric format

Defined by three properties:
- number of decimal places (0 as default),
- number of leading zeroes (0 as default),
- unit name (empty as default).

```php
$xlsx->useFormat(new NumFormat(2, 0, 'USD'));
```

#### Date format

Defined only in YYYY-MM-DD format - not possible to be changed as of today.

```php
$xlsx->useFormat(new DateFormat());
```

#### Hidden format

Format that tells Excel to hide contents of this cell (analogous to `;;;` format).

```php
$xlsx->useFormat(new HiddenFormat());
```

### Color

Colors definition are used while defining:
- [Fill color](#fill-color)
- [Font](#font)

There are three ways to define `Color` object that can be later used in above definitions:

#### RGB definition

```php
Color::factory(0, 15, 250);
```

#### RGB HEX definition

```php
Color::factory('FFAB34');
```

#### Color name definition

```php
Color::factory('white');
```

You can see all colors combination in `examples/XlsxPeasant_02_colorsTest.xlsx`.

Complete list of colors is: aliceblue, antiquewhite, aqua, aquamarine, azure, beige, bisque, black, blanchedalmond, blue, blueviolet, brown, burlywood, cadetblue, chartreuse, chocolate, coral, cornflower, cornsilk, crimson, cyan, darkblue, darkcyan, darkgoldenrod, darkgray, darkgreen, darkkhaki, darkmagenta, darkolivegreen, darkorange, darkorchid, darkred, darksalmon, darkseagreen, darkslateblue, darkslategray, darkturquoise, darkviolet, deeppink, deepskyblue, dimgray, dodgerblue, firebrick, floralwhite, forestgreen, fuchsia, gainsboro, ghostwhite, gold, goldenrod, gray, green, greenyellow, honeydew, hotpink, indianred, indigo, ivory, khaki, lavender, lavenderblush, lawngreen, lemonchiffon, lightblue, lightcoral, lightcyan, lightgoldenrodyellow, lightgreen, lightgray, lightpink, lightsalmon, lightseagreen, lightskyblue, lightslategray, lightsteelblue, lightyellow, lime, limegreen, linen, magenta, maroon, mediumaquamarine, mediumblue, mediumorchid, mediumpurple, mediumseagreen, mediumslateblue, mediumspringgreen, mediumturquoise, mediumvioletred, midnightblue, mintcream, mistyrose, moccasin, navajowhite, navy, oldlace, olive, olivedrab, orange, orangered, orchid, palegoldenrod, palegreen, paleturquoise, palevioletred, papayawhip, peachpuff, peru, pink, plum, powderblue, purple, red, rosybrown, royalblue, saddlebrown, salmon, sandybrown, seagreen, seashell, sienna, silver, skyblue, slateblue, slategray, snow, springgreen, steelblue, tan, teal, thistle, tomato, turquoise, violet, wheat, white, whitesmoke, yellow, yellowgreen.

### Application design defaults

If none settings are given (concerning font, color, fill, etc.) no information are sent to XLSX either
so in that situation - it is Excel which decides how to show the contents.
