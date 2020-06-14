<?php
require '../vendor/autoload.php';


//Flight::register('db', 'Database', array('localhost', 'sap', 'root', ''));
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=grbocovid', 'root', ''), function ($db) {
	//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
});

Flight::route('GET /countries', function () {
	$conn = Flight::db();
	$data = $conn->query("SELECT id, continent, country_name, total,total-deaths-recovered as pending, recovered, deaths, (deaths / total) * 100 as deaths_rate, (recovered / total) * 100 as recovered_rate FROM covid_countries");
	$count = $data->rowCount();
	header('Content-type: application/json');
	echo ("[");
	$i = 0;
	foreach ($data as $row) {
		$object = json_encode($row);
		echo $object;
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}

	echo ("]");
});

Flight::route('GET /graphs', function () {
	$conn = Flight::db();
	$data = $conn->query("SELECT continent, sum(recovered), sum(deaths) FROM covid_countries group by continent");
	$count = $data->rowCount();
	header('Content-type: application/json');
	echo ("[");
	$i = 0;
	foreach ($data as $row) {
		$object = json_encode($row);
		echo $object;
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}

	echo ("]");
});

Flight::route('/vijest', function () {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM vijest");
	$count = $data->rowCount();

	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		$object = json_encode($row);
		print_r($object);
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}

	echo ("]");
});

Flight::route('/event', function () {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM event order by datum");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/kategorijaEvent/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM event INNER JOIN kattolokal ON event.lokalID = kattolokal.lokalID having kattolokal.kategorijaID = ($id)");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/lokalSpon/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM lokal INNER JOIN kattolokal ON lokal.id = kattolokal.lokalID having lokal.sponzored = 1 and kattolokal.kategorijaID = ($id)");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/kategorijaLokal/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM lokal INNER JOIN kattolokal ON lokal.id = kattolokal.lokalID having kattolokal.kategorijaID = ($id)");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/event/@id', function () {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM event where lokalID = ($id)");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/lokalIme/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM kategorija where ime = ('$id')");

	foreach ($data as $row) {
		print_r(json_encode($row));
	}
});

Flight::route('/lokal/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT lokal.id, lokal.ime, lokal.lat, lokal.lng, lokal.adresa, lokal.radnoVrijeme, lokal.kontakt, lokal.rezervacija, lokal.opis, lokal.sponzored, lokal.slika11, lokal.slika169, kattolokal.kategorijaID, ocjena.likes, ocjena.dislikes, komentar.sadrzaj FROM lokal INNER JOIN kattolokal on lokal.id = kattolokal.kategorijaID INNER JOIN ocjena on lokal.id = ocjena.lokalID INNER JOIN komentar on lokal.id = komentar.lokalID group by ($id) having lokal.id = ($id) ");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/komentar/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM komentar where lokalID = ($id)");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/komentar2/@id', function ($id) {
	$conn = Flight::db();
	//$kom = $_REQUEST['sadrzaj'];
	//$_SESSION['username'] = $nick
	$data = $conn->query("INSERT INTO komentar (lokalID, sadrzaj, nick, brojLajkova) values ( $id , 'omco', 'omco',  0 )");
	if ($data) {
		echo 'ok';
	} else {
		echo ' not  ok ';
	}
	$data2 = $conn->query("SELECT * FROM komentar ");
	$count = $data2->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data2 as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/kartaLokal', function () {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM lokal INNER join kategorija on kategorija.ime = lokal.ime group by lokal.ime ");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/kartaLokal/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM lokal INNER join kategorija on kategorija.ime = lokal.ime group by lokal.ime having lokal.ime = ('$id')  ");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/kartaLokal2', function () {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM lokal INNER join kattolokal on kattolokal.lokalID = lokal.id group by lokal.id ");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/kartaLokal2/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM lokal INNER join kattolokal on kattolokal.lokalID = lokal.id group by lokal.id having kattolokal.lokalID = ($id)  ");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/ocjena/@id', function ($id) {
	$conn = Flight::db();
	$data = $conn->query("SELECT * FROM ocjena where lokalID = ($id)");
	$count = $data->rowCount();
	echo ("data: [");
	$i = 0;
	foreach ($data as $row) {
		print_r(json_encode($row));
		if ($i < $count - 1) {
			echo (",");
		}
		$i++;
	}
	echo ("]");
});

Flight::route('/ocjenaUpdate/@id', function ($id) {
	$conn = Flight::db();
	//$button = $_REQUEST['buttonClicked'];
	$button = 0;
	if ($button == 1) {
		$data = $conn->query("UPDATE ocjena SET likes = likes+1 where lokalID = ($id)");
	} else {
		$data = $conn->query("UPDATE ocjena SET dislikes = dislikes+1 where lokalID = ($id)");
	}
});

Flight::start();
