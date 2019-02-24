<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace entero\place;

use entero\place\Client\SearchClient;

/**
 * Inherited methods from SearchClient
 *
 * @see SearchClient
 *
 * @method nearby(string $location, array $params)
 * @method text(string $query, array $params)
 * @method radar(string $location, string $radius, array $params)
 * @method autoComplete(string $input, string $language, array $params)
 *
 * @author Jomon Johnson <jomon@entero.in>
 */
class Search extends AbstractComponent
{
    /**
     * @return SearchClient
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new SearchClient($this->key, $this->format);
        }

        return $this->client;
    }
}
