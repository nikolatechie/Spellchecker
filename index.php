<!DOCTYPE html>
<html>
	<head>
		<title>Spellchecker</title>
	</head>

	<body>
		<?php
			$conn = mysqli_connect("127.0.0.1", "root", "", "spellchecker");

			if ($conn->connect_error)
				die("<p style='color: red;'>Connection failed!</p>");

			function spellcheck($word)
			{
				global $conn;
				$output = array();
				$word = mysqli_real_escape_string($conn, $word);
				$wordExists = mysqli_query($conn, "SELECT COUNT() FROM english WHERE word='$word';"); // check if the entered word exists

				if ($wordExists == 0)
				{
					$words = mysqli_query($conn, "SELECT word FROM english;");

					while (($words_row = $words->fetch_assoc()))
					{
						similar_text($word, $words_row["word"], $percent);
						if ($percent > 80) array_push($output, $words_row["word"]);
					}
				}

				return (empty($output)) ? false : $output;
			}

			if (isset($_GET["word"]) && trim($_GET["word"]) != null)
			{
				$word = trim($_GET["word"]);
				$spellcheck = spellcheck($word);

				if ($spellcheck !== false)
					echo "<pre style='color: green;'>".print_r($spellcheck, true)."</pre>";
				else echo "<p style='color: blue;'>No suggestions.</p>";
			}
		?>

		<form method="GET">
			<label for="input-word">Check single word spelling:</label>
			<input type="text" id="word" name="word" id="input-word">
			<input type="submit" id="check" name="check" value="Check">
		</form>
	</body>

	<style type="text/css">
		#word
		{
			border: 1px solid black;
			border-radius: 5px;
			padding: 3px;
		}

		#check
		{
			border: none;
			color: white;
			border-radius: 3px;
			background-color: #4CAF50;
			padding: 6px;
			transition-duration: 0.3s;
		}

		#check:hover
		{
			background-color: #4CCF50;
		}
	</style>
</html>