

<?php
// Defina a página inicial como 1
$page = 1;

// Defina a consulta de pesquisa como uma string vazia
$searchQuery = '';

// Verifique se a consulta de pesquisa foi enviada
if (isset($_GET['search'])) {
	$searchQuery = urlencode($_GET['search']);
}

// Verifique se o botão "Carregar mais resultados" foi clicado
if (isset($_GET['page'])) {
	$page = $_GET['page'];
}

// Fazer uma solicitação GET para a API do GitHub com a página atual e a consulta de pesquisa, se houver
$url = "https://api.github.com/search/repositories?q=language:php&per_page=5&page=" . $page;
if ($searchQuery != '') {
	$url .= "&q=" . $searchQuery;
}
$options = array(
	'http' => array(
		'header' => "User-Agent: request\r\n" .
					"Authorization: token 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\r\n"
	)
);
$context = stream_context_create($options);
$data = file_get_contents($url, false, $context);

// Converter os dados JSON em um objeto PHP
$results = json_decode($data);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Only PHP Repositories</title>
    <link rel="stylesheet" href="<?php echo 'http://127.0.0.1/olnyphp' ?>/style.css">
</head>
<style>
    *{
 
 padding: 0;
 box-sizing: border-box;
}
header {
display: flex;
justify-content: center;
}
.centtur{

display: flex;
justify-content: center;   
}

.all-five-rp{
overflow-y: scroll;
width: 600px;
height: 500px;
border: 1px solid rgb(0, 0, 0);
}
.repositories {
padding: 20px;
}
button {
position: fixed;
bottom: 30px;
left: 860px;
margin-left: 20px;
}
form{
    display: flex;
    justify-content: center;
}
</style>
<body>
    
   <header><h1>Only PHP Repositories</h1></header>
   <form method="get">
	<label for="search">Pesquisar por repositórios PHP:</label>
	<input type="text" name="search" id="search" placeholder="Search here">
	<button type="submit">Pesquisar</button>
</form>
   <div class="centtur">
    <div class="all-five-rp">
      <?php foreach ($results->items as $item) : ?>
       <div class="repositories">
             <h3><?= $item->name ?></h3>
             <p><?= $item->description ?></p>
             <a href="<?= $item->html_url ?>">Visit the repository</a>
 
         </div>
         <?php endforeach; ?>
     </div>
   </div>
 <?php echo "<button onclick='window.location.href=\"?page=" . ($page + 1) . "\"'>Load More</button>"; ?>
</body>
</html>
