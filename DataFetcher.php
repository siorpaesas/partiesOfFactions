<?php

class DataFetcher {

    protected string $url = 'https://ws-old.parlament.ch';

    private array $defaultOptions = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
        ],
    ];

    /**
     * @param  string  $lang
     *
     * @return array
     */
    public function getFactions(string $lang) : array {
        return $this->get($this->url . '/factions?format=json&lang='.$lang);
    }

    /**
     * @param  int  $factionId
     * @param  string  $lang
     *
     * @return array
     */
    public function getPartiesOfFaction(int $factionId, string $lang = 'de') : array {
        $parties = array();
        $url = $this->url . '/factions/'.$factionId.'?format=json&lang='.$lang;
        $factionMembers = $this->get($url);
        foreach ($factionMembers['members'] as $key => $member) {
            $parties[] = $member['partyName'];
        };

        return array_unique($parties);
    }

    /**
     * @param  string  $url
     * @param  array  $options
     *
     * @return mixed
     */
    public function get(string $url, array $options = []): mixed {
        $ch = curl_init($url);
        // Merge default options with user-provided options
        $finalOptions = $options + $this->defaultOptions;

        curl_setopt_array($ch, $finalOptions);
        $response = curl_exec($ch);

        // Log errors (if any)
        if (curl_errno($ch)) {
            error_log('cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        // return API response in JSON format
        return json_decode($response, true);
    }
}