<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * While reading Table `Tab.Definicja` something went wrong.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class TableHasWrongColumnsException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Table columns are not proper as given.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'shouldBe', 'actualColumns' ];
}
