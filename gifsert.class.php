<?php

    namespace gifsert;
//I would make the namespace khaliqgant - Will
    /**
     * Gifsert caller
     * @author Khaliq
     *
     *
     */

    class gifsert
    {

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
    }

