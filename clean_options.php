<?php

/**
 * Search for specific content in the wp_options table and allow to
 * remove the value.
 */

namespace aeSecure;

// More info about how to fire SQL statements with WP :
//
// https://wp.smashingmagazine.com/2011/09/interacting-with-the-wordpress-database/

class WordPressSQL
{
    private $db = null;

    public function connect()
    {
        require_once 'wp-config.php';
        mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

        $this->db = $wpdb;

        return true;
    }

    public function options_search($value)
    {
        $db    = $this->db;
        $sSQL  = "SELECT * FROM $db->options WHERE (option_value Like '%" . $value . "%')";
        $posts = $db->get_results($sSQL);

        return $posts;
    }

    public function options_kill($id)
    {
        $db   = $this->db;
        $sSQL = "DELETE FROM $db->options WHERE (option_id=" . $id . ')';
        echo $sSQL;

        return $db->query($sSQL);
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">

    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="author" content="aeSecure (c) Christophe Avonture" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <title>aeSecure - WordPress - SQL Statements</title> 
    </head>
    
    <body class="container">

        <h1>aeSecure - WordPress - SQL Statements</h1>
        
        <div class="row">

            <?php
                $aeWP = new \aeSecure\WordPressSQL();
                $aeWP->connect();

                $results = $aeWP->options_search('blueeyeswebsite');

            if (count($results) > 0) {
                echo '<p><pre>' . print_r($results, true) . '</pre></p>';

                foreach ($results as $result) {
                    $id = $result->option_id;
                    echo '<h3>Kill record ' . $id . '</h3>';

                    $bResult = $aeWP->options_kill($id);

                    echo '<p>' . ($bResult ? 'Success' : 'Failure') . '</p>';
                }
            }

                unset($aeWP);

            ?>

        </div>
        
        <footer class="footer">
            <div class="container">
                <span class="text-muted">https://www.aesecure.com</span>
            </div>
        </footer>
        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
    </body>
</html>