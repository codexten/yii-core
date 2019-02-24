<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace entero\place;

use entero\place\Client\PlaceClient;

/**
 * Inherited methods from PlaceClient
 *
 * @see PlaceClient
 *
 * @method details(string $placeid, string $language, array $params) Returns the details of a place.
 * @method photo(string $reference, array $params) Returns a photo content.
 * @method add(array $location, string $name, array $types, string $accuracy, string $language, array $params)
 * @method delete(string $reference)
 *
 * @author Jomon Johnson <jomon@entero.in>
 */
class Places extends AbstractComponent
{
    /**
     * @return PlaceClient
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new PlaceClient($this->key, $this->format);
        }

        return $this->client;
    }
}
