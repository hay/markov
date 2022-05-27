<?php
/*
    PHP Markov Chain text generator 1.0
    Copyright (c) 2008-2010, Hay Kranen <http://www.haykranen.nl/projects/markov/>
    Fork on Github: < http://github.com/hay/markov >
*/

require 'markov.php';

$error = null;
$markov = null;

function process_post() {
    // generate text with markov library
    $order  = $_POST['order'];
    $length = $_POST['length'];
    $input  = $_POST['input'];
    $ptext  = $_POST['text'];

    if (!ctype_digit($order) || !ctype_digit($length)) {
        throw new Exception("Your order or length are not correct");
    }

    $order = (int) $order;
    $length = (int) $length;

    if ($order < 0 || $order > 20) {
        throw new Exception("Invalid order");
    }

    if ($length < 1 || $length > 25000) {
        throw new Exception("Text length is too short or too long");
    }

    if ($input) {
        $text = $input;
    } else if ($ptext) {
        if (!in_array($ptext, ['alice', 'calvin', 'kant'])) {
            throw new Exception("Invalid text");
        } else {
            $text = file_get_contents("./text/$ptext.txt");
        }
    }

    if (empty($text)) {
        throw new Exception("No text given");
    }

    $markov_table = generate_markov_table($text, $order);
    $markov = generate_markov_text($length, $markov_table, $order);
    return htmlentities($markov);
}

if (isset($_POST['submit'])) {
    try {
        $markov = process_post();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>PHP Markov chain text generator by Hay Kranen</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrapper">
    <h1>PHP Markov chain text generator</h1>
    <p>This is a very simple Markov chain text generator. Try it below by entering some
    text or by selecting one of the pre-selected texts available. </p>
    <p>The source code of this generator is available under the terms of the <a href="http://www.opensource.org/licenses/mit-license.php">MIT license</a>.See the original posting on this generator <a href="http://www.haykranen.nl/projects/markov">here</a>.</p>

    <?php if ($error): ?>
        <p class="error"><strong><?= $error; ?></strong></p>
    <?php endif; ?>

    <?php if ($markov): ?>
        <h2>Output text</h2>
        <textarea rows="20" cols="60" readonly="readonly"><?= $markov; ?></textarea>
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