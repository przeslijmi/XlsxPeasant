# Przeslijmi XlsxPeasant - my approach to generate and read XLSX files

Tool that creates XML files, and packs them into ZIP archive (using `ZipArchive` class).

## Table of contents
1. [Usage examples](#usage-examples)

## Usage examples

All usage examples are defined in `tests/ProperCreationTest.php`. Ready, generated example files are located in `examples/` folder.

### Creating simple XMLS (Hello World)

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

### Default settings

After creating instance you can define default settings.

Default settings are used for new cells created after defining `->use*`. You can set new defaults
during creation of XLSX file or restore to application defaults at any time (TODO) calling:

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

which is a synonim to above 5 statements.

#### Default fill color

You have to send `Fill` object. See color declaration for more info.

```php
$xlsx->useFill(Fill::factory('red'));
$xlsx->useFill(new Fill(Color::factory('red')));
```

#### Default font

In first param font name is defined. Second param is a string of definitions or `Color` object.

See font declaration for more information.

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

#### Default cell align

You define cell align by giving two chars, separated by nothing, with:
- first char defining horizontal align [L, C, R],
- second char defining vertical align [T, M, B].

```php
$xlsx->useAlign('CM');
$xlsx->useAlign('LT');
```

#### Default default format

Format sets how the values are presented in cell. It can be numerical format, date format, hidden
format, etc.

See format declaration for more info.

```php
$xlsx->useFormat(new NumFormat(0, 0, 'szt.'));
```

