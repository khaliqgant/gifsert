<?php

    //namespace should probably be khaliqgant
    namespace gifsert;

    /**
     * Gifsert caller
     * @author Khaliq
     *
     *
     */

    class gifsert
    {

        /**
         * @author          Khaliq
         * @description     ???
         *
         * @param $q
         * @return array
         */
        public function search_bar($q)
        {
            /**
             * Search by a keyword for a gif
             */

            if ($q) {
                $q = ltrim($q);
                $q = rtrim($q);
                $q = str_replace(' ', '_', $q);

                $csv          = file('https://raw.github.com/khaliqgant/gifsert/master/gifs.csv');
                $all_matches  = array();
                $descriptions = array();
                foreach ($csv as $gif) {
                    if (preg_match("/$q/i", "$gif")) {
                        $matches                = array();
                        $sep                    = substr($gif, 0, strpos($gif, ' '));
                        $description            = substr($gif, strpos($gif, ' '), -1);
                        $description            = str_replace('_', ' ', $description);
                        $matches['image']       = $sep;
                        $matches['description'] = $description;
                        $all_matches[]          = $matches;
                    }

                }

                return $all_matches;
            }

        }

        /**
         * @author          Khaliq
         * @description     ???
         *
         * @param $q
         * @return array
         */

        public function inserter($q)
        {
            /**
             * Call any gif by surronding the keyword with *[word]*
             */
            if ($q) {
                if (preg_match_all('/\*(.*?)\*/', $q, $gifs)) {
                    $input       = $gifs[1];
                    $csv         = file('https://raw.github.com/khaliqgant/gifsert/master/gifs.csv');
                    $all_matches = array();
                    foreach ($csv as $gif) {
                        if (preg_match("/$input[0]/i", "$gif")) {
                            $matches                = array();
                            $sep                    = substr($gif, 0, strpos($gif, ' '));
                            $description            = substr($gif, strpos($gif, ' '), -1);
                            $description            = str_replace('_', ' ', $description);
                            $matches['image']       = $sep;
                            $matches['description'] = $description;
                            $all_matches[]          = $matches;
                        }

                    }
                    return $all_matches;
                }
            }
        }


        /**
         * @author          Will
         * @description     Find gifs by keyword on reddit to use in gifsert
         *                  Eventually should be used to help add to the library
         *                  Keyword is matched to title and wont always represent
         *
         * @todo            Create way to moderate these and add them to the "main gifs" file
         * @todo            add check to make sure url exists and is an image
         *
         * @param           search_term
         * @param string    $subreddit_to_search
         *
         * @return array    image url and description
         */
        public function reddit_search($search_term, $subreddit_to_search = 'gifs')
        {
            if (!in_array('curl', get_loaded_extensions())) {
                throw new \Exception('cURL is required');
            }

            $reddit_json_url = "http://www.reddit.com/r/$subreddit_to_search/search.json?restrict_sr=on&q=";

            $curlopt_url = $reddit_json_url . $search_term;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $curlopt_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = json_decode(curl_exec($ch));

            $gifs = array();

            foreach ($output->data->children as $gif) {
                $gifs[] = array(
                    'image'       => $gif->data->url,
                    'description' => $search_term
                );
            }

            return $gifs;

        }

    }
