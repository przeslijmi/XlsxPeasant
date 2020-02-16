# Przeslijmi XlsxPeasant - my approach to generate and read XLSX files

Tool that creates XML files, and packs them into ZIP archive (using `ZipArchive` class).

Tool has a full support for UTF-16 characters.

## Table of contents

1. [Hello world](#hello-world)
1. [Sheets](#sheets)
1. [Cells values](#cells-values)
1. [Cells merges](#cells-merges)
1. [Cells design](#cells-design)
   1. [Fill color](#fill-color)
   1. [Font](#font)
   1. [Cell align](#cell-align)
   1. [Format](#format)
   1. [Color](#color)
   1. [Wrap text](#wrap-text)
   1. [Style](#style)
   1. [Design defaults](#design-defaults)
1. [Tables](#tables)
1. [Generating file](#generating-file)
1. [Reading XLSX files](#reading-xlsx-file)

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

## Sheets

After creating `Xlsx` object, `Book` object is created automatically (it can be only one book for oneRow
`Xlsx` object), while `Sheet` object is **not created automatically**.

To create `Sheet` call:
```php
$xlsx->getBook()->addSheet('Simplest Test');
```

You can add more sheets - but their names have to be unique along whole `Xlsx` object.

If you can call created Sheet:
```php
$xlsx->getBook()->getSheetByName('Simplest Test');
```

### Defining sheet's rows and cols dimensions

You can define rows height:
```php
$xlsx->getBook()->setRowHeight(1, 14.40);
```

**INFO** Height of `14.40` is actually standard (default) Excel row height.

You can define col width:
```php
$xlsx->getBook()->setColWidth(1, 8.11);
```

**INFO** Height of `8.11` is actually standard (default) Excel row height.

**TIP** You can also define row and col height calling from `Cell` object itself:

```php
$sheet->getCell(1, 1)->setRowHeight(14.40)->setColWidth(8.11);
```

## Cells values

To create cell and define its value use below command.

```php
$sheet->getCell(1, 1)->setValue('Hello World!');
```

Row has to be given in first param, column in second. Both starting with 1 (not zero).

You can also create cell consisting of more than one part. Use multipart cells if you want
to use different fonts or colors inside one cell.

In an example below first part of cell is in standard font, while the other is in bold.

```php
$sheet->getCell(1, 1)->setValueParts([
    [
        'first part value'
    ],
    [
        'second part value',
        Font::factory(null, 'bold')
    ]
]);
```

## Cells merges

XlsxPeasant lets you merge cells in a simple way.

```php
$sheet->getCell(1, 1)->setMerge(2, 1);
$sheet->getCell(1, 1)->setMerge(1, 2);
$sheet->getCell(1, 1)->setMerge(2, 2);
```

First call merges two rows - so that Excel's cell A1 will be merged with A2. Second call merges A1 with
B1. Third call merges A1 with B1, A2 and B2.

## Cells design

After creating instance you can create cells.

Before creating cells you have to define all design statements to use in cells, for eg.:

```php
$xlsx->useAlign('CM');
```

**BE AWARE** It is impossible to define design for already created cell.

For more examples see next sections.

You can restore definitions to [application design defaults](#design-defaults) at any time by calling:

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

### Wrap text

Setting wrap text to true effects in breaking lines in Excel if they are too long for given cell width.

```php
$xlsx->useWrapText(false);
$xlsx->useWrapText(true);
```

### Style

Style is a named combination of any of above design definition. Concept of style is to define it once
and then use it throughout the generation.

**BE AWARE** You can use defined style only with one `XLSX` object. If you want to use one style in more
than one `XLSX` file it has to be separately defined in each of them.

```php
$niceStyle   = ( new Style($xlsx) )
    ->setFill(Color::factory(68, 114, 196))
    ->setFont(Font::factory('Courier New', '15 black bold italic underline'));
$normalStyle = ( new Style($xlsx) )
    ->setFill(Color::factory(68, 114, 196))
    ->setFont(Font::factory('Courier New', '15 black bold italic underline'));
```

After defining `Style` you can use it during creation of new cells.

```php
$xlsx->useStyle($niceStyle);
$sheet->getCell(1, 1)->setValue('Nice World!');
$xlsx->useStyle($normalStyle);
$sheet->getCell(1, 2)->setValue('Normal World!');
$xlsx->useStyle(null);
$sheet->getCell(1, 3)->setValue('Standard World!');
```

### Design defaults

If none settings are given (concerning font, color, fill, etc.) no information are sent to XLSX either
so in that situation - it is Excel which decides how to show the contents.

Hierarchy of design definitions is as follows:
1. non-style specifications (ie. `->use*` other than `->useStyle`),
1. style specifications (ie. `->useStyle`),
1. defaults (ie. `$xlsx->setDefault()`),
1. `null`.

Third method allows you to define some defualt settings for whole `Xlsx` object. These are:
- `fontColor`,
- `fontSize`,
- `fontName`.

So in below situation font name is `Arial` (not `Courier New`), size is `15` and color is `null`, while:
- `Arial` is defined not in `Style` but directly `->useFont()` - so it is more important,
- font size is defined in `Style` and defaults - but `Style` is more specific then defults - so Style overrides defaults,
- color is not defined at all.

```php
$xlsx->useFont(Font::factory('Arial'));
$xlsx->useStyle( new Style($xlsx) )
    ->setFont(Font::factory('Courier New', '15'));
$xlsx->setDefault('fontSize', '11');
```

**BE AWARE** Inside `Xlsx` constructor defaults are defined to assure proper generation of XLSX files:
- `fontColor`: black,
- `fontSize`: 11,
- `fontName`: Calibri.

## Tables

XlsxPeasant lets you create Excel's tables in a simple way.

To create them you don't operate on `->getCell()` methods but on methods created specifically for
serving tables operations.

```php
// Create instance.
$xlsx  = new Xlsx();
$sheet = $xlsx->getBook()->addSheet('Tables Test');

// Define data.
$data = [
    [
        'department' => 'Research and Development',
        'phone' => '11122233',
    ],
    [
        'department' => 'Sales',
        'phone' => '11122234',
    ],
    [
        'department' => 'Services',
        'phone' => '11122235',
    ],
    [
        'department' => 'Training',
        'phone' => '11122236',
    ],
];

// Add table.
$table = $sheet->addTable('Workers', 1, 1);
$table->addColumns([ 'department', 'phone' ]);
$table->getColumnByName('phone')->setFormat(new NumFormat(0, 0, ''));
$table->addData($data1);
```

## Generating file

While generating file you can decide if generator is to overwrite previously generated file or not
(depending on second parameter of `->generate()` method).

**BE AWARE** Tool can generate only to current machine.

```php
$xlsx->generate($uri, true);
$xlsx->generate($uri, false);
```

**BE AWARE** Overwriting works only if file is currently unused and reachable.

## Reading XLSX files

XlsxPeasant is capable of also reading XLSX files, however **it can only read data** - not formats, styles or other definitions.

```php
// Create instance.
$xlsx = new Reader($uri);
$book = $xlsx->readIn()->getBook();

// Get sheet to read cells.
$sheet     = $book->getSheetByName('Sheet1');
$cellValue = $sheet->getCell(1, 1)->getSimpleValue();

// Get Table to read Table data.
$tableData = $book->getTableByName('Table1')->getData();
```

`$tableData` is just a pure one dimensional array with keys (table columns names) and values (rows simple values for this columns).

**BE AWARE** Reading large `XLSX` files can reach beyond 30 s PHP execution time limit.
