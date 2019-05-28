<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace codexten\yii\place\Client;

use codexten\yii\helpers\ArrayHelper;
use enyii\helpers\Json;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

/**
 * Class Place handles place details requests and common actions.
 *
 * @author Jomon Johnson <jomon@entero.in>
 */
class PlaceClient extends AbstractClient
{
    /**
     * Returns the details of a place.
     *
     * @see https://developers.google.com/places/documentation/details
     * @see https://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1
     *
     * @param string $placeid the place id
     * @param string $language the language to return the results. Defaults to 'en' (english).
     * @param array $params optional parameters
     *
     * @throws RequestException if the request fails
     *
     * @return mixed|null
     */
    public function details($placeid, $language = 'en', $params = [])
    {
        $params['placeid'] = $placeid;
        $params['language'] = $language;
        $params['sensor'] = $this->getParamValue($params, 'sensor', 'false');

        return $this->request('place/details', 'get', $params);
    }

    /**
     * Returns a photo content.
     *
     * @see https://developers.google.com/places/documentation/photos#place_photo_requests
     *
     * @param string $reference string identifier that uniquely identifies a photo. Photo references are returned from
     * either a [[Search::text]], [[Search::nearby]], [[Search::radar]] or [[Place::details]] request.
     * @param array $params optional parameters.
     *
     * @throws InvalidArgumentException
     * @throws RequestException if the request fails
     *
     * @return mixed|null
     */
    public function photo($reference, $params = [])
    {
        if (!isset($params['maxheight']) && !isset($params['maxwidth'])) {
            throw new InvalidArgumentException('You must set "maxheight" or "maxwidth".');
        }
        $params['photoreference'] = $reference;
        $params['key'] = $this->key;
        $url = str_replace('/{format}', '', $this->api);
        $url = str_replace('{cmd}', 'photo', $url);
        $response = $this->getGuzzleClient()->get($url, ['query' => $params]);

        return $response->getBody();
    }

    /**
     * Adds a place on Google's places database for your application. This function only works with JSON formats, that
     * means that no matter what you set the [[$format]] to work with, it will be superseded by 'json' type.
     *
     * @see https://developers.google.com/places/documentation/actions#adding_a_place
     *
     * @param array $location The textual latitude/longitude value from which you wish to add new place information.
     * @param string $name The full text name of the place. Limited to 255 characters.
     * @param array $types The category in which this place belongs.
     * @param string $accuracy The accuracy of the location signal on which this request is based, expressed in meters.
     * @param string $language The language in which the place's name is being reported.
     * @param array $params The extra recommended but not required parameters (ie address, phone_number, and website)
     *
     * @throws InvalidArgumentException
     * @throws RequestException if the request fails
     *
     * @return array
     */
    public function add(array $location, $name, array $types, $accuracy, $language = 'en', array $params = [])
    {
        if (strlen($name) > 255) {
            throw new InvalidArgumentException('"$name" cannot be larger than 255 chars');
        }
        $types = (array)$types;
        $data = $params;
        $data['location'] = $location;
        $data['name'] = $name;
        $data['types'] = $types;
        $data['accuracy'] = $accuracy;
        $data['language'] = $language;

        return $this->request(
            'add',
            'post',
            [
                'key' => $this->key,
            ],
            [
                'body' => json_encode($data),
            ]
        );
    }

    /**
     * Deletes a place. A place can only be deleted if:
     * - It was added by the same application as is requesting its deletion.
     * - It has not successfully passed through the Google Maps moderation process, and and is therefore not visible to
     * all applications.
     *
     * @param string $reference The textual identifier that uniquely identifies this place
     *
     * @throws RequestException if the request fails
     *
     * @return array
     */
    public function delete($reference)
    {
        return $this->request(
            'delete',
            'post',
            [
                'key' => $this->key,
            ],
            ['body' => json_encode(['reference' => $reference])]
        );
    }

    /**
     * get distance and duration between two points
     *
     * @param $origin
     * @param $destination
     *
     * @return array|bool
     */
    public function getDistance($origin, $destination)
    {
        $distanceMetrix = $this->distanceMetrix($origin, $destination);
        $status = $distanceMetrix->rows[0]->elements[0]->status;
        if ($status === 'OK') {
            $distanceInKm = $distanceMetrix->rows[0]->elements[0]->distance->value;
            $duration = $distanceMetrix->rows[0]->elements[0]->duration->value;

            return [
                'distance' => $distanceInKm,
                'duration' => $duration,
            ];
        }

        return false;
    }

    /**
     * to get geocode by lat and lng
     *
     * @param $lat
     * @param $lng
     * @param $destination
     *
     * @return mixed
     */
    public function getDistanceByPoint($lat, $lng, $destination)
    {
        $origin = $lat . ',' . $lng;
        $destination = $this->getLatLng($destination);
        $distanceMetrix = $this->distanceMetrix($origin, $destination);
        $status = $distanceMetrix->rows[0]->elements[0]->status;
        if ($status === 'OK') {
            $distanceInKm = $distanceMetrix->rows[0]->elements[0]->distance->value / 1000;
            $duration = $distanceMetrix->rows[0]->elements[0]->duration->value;

            return [
                'distance' => $distanceInKm,
                'duration' => $duration,
            ];
        }

        return false;
    }

    /**
     * get lat, lng of a place by id
     *
     * @param $placeId
     */
    public function getLatLng($placeId)
    {
        $data = $this->geocode($placeId);
        foreach ($data->results as $index => $item) {
            $points = $item->geometry->location;

        }

        return $points->lat . ',' . $points->lng;
    }

    public function getLatLngByAddress($address)
    {
        $data = $this->geocode(null, ['address' => $address]);
        foreach ($data->results as $index => $item) {
            $points = $item->geometry->location;

        }

        return $points;

    }

    /**
     * get the details of place by id
     * return JSON format data
     *
     * @param $placeId
     * @param array $params
     */
    public function geocode($placeId = null, $params = [])
    {
        $param['place_id'] = $placeId;
        $params = ArrayHelper::merge($param, $params);

        return $this->request('geocode', 'get', $params);
    }

    /**
     * get distance details between to points
     * return JSON format data
     *
     * @param $originPoint
     * @param $destinationPoint
     * @param array $params
     *
     * @return mixed|null
     */
    public function distanceMetrix($originPoint, $destinationPoint, $params = [])
    {
        $param['units'] = 'metric';
        $param['origins'] = $originPoint;
        $param['destinations'] = $destinationPoint;
        $params = ArrayHelper::merge($param, $params);

        return $this->request('distancematrix', 'get', $params);
    }

    /**
     * @param $input
     * @param array $params
     *
     * @return mixed|null
     */
    public function autoComplete($input, $params = [])
    {
        $param['input'] = $input;
        $params = ArrayHelper::merge($param, $params);

        return $this->request('place/autocomplete', 'get', $params);
    }

    /**
     * @param bool $query
     *
     * @return array
     */
    public function searchByQuery($query, $params = [])
    {
        $data = $this->autoComplete($query, $params);
        $place = [];
        foreach ($data->predictions as $index => $item) {

            $place[$item->place_id] = $item->description;
        }

        return $place;
    }

    /**
     * get lat, lng of a place by id
     *
     * @param $placeId
     *
     * @return mixed
     */
    public function getPlaceNameById($placeId)
    {
        $data = $this->geocode($placeId);
        foreach ($data->results as $index => $item) {
            $name = $item->formatted_address;

        }

        return $name;
    }
}
