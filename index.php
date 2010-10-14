<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >
*/

require 'markov.php';

if (isset($_POST['submit'])) {
    // generate text with markov library
    $order  = $_REQUEST['order'];
    $length = $_REQUEST['length'];
    $input  = $_REQUEST['input'];
    $ptext  = $_REQUEST['text'];

    if ($input) $text = $input;
    if ($ptext) $text = file_get_contents("text/".$ptext.".txt");

    if(isset($text)) {
        $markov_table = generate_markov_table($text, $order);
        $markov = generate_markov_text($length, $markov_table, $order);

        if (get_magic_quotes_gpc()) $markov = stripslashes($markov);
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>PHP Markov chain text generator by Hay Kranen</title>
    <link rel="stylesheet" type="text/css" href="http://static.haykranen.nl/common/style.css" />
    <?php echo file_get_contents('http://static.haykranen.nl/common/nav/html/head.php'); ?>        
</head>
<body>
<a href="http://github.com/hay/markov">
    <img style="position: absolute; top: 0; right: 0; border: 0;" src="http://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png" alt="Fork me on GitHub" />
</a>

<?php echo file_get_contents('http://static.haykranen.nl/common/nav/html/nav.php'); ?>
<div id="wrapper">
    <h1>PHP Markov chain text generator</h1>
    <p>This is a very simple Markov chain text generator. Try it below by entering some
    text or by selecting one of the pre-selected texts available. </p>
    <p>The source code of this generator is available under the terms of the <a href="http://www.opensource.org/licenses/mit-license.php">MIT license</a>.See the original posting on this generator <a href="http://www.haykranen.nl/projects/markov">here</a>.</p>

    <?php if (isset($markov)) : ?>
        <h2>Output text</h2>
        <textarea rows="20" cols="80" readonly="readonly"><?php echo $markov; ?></textarea>
    <?php endif; ?>

    <h2>Input text</h2>
    <form method="post" action="" name="markov">
        <textarea rows="20" cols="80" name="input"></textarea>
        <br />
        <select name="text">
            <option value="">Or select one of the input texts here below</option>
            <option value="alice">Alice's Adventures in Wonderland, by Lewis Carroll</option>
            <option value="calvin">The Wikipedia article on Calvin and Hobbes</option>
            <option value="kant">The Critique of Pure Reason by Immanuel Kant</option>
        </select>
        <br />
        <label for="order">Order</label>
        <input type="text" name="order" value="4" />
        <label for="length">Text size of output</label>
        <input type="text" name="length" value="2500" />
        <br />
        <input type="submit" name="submit" value="GO" />
    </form>
</div> <!-- /wrapper -->
</body>
</html>